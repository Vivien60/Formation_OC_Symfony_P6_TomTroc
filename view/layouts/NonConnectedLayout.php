<?php
declare(strict_types=1);

namespace view\layouts;

class NonConnectedLayout extends Layout
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