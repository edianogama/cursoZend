<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Localizacao;

use Localizacao\Model\Cidade;
use Localizacao\Model\CidadeTable;
use Localizacao\Model\Estado;
use Localizacao\Model\EstadoTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;



class Module {

    public function onBootstrap(MvcEvent $e) {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    //configuracao de banco de dados
    public function getServiceConfig() {
        return array(
            'factories' => array(
                'Localizacao\Model\EstadoTable' => function($sm) {
                    $tableGateway = $sm->get('EstadoTableGateway');
                    $table = new EstadoTable($tableGateway);
                    return $table;
                },
                'EstadoTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Estado());
                    return new TableGateway('estado', $dbAdapter, null, $resultSetPrototype);
                },
                'Localizacao\Model\CidadeTable' => function($sm) {
                    $tableGateway = $sm->get('CidadeTableGateway');
                    $table = new CidadeTable($tableGateway);
                    return $table;
                },
                'CidadeTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Cidade());
                    return new TableGateway('cidade', $dbAdapter, null, $resultSetPrototype);
                }
                        
            ),
        );
    }

}
