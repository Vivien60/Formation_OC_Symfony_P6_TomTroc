<?php
declare(strict_types=1);
require_once '../config/autoload.php';
session_start();

use config\Conf;
Conf::getInstance()->deploy();

// Exemple de reset du mot de passe d'un utilisateur
$manager = new \model\UserManager();
$user = $manager->fromId(21);
$user->newPassword("Ccccc.84");
$manager->save($user);