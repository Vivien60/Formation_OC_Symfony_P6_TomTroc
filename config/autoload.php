<?php

/**
 * Système d'autoload. 
 * A chaque fois que PHP va avoir besoin d'une classe, il va appeler cette fonction 
 * et chercher dnas les divers dossiers (ici models, controllers, views, services) s'il trouve 
 * un fichier avec le bon nom. Si c'est le cas, il l'inclut avec require_once.
 */
spl_autoload_register(function($className) {
    //On va chercher dans le dossier src si la classe existe en prenant en compte le namespace.
    $srcDir = dirname(__FILE__).'/src/';
    $classRelPath = str_replace('\\', '/', $className);
    if(file_exists($srcDir.$classRelPath.'.php')) {
        include_once $srcDir.$classRelPath . '.php';
    }
});