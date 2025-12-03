<?php
declare(strict_types=1);

namespace view\templates;

use model\BookCopy;
use model\User;
use \view\layouts\AbstractLayout;

class ReadProfile extends AbstractHtmlTemplate
{
    public string $title = 'My profile';
    private ?bool $success = null;

    /**
     * @param AbstractLayout $layout
     * @param User|null $user
     * @var $library null|BookCopy[]
     */
    public function __construct(AbstractLayout $layout, readonly ?User $user = null, readonly ?array $library = null)
    {
        parent::__construct($layout);
    }

    /**
     * @return string[]
     */
    protected function defaultHeaderHtml() : array
    {
        return [
            ...parent::defaultHeaderHtml(),
            <<<HEADERS
HEADERS
        ];
    }
    public function getMainContent(): string
    {
        return require_once dirname(__DIR__, 1).'/ui/readProfileMain.php';
    }

    public function successfull(bool $success): void
    {
        $this->success = $success;
    }
}