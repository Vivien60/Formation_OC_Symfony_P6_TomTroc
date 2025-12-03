<?php

namespace view\templates;

interface WithForm
{
    public string $csrfToken { get; set; }

    public function getCsrfField() : string;
}