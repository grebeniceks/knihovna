<?php
namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;

final class AuthorPresenter extends Nette\Application\UI\Presenter
{
    public function __construct(
        private Nette\Database\Explorer $database,
    ) {
    }

    public function renderShow(int $authorId): void
    {
        $this->template->author = $this->database
            ->table('authors')
            ->get($authorId);
    }
}
