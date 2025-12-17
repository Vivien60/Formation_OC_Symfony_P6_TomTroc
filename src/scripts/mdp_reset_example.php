<?php
declare(strict_types=1);
require_once 'config/autoload.php';
session_start();

// Exemple de reset du mot de passe d'un utilisateur
$manager = new \model\UserManager();
$user = $manager->fromId(3);
$user->newPassword("MOT DE PASSE");
$user->save();