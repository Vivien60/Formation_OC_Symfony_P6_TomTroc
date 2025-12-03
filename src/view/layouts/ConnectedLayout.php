<?php
declare(strict_types=1);

namespace view\layouts;

use view\layouts\AbstractLayout;

class ConnectedLayout extends AbstractLayout
{
    public function __construct()
    {
        parent::__construct();
    }

    public function __toString() : string
    {
        return parent::__toString();
    }
}