<?php
require_once 'config/autoload.php';

use controller\IndexController;
use services\DBManager;
use config\Conf;
use services\Utils;

Conf::fromInstance()->deploy();

switch(Utils::request('action')) {
    case 'home':
        $controller = new IndexController();
        $controller->index();
        break;
}