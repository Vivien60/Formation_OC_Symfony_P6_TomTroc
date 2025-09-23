<?php
declare(strict_types=1);
namespace config;

use services\DBManager;

class Conf extends \config\AbstractConf
{
    protected function defaultConfig(): array
    {
        return array(
            "bddConfig"     => [
                "dsn"           => "mysql:host=localhost;dbname=tomtroc;charset=utf8",
                "user"          => getenv("MYSQL_USERNAME"),
                "password"      => '',
            ],
        );
    }

    public function deploy(): void
    {
        DBManager::$bddConfig = $this->_config['bddConfig'];
    }
}
