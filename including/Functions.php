<?php

/**
 * @package Kholifa CMS
 *
 */


/**
 * Get application object.
 *
 * @return \including\Application Application object
 */
function knApplication()
{
    return \including\ServiceLocator::application();
}

/**
 * Get security token string. Used to prevent XSRF attacks.
 *
 * Security token is a long random string generated for currently browsing user.
 * @return string Security token string.
 */
function knSecurityToken()
{
    return knApplication()->getSecurityToken();
}

/**
 * Get option value
 *
 * Options can be viewed or changed using administration pages. You can use this function to get your plugin settings.
 * @param string $option Option name. Option names use syntax "PluginName.optionName".
 * @param mixed|null $defaultValue Default value. Returned if the option was not set.
 * @return mixed Option value.
 */
function knGetOption($option, $defaultValue = null)
{
    return \including\ServiceLocator::options()->getOption($option, $defaultValue);
}

/**
 * Get language specific option value
 *
 * @param string $option Option name. Option names use syntax "PluginName.optionName".
 * @param string $languageCode Language code.
 * @param mixed|null $defaultValue Default value. Returned if the option was not set.
 * @return mixed Option value.
 */
function knGetOptionLang($option, $languageCode = null, $defaultValue = null)
{
    if ($languageCode == null) {
        $languageCode = knContent()->getCurrentLanguage()->getCode();
    }
    return \including\ServiceLocator::options()->getOptionLang($option, $languageCode, $defaultValue);
}

/**
 * Set option value
 *
 * You can use this function to set your plugin settings. Also, options can be viewed or changed using administration pages.
 * @param string $option Option name. Option names use syntax "PluginName.optionName".
 * @param mixed $value Option value.
 */
function knSetOption($option, $value)
{
    \including\ServiceLocator::options()->setOption($option, $value);
}

/**
 * Set language specific option value
 *
 * @param string $option Option name. Option names use syntax PluginName.optionName.
 * @param mixed $value Option value.
 * @param string $languageCode Language code string.
 */
function knSetOptionLang($option, $value, $languageCode = null)
{
    if ($languageCode == null) {
        $languageCode = knContent()->getCurrentLanguage()->getCode();
    }

    \including\ServiceLocator::options()->setOptionLang($option, $languageCode, $value);
}

/**
 * Remove option
 *
 * Options can be viewed or changed using administration pages.
 * @param string $option Option name. Option names use syntax PluginName.optionName.
 */
function knRemoveOption($option)
{
    \including\ServiceLocator::options()->removeOption($option);
}

/**
 * Remove language specific option value
 *
 * @param string $option Option name. Option names use syntax PluginName.optionName.
 * @param int $languageId Language ID.
 * @return null
 */
function knRemoveOptionLang($option, $languageId)
{
    \including\ServiceLocator::options()->removeOptionLang($option, $languageId);
}

/**
 * Get website configuration object
 *
 * Use website configuration object to access configuration values, such as base URL, debug mode, current theme, etc.
 * @return \including\Config Configuration object.
 */
function knConfig()
{
    return \including\ServiceLocator::config();
}

/**
 * Get content object.
 *
 * Use this object to access pages and languages.
 * @return \including\Content Content object.
 */
function knContent()
{
    return \including\ServiceLocator::content();
}

/**
 * Add JavaScript file to a web page
 *
 * After adding all JavaScript files, issue ipJs() function to generate JavaScript links HTML code.
 * @param string $file JavaScript file pathname. Can be provided as URL address, a pathname relative to current directory or to website root.
 * Place CSS files in assets subdirectory of a theme or a plugin.
 * @param array|null $attributes for example array('id' => 'example')
 * @param int $priority JavaScript file priority. The lower the number the higher the priority.
 * @param bool $cacheFix add website version number at the end to force browser to reload new version of the file when website's cache is cleared
 */
function knAddJs($file, $attributes = null, $priority = 50, $cacheFix = true)
{
    if (preg_match('%(https?:)?//%', $file)) {
        $absoluteUrl = $file;
    } else {
        if (preg_match('%^(Plugin|Theme|file|including)/%', $file)) {
            $relativePath = $file;
        } else {
            $relativePath = \including\Internal\PathHelper::knRelativeDir(1) . $file;
        }

        $absoluteUrl = knFileUrl($relativePath);
    }

    \including\ServiceLocator::pageAssets()->addJavascript($absoluteUrl, $attributes, $priority, $cacheFix);
}

