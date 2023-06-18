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
            ->setRequired();
        $form->addTextArea('description', 'Description:')
            ->setRequired();
        $form->addInteger('year', 'Year:')
            ->setRequired()
            ->addRule(function ($value) {
                return $value->getValue() <= 2023 && $value->getValue() >=1800;
            }, 'Input a value between 0 and 2023');
        $form->addInteger('pages', 'Pages:')
            ->setRequired();

        $form->addSubmit('send', 'Save and publish');
        $form->onSuccess[] = [$this, 'bookFormSucceeded'];

        return $form;
    }

    public function bookFormSucceeded(array $data): void
    {
        $book = $this->database
            ->table('books')
            ->insert($data);

        $this->flashMessage('Book was published', 'success');
    }

}
