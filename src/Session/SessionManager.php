<?php
    
namespace Auth\Session;

use Zend\Stdlib\AbstractOptions;
use Zend\Session\Container as SessionContainer;
use Zend\Session\SessionManager as ZendSessionManager;

class SessionManager extends AbstractOptions
{
    protected $configClass = "\Zend\Session\Config\SessionConfig";

    protected $configOptions = array();

    protected $storage = "\Zend\Session\Storage\SessionArrayStorage";

    protected $sessionSaveHandlerObject = null;

    protected $validators = array();

    protected $saveHandlerOrNOt = true;

    protected $saveHandler = "AuthSessionSaveHandler";

    public function setSaveHandlerOrNOt($saveHandlerOrNOt)
    {
        $this->saveHandlerOrNOt = (bool) $saveHandlerOrNOt;
    }

    public function getSaveHanderOrNOt()
    {
        return $this->saveHandlerOrNOt;
    }

    public function setSaveHandler($saveHandler)
    {
        $this->saveHandler = $saveHandler;
    }

    public function getSaveHandler()
    {
        return $this->saveHandler;
    }

    public function setConfigClass($configClass)
    {
        $this->configClass = $configClass;
    }

    public function setConfigOptions($configOptions) 
    {
        
        $this->configOptions = $configOptions;
    }

    public function setStorage($storage)
    {
        $this->storage = $storage;
    }

    public function setValidators(array $validators)
    {
        $this->validators = $validators;
    }

    public function addValidator($validator)
    {
        $this->validators[] = $validator;
    }

    private function getConfigObject()
    {
        $configObject = null;
        if ($this->configClass) {
            $configObject = new $this->configClass;
            try {
                $configObject->setOptions($this->configOptions);            
            } catch (\Exception $e) {
                
            }
            
        }
        return $configObject;

    }

    private function getStorageObject()
    {
        $sessionStorageObject = null;
        if ($this->storage) {
            $sessionStorageObject = new $this->storage;
        }
        return $sessionStorageObject;
    }

    private function getSessionSaveHandlerObject()
    {
  
        return $this->sessionSaveHandlerObject;
               
    }

    public function setSessionSaveHandlerObject($sessionSaveHandlerObject)
    {
        $this->sessionSaveHandlerObject = $sessionSaveHandlerObject;
    }

    public function getSessionManager()
    {
       $sessionManager = new ZendSessionManager(
            $this->getConfigObject(), 
            $this->getStorageObject(), 
            $this->getSessionSaveHandlerObject()
        );

        $chain = $sessionManager->getValidatorChain();
        foreach ($this->validators as $validator) {
            $validator = new $validator();
            $chain->attach('session.validate', array($validator, 'isValid'));

        }

        SessionContainer::setDefaultManager($sessionManager);
        return $sessionManager;
    }

} 