/**
 * Add JavaScript variable
 *
 * Generates JavaScript code which sets variables using specified values.
 * @param string $name JavaScript variable name.
 * @param mixed $value Variable value. Note: Do not use object as a value.
 */
function knAddJsVariable($name, $value)
{
    \including\ServiceLocator::pageAssets()->addJavascriptVariable($name, $value);
}

/**
 * Add inline JavaScript.
 * @param string $name JavaScript variable name.
 * @param string $value JavaScript
 * @param int $priority JavaScript file priority. The lower the number the higher the priority.
 */

function knAddJsContent($name, $value, $priority = 50)
{
    \including\ServiceLocator::pageAssets()->addJavascriptContent($name, $value, $priority);
}

/**
 * Add CSS file from your plugin or theme
 *
 * After adding all CSS files, use ipHead() function to generate HTML head.
 * @param string $file CSS file pathname. Can be provided as URL address, a pathname relative to current directory or to website root.
 * Place CSS files in assets subdirectory of a theme or a plugin.
 * @param array $attributes Attributes for HTML <link> tag. For example, attribute argument array('id' => 'example') adds HTML attribute id="example"
 * @param int $priority CSS priority (loading order). The lower the number the higher the priority.
 * @param bool $cacheFix add website version number at the end to force browser to reload new version of the file when website's cache is cleared
 */
function ipAddCss($file, $attributes = null, $priority = 50, $cacheFix = true)
{
    if (preg_match('%(https?:)?//%', $file)) {
        $absoluteUrl = $file;
    } else {
        if (preg_match('%^(Plugin|Theme|file|including)/%', $file)) {
            $relativePath = $file;
        } else {
            $relativePath = \including\Internal\PathHelper::knRelativeDir(1) . $file;
        }

        $absoluteUrl = knFileUrl($relativePath);
    }

    \including\ServiceLocator::pageAssets()->addCss($absoluteUrl, $attributes, $priority, $cacheFix);
}

/**
 * Return log object
 *
 * Use this object to create or access log records.
 * @return \Psr\Log\LoggerInterface Logger interface object (\including\Internal\Log\Logger)
 */
function knLog()
{
    return \including\ServiceLocator::log();
}

/**
 * Generate HTML code for loading JavaScript files
 *
 * Generate HTML code which loads JavaScript files added by ipAddJs() function.
 * @return string HTML code with links to JavaScript files.
 */
function knJs()
{
    return \including\ServiceLocator::pageAssets()->generateJavascript();
}

/**
 * Generate HTML head
 *
 * @return string Webpage HTML head
 */
function knHead()
{
    return \including\ServiceLocator::pageAssets()->generateHead();
}

/**
 * Set HTML layout file
 *
 * @param string $file Layout file name, e.g. "main.php".
 */
function knSetLayout($file)
{
    $response = \including\ServiceLocator::response();
    if (method_exists($response, 'setLayout')) {
        $response->setLayout($file);
    } else {
        knLog()->error('Response.cantSetLayout: Response has no setLayout method', array('response' => $response));
    }
}

/**
 * Get response object
 *
 * @return \including\Response\Layout | \including\Response\Layout response object
 */
function knResponse()
{
    return \including\ServiceLocator::response();
}

/**
 * Get current HTML layout name
 *
 * @return string HTML layout, e.g., "main.php".
 */
function knGetLayout()
{
    $response = \including\ServiceLocator::response();
    if (method_exists($response, 'getLayout')) {
        return $response->getLayout();
    } else {
        knLog()->error(
            'Response.cantGetLayout: Response method has no method getLayout',
            array('response' => $response)
        );
    }
    return null;
}

/**
 * Get block object
 *
 * @param string $block Block name, e.g. "main".
 * @return \including\Block Block object.
 */
function knBlock($block)
{
    return \including\ServiceLocator::content()->generateBlock($block);
}

/**
 * Generate slot HTML
 * http://www.kholifa.com/docs/slots
 *
 * @param string $slot Slot name.
 * @param array|null $params Slot parameters.
 * @return string
 */
function knSlot($slot, $params = array())
{
    return \including\ServiceLocator::slots()->generateSlot($slot, $params);
}

/**
 * Get management state
 *
 * Checks if the website is opened in management mode.
 * @return bool Returns true if the website is opened in management state.
 */

function knIsManagementState()
{
    return \including\Internal\Content\Service::isManagementMode();
}

/**
 * Get HTTP request object
 *
 * HTTP request object can be used to get HTTP POST, GET and SERVER variables, and to perform other HTTP request related tasks.
 * @return \including\Request Request object.
 */

