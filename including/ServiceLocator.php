<?php
/**
 * @package Kholifa CMS
 *
 */

namespace including;


/**
 *
 * Locate system services
 *
 */
class ServiceLocator
{
    protected static $requests = array();
    protected static $routes = array();
    protected static $dispatchers = array();
    protected static $contents = array();
    protected static $ecommerce = array();
    protected static $responses = array();
    protected static $config = null;
    protected static $log = null;
    protected static $options = null;
    protected static $storage = null;
    protected static $db;
    protected static $translator;
    protected static $permissions;
    protected static $slots;
    protected static $pageAssets = array();
    protected static $routers = array();

    protected static $serviceClasses = array(
        'db' => '\including\Db',
        'reflection' => '\including\Reflection',
        'options' => '\including\Options',
        'storage' => '\including\Storage',
        'log' => '\including\Internal\Log\Logger',
        'translator' => '\including\Internal\Translations\Translator',
        'dispatcher' => '\including\Dispatcher',
        'response' => '\including\Response\Layout',
        'content' => '\including\Content',
        'adminPermissions' => '\including\Internal\AdminPermissions',
        'slots' => '\including\Internal\Slots',
        'pageAssets' => '\including\Internal\PageAssets',
        'router' => '\including\Router',
        'ecommerce' => '\including\Ecommerce',
        'route' => '\including\Route',
    );

    /**
     * @return \including\Options
     */
    public static function options()
    {
        if (self::$options == null) {
            self::$options = static::loadService('options');
        }
        return self::$options;
    }


    /**
     * @return \including\Storage
     */
    public static function storage()
    {
        if (self::$storage == null) {
            self::$storage = static::loadService('storage');
        }
        return self::$storage;
    }

    /**
     * @return \including\Internal\PageAssets
     */
    public static function pageAssets()
    {
        return end(self::$pageAssets);
    }

    /**
     * @return \including\Config
     */
    public static function config()
    {
        return self::$config;
    }

    /**
     * @param \including\Config $config
     */
    public static function setConfig($config)
    {
        self::$config = $config;

        $serviceClasses = $config->get('services');
        if ($serviceClasses) {
            static::$serviceClasses = array_merge(static::$serviceClasses, $serviceClasses);
        }
    }

    /**
     * @return \Psr\Log\LoggerInterface
     */
    public static function log()
    {
        if (self::$log == null) {
            self::$log = static::loadService('log');
        }
        return self::$log;
    }

    /**
     * @return Dispatcher
     */
    public static function dispatcher()
    {
        return end(self::$dispatchers);
    }


    /**
     * @return \including\Application
     */
    public static function application()
    {
        global $application;
        return $application;
    }


    /**
     * Add new request to HMVC queue
     * Used by Application. Never add requests manually.
     * @param $request
     */
    public static function addRequest($request)
    {
        self::$requests[] = $request;
        self::$dispatchers[] = static::loadService('dispatcher');
        self::$contents[] = static::loadService('content');
        self::$responses[] = static::loadService('response');
        self::$slots[] = static::loadService('slots');
        self::$pageAssets[] = static::loadService('pageAssets');
        self::$routers[] = static::loadService('router');
        self::$ecommerce[] = static::loadService('ecommerce');
        self::$routes[] = static::loadService('route');
    }

    /**
     * Remove request from HMVC. Last request should always stay intact and can't be removed as it is needed for application close action
     */
    public static function removeRequest()
    {
        if (count(self::$requests) > 1) {
            array_pop(self::$dispatchers);
            array_pop(self::$requests);
            array_pop(self::$contents);
            array_pop(self::$responses);
            array_pop(self::$slots);
            array_pop(self::$pageAssets);
            array_pop(self::$routers);
            array_pop(self::$ecommerce);
            array_pop(self::$routes);
        }
    }

    /**
     * @return \including\Request
     */
    public static function request()
    {
        return end(self::$requests);
    }

    /**
     * @return \including\Content
     */
    public static function content()
    {
        return end(self::$contents);
    }

    /**
     * @return \including\Ecommerce
     */
    public static function ecommerce()
    {
        return end(self::$ecommerce);
    }

    /**
     * @return \including\Internal\Slots
     */
    public static function slots()
    {
        return end(self::$slots);
    }


    /**
     * @return \including\Response\Layout
     */
    public static function response()
    {
        return end(self::$responses);
    }

    /**
     * @param Response $response
     */
    public static function setResponse(\including\Response $response)
    {
        array_pop(self::$responses);
        self::$responses[] = $response;
    }

    /**
     * @return \including\Db
     */
    public static function db()
    {
        if (static::$db === null) {
            static::$db = static::loadService('db');
        }

        return static::$db;
    }

    /**
     * @param $db
     * @return Db
     */
    public static function setDb($db)
    {
        $curDb = self::db();
        static::$db = $db;
        return $curDb;
    }

    /**
     * @return \including\Internal\Translations\Translator
     */
    public static function translator()
    {
        if (static::$translator === null) {
            static::$translator = static::loadService('translator');
        }

        return static::$translator;
    }

    protected static function loadService($name)
    {
        return new static::$serviceClasses[$name]();
    }

    /**
     * @return \including\Internal\AdminPermissions
     */
    public static function adminPermissions()
    {
        if (static::$permissions === null) {
            static::$permissions = static::loadService('adminPermissions');
        }

        return static::$permissions;
    }

    /**
     * @return \including\Router
     */
    public static function router()
    {
        return end(self::$routers);
    }

    /**
     * @return \including\Route
     */
    public static function route()
    {
        return end(self::$routes);
    }


}
