<?php

namespace Portal\Components;


use Bitrix\Main;
use Bitrix\Main\Loader;

trait General{

    /**
     * @var array массив с названиями модулей, которые необходимо подключить при работе компонента
     */
    protected $needModules = array();

    /**
     * @var string имя страницы шаблона компонента
     */
    protected $templatePage;

    /**
     * Include modules
     *
     * @uses $this->needModules
     * @throws \Bitrix\Main\LoaderException
     */
    protected function includeModules()
    {
        if (empty($this->needModules))
        {
            return false;
        }

        foreach ($this->needModules as $module)
        {

            if (!Loader::includeModule($module))
            {
                throw new Main\LoaderException('Failed include module "'.$module.'"');
            }
        }
    }


}