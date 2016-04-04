<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\Model\Carro;
use Application\Model\CarroTable;
use Application\Model\Perfil;
use Application\Model\PerfilTable;
use Application\Model\Usuario;
use Application\Model\UsuarioTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Resource\GenericResource;
use Zend\Permissions\Acl\Role\GenericRole;



class Module {
    protected $acl = null;
    public function onBootstrap(MvcEvent $e) {
    
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        //criacao do globals
        $GLOBALS['sm'] = $e->getApplication()->getServiceManager();
        
        $application = $e->getApplication();
                
        $this->configurarAcl($e);
        $e->getApplication()
                ->getEventManager()
                ->attach('route', array(
                    $this,
                    'checkAcl'
        ));
    }
    
    public function loadConfiguration(MvcEvent $e) {

        $application = $e->getApplication();
        $sm = $application->getServiceManager();


        if ($sm->get('AuthService')->hasIdentity()) {
            $usuario = $sm->get('Autenticacao\Model\AutenticacaoStorage')->read();
            if (!empty($usuario->perfil->id)) {
                return $usuario->perfil->id;
            }
        }
    }
     public function configurarAcl(MvcEvent $e) {

        $this->acl = new Acl();
        $aclRoles = include __DIR__ . '/config/module.acl.perfis.php';
        $allResources = array();
        $this->acl->addResource(new GenericResource("Application\Controller\Index"));
        $this->acl->addResource(new GenericResource("Autenticacao\Controller\Auth"));
        
        foreach ($aclRoles as $valores) {
            $role = new GenericRole($valores['role']);
            if (!$this->acl->hasRole(($role)))
                $this->acl->addRole($role);

            if (!$this->acl->hasResource('deny'))
                $this->acl->addResource(new GenericResource('deny'));

            if (!$this->acl->hasResource($valores['resource']))
                $this->acl->addResource(new GenericResource($valores['resource']));


            $this->acl->allow($role, $valores['resource'], $valores['privileges']);

            $this->acl->allow($role, 'Application\Controller\Index', array());
            $this->acl->allow($role, 'Autenticacao\Controller\Auth', array());
        }
        $e->getViewModel()->acl = $this->acl;
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

    public function getServiceConfig() {
        return array(
            'factories' => array(
                'Application\Model\UsuarioTable' => function($sm) {
                    $tableGateway = $sm->get('UsuarioTableGateway');
                    $table = new UsuarioTable($tableGateway);
                    return $table;
                },
                'UsuarioTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Usuario());
                    return new TableGateway('usuario', $dbAdapter, null, $resultSetPrototype);
                },
                'Application\Model\PerfilTable' => function($sm) {
                    $tableGateway = $sm->get('PerfilTableGateway');
                    $table = new PerfilTable($tableGateway);
                    return $table;
                },
                'PerfilTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Perfil());
                    return new TableGateway('perfil', $dbAdapter, null, $resultSetPrototype);
                },
                'Application\Model\CarroTable' => function($sm) {
                    $tableGateway = $sm->get('CarroTableGateway');
                    $table = new CarroTable($tableGateway);
                    return $table;
                },
                'CarroTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Carro());
                    return new TableGateway('carro', $dbAdapter, null, $resultSetPrototype);
                },
                        
            ),
        );
    }
     public function checkAcl(MvcEvent $e) {
        $route = $e->getRouteMatch()->getMatchedRouteName();
        $resource = $e->getRouteMatch()->getParam('controller');

        if (!$this->acl->hasResource($resource))
            $this->acl->addResource(new GenericResource($resource));

        $perfilId = $this->loadConfiguration($e);
        if (empty($perfilId)) {
            if ($route != 'autenticar') {
                $response = $e->getResponse();
                $response->getHeaders()->addHeaderLine('Location', $e->getRequest()->getBaseUrl() . '/autenticar/');
                $response->setStatusCode(404);
                $response->sendHeaders();
                exit;
            }
        } else {
            $privilegio = $e->getRouteMatch()->getParam('action');

            // echo  $e->getRouteMatch()->getParam('action');exit;
            if (!$e->getViewModel()->acl->isAllowed($perfilId, $resource, $privilegio)) {
                if ($route != 'deny') {
                    $response = $e->getResponse();
                    $response->getHeaders()->addHeaderLine('Location', $e->getRequest()->getBaseUrl() . '/deny/');
                    $response->setStatusCode(404);
                    $response->sendHeaders();
                    exit;
                }
            }
        }
    }
}
