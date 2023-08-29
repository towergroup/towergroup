<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main;
use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc as Loc;

class StandardVacancyListComponent extends CBitrixComponent
{
    const IBLOCK_ID = VACANCIES_IBLOCK_ID;

    /**
     * подключает языковые файлы
     */
    public function onIncludeComponentLang()
    {
        $this->includeComponentLang(basename(__FILE__));
        Loc::loadMessages(__FILE__);
    }

    /**
     * подготавливает входные параметры
     * @param array $arParams
     * @return array
     */
    public function onPrepareComponentParams($params)
    {
        return $params;
    }

    /**
     * проверяет подключение необходиимых модулей
     * @throws LoaderException
     */
    protected function checkModules()
    {
        if (!Main\Loader::includeModule('iblock')) {
            throw new Main\LoaderException(Loc::getMessage('IBLOCK_MODULE_NOT_INSTALLED'));
        }
    }

    /**
     * проверяет заполнение обязательных параметров
     * @throws SystemException
     */
    protected function checkParams()
    {
    }

    /**
     * выполяет действия перед кешированием
     */
    protected function executeProlog()
    {
    }

    /**
     * Выполнение логики работы компонента
     */
    protected function exec()
    {
        /***********
 * Разделы *
 ***********/

        $request = Application::getInstance()->getContext()->getRequest();
        $isAjax = $request->isAjaxRequest();
        $page = ($request->get('page')) ? $request->get('page') : 1;
        $arFilter = array('IBLOCK_ID' => self::IBLOCK_ID, 'ACTIVE' => 'Y');
        $arNavStartParams = array('nPageSize'=>$this->arParams["VACANCIES_PAGE_COUNT_LIST"], 'iNumPage'=>$page);

        $rsVacancies = \CIBlockElement::GetList(array("ACTIVE_FROM"=>"DESC", "SORT"=>"ASC"), $arFilter, false,
            $arNavStartParams,
            array(
                "ACTIVE",
                "ACTIVE_FROM",
                "CREATED_DATE",
                "NAME",
                "IBLOCK_ID",
                "IBLOCK_SECTION_ID",
                "ID",
                "PREVIEW_TEXT",
                "PROPERTY_SALARY",
                "PROPERTY_REQUIREMENTS",
                "PROPERTY_DUTIES"
            ));
        while ($vacancies = $rsVacancies->GetNext()) {
            $arVacancies[] = $vacancies;
        }

        $arOutput = array(
            "VACANCIES" => $arVacancies,
            "IS_AJAX" => $isAjax,
            "NAV_RESULT" => $rsVacancies,
        );

        $this->arResult = $arOutput;

        return true;
    }


    /**
     * выполняет действия после выполения компонента, например установка заголовков из кеша
     */
    protected function executeEpilog()
    {
    }

    /**
     * выполняет логику работы компонента
     */
    public function executeComponent()
    {
        try {
            $this->checkModules();
            $this->checkParams();
            $this->executeProlog();
            $this->exec();
            $this->includeComponentTemplate();
            $this->executeEpilog();
        } catch (Exception $e) {
            ShowError($e->getMessage());
        }
    }
}