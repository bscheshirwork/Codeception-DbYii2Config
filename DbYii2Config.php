<?php
namespace bscheshirwork\Codeception\Module;

use Codeception\Configuration;
use Codeception\Exception\ModuleConfigException;
use Codeception\Lib\Connector\Yii2 as Yii2Connector;

/**
 * Db module with Yii2 database config.
 *
 * Dependence: Yii2 codeception module (part: init)
 * Must be init after Yii2
 *
 */
class DbYii2Config extends \Codeception\Module\Db
{

    /**
     * @var \yii\db\Connection
     */
    public $db;

    /**
     * @var array
     */
    protected $requiredFields = [];

    public function _setConfig($config)
    {
        $moduleConfig = (new \ReflectionMethod($this->moduleContainer, 'getModuleConfig'))
            ->getClosure($this->moduleContainer)('Yii2');
        if (!is_file($configFile = Configuration::projectDir() . $moduleConfig['configFile'])) {
            throw new ModuleConfigException(
                __CLASS__,
                "The yii2 application config file does not exist: " . $configFile
            );
        }
        defined('YII_ENV') or define('YII_ENV', 'test');
        $client = new Yii2Connector();
        $client->configFile = $configFile;
        $this->db = $client->getApplication()->db;
        parent::_setConfig(array_merge(
            $config,
            [
                'dsn' => $this->db->dsn,
                'user' => $this->db->username,
                'password' => $this->db->password,
            ]));
    }
}