function knRequest()
{
    return \including\ServiceLocator::request();
}

/**
 * Trigger an event
 *
 * @param string $event Event name, e.g. "MyPlugin_myEvent".
 * @param array $data Array with event data.
 * @return \including\Dispatcher Event dispatcher object.
 */
function knEvent($event, $data = array())
{
    return \including\ServiceLocator::dispatcher()->event($event, $data);
}

/**
 * Filter data
 *
 * Fires an event for transforming a value.
 * @param string $event Filter name, e.g. "MyPlugin_myFilter".
 * @param mixed $value Value to filter.
 * @param array $data Context array.
 * @return mixed Filtered value.
 */
function knFilter($event, $value, $data = array())
{
    return \including\ServiceLocator::dispatcher()->filter($event, $value, $data);
}

/**
 * Create a job
 *
 * @param string $eventName Job event name, e.g. "MyPlugin_myJob"
 * @param array $data Data for job processing.
 * @return mixed|null Job result value.
 */
function knJob($eventName, $data = array())
{
    return \including\ServiceLocator::dispatcher()->job($eventName, $data);
}

/**
 * Get database object
 *
 * Returns an object, which provides plugin developers with methods for connecting to database, executing SQL queries and fetching results.
 * @return \including\Db Database object.
 */
function knDb()
{
    return \including\ServiceLocator::db();
}

/**
 * Get escaped text string
 *
 * @param string $string Unescaped string.
 * @return string Escaped string.
 */
function esc($string)
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Get escaped HTML text area content
 *
 * @param string $value Unescaped string, containing HTML <textarea> tag content.
 * @return string Escaped string.
 */
function escTextarea($value)
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

/**
 * Get escaped HTML attribute.
 *
 * QUOTES ARE MANDATORY!!!
 *
 * Correct example:
 * &lt;div css=&quot;&lt;?php echo escAttr() ?&gt;&quot;&gt;
 *
 * Incorrect example:
 * &lt;div css=&lt;?php echo escAttr() ?&gt;&gt;
 * @param string $value Unescaped HTML attribute.
 * @return string Escaped string.
 */
function escAttr($value)
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

/**
 * Translate and escape a string
 *
 * @param string $text Original value in English.
 * @param string $domain Context, e.g. plugin name.
 * @param string $esc Escape type. Available values: false, 'html', 'attr', 'textarea'.
 * @return string Translated string or original string if no translation exists.
 * @throws including\Exception
 */
function __($text, $domain, $esc = 'html')
{
    $translation = \including\ServiceLocator::translator()->translate($text, $domain);

    if ('html' == $esc) {
        return esc($translation);
    } elseif (false === $esc) {
        return $translation;
    } elseif ('attr' == $esc) {
        return escAttr($translation);
    } elseif ('textarea' == $esc) {
        return escTextarea($translation);
    }

    throw new \including\Exception('Unknown escape method: {$esc}');
}

/**
 * You can change translation locale for some code.
 *
 * @param string $languageCode
 * @param callable $closure this code will be executed in given language.
 * @return mixed old language or the result of closure.
 */
function knSetTranslationLanguage($languageCode, \Closure $closure = null)
{
    if ($closure) {
        $oldLanguage = knSetTranslationLanguage($languageCode);

        $result = $closure();

        knSetTranslationLanguage($oldLanguage);

        return $result;
    } else {
        $oldLanguage = \including\ServiceLocator::translator()->getLocale();
        \including\ServiceLocator::translator()->setLocale($languageCode);

        return $oldLanguage;
    }
}

/**
 * Translate, escape and then output a string
 *
 * @param string $text Original value in English.
 * @param string $domain Context, e.g. plugin name.
 * @param string $esc Escape type. Available values: false, 'html', 'attr', 'textarea'.
 */
function _e($text, $domain, $esc = 'html')
{
    echo __($text, $domain, $esc);
}


/**
 * Gets absolute file path
 *
 * @param string $path A path or a pathname.
 * @return mixed|string Absolute path or pathname.
 * @throws \including\Exception
 */
