<?php
/**
 * @package   Kholifa CMS
 */

namespace including;

/**
 * Base class for Kholifa CMS application
 */


class Application
{
    const ASSETS_DIR = 'assets';
    protected $configSetting = null;

    /**
     * @param string|array $configSetting string to the configuration directory or configuration data array
     */
    public function __construct($configSetting = null)
    {
        $this->configSetting = $configSetting;
    }

    /**
     * Get framework version
     * @return string
     */
    public static function getVersion()
    {
        return '5.0.3'; //CHANGE_ON_VERSION_UPDATE
    }


    /**
     * @ignore
     */
    public function init()
    {
        //this function has been left here just to avoid any issues with old index.php fies running it.
    }

    /**
     * @ignore
     * @param array $options
     */
    public function prepareEnvironment($options = array())
    {
        if (empty($options['skipErrorHandler'])) {
            set_error_handler(array('including\Internal\ErrorHandler', 'knErrorHandler'));
        }

        if (empty($options['skipError'])) {
            if (knConfig()->showErrors()) {
                error_reporting(E_ALL | E_STRICT);
                ini_set('display_errors', '1');
            } else {
                ini_set('display_errors', '0');
            }
        }

        if (empty($options['skipSession'])) {
            if (session_id() == '' && !headers_sent()) { //if session hasn't been started yet
                session_name(knConfig()->get('sessionName', 'Kholifa CMS'));
                if (!knConfig()->get('disableHttpOnlySetting')) {
                    ini_set('session.cookie_httponly', 1);
                }

                session_start();
            }

            $expireIn = knConfig()->get('sessionMaxIdle', 1800);
            if (isset($_SESSION['module']['admin']['last_activity']) && (time() - $_SESSION['module']['admin']['last_activity'] > $expireIn)) {
                session_unset();
                session_destroy();
            }
            $_SESSION['module']['admin']['last_activity'] = time();
        }

        if (empty($options['skipEncoding'])) {
            mb_internal_encoding(knConfig()->get('charset'));
        }

        if (empty($options['skipTimezone']) && knConfig()->get('timezone')) {
            date_default_timezone_set(knConfig()->get('timezone')); //PHP 5 requires timezone to be set.
        }
    }


    protected function initTranslations($languageCode)
    {
        $translator = \including\ServiceLocator::translator();
        $translator->setLocale($languageCode);

        if (knConfig()->adminLocale()) {
            $translator->setAdminLocale(knConfig()->adminLocale());
        }

        $theme = knConfig()->theme();
        $originalDir = knFile('file/translations/original/');
        $overrideDir = knFile('file/translations/override/');
        $themeDir = knFile("Theme/$theme/translations/");
        $knDir = knFile('including/Internal/Translations/translations/');

        $translator->addTranslationFilePattern('json', $originalDir, "$theme-%s.json", $theme);
        $translator->addTranslationFilePattern('json', $themeDir, "$theme-%s.json", $theme);
        $translator->addTranslationFilePattern('json', $overrideDir, "$theme-%s.json", $theme);

        $translator->addTranslationFilePattern('json', $originalDir, 'including-admin-%s.json', 'including-admin');
        $translator->addTranslationFilePattern('json', $knDir, 'including-admin-%s.json', 'including-admin');
        $translator->addTranslationFilePattern('json', $overrideDir, 'including-admin-%s.json', 'including-admin');

        $translator->addTranslationFilePattern('json', $originalDir, 'including-%s.json', 'including');
        $translator->addTranslationFilePattern('json', $knDir, 'including-%s.json', 'including');
        $translator->addTranslationFilePattern('json', $overrideDir, 'including-%s.json', 'including');
    }

    /**
     * @ignore
     * @param Request $request
     * @param array $options
     * @param bool $subrequest
     * @return Response\Json|Response\PageNotFound|Response\Redirect
     * @throws Exception
     * @ignore
     */

