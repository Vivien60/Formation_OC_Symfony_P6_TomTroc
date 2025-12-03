<?php

namespace services;

class CsrfToken implements \Stringable
{
    private string $token = "";
    public function __construct()
    {
        $this->generate();
        $this->store();
    }

    private function generate() : void
    {
        $this->token = bin2hex(random_bytes(32));
    }

    private function store() : void
    {
        $_SESSION['csrf_token'] = $this->token;
    }

    public static function verify(string $token) : bool
    {
        return hash_equals($token, $_SESSION['csrf_token'] ?? "");
    }

    private function fromStorage()
    {
        return $_SESSION['csrf_token'] ?? "";
    }

    public function __toString() : string
    {
        return $this->token;
    }
}