function knFile($path)
{
    global $knFile_baseDir, $knFile_coreDir, $knFile_composerPlugins; // Optimization: caching these values speeds things up a lot.

    if (!$knFile_baseDir) {
        $knFile_baseDir = knConfig()->get('baseDir');
        $knFile_coreDir = knConfig()->get('coreDir');
        $knFile_composerPlugins = knConfig()->get('composerPlugins');
    }

    if (strpos($path, 'Plugin/') === 0) {
        $parts = explode('/', $path);
        if (empty($parts[1])) {
            return $knFile_baseDir . '/' . $path;
        }

        if (!empty($knFile_composerPlugins[$parts[1]])) {
            return dirname($knFile_baseDir) . '/' . $knFile_composerPlugins[$parts[1]] . '/' . implode('/', array_slice($parts, 2));
        }

        return $knFile_baseDir . '/' . $path;
    }

    if (
        strpos($path, 'Theme/') === 0 ||
        strpos($path, 'file/') === 0 ||
        $path === ''
    ) {
        return $knFile_baseDir . '/' . $path;
    }

    if (
        strpos($path, 'including/') === 0
    ) {
        return $knFile_coreDir . '/' . $path;
    }

    throw new \including\Exception('knFile function accepts only paths, that start with including/, Plugin/, Theme/, file/. Requested path: ' . $path);
}



/**
 * Gets URL by a file name
 *
 * @param string $path Pathname relative to current directory or root.
 * @return mixed|string File's URL address.
 */
function knFileUrl($path)
{
    $overrides = knConfig()->get('urlOverrides');
    if ($overrides) {
        foreach ($overrides as $prefix => $newPath) {
            if (strpos($path, $prefix) === 0) {
                return substr_replace($path, $newPath, 0, strlen($prefix));
            }
        }
    }
    return knConfig()->baseUrl() . $path;
}


/**
 * Generate URL-encoded query string
 *
 * @param array $query Associative (or indexed) array.
 * @return string URL string.
 */
function knActionUrl($query)
{
    return knConfig()->baseUrl() . '?' . http_build_query($query);
}

/**
 * @param string $route
 * @param array $params
 * @return string
 */
function knRouteUrl($route, $params = array())
{
    return knHomeUrl() . \including\ServiceLocator::router()->generate($route, $params);
}

/**
 * Get URL address of current theme folder
 *
 * @param string $path Path or pathname relative to current theme directory.
 * @return mixed|string Theme's URL path
 */
function knThemeUrl($path)
{
    return knFileUrl('Theme/' . knConfig()->theme() . '/' . $path);
}

/**
 * Gets the file path of the current theme folder
 *
 * @param string $path A path or a pathname relative to Theme/ directory.
 * @return mixed|string Absolute path or pathname.
 */
function knThemeFile($path)
{
    return knFile('Theme/' . knConfig()->theme() . '/' . $path);
}

/**
 * @param string|null $languageCode
 * @return string
 */
function knHomeUrl($languageCode = null)
{
    $homeUrl = knConfig()->baseUrl();
    if (knConfig()->get('rewritesDisabled')) {
        $homeUrl .= 'index.php/';
    }

    if ($languageCode == null) {
        $language = knContent()->getCurrentLanguage();
    } else {
        $language = knContent()->getLanguageByCode($languageCode);
    }
    $homeUrl .= $language->getUrlPath();

    return $homeUrl;
}

/**
 * Generate widget HTML
 *
 * @param string $widgetName Widget name.
 * @param array $data Widget's data.
 * @param null $skin Widget skin name.
 * @return string Widget HTML.
 */
function knRenderWidget($widgetName, $data = array(), $skin = null)
{
    return \including\Internal\Content\Model::generateWidgetPreviewFromStaticData($widgetName, $data, $skin);
}

/**
 * Get formatted byte string
 *
 * Returns a string containing a rounded numeric value and appropriate 'B', 'KB', 'MB', 'GB', 'TB', 'PB' modifiers.
 *
 * @param int $bytes Size in bytes.
 * @param string $context plugin name
 * @param int $precision number of digits after the decimal point
 * @param string $languageCode
 * @return string A string formatted in byte size units.
 */
function knFormatBytes($bytes, $context, $precision = 0, $languageCode = null)
{
    return \including\Internal\FormatHelper::formatBytes($bytes, $context, $precision, $languageCode);
}

/**
 * Get formatted currency string. If you don't like the way the price is formatted by default, catch knFormatPrice job and provide your own formatting method.
 *
 * @param int $price Numeric price. Multiplied by 100.
 * @param string $currency Three letter currency code. E.g. "EUR".
 * @param string $context Plugins name that's requesting the operation. This makes it possible to render the price differently for each plugin.
 * @param string $languageCode
 * @return string A currency string in specific country format.
 */
function knFormatPrice($price, $currency, $context, $languageCode = null)
{
    return \including\Internal\FormatHelper::formatPrice($price, $currency, $context, $languageCode);
}