    public function _handleOnlyRequest(\including\Request $request, $options = array(), $subrequest = true)
    {
        if (empty($options['skipInitEvents'])) {
            \including\ServiceLocator::dispatcher()->_bindApplicationEvents();
        }


        $result = knJob('knRouteLanguage', array('request' => $request, 'relativeUri' => $request->getRelativePath()));
        if ($result) {
            $requestLanguage = $result['language'];
            $routeLanguage = $requestLanguage->getCode();
            knRequest()->_setRoutePath($result['relativeUri']);
        } else {
            $routeLanguage = null;
            $requestLanguage = knJob('knRequestLanguage', array('request' => $request));
            knRequest()->_setRoutePath($request->getRelativePath());
        }

        if ($requestLanguage) {
            $this->setLocale($requestLanguage);
            knContent()->_setCurrentLanguage($requestLanguage);
            $_SESSION['knLastLanguageId'] = $requestLanguage->getId();
        }

        if (empty($options['skipTranslationsInit'])) {
            if (!empty($options['translationsLanguageCode'])) {
                $languageCode = $options['translationsLanguageCode'];
            } else {
                $languageCode = $requestLanguage->getCode();
            }
            $this->initTranslations($languageCode);
        }

        if (empty($options['skipModuleInit'])) {
            $this->modulesInit();
        }
        knEvent('knInitFinished');


        $routeAction = knJob(
            'knRouteAction',
            array('request' => $request, 'relativeUri' => knRequest()->getRoutePath(), 'routeLanguage' => $routeLanguage)
        );

        if (!empty($routeAction)) {
            if (!empty($routeAction['page'])) {
                knContent()->_setCurrentPage($routeAction['page']);
            }
            if (!empty($routeAction['environment'])) {
                knRoute()->setEnvironment($routeAction['environment']);
            } else {
                if ((!empty($routeAction['controller'])) && $routeAction['controller'] == 'AdminController') {
                    knRoute()->setEnvironment(\including\Route::ENVIRONMENT_ADMIN);
                } else {
                    knRoute()->setEnvironment(\including\Route::ENVIRONMENT_PUBLIC);
                }
            }
            if (!empty($routeAction['controller'])) {
                knRoute()->setController($routeAction['controller']);
            }
            if (!empty($routeAction['plugin'])) {
                knRoute()->setPlugin($routeAction['plugin']);
            }
            if (!empty($routeAction['name'])) {
                knRoute()->setName($routeAction['name']);
            }
            if (!empty($routeAction['action'])) {
                knRoute()->setAction($routeAction['action']);
            }
        }


        //check for CSRF attack
        if (knRoute()->environment() != \including\Route::ENVIRONMENT_PUBLIC && empty($options['skipCsrfCheck']) && $request->isPost() && ($request->getPost(
                    'securityToken'
                ) != $this->getSecurityToken(
                )) && (empty($routeAction['controller']) || $routeAction['controller'] != 'PublicController')
        ) {

            knLog()->error('Core.possibleCsrfAttack', array('post' => knRequest()->getPost()));
            $data = array(
                'status' => 'error'
            );
            if (knConfig()->isDevelopmentEnvironment()) {
                $data['errors'] = array(
                    'securityToken' => __('Possible CSRF attack. Please pass correct securityToken.', 'Ip-admin')
                );
            }
            // TODO JSONRPC
            return new \including\Response\Json($data);
        }

        if (empty($routeAction)) {
            $routeAction = array(
                'plugin' => 'Core',
                'controller' => 'PublicController',
                'action' => 'pageNotFound'
            );
        }

        $eventInfo = $routeAction;

        if (!empty($routeAction['plugin'])) {

            $plugin = $routeAction['plugin'];
            $controller = $routeAction['controller'];

            if (in_array($plugin, \including\Internal\Plugins\Model::getModules())) {
                $controllerClass = 'including\\Internal\\' . $plugin . '\\' . $controller;
            } else {
                if (!in_array($plugin, \including\Internal\Plugins\Service::getActivePluginNames())) {
                    throw new \including\Exception("Plugin '" . esc($plugin) . "' doesn't exist or isn't activated.");
                }
                $controllerClass = 'Plugin\\' . $plugin . '\\' . $controller;
            }

            if (!class_exists($controllerClass)) {
                throw new \including\Exception('Requested controller doesn\'t exist. ' . esc($controllerClass));
            }

            // check if user is logged in
            if ($controller == 'AdminController' && !\including\Internal\Admin\Backend::userId()) {

                if (knConfig()->get('rewritesDisabled')) {
                    return new \including\Response\Redirect(knConfig()->baseUrl() . 'index.php/admin');
                } else {
                    return new \including\Response\Redirect(knConfig()->baseUrl() . 'admin');
                }
            }

            if ($controller == 'AdminController') {
                if (!knAdminPermission($plugin)) {
                    throw new \including\Exception('User has no permission to access ' . esc($plugin) . '');
                }
            }

            $eventInfo['controllerClass'] = $controllerClass;
            $eventInfo['controllerType'] = $controller;
        }

        if (empty($eventInfo['page'])) {
            $eventInfo['page'] = null;
        }

        // change layout if safe mode
        if (\including\Internal\Admin\Service::isSafeMode()) {
            knSetLayout(knFile('including/Internal/Admin/view/safeModeLayout.php'));
        } else {
            if ($eventInfo['page']) {
                knSetLayout($eventInfo['page']->getLayout());
            }
        }

        if (knConfig()->database()) {
            knEvent('knBeforeController', $eventInfo);
        }

        $controllerAnswer = knJob('knExecuteController', $eventInfo);

        return $controllerAnswer;
    }

