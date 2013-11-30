<?php

namespace Auth;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Session\Container as SessionContainer;
use Zend\Db\TableGateway\TableGateway;
use Zend\Session\SaveHandler\DbTableGateway;
use Zend\Session\SaveHandler\DbTableGatewayOptions;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $this->bootstrapSession($e);
    }


    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            "Zend\Loader\ClassMapAutoloader" => array(
                __DIR__ . "/autoload_classmap.php",
            ),
            "Zend\Loader\StandardAutoloader" => array(
                "namespaces" => array(
                    __NAMESPACE__ => __DIR__ . "/src/" . __NAMESPACE__,
                ),
            ),
        );
    }

    
    public function bootstrapSession($e)
    {
        $sm = $e->getApplication()->getServiceManager();
        $session = $sm->get('Auth\Session\Manager');
        $sessionBootstraper = new Session\BootstrapSession($session);
        $sessionBootstraper->bootstrap(); 
        $e->getApplication()->getEventManager()->getSharedManager()->attach('Zend\Mvc\Controller\AbstractController', 'dispatch', function($e) use($sm) {            
            $controller      = $e->getTarget();
            $AuthOrNot = $sm->get('AuthOrNot');
            $config = $e->getApplication()->getServiceManager()->get('config')['auth'];

            if (isset($config['modules'])) {
                $AuthOrNot->setModules($config['modules']);
            }

            if (isset($config['controllers'])) {
                $AuthOrNot->setControllers($config['controllers']);
            }

            $AuthOrNot->setRequestController($controller);

            if ($AuthOrNot->isLoginRequired()) {
                $auth = $sm->get('zfcuser_auth_service');
                if (!$auth->hasIdentity()) {
                    $controller->flashMessenger()->setNamespace('zfcuser-login-form')->addMessage("Please log in to view the page!");
                    $loginRoute = isset($config['auth']['login_route']) ? $config['auth']['login_route'] : "zfcuser/login";
                    return call_user_func_array(array($controller->plugin("redirect"),"toRoute"),(array) $loginRoute);
                    exit;
                }                 
            }

        }, PHP_INT_MAX);
            
    }
    

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Auth\Session\Manager' => function ($sm) {
                    $config = isset($sm->get('config')['sessions']) ? $sm->get('config')['sessions'] : array();
                    $HSessionManager = new Session\SessionManager($config);
                    if ($HSessionManager->getSaveHanderOrNOt()) {
                        $HSessionManager->setSessionSaveHandlerObject($sm->get($HSessionManager->getSaveHandler()));
                    }
                    $SessionManager = $HSessionManager->getSessionManager();
                    return $SessionManager;
                },
                'AuthSessionSaveHandler' => function ($sm) {
                    $tableGateway = new TableGateway('session', $sm->get('Zend\Db\Adapter\Adapter'));
                    $saveHandler = new DbTableGateway($tableGateway, new DbTableGatewayOptions());
                    return $saveHandler;
                }
                
            ),
            'invokables' => array(
                'AuthOrNot' => 'Auth\Model\AuthOrNot'
            )
        );
    }

}