/**
 * Get formatted date string
 *
 * @param int $unixTimestamp Unix timestamp.
 * @param string $context A context string: "including", "including-admin" or plugin's name.
 * @param string $languageCode
 * @return string|null A date string formatted according to country format.
 */
function knFormatDate($unixTimestamp, $context, $languageCode = null)
{
    return \kn\Internal\FormatHelper::formatDate($unixTimestamp, $context, $languageCode);
}

/**
 * Get formatted time string
 *
 * @param int $unixTimestamp Unix timestamp.
 * @param string $context A context string: "including", "including-admin" or plugin's name.
 * @param string $languageCode
 * @return string|null A time string formatted according to country format.
 */
function knFormatTime($unixTimestamp, $context, $languageCode = null)
{
    return \including\Internal\FormatHelper::formatTime($unixTimestamp, $context, $languageCode);
}

/**
 * Get formatted date-time string
 *
 * @param int $unixTimestamp Unix timestamp.
 * @param string $context A context: "including", "including-admin" or plugin's name.
 * @param string $languageCode
 * @return bool|mixed|null|string A date-time string formatted according to country format.
 */
function knFormatDateTime($unixTimestamp, $context, $languageCode = null)
{
    return \including\Internal\FormatHelper::formatDateTime($unixTimestamp, $context, $languageCode);
}

/**
 * Get a theme option value.
 *
 * Theme options ar used for changing theme design. These options can be managed using administration page.
 * @param string $name Option name.
 * @param mixed|null $default A value returned if the option was not set.
 * @return string Theme option value.
 */
function knGetThemeOption($name, $default = null)
{
    $themeService = \including\Internal\Design\Service::instance();
    return $themeService->getThemeOption($name, $default);
}

/**
 * Get HTML attributes for <html> tag.
 *
 * @param int|null $doctype Doctype value. For constant value list, see \including\Response\Layout class definition.
 * @return string A string with generated attributes for <html> tag.
 */
function knHtmlAttributes($doctype = null)
{
    $content = \including\ServiceLocator::content();
    if ($doctype === null) {
        $doctypeConstant = knConfig()->get('defaultDoctype');
        $doctype = constant('\including\Response\Layout::' . $doctypeConstant);
    }

    switch ($doctype) {
        case \including\Response\Layout::DOCTYPE_XHTML1_STRICT:
        case \including\Response\Layout::DOCTYPE_XHTML1_TRANSITIONAL:
        case \including\Response\Layout::DOCTYPE_XHTML1_FRAMESET:
            $lang = $content->getCurrentLanguage()->getCode();
            $answer = ' xmlns="http://www.w3.org/1999/xhtml" xml:lang="' . $lang . '" lang="' . $lang . '"';
            break;
        case \including\Response\Layout::DOCTYPE_HTML4_STRICT:
        case \including\Response\Layout::DOCTYPE_HTML4_TRANSITIONAL:
        case \including\Response\Layout::DOCTYPE_HTML4_FRAMESET:
        default:
            $answer = '';
            break;
        case \including\Response\Layout::DOCTYPE_HTML5:
            $lang = $content->getCurrentLanguage()->getCode();
            $answer = ' lang="' . escAttr($lang) . '"';
            break;
    }

    return $answer;
}

/**
 * Get HTML document type declaration string
 *
 * @param int|null $doctype Doctype value. For constant value list, see \including\Response\Layout class definition.
 * @return string Document type declaration string.
 * @throws Exception
 */
function knDoctypeDeclaration($doctype = null)
{
    if ($doctype === null) {
        $doctypeConstant = knConfig()->get('defaultDoctype');
        $doctype = constant('\including\Response\Layout::' . $doctypeConstant);
    }

    switch ($doctype) {
        case \including\Response\Layout::DOCTYPE_XHTML1_STRICT:
            $answer = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
            break;
        case \including\Response\Layout::DOCTYPE_XHTML1_TRANSITIONAL:
            $answer = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
            break;
        case \including\Response\Layout::DOCTYPE_XHTML1_FRAMESET:
            $answer = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">';
            break;
        case \including\Response\Layout::DOCTYPE_HTML4_STRICT:
            $answer = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">';
            break;
        case \including\Response\Layout::DOCTYPE_HTML4_TRANSITIONAL:
            $answer = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">';
            break;
        case \including\Response\Layout::DOCTYPE_HTML4_FRAMESET:
            $answer = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">';
            break;
        case \including\Response\Layout::DOCTYPE_HTML5:
            $answer = '<!DOCTYPE html>';
            break;
        default:
            throw new Exception('Unknown doctype: ' . $doctype);
    }

    return $answer;
}


