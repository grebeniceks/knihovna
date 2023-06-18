<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;


final class HomePresenter extends Nette\Application\UI\Presenter
{
    public function __construct(
        private Nette\Database\Explorer $database,
    )
    {

    }

    public function renderDefault(): void
    {
        $this->template->authors = $this->database
            ->table('authors')
            ->order('id ASC')
            ->limit(10000);

        $this->template->books = $this->database
            ->table('books')
            ->order('id ASC')
            ->limit(10000);
    }
}
