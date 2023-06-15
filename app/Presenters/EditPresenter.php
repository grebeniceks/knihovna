<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use function Sodium\add;

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
        $form->addText('name', 'Name:')
            ->setRequired();
        $form->addTextArea('description', 'Description:')
            ->setRequired();
        $form->addInteger('year', 'Year:')
            ->setRequired()
            ->addRule(function ($book) {
                return $book->getValue() <= 2023 && $book->getValue() >=0;
            }, 'Input a value between 0 and 2023');

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
        $this->redirect('Book:show', $book->id);
    }

}
