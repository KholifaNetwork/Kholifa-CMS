<?php
/**
 * @package Kholifa CMS
 *
 *
 */

namespace including;


class Page404 extends Page
{
    public function __construct()
    {
    }

    public function getTitle()
    {
        return knGetOptionLang('Config.websiteTitle', null, 'Page not found');
    }

    public function getMetaTitle()
    {
        return knGetOptionLang('Config.websiteTitle', null, 'Page not found');
    }

    public function generateContent()
    {
    }

    public function getType()
    {
        return 'error404';
    }
}
