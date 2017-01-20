<?php
namespace bscheshirwork\Codeception\Module;

use Codeception\Configuration;
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
        $module = $this->getModule('Yii2');
        $module->_initialize();
        $client = new Yii2Connector();
        $client->configFile = Configuration::projectDir() . $module->_getConfig('configFile');
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
