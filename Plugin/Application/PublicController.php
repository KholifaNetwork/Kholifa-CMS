<?php


namespace Plugin\Application;


class PublicController extends \content\Controller
{
    /**
     * Go to /day to see the result
     * @return \content\View
     */
    public function day($day = null)
    {
        // Uncomment to include assets
        // knAddJs('assets/application.js');
        // knAddCss('assets/application.css');

        if (!$day) {
            $day = date('l');
        }

        $data = array(
            'day' => $day
        );

        //change the layout if you like
        //knSetLayout('home.php');

        return knView('view/day.php', $data);
    }



}
