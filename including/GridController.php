<?php
/**
 * @package  Kholifa CMS
 */

namespace including;

abstract class GridController extends \including\Controller
{
    public function index()
    {
        knAddJs('including/Internal/Grid/assets/grid.js');
        knAddJs('including/Internal/Grid/assets/gridInit.js');
        knAddJs('including/Internal/Grid/assets/subgridField.js');


        $controllerClass = get_class($this);
        $controllerClassParts = explode('\\', $controllerClass);

        $aa = $controllerClassParts[count($controllerClassParts) - 2] . '.grid';

        $gateway = array('aa' => $aa);

        $variables = array(
            'gateway' => knActionurl($gateway)
        );
        $content = knView('Internal/Grid/view/placeholder.php', $variables)->render();
        return $content;
    }

    public function grid()
    {
        $worker = new \including\Internal\Grid\Worker($this->config());
        $result = $worker->handleMethod(knRequest());

        if (is_array($result) && !empty($result['error']) && !empty($result['errors'])) {
            return new \including\Response\Json($result);
        }

        return new \including\Response\JsonRpc($result);
    }

    /**
     * @return array
     */
    abstract protected function config();


}
