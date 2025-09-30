<?php
declare(strict_types=1);
session_start();

require_once 'config/autoload.php';
use config\Conf;
use services\Utils;
use controller\{BookController,
    ErrorController,
    IndexController,
    MessagerieController,
    ThreadController,
    UserController};

Conf::getInstance()->deploy();
switch(Utils::request('action', null)) {
    case 'home':
    case null:
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
        $controller->displaySignInForm();
        break;
    case 'sign-out':
        $controller = new UserController();
        $controller->signOut();
        break;
    case 'edit-profile':
        $controller = new UserController();
        $controller->editProfile();
        break;
    case 'update-account':
        $controller = new UserController();
        $controller->update();
        break;
    case 'not-allowed':
        $controller = new ErrorController();
        $controller->notAllowed();
        break;
    case 'profile':
        $controller = new UserController();
        $controller->readProfile();
        break;
    case 'book-copy':
        $controller = new BookController();
        $controller->copyDetail();
        break;
    case 'messagerie':
        $controller = new MessagerieController();
        $controller->home();
        break;
    case 'thread':
        $controller = new ThreadController();
        $controller->detail();
        break;
    case 'create-thread':
        $controller = new ThreadController();
        $controller->create();
        break;
    default:
        $controller = new ErrorController();
        $controller->pageNotFound();
}