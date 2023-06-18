<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;

final class BookPresenter extends Nette\Application\UI\Presenter
{
    public function __construct(
        private Nette\Database\Explorer $database,
    )
    {
    }

    public function renderShow(int $bookId): void
    {
        $this->template->book = $this->database
            ->table('books')
            ->get($bookId);
    }
}
