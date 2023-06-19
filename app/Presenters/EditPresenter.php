<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;

final class EditPresenter extends Nette\Application\UI\Presenter
{
    public function __construct(
        private Nette\Database\Explorer $database,
    )
    {


    }

    protected function createComponentBookForm(): Form
    {

        $form = new Form;

        $form->addHidden('id');
        $authors = $this->database->table('authors')
            ->select('CONCAT(name, " ", surname) AS full_name, id')
            ->order('full_name')
            ->fetchPairs('id', 'full_name');
        $form->addSelect('id_author', 'Author:', $authors)
            ->setRequired();
        $form->addText('name', 'Name:')
            ->setMaxLength(60)
            ->setRequired();
        $form->addTextArea('description', 'Description:')
            ->setMaxLength(300)
            ->setRequired();
        $form->addInteger('year', 'Year:')
            ->setRequired()
            ->addRule(function ($value) {
                return $value->getValue() <= 2023 && $value->getValue() >= 1800;
            }, 'Input a value between 1800 and 2023');
        $form->addInteger('pages', 'Pages:')
            ->setRequired()
            ->addRule(function ($value) {
                return $value->getValue() <= 1000 && $value->getValue() >= 100;
            }, 'Input a value between 100 and 1000');

        $form->addSubmit('send', 'Submit')
            ->setHtmlAttribute('class', 'btn btn-primary');
        $form->onSuccess[] = [$this, 'bookFormSucceeded'];

        return $form;
    }

    public function bookFormSucceeded(array $data): void
    {
        $bookId = $this->getParameter('bookId');

        if ($bookId) {
            $book = $this->database
                ->table('books')
                ->get($bookId);
            $book->update($data);

        } else {
            $book = $this->database
                ->table('books')
                ->insert($data);
        }

        $this->redirect('Book:show', $book->id);
    }


    public function renderEdit(int $bookId): void
    {
        $book = $this->database
            ->table('books')
            ->get($bookId);

        if (!$book) {
            $this->error('Book not found');
        }

        $this->getComponent('bookForm')
            ->setDefaults($book->toArray());
    }

    protected function createComponentAuthorForm(): Form
    {

        $form = new Form;

        $form->addHidden('id');
        $form->addText('name', 'Name:')
            ->setRequired();
        $form->addText('surname', 'Surname:')
            ->setRequired();

        $form->addSubmit('send', 'Submit')
            ->setHtmlAttribute('class', 'btn btn-primary');
        $form->onSuccess[] = [$this, 'authorFormSucceeded'];

        return $form;
    }

    public function authorFormSucceeded(array $data): void
    {
        $authorId = $this->getParameter('authorId');

        if ($authorId) {
            $author = $this->database
                ->table('authors')
                ->get($authorId);
            $author->update($data);

        } else {
            $author = $this->database
                ->table('authors')
                ->insert($data);
        }

        $this->redirect('Home:default');
    }
}