/**
 * Get SQL table name by adding database prefix
 * @param string $table SQL table name without prefix.
 * @param bool $as SQL "as" keyword to be added.
 * @return string Actual SQL table name.
 */
function knTable($table, $as = false)
{
    $prefix = knConfig()->tablePrefix();
    $answer = '`' . $prefix . $table . '`';
    if ($as === true) {
        if ($prefix) { // If table prefix is empty we don't need to use `tableName` as `tableName`.
            $answer .= ' as `' . $table . '` ';
        }
    } elseif ($as) {
        $answer .= ' as `' . $as . '` ';
    }

    return $answer;
}

/**
 * Check the permission to access plugin's administration
 *
 * Check if user has a right to access plugin's Admin controller.
 * @param $permission
 * @param null $administratorId
 * @return bool Returns true if user has plugin's administration permission.
 */
function knAdminPermission($permission, $administratorId = null)
{
    return \including\ServiceLocator::adminPermissions()->hasPermission($permission, $administratorId);
}

/**
 * Send e-mail message
 *
 * Adds new e-mail to the queue. If possible, Kholifa CMS will send the email immediately.
 * If hourly e-mail limit is exhausted, emails will be sent in the next hour.
 * Kholifa CMS always preserve 20% of hourly limit for urgent emails. So even if you have
 * just added thousands of non urgent e-mails, urgent e-mails will still be sent immediately.
 * Set $urgent parameter to false when delivery time is not so important, like newsletters, etc.
 * And set $urgent to true, when sending notification about purchase, etc.
 * @param string $from Sender's e-mail address
 * @param string $fromName Sender's name
 * @param string $to Recipient's email address
 * @param string $toName Recipient's name
 * @param string $subject Message subject
 * @param string $content Content to be sent (html or plain text. See $html attribute). If you need e-mail templates, use ipEmailTemplate() function to generate the content.
 * @param bool $urgent E-mail urgency
 * @param bool $html HTML mode. Set to false for plain text mode.
 * @param string|array|null $files Full pathname of the file to be attached or array of pathnames.
 */
function knSendEmail($from, $fromName, $to, $toName, $subject, $content, $urgent = true, $html = true, $files = null)
{
    $emailQueue = new \including\Internal\Email\Module();
    $emailQueue->addEmail($from, $fromName, $to, $toName, $subject, $content, $urgent, $html, $files);
    $emailQueue->send();
}

/**
 * Generates e-mail message HTML using given template data, such as title, content, signature, footer, etc.
 * To send a message generated using knEmailTemplate() function, use knSendEmail() function.
 *
 * This function uses the default template, located at Internal/Config/view/email.php file. You can use your own template by overriding the default one.
 * @param array $data Associative array with template content. Default template outputs HTML using following array elements: 'title', 'content', 'signature', 'footer'.
 * @return string Generated e-mail message in HTML format.
 */
function knEmailTemplate($data)
{
    return knView('Internal/Config/view/email.php', $data)->render();
}

/**
 * Get MVC view object
 *
 * Get a view object using specified view file and data array.
 * @param string $file MVC view file pathname.
 * @param array $data View's data.
 * @param int $_callerDepth
 * @return \including\View
 * @throws \including\Exception\View
 */
function knView($file, $data = array(), $_callerDepth = 0)
{
    if ($file[0] == '/' || $file[1] == ':') { // Absolute filename
        return new \including\View($file, $data);
    }

    if (preg_match('%^(Plugin|Theme|file|including)/%', $file)) {
        $relativePath = $file;
    } else {
        $relativePath = \including\Internal\PathHelper::knRelativeDir($_callerDepth + 1) . $file;
    }

    $fileInThemeDir = knThemeFile(\including\View::OVERRIDE_DIR . '/' . $relativePath);

    if (is_file($fileInThemeDir)) {
        return new \including\View($fileInThemeDir, $data);
    }

    $absolutePath = knFile($relativePath);
    if (file_exists($absolutePath)) {
        // The most common case
        return new \including\View($absolutePath, $data);
    }

    // File was not found, check whether it is in theme override dir.
    if (strpos($relativePath, 'Theme/' . knConfig()->theme() . '/override/') !== 0) {
        $file = esc($file);
        throw new \including\Exception\View("View {$file} not found.");
    }


    $pathFromWebsiteRoot = str_replace(knFile('Theme/' . knConfig()->theme() . '/override/'), '', $absolutePath);
    return knView($pathFromWebsiteRoot);
}

