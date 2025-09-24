<?php
declare(strict_types=1);

namespace view\templates;

use \view\layouts\Layout;

class signUpForm extends AbstractHtmlTemplate
{
    public string $title = 'Home';

    public function __construct(Layout $layout)
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
        return
        <<<MAIN
            <div>
            Look my sign-up form !
            </div>
            <form name="sign-up" method="POST" action="?action=create-account">
                <input type="text" name="name" placeholder="Username">
                <input type="password" name="password" placeholder="Password">
                <input type="submit" value="Sign Up">
            </form>
        MAIN;
    }
}