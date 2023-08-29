<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main;
use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc as Loc;

class StandardReviewsListComponent extends CBitrixComponent
{
    const IBLOCK_ID = REVIEWS_IBLOCK_ID;

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
        $broker_id = ($request->get('broker_id') && $request->get('broker_id') !== 'all') ? $request->get('broker_id') : "";
        $sort = ($request->get('active_from') && $request->get('active_from') !== 'default') ? $request->get('active_from') : "";
        $sortDefault = true;
        if ($sort) {
            switch ($sort) {
                case "desc" :
                    $sortDefault = false;
                    $arSort['order'] = "DESC";
                    break;
                case "asc" :
                    $sortDefault = false;
                    $arSort['order'] = "ASC";
                    break;
                default :
                    $arSort['order'] = $this->arParams["SORT_ORDER1"];
                    break;
            }
        }
        else {
            $arSort['order'] = $this->arParams["SORT_ORDER1"];
        }
        $arSort['order'] =
        $arSortReviews = array($this->arParams["SORT_BY1"]=> $arSort['order'], $this->arParams["SORT_BY2"]=> $this->arParams["SORT_ORDER2"]);
        $arFilterReviews = array('IBLOCK_ID' => self::IBLOCK_ID, 'ACTIVE' => 'Y' );
        if ($request->get('reviews_type') == 'video') {
            $arFilterReviews['>PROPERTY_'.strtoupper($request->get('reviews_type'))] = 0;
        }
        else {
            $arFilterReviews['!DETAIL_TEXT'] = false;
        }
        $arFilterReviews['PROPERTY_BROKER'] = $broker_id;
        $arFilterBrokers = array('IBLOCK_ID' => EMPLOYEES_IBLOCK_ID, 'ACTIVE' => 'Y');
        $arNavStartParams = array('nPageSize'=>$this->arParams["REVIEWS_PAGE_COUNT_LIST"], 'iNumPage'=>$page);

        $rsReviews = \CIBlockElement::GetList($arSortReviews, $arFilterReviews, false,
            $arNavStartParams,
            array(
                "ACTIVE",
                "ACTIVE_FROM",
                "CREATED_DATE",
                "NAME",
                "IBLOCK_ID",
                "IBLOCK_SECTION_ID",
                "ID",
                "DETAIL_TEXT",
                "PROPERTY_BROKER",
                "PROPERTY_STARS",
                "PROPERTY_VIDEO"
            ));
        while ($reviews = $rsReviews->GetNext()) {
            $arReviews[] = $reviews;
        }

        $rsBrokers = \CIBlockElement::GetList(array("ACTIVE_FROM"=>"DESC", "SORT"=>"ASC"), $arFilterBrokers, false,
            Array("nPageSize"=>50),
            array(
                "ACTIVE",
                "ACTIVE_FROM",
                "CREATED_DATE",
                "NAME",
                "IBLOCK_ID",
                "IBLOCK_SECTION_ID",
                "ID"
            ));
        while ($brokers = $rsBrokers->GetNext()) {
            $dividedName = explode(" ", $brokers['NAME']);
            $brokers['DIVIDED_NAME'] = $dividedName;
            $arBrokers[$brokers['ID']] = $brokers;
        }

        $arOutput = array(
            "SORT"    => $arSortReviews,
            "SORT_DEFAULT" => $sortDefault,
            "REVIEWS" => $arReviews,
            "BROKERS" => $arBrokers,
            "IS_AJAX" => $isAjax,
            "NAV_RESULT" => $rsReviews,
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