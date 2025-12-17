<?php
declare(strict_types=1);

namespace controller;

use model\BookCopySearch;
use services\BookCopyService;
use view\layouts\NonConnectedLayout;
use view\templates\{Index, SignInForm, SignUpForm};

class IndexController extends AbstractController
{
    public function index() : void
    {
        $service = new BookCopyService($this->userManager, $this->bookCopyManager);
        $bookCopies = $service->bookSearch('', 4);
        $layout = new NonConnectedLayout(); //Squelette de la page
        $view = new Index($layout, $bookCopies);
        echo $this->renderView($view);
    }

    public function displaySignUpForm() : void
    {
        $layout = new NonConnectedLayout(); //Squelette de la page
        $view = new SignUpForm($layout); //Template de la page structuré par le layout
        echo $this->renderView($view);
    }

    public function displaySignInForm() : void
    {
        $layout = new NonConnectedLayout();
        $view = new SignInForm($layout);
        echo $this->renderView($view);
    }

}