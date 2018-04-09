<?php
/**
 * Kholifa CMS main frontend file
 * @package Kholifa CMS
 */
if ((PHP_MAJOR_VERSION < 5) || (PHP_MAJOR_VERSION == 5 && PHP_MINOR_VERSION < 5)) {
    echo 'Your PHP version is: '.PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION.'. To run Kholifa CMS you need PHP >= 5.5';
    exit;
}

require_once 'including/script/run.php';
