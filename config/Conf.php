<?php
declare(strict_types=1);
namespace config;

use services\DBManager;
use view\templates\AbstractHtmlTemplate;

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
            'baseUrl'       => 'http://openclassrooms.local/Formation_OC_Symfony_P6_TomTroc/',        );
    }

    public function deploy(): void
    {
        DBManager::$bddConfig = $this->_config['bddConfig'];
        AbstractHtmlTemplate::setBaseUrl($this->_config['baseUrl']);
    }
}
