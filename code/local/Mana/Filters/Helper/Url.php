<?php

class Mana_Filters_Helper_Url extends Mage_Core_Helper_Abstract
{

    protected $actionParams = array(
                                      'category'        => array('module' => 'catalog', 'controller' => 'category', 'action' => 'view'),
                                      'catalogsearch'   => array('module' => 'catalogsearch', 'controller' => 'result', 'action' => 'index'),
                                      'discounts'       => array('module' => 'discounts', 'controller' => 'index', 'action' => 'index'),
                                      'cms'             => array('module' => 'cms', 'controller' => 'page', 'action' => 'layered'),
                                   );

    protected $ajaxActions = array(
                                      'category'        => array('module' => 'filters', 'controller' => 'category', 'action' => 'view'),
                                      'catalogsearch'   => array('module' => 'filters', 'controller' => 'search', 'action' => 'index'),
                                      'discounts'       => array('module' => 'filters', 'controller' => 'discounts', 'action' => 'index'),
                                      'cms'             => array('module' => 'filters', 'controller' => 'page', 'action' => 'layered'),
                                  );

    public function getFilterUrl($currentUrl)
    {
        $request = Mage::app()->getRequest();
        $module = $request->getModuleName();
        $controller = $request->getControllerName();
        $action = $request->getActionName();

        $filterUrl = $currentUrl;

        foreach($this->actionParams as $key => $param)
        {
            if($module == $param['module'] && $controller == $param['controller'] && $action == $param['action'])
            {
                $filterUrl = Mage::getBaseUrl();

                $filterUrl .= $this->ajaxActions[$key]['module'].'/'.$this->ajaxActions[$key]['controller'].'/'.$this->ajaxActions[$key]['action'];
                if($category = Mage::registry('current_category'))
                    $filterUrl .= '/id/'.$category->getId();
                $requestParams = explode("?", $currentUrl);
                if(isset($requestParams[1]))
                    $filterUrl .= '?'.$requestParams[1];
                break;
            }
        }

        return $filterUrl;
    }
}