    /**
     * Handle HMVC request
     * @param Request $request
     * @param array $options
     * @param bool $subrequest
     * @return Response\Json|Response\Layout|Response\PageNotFound|Response\Redirect|string
     * @throws Exception
     */
    public function handleRequest(Request $request, $options = array(), $subrequest = true)
    {

        \including\ServiceLocator::addRequest($request);

        $rawResponse = $this->_handleOnlyRequest($request, $options, $subrequest);

        if (!empty($options['returnRawResponse'])) {
            if ($subrequest) {
                \including\ServiceLocator::removeRequest();
            }
            return $rawResponse;
        }

        if (empty($rawResponse) || is_string($rawResponse) || $rawResponse instanceof \Ip\View) {
            if ($rawResponse instanceof \including\View) {
                $rawResponse = $rawResponse->render();
            }
            if (empty($rawResponse)) {
                $rawResponse = '';
            }

            $response = \including\ServiceLocator::response();
            $response->setContent($rawResponse);
        } elseif ($rawResponse instanceof \including\Response) {
            \including\ServiceLocator::setResponse($rawResponse);
            if ($subrequest) {
                \including\ServiceLocator::removeRequest();
            }
            return $rawResponse;
        } elseif ($rawResponse === null) {
            $response = \including\ServiceLocator::response();
        } else {
            \including\ServiceLocator::removeRequest();
            throw new \including\Exception('Unknown response');
        }

        if ($subrequest) {
            \including\ServiceLocator::removeRequest();
        }

        return $response;
    }

    /**
     * @ignore
     */
    public function modulesInit()
    {
        if (!knConfig()->database()) {
            return;
        }
        $translator = \including\ServiceLocator::translator();
        $overrideDir = knFile("file/translations/override/");

        $plugins = \including\Internal\Plugins\Service::getActivePluginNames();
        foreach ($plugins as $plugin) {

            $translationsDir = knFile("Plugin/$plugin/translations/");
            $translator->addTranslationFilePattern('json', $translationsDir, "$plugin-%s.json", $plugin);
            $translator->addTranslationFilePattern('json', $overrideDir, "$plugin-%s.json", $plugin);

            $translator->addTranslationFilePattern('json', $translationsDir, "$plugin-admin-%s.json", $plugin . '-admin');
            $translator->addTranslationFilePattern('json', $overrideDir, "$plugin-admin-%s.json", $plugin . '-admin');
        }


        foreach ($plugins as $plugin) {
            $routesFile = knFile("Plugin/$plugin/routes.php");
            $this->addFileRoutes($routesFile, $plugin);
        }
        $this->addFileRoutes(knFile('including/Internal/Ecommerce/routes.php'), 'Ecommerce');

    }

    protected function addFileRoutes($routesFile, $plugin)
    {
        $router = \including\ServiceLocator::router();
        if (file_exists($routesFile)) {
            $routes = array();
            include $routesFile;

            $router->addRoutes(
                $routes,
                array(
                    'plugin' => $plugin,
                    'controller' => 'PublicController',
                )
            );
        }
    }

    /**
     * @ignore
     * @param array $options
     */
    public function run($options = array())
    {
        $config = new \including\Config($this->configSetting);
        \including\ServiceLocator::setConfig($config);

        require_once __DIR__ . '/Functions.php';

        $this->prepareEnvironment($options);
        $request = new \including\Request();

        $request->setQuery($_GET);
        $request->setPost($_POST);
        $request->setServer($_SERVER);
        $request->setRequest($_REQUEST);


        $response = $this->handleRequest($request, $options, false);
        $this->handleResponse($response);
        $this->close();
    }

    /**
     * @ignore
     * @param \including\Response $response
     * @throws \including\Exception
     */
    public function handleResponse(\including\Response $response)
    {
        $response = knFilter('knSendResponse', $response);
        knEvent('knBeforeResponseSent', array('response' => $response));
        if (method_exists($response, 'execute')) {
            $response = $response->execute();
        }
        $response->send();
    }

    /**
     * @ignore
     */
    public function close()
    {
        knEvent('knBeforeApplicationClosed');
        if (knConfig()->database()) {
            knDb()->disconnect();
        }
    }

    /**
     * Get security token used to prevent cross site scripting attacks
     *
     * @return string security token
     */
    public function getSecurityToken()
    {
        if (empty($_SESSION['knSecurityToken'])) {
            $_SESSION['knSecurityToken'] = md5(uniqid(rand(), true));
        }
        return $_SESSION['knSecurityToken'];
    }

    /**
     * @param $requestLanguage
     */
    protected function setLocale($requestLanguage)
    {
        //find out and set locale
        $locale = $requestLanguage->getCode();
        if (strlen($locale) == '2') {
            $locale = strtolower($locale) . '_' . strtoupper($locale);
        } else {
            $locale = str_replace('-', '_', $locale);
        }
        $locale .= '.utf8';
        if ($locale == "tr_TR.utf8" && (PHP_MAJOR_VERSION == 5 && PHP_MINOR_VERSION < 5)) { //Overcoming this bug https://bugs.php.net/bug.php?id=18556
            setlocale(LC_COLLATE, $locale);
            setlocale(LC_MONETARY, $locale);
            setlocale(LC_NUMERIC, $locale);
            setlocale(LC_TIME, $locale);
            setlocale(LC_MESSAGES, $locale);
            setlocale(LC_CTYPE, "en_US.utf8");
        } else {
            setLocale(LC_ALL, $locale);
        }
        setlocale(LC_NUMERIC, "C"); //user standard C syntax for numbers. Otherwise you will get funny things with when autogenerating CSS, etc.
    }
}
