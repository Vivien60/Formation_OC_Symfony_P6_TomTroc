<?php

namespace controller;

use view\templates\MessagerieHome;

class MessagerieController
{
    public function home()
    {
        $view = new MessagerieHome(new \view\layouts\ConnectedLayout());
        echo $view->render();
    }
}