/**
 * Get Key-Value storage object
 *
 * @return \including\Storage Storage object
 */
function knStorage()
{
    return \including\ServiceLocator::storage();
}

/**
 * Get currently logged-in administrator ID
 *
 * @return int|bool Administrator ID. Returns false if not logged-in as administrator.
 */
function knAdminId()
{
    return \including\Internal\Admin\Service::adminId();
}

/**
 * @param int|null $pageId
 * @return \including\PageStorage
 */
function knPageStorage($pageId = null)
{
    if (!$pageId) {
        $page = knContent()->getCurrentPage();
        if (!$page) {
            return null;
        }

        $pageId = $page->getId();
    }

    return new \including\PageStorage($pageId);
}

/**
 * @param string|null $theme
 * @return \including\ThemeStorage
 */
function knThemeStorage($theme = null)
{
    if (!$theme) {
        $theme = knConfig()->theme();
    }

    return new \including\ThemeStorage($theme);
}

/**
 * Get a modified copy of original file in repository
 *
 * @param string $file filename relative to /file/repository directory. Full path will not work.
 * @param array $options image transformation options.
 * @param string|null $desiredName desired filename of modified copy. A number will be added if desired name is already taken.
 * @param bool $onDemand transformation will be create on the fly when image accessed for the first time.
 * @return string path to modified copy starting from website's root. Use knFileUrl and knFile functions to get full URL or full path to that file.
 */
function knReflection($file, $options, $desiredName = null, $onDemand = true)
{
    $reflectionService = \including\Internal\Repository\ReflectionService::instance();
    $reflection = $reflectionService->getReflection($file, $options, $desiredName, $onDemand);
    return $reflection;
}

/**
 * Get last exception of knReflection method
 *
 * @return \including\Exception\Repository\Transform|null
 */
function knReflectionException()
{
    $reflectionService = \including\Internal\Repository\ReflectionService::instance();
    return $reflectionService->getLastException();
}

/**
 * @param int $pageId
 * @return \including\Page
 */
function knPage($pageId)
{
    return new \including\Page($pageId);
}

/**
 * This method copy provided file into repository assuring unique name.
 * Usually the file you want to add to the repository reside in tmp dir or so. Where you had been working on it.
 * After this function is executed, you can safely remove the source file.
 *
 * @param string $file absolute path to file in tmp directory.
 * @param null|string $desiredName desired file name in repository.
 * @return string relative file name in repository.
 * @throws \including\Exception
 */
function knRepositoryAddFile($file, $desiredName = null)
{
    $repositoryModel = \including\Internal\Repository\Model::instance();
    return $repositoryModel->addFile($file, $desiredName);
}

/**
 * Mark repository file as being used by a plugin. The point of this is to
 * instruct Kholifa CMS to prevent original file in repository from accidental deletion.
 * See knUnbindFile on how to undo this action and mark asset as not being used by the plugin.
 * @param string $file file name relative to file/repository/. Eg. 'im-naked-in-the-shower.jpg'
 * @param string $plugin plugin name that uses the asset.
 * @param int $id single plugin might bind to the same file several times. In that case plugin might differentiate those binds by $id. If you sure this can't be the case for your plugin, use 1. You have to use the same id in ipUnbindFile
 * @param string $baseDir by default repository locate files in 'file/repository/'. If you work with 'file/secure' dir, pass this value here.
 */
function knBindFile($file, $plugin, $id, $baseDir = 'file/repository/')
{
    \including\Internal\Repository\Model::bindFile($file, $plugin, $id, $baseDir);
}

/**
 * Release file binding. See knBindFile for more details.
 *
 * @param string $file file name relative to file/repository/. Eg. 'im-naked-in-the-shower.jpg'
 * @param string $plugin plugin name that uses the asset.
 * @param int $id single plugin might bind to the same file several times. In that case plugin might differentiate those bind by $id.
 * @param string $baseDir by default repository locate files in 'file/repository/'. If you work with 'file/secure/' dir, pass this value here.
 */
function knUnbindFile($file, $plugin, $id, $baseDir = 'file/repository/')
{
    \including\Internal\Repository\Model::unbindFile($file, $plugin, $id, $baseDir);
}

/**
 * Get user login manipulation object.
 * Eg.
 *
 * knUser()->loggedIn(); //check if user is logged in
 * knUser()->userId(); //get logged in user id
 * knUser()->data(); //get all user related data. All plugins can contribute their input and add values to this array by catching ipUserData filter.
 *
 * @return \including\User
 */
function knUser()
{
    $user = new \including\User();
    return $user;
}

