<?php
declare(strict_types=1);
namespace config;

use model\AbstractEntity;
use model\AbstractEntityManager;
use model\Thread;
use model\ThreadManager;
use lib\DBManager;
use lib\Utils;
use view\templates\AbstractHtmlTemplate;

class Conf extends AbstractConf
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
        $this->deployGeneral();
        $this->deployManagers();
    }

    /**
     * @return void
     */
    protected function deployGeneral(): void
    {
        DBManager::$bddConfig = $this->_config['bddConfig'];
        AbstractHtmlTemplate::setBaseUrl($this->_config['baseUrl']);
        AbstractEntity::$db = DBManager::getInstance();
        AbstractEntityManager::$db = DBManager::getInstance();
        Utils::$debugFile = dirname(__FILE__, 3) . '/logs/debug.log';
    }

    protected function deployManagers() : void
    {
        Thread::$manager = new ThreadManager();
    }
}
