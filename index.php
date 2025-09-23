<?php
require_once 'config/autoload.php';

use services\DBManager;
use config\Conf;
Conf::fromInstance()->deploy();

echo "Hello World!";

var_dump(DBManager::getInstance());