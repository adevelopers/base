<?php
/**
 * Created by PhpStorm.
 * User: adeveloper aka Худяков Кирилл
 * Date: 23.10.2015
 * Time: 10:49
 */
namespace Portal\Components;
use Bitrix\Main;

if(!defined('B_PROLOG_INCLUDED')||B_PROLOG_INCLUDED!==true)die();

\CBitrixComponent::includeComponentClass(basename(dirname(__DIR__)).':base');

/**
 * Class BaseRouter - класс комплексной компоненты
 * @package Portal\Components
 */
abstract class BaseRouter extends \CBitrixComponent{
    use General;

    /**
     * @var страница шаблона по умолчанию
     */
    protected $defaultSefPage='index'; // template.php если пустая

    /**
     * @var array Paths of templates default
     */
    public $defaultUrlTemplates=array(
        "list" => "index.php",
        "detail" => "#ELEMENT_ID#/",
        "detail_code"=>"code/#ELEMENT_CODE#/",
        "blog"=>"blog/#BLOG_URL#/"
    );

    /**
     * Set type of the page
     */
    protected function setPage()
    {
        $urlTemplates = array();

        if ($this->arParams['SEF_MODE'] === 'Y')
        {
            $variables = array();

            $urlTemplates = \CComponentEngine::MakeComponentUrlTemplates(
                $this->defaultUrlTemplates,
                $this->arParams['SEF_URL_TEMPLATES']
            );

            $variableAliases = \CComponentEngine::MakeComponentVariableAliases(
                $this->defaultUrlTemplates,
                $this->arParams['VARIABLE_ALIASES']
            );

            $this->templatePage = \CComponentEngine::ParseComponentPath(
                $this->arParams['SEF_FOLDER'],
                $urlTemplates,
                $variables
            );

            if (!$this->templatePage)
            {
                if ($this->arParams['SET_404'] === 'Y')
                {
                    $folder404 = str_replace('\\', '/', $this->arParams['SEF_FOLDER']);

                    if ($folder404 != '/')
                    {
                        $folder404 = '/'.trim($folder404, "/ \t\n\r\0\x0B")."/";
                    }

                    if (substr($folder404, -1) == '/')
                    {
                        $folder404 .= 'index.php';
                    }

                    if ($folder404 != Main\Context::getCurrent()->getRequest()->getRequestedPage())
                    {
                        $this->return404();
                    }
                }

                $this->templatePage = $this->defaultSefPage;
            }

           /* if ($this->isSearchRequest() && $this->arParams['USE_SEARCH'] === 'Y')
            {
                $this->templatePage = 'search';
            } */

            \CComponentEngine::InitComponentVariables(
                $this->templatePage,
                $this->componentVariables,
                $variableAliases,
                $variables
            );
        }
        else
        {
            $this->templatePage = $this->defaultPage;
        }

        $this->arResult['FOLDER'] = $this->arParams['SEF_FOLDER'];
        $this->arResult['URL_TEMPLATES'] = $urlTemplates;
        $this->arResult['VARIABLES'] = $variables;
        $this->arResult['ALIASES'] = $variableAliases;
    }
}