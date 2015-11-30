<?
namespace Portal\Components;

include_once __DIR__.'/traits/general.php';

use \Bitrix\Main;

if(!defined('B_PROLOG_INCLUDED')||B_PROLOG_INCLUDED!==true)die();


abstract class CBaseComponent extends \CBitrixComponent{

use General;
    /**
     * страница шаблона
     * @var string
     */
    protected $page = '';

    /**
     * получение результатов
     */
    protected function getResult()
    {
        $arRes = array();

        return $this->arResult = $arRes;
    }


    /**
     * выполняет логику работы компонента
     */
    public function executeComponent()
    {
        try
        {
            //$this->setSefDefaultParams();
            $this->getResult();
            $this->includeComponentTemplate($this->page);
        }
        catch (Exception $e)
        {
            ShowError($e->getMessage());
        }
    }
}
