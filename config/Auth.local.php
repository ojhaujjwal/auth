<?php
/**
 * Auth Configuration
 *
 * If you have a ./config/autoload/ directory set up for your project, you can
 * drop this config file in it and change the values as you wish.
 */

$auth_settings = array(

    /**
     * Log In Modules
     *
     * Please set modules where logged in user can only gain access
     */
    //'modules' => array(),

    /**
     * Controllers where you can set if user can access or not
     *
     * 'controllers' => array(
        'include' => array(), // Please set controllers where logged in user can only gain access


        'exclude' => array(), // Please set controllers where public user as well as logged in user can gain access
        // Note that, this part will override above `include` and `modules`
     ),
    */

);

$session_settings = array(
    /**
     * Session Config Class
     *
     * Name of class used to manage session config options. Useful to create your own
     * Session Config Class.  Default is Zend\Session\Config\SessionConfig.
     * The class should implement Zend\Session\Config\ConfigInterface.
     */
    //'config_class' => 'Zend\Session\Config\SessionConfig',

    /**
     * Session Config Options
     *
     * session options such as name, save_path can be set from here
     * This is the part sent to Session Config Class. Default is empty array.
     */
    //'config_options' => array(),


    /**
     * Session Storage Class
     *
     */
    //'storage' => 'Zend\Session\Storage\SessionArrayStorage',

    /**
     * Session Validators
     *
     * Session validators provide various protection against session hijacking.
     * see http://framework.zend.com/manual/2.2/en/modules/zend.session.validator.html for more details
     */
    //'validators' => array(),


    /**
     * Use session save handler or not.
     * 
     * Default is true. Useful to store session data in database
     * see http://php.net/manual/en/function.session-set-save-handler.php
     * Accept values: true and false
     */
    //'save_handler_or_not' => true,

    /**
     * Session Save Handler DI Alias
     *
     * Please specify the DI alias for the configured AuthSessionSaveHandler
     * instance that this module should use.
     * Default is AuthSessionSaveHandler which is provided by this module.
     * This class should implement Zend\Session\SaveHandler\SaveHandlerInterface
     * Note that, if above is false, this will be useless
     */
    //'saveHandler' => 'AuthSessionSaveHandler'
);

/**
 * You do not need to edit below this line
 */
return array(
    'auth' => $auth_settings,
    'sessions' => $session_settings,
);
