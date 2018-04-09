<?php

namespace Plugin\Colorbox;

class Event
{
    public static function knBeforeController()
    {
        $style = knGetOption('Colorbox.style', 1);
        knAddCss('Plugin/Colorbox/assets/theme' . $style . '/colorbox.css');
        knAddJs('Plugin/Colorbox/assets/colorbox/jquery.colorbox-min.js');
        knAddJs('Plugin/Colorbox/assets/colorboxInit.js');
    }
}
