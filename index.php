<?php
declare(strict_types=1);

require_once 'config/autoload.php';

use config\Conf;
use services\Utils;
use controller\{ErrorController, IndexController, UserController};

Conf::fromInstance()->deploy();
switch(Utils::request('action')) {
    case 'home':
        $controller = new IndexController();
        $controller->index();
        break;
    case 'signup':
        $controller = new IndexController();
        $controller->displaySignUpForm();
        break;
    case 'create-account':
        $controller = new UserController();
        $controller->signUp();
        break;
    case 'login':
        $controller = new UserController();
        $controller->signIn();
        break;
    case 'sign-in':
        $controller = new IndexController();
        $controller->signIn();
        break;
    default:
        $controller = new ErrorController();
        $controller->pageNotFound();
}