/**
 * Get ecommerce object
 *
 * Use this object to access ecommerce related methods.
 * @return \including\Ecommerce
 */
function knEcommerce()
{
    return \including\ServiceLocator::ecommerce();
}


/**
 * Get info about current route
 * @return \including\Route
 */
function knRoute()
{
    return \including\ServiceLocator::route();
}


/**
 * Initialize grid in controller
 * @param $config array
 * @throws including\Exception
 * @throws including\Exception\View
 * @return \including\Response\Json|\including\Response\JsonRpc
 */
function knGridController($config)
{
    $request = knRequest()->getRequest();

    if (empty($request['method'])) {
        //Grid initialization. Add JS and display GRID's HTML
        knAddJs('including/Internal/Grid/assets/grid.js');
        knAddJs('including/Internal/Grid/assets/gridInit.js');
        knAddJs('including/Internal/Grid/assets/subgridField.js');

        $backtrace = debug_backtrace();
        if (empty($backtrace[1]['object']) || empty($backtrace[1]['function']) || empty($backtrace[1]['class'])) {
            throw new \including\Exception('knGridController() function must be used only in controller.');
        }
        $method = $backtrace[1]['function'];

        $controllerClassParts = explode('\\', $backtrace[1]['class']);
        if (empty($controllerClassParts[2])) {
            throw new \including\Exception('knGridController() function must be used only in controller (' . $backtrace[1]['class'] . '). ');
        }
        $plugin = $controllerClassParts[1];

        switch($controllerClassParts[2]) {
            case 'AdminController':
                $gateway = array('aa' => $plugin . '.' . $method);
                break;
            case 'SiteController':
                $gateway = array('sa' => $plugin . '.' . $method);
                break;
            case 'PublicController':
                $gateway = array('pa' => $plugin . '.' . $method);
                break;
            default:
                throw new \including\Exception('knGridController() function must be used only in controller (' . $backtrace[1]['class'] . '). ');
        }

        if (!empty($config['gatewayData'])) {
            $gateway = array_merge($config['gatewayData'], $gateway);
        }

        $variables = array(
            'gateway' => knActionUrl($gateway)
        );

        $content = knView('including/Internal/Grid/view/placeholder.php', $variables);
        return $content;
    } else {
        //GRID AJAX method
        $worker = new \including\Internal\Grid\Worker($config);
        $result = $worker->handleMethod(knRequest());

        if (is_array($result) && !empty($result['error']) && !empty($result['errors'])) {
            return new \including\Response\Json($result);
        }

        return new \including\Response\JsonRpc($result);
    }


}

/**
 * Convert price from one currency to another.
 * This method throws knConvertCurrency job. Any plugin that claims knowing how to convert one currency to another can provide the answer.
 * This method has no default implementation. So if you will request currency conversion that's not covered by any of the plugins, you will get null as the result.
 * @param int $amount amount in cents
 * @param string $sourceCurrency three letter uppercase currency code. Eg. USD
 * @param $destinationCurrency three letter uppercase currency code. Eg. USD
 * @return int amount in cents
 */
function knConvertCurrency($amount, $sourceCurrency, $destinationCurrency)
{
    $result = knJob('knConvertCurrency', compact('amount', 'sourceCurrency', 'destinationCurrency'));
    return $result;
}


/**
 * Get unocupied file name in directory. Very useful when storing uploaded files.
 *
 * @param string $dir
 * @param string $desiredName
 * @param bool $sanitize clean up supicious symbols from file name
 * @return string
 */
function knUnoccupiedFileName($dir, $desiredName, $sanitize = true)
{
    $availableFileName = \including\Internal\File\Functions::genUnoccupiedName($desiredName, $dir, '', $sanitize);
    return $availableFileName;
}


/**
 * Replace placeholders with actual values in string or array of strings. Default placeholders:
 * websiteTitle
 * websiteEmail
 * websiteUrl
 * userId
 * userEmail
 * userName
 *
 * @param string $content
 * @param array $customValues
 * @param string $context plugin name which executes the function. Makes possible to have different values in different contexts.
 * @return string
 */
function knReplacePlaceholders($content, $context = 'including', $customValues = array())
{

    $info = array (
        'content' => $content,
        'context' => $context,
        'customValues' => $customValues
    );
    if (is_array($content)) {
        $answer = array();
        foreach($content as $item) {
            $answer[] = knJob('knReplacePlaceholders', $info);
        }
        return $answer;
    } else {
        return knJob('knReplacePlaceholders', $info);
    }
}
