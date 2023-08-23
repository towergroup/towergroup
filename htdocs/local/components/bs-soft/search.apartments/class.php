<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Highloadblock as HL;
use Bitrix\Main;
use Bitrix\Main\Application;
use Bitrix\Main\Data\Cache;
use Bitrix\Main\Entity;
use Bitrix\Main\Localization\Loc as Loc;

class SearchApartmentsComponent extends CBitrixComponent
{
    private $templateName;
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

        if (!Main\Loader::includeModule('highloadblock')) {
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

        $iblockID = $this->arParams["IBLOCK_ID"];
        $request = Application::getInstance()->getContext()->getRequest();
        $isAjax = $request->isAjaxRequest();
        $cityCode = $request->getQuery('CITY_CODE') ? $request->getQuery('CITY_CODE') : $this->arParams["CITY_CODE"];
        if(empty($_GET['search'])){
            $objectCode = $request->getQuery('OBJECT_CODE') ? $request->getQuery('OBJECT_CODE') : $this->arParams["OBJECT_CODE"];
        }
        /*if (!$cityCode || (!$request->getQuery('CITY_CODE') && $this->arParams["CITY_CODE"] == 'moskva')) {
            LocalRedirect('/moskva/novostroyki/', false, '301 Moved permanently');
        }*/

        // Получаем пользовательские поля самого Инфоблока
        if (Cmodule::IncludeModule('asd.iblock')) {
            $arFieldsIb = CASDiblockTools::GetIBUF($iblockID);
        }

        //Получаем рандомное ID брокера из списка брокеров для текущей категории каталога
        $managerID = $arFieldsIb["UF_BROKERS_LIST"][array_rand($arFieldsIb["UF_BROKERS_LIST"])];

        // Менеджер ЖК текущей категории каталога
        $rsManager = CIBlockElement::GetList(
            false,
            [
                "IBLOCK_ID" => EMPLOYEES_IBLOCK_ID,
                "ACTIVE" => "Y",
                "ID" => $managerID ? $managerID : '7',
            ],
            false,
            false,
            [
                "ID",
                "IBLOCK_ID",
                "CODE",
                "NAME",
                "PREVIEW_PICTURE",
                "DETAIL_PICTURE",
                "PROPERTY_DEPARTMENT",
                "PROPERTY_PHONE",
                "PROPERTY_WHATSAPP",
                "PROPERTY_TELEGRAM",
                "PROPERTY_EMAIL",
            ]
        )->Fetch();

        if (!empty($rsManager["PREVIEW_PICTURE"])) $rsManager["PREVIEW_PICTURE"] = CFile::GetFileArray($rsManager["PREVIEW_PICTURE"]);

        $arFilterCurCity = array('CODE' => $cityCode,'IBLOCK_ID' => 14, 'ACTIVE' => 'Y', "CNT_ACTIVE" => "Y");
        $rsCurCity = \CIBlockSection::GetList(
            false,
            $arFilterCurCity,
            true,
            array(
                "ACTIVE",
                "ACTIVE_FROM",
                "CREATED_DATE",
                "NAME",
                "CODE",
                "IBLOCK_ID",
                "IBLOCK_SECTION_ID",
                "ID"
            ),
            false
        //$arNavStartParams
        )->fetch();

        if (!$rsCurCity) {
            LocalRedirect('/moskva/novostroyki/', false, '301 Moved permanently');
        }
        echo '<!-- OBJECT'.$objectCode.'-->';
        if(!empty($objectCode)){
            $arObject = CIBlockSection::GetList(
                array(),
                array(
                    "IBLOCK_ID" => $iblockID,
                    //"ACTIVE" => "N",
                    "CODE" => $objectCode,
                    "DEPTH_LEVEL" => 1,
                    "UF_CITY" => $rsCurCity["NAME"],
                    //"CNT_ACTIVE" => "Y"
                ),
                true,
                array(
                    "ACTIVE",
                    "ID",
                    "IBLOCK_ID",
                    "CODE",
                    "NAME",
                    "DESCRIPTION",
                    "PICTURE",
                    "DETAIL_PICTURE",
                    "UF_*"
                ),
                false
            )->Fetch();

            //$arObject["ELEMENT_CNT"] = CIBlockSection::GetSectionElementsCount($arObject["ID"], Array("CNT_ACTIVE"=>"Y"));
            $arObject["ELEMENT_CNT"] = CIBlockElement::GetList(
                false,
                array(
                    "IBLOCK_ID"                => $iblockID,
                    "ACTIVE"                   => "Y",
                    "IBLOCK_SECTION_ID"        => $arObject["ID"]
                ),
                array(),
                false,
                array(
                    "ID",
                    "IBLOCK_ID",
                    "IBLOCK_SECTION_ID",
                    "CODE",
                    "NAME",
                )
            );

        }
        if((!empty($cityCode) && !empty($arObject)) || !empty($objectCode)) { // Страница объекта

            if (!$arObject) {
                $isNotObject = true;
                /*
                $arLocation = CIBlockSection::GetList(
                    array(),
                    array(
                        "IBLOCK_ID" => 14,
                        "ACTIVE" => "Y",
                        "CODE" => $objectCode,
                    ),
                    false,
                    array(
                        "ID",
                        "IBLOCK_ID",
                        "CODE",
                        "NAME",
                        "DESCRIPTION",
                        "PICTURE",
                        "DEPTH_LEVEL",
                        "UF_*"
                    ),
                    false
                )->Fetch();

                if (empty($arLocation)) {
                    $arSubway = CIBlockElement::GetList(
                        false,
                        array(
                            "IBLOCK_ID" => 14,
                            "ACTIVE" => "Y",
                            "CODE" => $objectCode
                        ),
                        false,
                        array("nTopCount" => 1),
                        array(
                            "ID",
                            "IBLOCK_ID",
                            "IBLOCK_SECTION_ID",
                            "CODE",
                            "NAME",
                            "PROPERTY_*",
                        )
                    )->Fetch();
                }

                if ($arLocation)
                    $isNotObject = true;
                if ($arSubway) {
                    $isNotObject = true;
                    $isSubway = true;
                }*/
            }

            //if (!$isNotObject && !empty($arObject)) {
            if (!empty($arObject) && !empty($arObject['ID'])) {
                echo '<!-- OBJECT_ID '.$arObject['ID'].'-->';

                if($arObject["ACTIVE"] == 'N'){
                    //LocalRedirect('/'.$cityCode.'/novostroyki/', false, '301 Moved permanently');
                }
                $arObject["UF_CITY_CODE"] = $cityCode;
                if (!empty($arObject["PICTURE"])) $arObject["PICTURE"] = CFile::GetFileArray($arObject["PICTURE"]);
                if (!empty($arObject["DETAIL_PICTURE"])) $arObject["DETAIL_PICTURE"] = CFile::GetFileArray($arObject["DETAIL_PICTURE"]);
                if (!empty($arObject["UF_PHOTOS"])) {
                    foreach ($arObject["UF_PHOTOS"] as $photoKey => $arPhotoId) {
                        $arObject["UF_PHOTOS"][$photoKey] = CFile::GetFileArray($arPhotoId);
                        if ($arObject["UF_PHOTOS"][$photoKey]["WIDTH"] > 800)
                            $arObject["UF_PHOTOS"][$photoKey]["PHOTO_RESIZE"] = CFile::ResizeImageGet($arObject["UF_PHOTOS"][$photoKey]["ID"], array('width' => 800, 'height' => 600),
                                BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, 70)['src'];
                    }
                }
                if (!empty($arObject["UF_PRESENTATION"])) {
                    $arObject["UF_PRESENTATION"] = CFile::GetFileArray($arObject["UF_PRESENTATION"]);
                }
                if (!empty($arObject["PICTURE"]))
                    $arObject["UF_PROJECT_LOGO"] = CFile::GetFileArray($arObject["UF_PROJECT_LOGO"]);

                $arObject["SECTION_PAGE_URL"] = "/".$cityCode."/novostroyki/".$arObject['CODE']."/";

                if (!empty($arObject["UF_SPECIALIST"])) {
                    // Специалист ЖК текущей категории каталога
                    $rsSpecialist = CIBlockElement::GetList(
                        false,
                        [
                            "IBLOCK_ID" => EMPLOYEES_IBLOCK_ID,
                            "ACTIVE" => "Y",
                            "ID" => $arObject["UF_SPECIALIST"],
                        ],
                        false,
                        false,
                        [
                            "ID",
                            "IBLOCK_ID",
                            "CODE",
                            "NAME",
                            "PREVIEW_PICTURE",
                            "DETAIL_PICTURE",
                            "PROPERTY_DEPARTMENT",
                            "PROPERTY_PHONE",
                            "PROPERTY_WHATSAPP",
                            "PROPERTY_TELEGRAM",
                            "PROPERTY_EMAIL",
                        ]
                    )->Fetch();

                    if (!empty($rsSpecialist["PREVIEW_PICTURE"])) $rsSpecialist["PREVIEW_PICTURE"] = CFile::GetFileArray($rsSpecialist["PREVIEW_PICTURE"]);
                    $arObject["UF_SPECIALIST"] = $rsSpecialist;
                }

                if (!empty($arObject["UF_AREA"])) {
                    $rsAreaObject = CIBlockSection::GetList(
                        false,
                        array(
                            "IBLOCK_ID" => REGION_IBLOCK_ID,
                            "ACTIVE" => "Y",
                            "ID" => $arObject["UF_AREA"],
                            "DEPTH_LEVEL" => 2,
                            "CNT_ACTIVE" => "Y",
                        ),
                        true,
                        array(
                            "ID",
                            "IBLOCK_ID",
                            "CODE",
                            "NAME",
                            "DESCRIPTION",
                            "PICTURE",
                            "UF_*"
                        ),
                        false
                    )->Fetch();
                    $arObject["UF_AREA"] = $rsAreaObject;
                }

                if (!empty($arObject["UF_SIMILAR_OBJECTS"])) {
                    if (count($arObject["UF_SIMILAR_OBJECTS"]) == 1) {
                        $arSimilarObjectId = $arObject["UF_SIMILAR_OBJECTS"];
                        $rsSimilarObjects = CIBlockSection::GetList(
                            false,
                            array(
                                "IBLOCK_ID" => $iblockID,
                                "ACTIVE" => "Y",
                                "ID" => $arSimilarObjectId,
                                "DEPTH_LEVEL" => 1,
                                "CNT_ACTIVE" => "Y"
                            ),
                            true,
                            array(
                                "ID",
                                "IBLOCK_ID",
                                "CODE",
                                "NAME",
                                "DESCRIPTION",
                                "PICTURE",
                                "UF_*"
                            ),
                            false
                        )->Fetch();

                        $rsAreaObjectSimilar = CIBlockSection::GetList(
                            false,
                            array(
                                "IBLOCK_ID" => REGION_IBLOCK_ID,
                                "ACTIVE" => "Y",
                                "ID" => $rsSimilarObjects["UF_AREA"],
                                "DEPTH_LEVEL" => 2,
                                "CNT_ACTIVE" => "Y"
                            ),
                            true,
                            array(
                                "ID",
                                "IBLOCK_ID",
                                "CODE",
                                "NAME",
                                "DESCRIPTION",
                                "PICTURE",
                                "UF_*"
                            ),
                            false
                        )->Fetch();
                        $rsSimilarObjects["UF_AREA"] = $rsAreaObjectSimilar["NAME"];

                        if (!empty($rsSimilarObjects["PICTURE"])) $rsSimilarObjects["PICTURE"] = CFile::GetFileArray($rsSimilarObjects["PICTURE"]);
                        if ($rsSimilarObjects["PICTURE"]["WIDTH"] > 640)
                            $rsSimilarObjects["PICTURE_RESIZE"] = CFile::ResizeImageGet($rsSimilarObjects["PICTURE"]["ID"], array('width' => 640, 'height' => 480),
                                BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, 70)['src'];
                        $rsSimilarObjects["SECTION_PAGE_URL"] = "/".$cityCode."/novostroyki/".$rsSimilarObjects['CODE']."/";
                        $arSimilarObjects[] = $rsSimilarObjects;
                    }
                    else {
                        foreach ($arObject["UF_SIMILAR_OBJECTS"] as $arSimilarObjectId) {
                            $rsSimilarObjects = CIBlockSection::GetList(
                                false,
                                array(
                                    "IBLOCK_ID" => $iblockID,
                                    "ACTIVE" => "Y",
                                    "ID" => $arSimilarObjectId,
                                    "DEPTH_LEVEL" => 1,
                                    "CNT_ACTIVE" => "Y"
                                ),
                                true,
                                array(
                                    "ID",
                                    "IBLOCK_ID",
                                    "CODE",
                                    "NAME",
                                    "DESCRIPTION",
                                    "PICTURE",
                                    "UF_*"
                                ),
                                false
                            )->Fetch();

                            $rsAreaObjectSimilar = CIBlockSection::GetList(
                                false,
                                array(
                                    "IBLOCK_ID" => REGION_IBLOCK_ID,
                                    "ACTIVE" => "Y",
                                    "ID" => $rsSimilarObjects["UF_AREA"],
                                    "DEPTH_LEVEL" => 2,
                                    "CNT_ACTIVE" => "Y"
                                ),
                                true,
                                array(
                                    "ID",
                                    "IBLOCK_ID",
                                    "CODE",
                                    "NAME",
                                    "DESCRIPTION",
                                    "PICTURE",
                                    "UF_*"
                                ),
                                false
                            )->Fetch();
                            $rsSimilarObjects["UF_AREA"] = $rsAreaObjectSimilar["NAME"];

                            if (!empty($rsSimilarObjects["PICTURE"])) $rsSimilarObjects["PICTURE"] = CFile::GetFileArray($rsSimilarObjects["PICTURE"]);
                            if ($rsSimilarObjects["PICTURE"]["WIDTH"] > 640)
                                $rsSimilarObjects["PICTURE_RESIZE"] = CFile::ResizeImageGet($rsSimilarObjects["PICTURE"]["ID"], array('width' => 640, 'height' => 480),
                                    BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, 70)['src'];
                            $rsSimilarObjects["SECTION_PAGE_URL"] = "/".$cityCode."/novostroyki/".$rsSimilarObjects['CODE']."/";
                            $arSimilarObjects[] = $rsSimilarObjects;
                        }
                    }
                }
                else {
                    $rsSimilarObjects = CIBlockSection::GetList(
                        false,
                        array(
                            "IBLOCK_ID" => $iblockID,
                            "ACTIVE" => "Y",
                            "!ID" => $arObject["ID"],
                            "UF_AREA" => $arObject["UF_AREA"]["ID"],
                            "DEPTH_LEVEL" => 1,
                            "CNT_ACTIVE" => "Y"
                        ),
                        true,
                        array(
                            "ID",
                            "IBLOCK_ID",
                            "CODE",
                            "NAME",
                            "DESCRIPTION",
                            "PICTURE",
                            "UF_*"
                        ),
                        array("nTopCount" => 4)
                    );

                    while($arSimilar = $rsSimilarObjects->Fetch()){
                        $arSimilar["UF_AREA"] = $arObject["UF_AREA"]["NAME"];
                        if (!empty($arSimilar["PICTURE"])) $arSimilar["PICTURE"] = CFile::GetFileArray($arSimilar["PICTURE"]);
                        if ($arSimilar["PICTURE"]["WIDTH"] > 640)
                            $arSimilar["PICTURE_RESIZE"] = CFile::ResizeImageGet($arSimilar["PICTURE"]["ID"], array('width' => 640, 'height' => 480),
                                BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, 70)['src'];
                        $arSimilar["SECTION_PAGE_URL"] = "/".$cityCode."/novostroyki/".$arSimilar['CODE']."/";
                        $arSimilarObjects[] = $arSimilar;
                    }
                }


                // Поиск дефолтных значений для фильтра
                $arFilterRanges = array();

                $arMinPriceApartment = CIBlockElement::GetList(
                    array("PROPERTY_PRICE_RUB" => "ASC"),
                    array(
                        "IBLOCK_ID"                => $iblockID,
                        "ACTIVE"                   => "Y",
                        ">PROPERTY_PRICE_RUB"      => 0,
                        "IBLOCK_SECTION_ID"        => $arObject["ID"]
                    ),
                    false,
                    array("nTopCount" => 1),
                    array(
                        "ID",
                        "IBLOCK_ID",
                        "IBLOCK_SECTION_ID",
                        "CODE",
                        "NAME",
                        "PROPERTY_PRICE_RUB",
                    )
                )->Fetch();

                $arMaxPriceApartment = CIBlockElement::GetList(
                    array("PROPERTY_PRICE_RUB" => "DESC"),
                    array(
                        "IBLOCK_ID"                => $iblockID,
                        "ACTIVE"                   => "Y",
                        ">PROPERTY_PRICE_RUB"      => 0,
                        "IBLOCK_SECTION_ID"        => $arObject["ID"]
                    ),
                    false,
                    array("nTopCount" => 1),
                    array(
                        "ID",
                        "IBLOCK_ID",
                        "IBLOCK_SECTION_ID",
                        "CODE",
                        "NAME",
                        "PROPERTY_PRICE_RUB",
                    )
                )->Fetch();

                $arMinSquareApartment = CIBlockElement::GetList(
                    array("PROPERTY_SQUARE"=>"ASC"),
                    array(
                        "IBLOCK_ID"                => $iblockID,
                        "ACTIVE"                   => "Y",
                        ">PROPERTY_PRICE_RUB"      => 0,
                        ">PROPERTY_SQUARE"         => 0,
                        "IBLOCK_SECTION_ID"        => $arObject["ID"]
                    ),
                    false,
                    array("nTopCount" => 1),
                    array(
                        "ID",
                        "IBLOCK_ID",
                        "IBLOCK_SECTION_ID",
                        "CODE",
                        "NAME",
                        "PROPERTY_SQUARE",
                    )
                )->Fetch();

                $arMaxSquareApartment = CIBlockElement::GetList(
                    array("PROPERTY_SQUARE"=>"DESC"),
                    array(
                        "IBLOCK_ID"                => $iblockID,
                        "ACTIVE"                   => "Y",
                        ">PROPERTY_PRICE_RUB"      => 0,
                        ">PROPERTY_SQUARE"         => 0,
                        "IBLOCK_SECTION_ID"        => $arObject["ID"]
                    ),
                    false,
                    array("nTopCount" => 1),
                    array(
                        "ID",
                        "IBLOCK_ID",
                        "IBLOCK_SECTION_ID",
                        "CODE",
                        "NAME",
                        "PROPERTY_SQUARE",
                    )
                )->Fetch();

                $arMinKitchenSquareApartment = CIBlockElement::GetList(
                    array("PROPERTY_KITCHEN_SQUARE"=>"ASC"),
                    array(
                        "IBLOCK_ID"                => $iblockID,
                        "ACTIVE"                   => "Y",
                        ">PROPERTY_PRICE_RUB"      => 0,
                        ">PROPERTY_KITCHEN_SQUARE" => 0,
                        "IBLOCK_SECTION_ID"        => $arObject["ID"]
                    ),
                    false,
                    array("nTopCount" => 1),
                    array(
                        "ID",
                        "IBLOCK_ID",
                        "IBLOCK_SECTION_ID",
                        "CODE",
                        "NAME",
                        "PROPERTY_KITCHEN_SQUARE",
                    )
                )->Fetch();

                $arMaxKitchenSquareApartment = CIBlockElement::GetList(
                    array("PROPERTY_KITCHEN_SQUARE"=>"DESC"),
                    array(
                        "IBLOCK_ID"                => $iblockID,
                        "ACTIVE"                   => "Y",
                        ">PROPERTY_PRICE_RUB"      => 0,
                        ">PROPERTY_KITCHEN_SQUARE" => 0,
                        "IBLOCK_SECTION_ID"        => $arObject["ID"]
                    ),
                    false,
                    array("nTopCount" => 1),
                    array(
                        "ID",
                        "IBLOCK_ID",
                        "IBLOCK_SECTION_ID",
                        "CODE",
                        "NAME",
                        "PROPERTY_KITCHEN_SQUARE",
                    )
                )->Fetch();

                $arMinFloorApartment = CIBlockElement::GetList(
                    array("PROPERTY_FLOOR"=>"ASC"),
                    array(
                        "IBLOCK_ID"                => $iblockID,
                        "ACTIVE"                   => "Y",
                        ">PROPERTY_PRICE_RUB"      => 0,
                        "IBLOCK_SECTION_ID"        => $arObject["ID"]
                    ),
                    false,
                    array("nTopCount" => 1),
                    array(
                        "ID",
                        "IBLOCK_ID",
                        "IBLOCK_SECTION_ID",
                        "CODE",
                        "NAME",
                        "PROPERTY_FLOOR",
                    )
                )->Fetch();

                $arMaxFloorApartment = CIBlockElement::GetList(
                    array("PROPERTY_FLOOR"=>"DESC"),
                    array(
                        "IBLOCK_ID"                => $iblockID,
                        "ACTIVE"                   => "Y",
                        ">PROPERTY_PRICE_RUB"      => 0,
                        "IBLOCK_SECTION_ID"        => $arObject["ID"]
                    ),
                    false,
                    array("nTopCount" => 1),
                    array(
                        "ID",
                        "IBLOCK_ID",
                        "IBLOCK_SECTION_ID",
                        "CODE",
                        "NAME",
                        "PROPERTY_FLOOR",
                    )
                )->Fetch();

                $arTerraceApartment = CIBlockElement::GetList(
                    array("PROPERTY_TERRACE"=>"ASC"),
                    array(
                        "IBLOCK_ID"                => $iblockID,
                        "ACTIVE"                   => "Y",
                        ">PROPERTY_PRICE_RUB"      => 0,
                        "=PROPERTY_TERRACE_VALUE"  => "Y",
                        "IBLOCK_SECTION_ID"        => $arObject["ID"]
                    ),
                    false,
                    array("nTopCount" => 1),
                    array(
                        "ID",
                        "IBLOCK_ID",
                        "IBLOCK_SECTION_ID",
                        "CODE",
                        "NAME",
                        "PROPERTY_TERRACE",
                    )
                )->Fetch();

                $arTwoTierApartment = CIBlockElement::GetList(
                    array("PROPERTY_TWO_TIER"=>"ASC"),
                    array(
                        "IBLOCK_ID"                => $iblockID,
                        "ACTIVE"                   => "Y",
                        ">PROPERTY_PRICE_RUB"      => 0,
                        "=PROPERTY_TWO_TIER_VALUE" => "Y",
                        "IBLOCK_SECTION_ID"        => $arObject["ID"]
                    ),
                    false,
                    array("nTopCount" => 1),
                    array(
                        "ID",
                        "IBLOCK_ID",
                        "IBLOCK_SECTION_ID",
                        "CODE",
                        "NAME",
                        "PROPERTY_TWO_TIER",
                    )
                )->Fetch();

                $arHighCeilingsApartment = CIBlockElement::GetList(
                    array("PROPERTY_HIGH_CEILINGS"=>"ASC"),
                    array(
                        "IBLOCK_ID"                => $iblockID,
                        "ACTIVE"                   => "Y",
                        ">PROPERTY_PRICE_RUB"      => 0,
                        "=PROPERTY_HIGH_CEILINGS_VALUE"  => "Y",
                        "IBLOCK_SECTION_ID"        => $arObject["ID"]
                    ),
                    false,
                    array("nTopCount" => 1),
                    array(
                        "ID",
                        "IBLOCK_ID",
                        "IBLOCK_SECTION_ID",
                        "CODE",
                        "NAME",
                        "PROPERTY_HIGH_CEILINGS",
                    )
                )->Fetch();

                $arFilterRanges['MIN_PRICE'] = $arMinPriceApartment['PROPERTY_PRICE_RUB_VALUE'];
                $arFilterRanges['MAX_PRICE'] = $arMaxPriceApartment['PROPERTY_PRICE_RUB_VALUE'];
                $arFilterRanges['MIN_SQUARE'] = $arMinSquareApartment['PROPERTY_SQUARE_VALUE'];
                $arFilterRanges['MAX_SQUARE'] = $arMaxSquareApartment['PROPERTY_SQUARE_VALUE'];
                $arFilterRanges['MIN_KITCHEN_SQUARE'] = $arMinKitchenSquareApartment['PROPERTY_KITCHEN_SQUARE_VALUE'];
                $arFilterRanges['MAX_KITCHEN_SQUARE'] = $arMaxKitchenSquareApartment['PROPERTY_KITCHEN_SQUARE_VALUE'];
                $arFilterRanges['MIN_FLOOR'] = $arMinFloorApartment['PROPERTY_FLOOR_VALUE'];
                $arFilterRanges['MAX_FLOOR'] = $arMaxFloorApartment['PROPERTY_FLOOR_VALUE'];
                $arFilterRanges['TERRACE'] = $arTerraceApartment['PROPERTY_TERRACE_VALUE'];
                $arFilterRanges['HIGH_CEILINGS'] = $arHighCeilingsApartment['PROPERTY_HIGH_CEILINGS_VALUE'];
                $arFilterRanges['TWO_TIER'] = $arTwoTierApartment['PROPERTY_TWO_TIER_VALUE'];

                $getCount = ($_GET['getCount']) ? $_GET['getCount'] : false;
                $page = ($_GET['page']) ? $_GET['page'] : 1;
                $showMore = ($_GET['showMore']) ? $_GET['showMore'] : false;

                $priceMin = ($_GET['price_min']) ? $_GET['price_min'] : null ;
                $priceMax = ($_GET['price_max']) ? $_GET['price_max'] : null ;
                $oldCurrency = ($_GET['cur_currency'] && $_GET['cur_currency'] != $_GET['price_currency']) ? $_GET['cur_currency'] : null ;
                $priceCurrency = ($_GET['price_currency']) ? $_GET['price_currency'] : 'rub' ;
                $squareMin = ($_GET['square_min']) ? $_GET['square_min'] : null ;
                $squareMax = ($_GET['square_max']) ? $_GET['square_max'] : null ;
                $kitchenSquareMin = ($_GET['kitchensizemin']) ? $_GET['kitchensizemin'] : null ;
                $kitchenSquareMax = ($_GET['kitchensizemax']) ? $_GET['kitchensizemax'] : null ;
                $floorMin = ($_GET['floor_min']) ? $_GET['floor_min'] : null ;
                $floorMax = ($_GET['floor_max']) ? $_GET['floor_max'] : null ;
                $rooms = ($_GET['rooms']) ? $_GET['rooms'] : null ;
                $additionalOptions = ($_GET['additional']) ? $_GET['additional'] : null ;
                $decorations = ($_GET['decoration']) ? $_GET['decoration'] : null ;

                $buildtype = ($_GET['buildtype'] && $_GET['buildtype'] == 7) ? $_GET['buildtype'] : null ;

                $arFilterObjects = array('IBLOCK_ID' => $iblockID, 'ACTIVE' => 'Y', 'UF_CITY' => $rsCurCity['NAME']);
                $arApartmentsSort = array("PROPERTY_PRICE_RUB" => "ASC");
                $arFilterApartments = array('IBLOCK_ID' => $iblockID, 'ACTIVE' => 'Y', "IBLOCK_SECTION_ID" => $arObject['ID']);
                $arFilterValues = [];

                function getCBRRates()
                {
                    $xml_daily_file = __DIR__ . '/daily.xml';

                    // кеш на четыре часа
                    if (!is_file($xml_daily_file) || filemtime($xml_daily_file) < time() - 7200) {
                        if ($xml_daily = file_get_contents('http://www.cbr.ru/scripts/XML_daily.asp')) {
                            file_put_contents($xml_daily_file, $xml_daily);
                        }
                    }

                    $result = array();
                    foreach (simplexml_load_file($xml_daily_file) as $el) {
                        $result[strval($el->CharCode)] = (float)strtr($el->Value, ',', '.');
                    }

                    return $result;
                }

                $rate = getCBRRates();

                if ($oldCurrency != null && $oldCurrency != $priceCurrency) {
                    if ($priceCurrency != 'rub') {
                        if ($oldCurrency != 'rub') {
                            $priceMin = ceil((floatval($priceMin)*1000000 * $rate[strtoupper($oldCurrency)]) / $rate[strtoupper($priceCurrency)]);
                            $priceMax = ceil((floatval($priceMax)*1000000 * $rate[strtoupper($oldCurrency)]) / $rate[strtoupper($priceCurrency)]);
                        }
                        else {
                            $priceMin = ceil(floatval($priceMin)*1000000 / $rate[strtoupper($priceCurrency)]);
                            $priceMax = ceil(floatval($priceMax)*1000000 / $rate[strtoupper($priceCurrency)]);
                        }

                    }
                    else {
                        $priceMin = ceil(floatval($priceMin)*1000000 * $rate[strtoupper($oldCurrency)]);
                        $priceMax = ceil(floatval($priceMax)*1000000 * $rate[strtoupper($oldCurrency)]);
                    }

                }

                switch ($priceCurrency) {
                    case 'usd' :
                        if($priceMin != null) {
                            $arFilterApartments['>=PROPERTY_PRICE_USD'] = ($oldCurrency != null) ? floatval($priceMin) : floatval($priceMin) * 1000000;;
                            $arFilterValues['PRICE'][0] = ($oldCurrency != null) ? floatval($priceMin) : floatval($priceMin) * 1000000;

                        }
                        if($priceMax != null) {
                            $arFilterApartments['<=PROPERTY_PRICE_USD'] = ($oldCurrency != null) ? floatval($priceMax) : floatval($priceMax) * 1000000;
                            $arFilterValues['PRICE'][1] = ($oldCurrency != null) ? floatval($priceMax) : floatval($priceMax) * 1000000;

                        }
                        $priceCurrencySymbol = "$";
                        break;
                    case 'eur' :
                        if($priceMin != null) {
                            $arFilterApartments['>=PROPERTY_PRICE_EUR'] = ($oldCurrency != null) ? floatval($priceMin) : floatval($priceMin) * 1000000;;
                            $arFilterValues['PRICE'][0] = ($oldCurrency != null) ? floatval($priceMin) : floatval($priceMin) * 1000000;

                        }
                        if($priceMax != null) {
                            $arFilterApartments['<=PROPERTY_PRICE_EUR'] = ($oldCurrency != null) ? floatval($priceMax) : floatval($priceMax) * 1000000;
                            $arFilterValues['PRICE'][1] = ($oldCurrency != null) ? floatval($priceMax) : floatval($priceMax) * 1000000;

                        }
                        $priceCurrencySymbol = "€";
                        break;
                    case 'gbp' :
                        if($priceMin != null) {
                            $arFilterApartments['>=PROPERTY_PRICE_GBP'] = ($oldCurrency != null) ? floatval($priceMin) : floatval($priceMin) * 1000000;;
                            $arFilterValues['PRICE'][0] = ($oldCurrency != null) ? floatval($priceMin) : floatval($priceMin) * 1000000;
                        }
                        if($priceMax != null) {
                            $arFilterApartments['<=PROPERTY_PRICE_GBP'] = ($oldCurrency != null) ? floatval($priceMax) : floatval($priceMax) * 1000000;
                            $arFilterValues['PRICE'][1] = ($oldCurrency != null) ? floatval($priceMax) : floatval($priceMax) * 1000000;
                        }
                        $priceCurrencySymbol = "£";
                        break;
                    case 'rub' :
                    default :
                        if($priceMin != null) {
                            $arFilterApartments['>=PROPERTY_PRICE_RUB'] = ($oldCurrency != null) ? floatval($priceMin) : floatval($priceMin) * 1000000;
                            $arFilterValues['PRICE'][0] = ($oldCurrency != null) ? floatval($priceMin) : floatval($priceMin) * 1000000;

                        }
                        if($priceMax != null) {
                            $arFilterApartments['<=PROPERTY_PRICE_RUB'] = ($oldCurrency != null) ? floatval($priceMax) : floatval($priceMax) * 1000000;
                            $arFilterValues['PRICE'][1] = ($oldCurrency != null) ? floatval($priceMax) : floatval($priceMax) * 1000000;

                        }
                        $priceCurrencySymbol = "₽";
                        break;
                }
                $arFilterValues['CURRENCY'] = strtoupper($priceCurrency);
                $arFilterValues['CURRENCY_SYMBOL'] = $priceCurrencySymbol;


                $arMinPriceCurrency = CIBlockElement::GetList(
                    array("PROPERTY_PRICE_".$arFilterValues['CURRENCY'] => "ASC"),
                    array(
                        "IBLOCK_ID"                => $iblockID,
                        "ACTIVE"                   => "Y",
                        ">PROPERTY_PRICE_".$arFilterValues['CURRENCY']      => 0,
                        "IBLOCK_SECTION_ID"        => $arObject["ID"]
                    ),
                    false,
                    array("nTopCount" => 1),
                    array(
                        "ID",
                        "IBLOCK_ID",
                        "IBLOCK_SECTION_ID",
                        "CODE",
                        "NAME",
                        "PROPERTY_PRICE_".$arFilterValues['CURRENCY'],
                    )
                )->Fetch();

                $arMaxPriceCurrency = CIBlockElement::GetList(
                    array("PROPERTY_PRICE_".$arFilterValues['CURRENCY'] => "DESC"),
                    array(
                        "IBLOCK_ID"                => $iblockID,
                        "ACTIVE"                   => "Y",
                        ">PROPERTY_PRICE_".$arFilterValues['CURRENCY']      => 0,
                        "IBLOCK_SECTION_ID"        => $arObject["ID"]
                    ),
                    false,
                    array("nTopCount" => 1),
                    array(
                        "ID",
                        "IBLOCK_ID",
                        "IBLOCK_SECTION_ID",
                        "CODE",
                        "NAME",
                        "PROPERTY_PRICE_".$arFilterValues['CURRENCY'],
                    )
                )->Fetch();

                $arFilterRanges['CURRENCY']['MIN_PRICE'] = $arMinPriceCurrency['PROPERTY_PRICE_'.$arFilterValues['CURRENCY'].'_VALUE'];
                $arFilterRanges['CURRENCY']['MAX_PRICE'] = $arMaxPriceCurrency['PROPERTY_PRICE_'.$arFilterValues['CURRENCY'].'_VALUE'];

                if($squareMin != null) {
                    $arFilterApartments['>=PROPERTY_SQUARE'] = floatval($squareMin);
                    $arFilterValues['SQUARE'][0] = $squareMin;

                }

                if($squareMax != null) {
                    $arFilterApartments['<=PROPERTY_SQUARE'] = floatval($squareMax);
                    $arFilterValues['SQUARE'][1] = $squareMax;

                }

                if($kitchenSquareMin != null) {
                    $arFilterApartments['>=PROPERTY_KITCHEN_SQUARE'] = floatval($kitchenSquareMin);
                    $arFilterValues['KITCHEN_SQUARE'][0] = $kitchenSquareMin;

                }

                if($kitchenSquareMax != null) {
                    $arFilterApartments['<=PROPERTY_KITCHEN_SQUARE'] = floatval($kitchenSquareMax);
                    $arFilterValues['KITCHEN_SQUARE'][1] = $kitchenSquareMax;

                }

                if($floorMin != null) {
                    $arFilterApartments['>=PROPERTY_FLOOR'] = $floorMin;
                    $arFilterValues['FLOOR'][0] = $floorMin;

                }

                if($floorMax != null) {
                    $arFilterApartments['<=PROPERTY_FLOOR'] = $floorMax;
                    $arFilterValues['FLOOR'][1] = $floorMax;

                }

                if($rooms != null) {
                    $reqRooms = (is_array($rooms)) ? $rooms : explode(',', $rooms);
                    if(count($reqRooms) > 0) {
                        $arFilterApartments["PROPERTY_ROOMS"] = array_merge(array("LOGIC" => "OR"), $reqRooms);
                        $arFilterValues['ROOMS'] = $reqRooms;
                    }
                }

                if($decorations != null) {
                    $reqDecorations = (is_array($decorations)) ? $decorations : explode(',', $decorations);
                    if(count($reqDecorations) > 0) {
                        $arFilterApartments["PROPERTY_DECORATION"] = array_merge(array("LOGIC" => "OR"), $reqDecorations);
                        $arFilterValues['DECORATIONS'] = $reqDecorations;
                    }
                }

                if($additionalOptions != null) {
                    $reqAdditional = (is_array($additionalOptions)) ? $additionalOptions : explode(',', $additionalOptions);
                    if(count($reqAdditional) > 0) {
                        foreach ($reqAdditional as $option) {
                            switch ($option) {
                                case 'terrace':
                                    $arFilterApartments["PROPERTY_TERRACE_VALUE"] = "Y";
                                    break;
                                case 'highceilings':
                                    $arFilterApartments["PROPERTY_HIGH_CEILINGS_VALUE"] = "Y";
                                    break;
                                case 'twotier':
                                    $arFilterApartments["PROPERTY_TWO_TIER_VALUE"] = "Y";
                                    break;
                            }
                        }
                        $arFilterValues['ADDITIONAL_OPTIONS'] = $reqAdditional;
                    }
                }

                if($buildtype != null) {
                    $arFilterApartments["=PROPERTY_BUILD_TYPE"] = $buildtype;
                }

                $arObject["UF_MANAGER"] = $rsManager;

                if(!empty($arObject["UF_DECORATION_IMAGE"])) {
                    $rsDecoratonSlider = CIBlockElement::GetList(
                        false,
                        [
                            "IBLOCK_ID" => DECORATION_SLIDER_IBLOCK_ID,
                            "ACTIVE" => "Y",
                            "SECTION_ID" => $arObject["UF_DECORATION_IMAGE"],
                        ],
                        false,
                        false,
                        [
                            "ID",
                            "IBLOCK_ID",
                            "CODE",
                            "NAME",
                            "PREVIEW_PICTURE",
                            "PREVIEW_TEXT",
                            "DETAIL_TEXT",
                        ]
                    );
                    while ($arDecoratonSlider = $rsDecoratonSlider->GetNext()) {
                        $arDecoratonSlider["IMAGE_SLIDE"] = CFile::GetFileArray($arDecoratonSlider["PREVIEW_PICTURE"]);
                        $arSlides[] = $arDecoratonSlider;
                    }
                    $arObject["UF_DECORATION_IMAGE"] = $arSlides;
                }

                //находим типы комнат
                $property_rooms_enums = CIBlockPropertyEnum::GetList(Array("SORT"=>"ASC", "ID"=>"ASC"), Array("IBLOCK_ID"=>$iblockID, "CODE"=>"ROOMS"));
                while($room_enum_fields = $property_rooms_enums->GetNext())
                {
                    $arRooms[$room_enum_fields["ID"]] = $room_enum_fields;
                }

                //Получаем комнатность у текущего жк
                $obFlatRooms = CIBlockElement::GetList(
                    false,
                    array("IBLOCK_ID" => $iblockID, "ACTIVE" => "Y", "IBLOCK_SECTION_ID" => $arObject['ID']),
                    array("PROPERTY_ROOMS"),
                    false
                );

                $arFlatRooms = array();

                while($arFlatRoom = $obFlatRooms->Fetch()){
                    array_push($arFlatRooms,$arFlatRoom['PROPERTY_ROOMS_ENUM_ID']);
                }

                //находим Отделки

                $hlblFinish = DECORATIONS_HIGHLOADBLOCK_ID; // Указываем ID highloadblock типов отделок
                $hlblock = HL\HighloadBlockTable::getById($hlblFinish)->fetch();

                $entity = HL\HighloadBlockTable::compileEntity($hlblock);
                $entity_data_class = $entity->getDataClass();

                $rsData = $entity_data_class::getList(array(
                    "select" => array("*"),
                    "order" => array("ID" => "ASC"),
                    "filter" => array()  // Задаем параметры фильтра выборки
                ));

                while($arData = $rsData->Fetch()){
                    $arFinishes[$arData['UF_ID']] = $arData;
                }

                //Получаем отделки у текущего жк
                $obFlatDecoration = CIBlockElement::GetList(
                    false,
                    array("IBLOCK_ID" => $iblockID, "ACTIVE" => "Y", "IBLOCK_SECTION_ID" => $arObject['ID']),
                    array("PROPERTY_DECORATION"),
                    false
                );

                $arFlatDecorations = array();

                while($arFlatDecoration = $obFlatDecoration->Fetch()){
                    $arFlatDecorations[$arFlatDecoration['PROPERTY_DECORATION_VALUE']] = $arFinishes[$arFlatDecoration['PROPERTY_DECORATION_VALUE']];
                }

                $arFilterRanges["DECORATIONS"] = $arFlatDecorations;

                //находим класс ЖК

                $hlblObjectLiveClass = LIVE_CLASSES_HIGHLOADBLOCK_ID; // Указываем ID highloadblock типов жк
                $hlblock = HL\HighloadBlockTable::getById($hlblObjectLiveClass)->fetch();

                $entity = HL\HighloadBlockTable::compileEntity($hlblock);
                $entity_data_class = $entity->getDataClass();

                $rsDataObjectLiveClass = $entity_data_class::getList(array(
                    "select" => array("*"),
                    "order" => array("ID" => "ASC"),
                    "filter" => array("ID" => $arObject["UF_CLASS"])  // Задаем параметры фильтра выборки
                ))->Fetch();

                $arObject["UF_CLASS_NAME"] = $rsDataObjectLiveClass["UF_NAME"];

                //находим тип объекта

                $hlblObjectLiveClass = OBJECT_TYPE_HIGHLOADBLOCK_ID; // Указываем ID highloadblock типов объектов
                $hlblock = HL\HighloadBlockTable::getById($hlblObjectLiveClass)->fetch();

                $entity = HL\HighloadBlockTable::compileEntity($hlblock);
                $entity_data_class = $entity->getDataClass();

                $rsDataObjectLiveClass = $entity_data_class::getList(array(
                    "select" => array("*"),
                    "order" => array("ID" => "ASC"),
                    "filter" => array("ID" => $arObject["UF_TYPE"])  // Задаем параметры фильтра выборки
                ))->Fetch();

                $arObject["UF_TYPE_NAME"] = $rsDataObjectLiveClass["UF_NAME"];
                $arObject["UF_TYPE_NAME_GENITIVE"] = $rsDataObjectLiveClass["UF_GENITIVE"];


                //находим Застройщика

                if ($arObject["UF_BUILD"]){

                    $hlblObjectLiveClass = $cityCode == 'spb' ? BUILDERS_SPB_HIGHLOADBLOCK_ID : BUILDERS_HIGHLOADBLOCK_ID; // Указываем ID highloadblock застройщиков
                    $hlblock = HL\HighloadBlockTable::getById($hlblObjectLiveClass)->fetch();

                    $entity = HL\HighloadBlockTable::compileEntity($hlblock);
                    $entity_data_class = $entity->getDataClass();

                    $rsDataObjectLiveClass = $entity_data_class::getList(array(
                        "select" => array("*"),
                        "order" => array("ID" => "ASC"),
                        "filter" => array("ID" => $arObject["UF_BUILD"])  // Задаем параметры фильтра выборки
                    ))->Fetch();

                    $arObject["UF_BUILD_NAME"] = $rsDataObjectLiveClass["UF_NAME"];
                    $rsDataObjectLiveClass["UF_PROJECT_LOGO"] = CFile::GetFileArray($rsDataObjectLiveClass["UF_PROJECT_LOGO"]);
                    $arObject["UF_BUILD_LOGO"] = $rsDataObjectLiveClass["UF_PROJECT_LOGO"];

                    if ($rsDataObjectLiveClass["UF_DOCS"]){
                        foreach ($rsDataObjectLiveClass["UF_DOCS"] as $keyBuildDoc => $buildDocID){
                            $rsDataObjectLiveClass["UF_DOCS"][$keyBuildDoc] = CFile::GetFileArray($buildDocID);
                        }
                    }

                    $rsBuilderObjects = CIBlockSection::GetList(
                        false,
                        array(
                            "IBLOCK_ID" => $iblockID,
                            "ACTIVE" => "Y",
                            "!ID" => $arObject["ID"],
                            "UF_BUILD" => $arObject["UF_BUILD"],
                            "DEPTH_LEVEL" => 1,
                            "CNT_ACTIVE" => "Y"
                        ),
                        true,
                        array(
                            "ID",
                            "IBLOCK_ID",
                            "CODE",
                            "NAME",
                            "DESCRIPTION",
                            "PICTURE",
                            "UF_*"
                        ),
                        false
                    );

                    while ($arBuilderObject = $rsBuilderObjects->GetNext()) {
                        if (!empty($arBuilderObject["PICTURE"])) $arBuilderObject["PICTURE"] = CFile::GetFileArray($arBuilderObject["PICTURE"]);
                        if ($arBuilderObject["PICTURE"]["WIDTH"] > 640)
                            $arBuilderObject["PICTURE_RESIZE"] = CFile::ResizeImageGet($arBuilderObject["PICTURE"]["ID"], array('width' => 640, 'height' => 480),
                                BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, 70)['src'];
                        $arBuilderObject["SECTION_PAGE_URL"] = "/".$cityCode."/novostroyki/".$arBuilderObject['CODE']."/";
                        $rsDataObjectLiveClass["OBJECTS"][] = $arBuilderObject;
                        $arBuilderObjectAreaIDs[] = $arBuilderObject["UF_AREA"];
                    }
                    $arBuilderObjectAreaIDs = array_unique($arBuilderObjectAreaIDs);

                    $rsAreaBuilderObject = CIBlockSection::GetList(
                        false,
                        array(
                            "IBLOCK_ID" => REGION_IBLOCK_ID,
                            "ACTIVE" => "Y",
                            "ID" => $arBuilderObjectAreaIDs,
                            "DEPTH_LEVEL" => 2,
                        ),
                        false,
                        array(
                            "ID",
                            "IBLOCK_ID",
                            "CODE",
                            "NAME"
                        ),
                        false
                    );

                    while ($arAreaBuilderObject = $rsAreaBuilderObject->GetNext()) {
                        $rsDataObjectLiveClass["AREAS"][$arAreaBuilderObject["ID"]] = $arAreaBuilderObject;
                    }

                    $arObject["UF_BUILDER"] = $rsDataObjectLiveClass;
                }


                if ($arObject["UF_METRO_HL"]){
                    //находим Метро

                    $hlblMetro = $cityCode == 'spb' ? SUBWAYS_SPB_HIGHLOADBLOCK_ID : SUBWAYS_HIGHLOADBLOCK_ID; // Указываем ID highloadblock метро.
                    $hlblock = HL\HighloadBlockTable::getById($hlblMetro)->fetch();

                    $entity = HL\HighloadBlockTable::compileEntity($hlblock);
                    $entity_data_class = $entity->getDataClass();

                    $rsData = $entity_data_class::getList(array(
                        "select" => array("*"),
                        "order" => array("ID" => "ASC"),
                        "filter" => array("ID" => $arObject["UF_METRO_HL"])  // Задаем параметры фильтра выборки
                    ));
                    while ($arData = $rsData->Fetch()) {
                        $arObject["METRO_HL"][] = $arData["UF_NAME"];
                    }
                    $arObject["UF_METRO_HL"] = implode(", ", $arObject["METRO_HL"]);
                }

                //Корпуса

                $hlblFinish = BUILDINGS_HIGHLOADBLOCK_ID; // Указываем ID highloadblock типов отделок
                $hlblock = HL\HighloadBlockTable::getById($hlblFinish)->fetch();

                $entity = HL\HighloadBlockTable::compileEntity($hlblock);
                $entity_data_class = $entity->getDataClass();

                $rsData = $entity_data_class::getList(array(
                    "select" => array("*"),
                    "order" => array("ID" => "ASC"),
                    "filter" => array()
                ));

                while($arData = $rsData->Fetch()){
                    $arBuilding[$arData['UF_ID']] = $arData;
                }

                if (!empty($arObject["UF_INFRASTRUCTURE"])) {
                    $resInfrastructure = [];
                    $arFilter = ['IBLOCK_ID' => INFRASTRUCTURE_IBLOCK_ID, 'DEPTH_LEVEL' => 2, "SECTION_ID" => $arObject["UF_INFRASTRUCTURE"]];
                    $arSelect = ['NAME', 'ID', 'DESCRIPTION', 'CODE', 'UF_*'];
                    $rsSect = CIBlockSection::GetList(['SORT' => 'ASC'], $arFilter, false, $arSelect);

                    while ($arSect = $rsSect->Fetch()){
                        $resInfrastructure['CATEGORY'][$arSect["CODE"]] = $arSect;
                        $rsMapObjects = ['IBLOCK_ID' => INFRASTRUCTURE_IBLOCK_ID, 'SECTION_ID' => $arSect['ID'], 'ACTIVE' => 'Y'];
                        $rsMaprObjects = CIBlockElement::GetList(
                            ['SORT' => 'ASC'],
                            $rsMapObjects,
                            false,
                            false,
                            [
                                "ID",
                                "IBLOCK_ID",
                                "IBLOCK_SECTION_ID",
                                "CODE",
                                "NAME",
                                "PROPERTY_COORDS",
                            ]
                        );
                        while ($arMapObject = $rsMaprObjects->GetNext()){
                            $resInfrastructure["CATEGORY"][$arSect['CODE']]['ITEMS'][] = ['COORDS' => $arMapObject['PROPERTY_COORDS_VALUE'], 'CODE' => $arSect['CODE']];
                        }
                    }
                }

                switch (SITE_ID) {
                    case s1:
                        $siteUrl = '/moskva';
                        break;
                    case s2:
                        $siteUrl = '';
                        break;
                    default:
                        $siteUrl = 'https://towergroup.ru';
                        break;
                }

                //Получаем 3 последних новости связанных с объектом
                $arFilterNews = array(
                    "IBLOCK_ID" => NEWS_IBLOCK_ID,
                    "ACTIVE" => "Y"

                );
                switch ($cityCode){
                    case moskva:
                        $arFilterNews["PROPERTY_OBJECT_NEW_BUILD_MSK"] = $arObject["ID"];

                        break;
                    case spb:
                        $arFilterNews["PROPERTY_OBJECT_NEW_BUILD_SPB"] = $arObject["ID"];
                        break;
                }
                $rsNews = CIBlockElement::GetList(
                    array(
                        "ACTIVE_FROM" => "DESC"
                    ),
                    $arFilterNews,
                    false,
                    array("nTopCount" => 3),
                    array(
                        "ID",
                        "IBLOCK_ID",
                        "IBLOCK_SECTION_ID",
                        "CODE",
                        "NAME",
                        "DETAIL_PAGE_URL",
                        "PREVIEW_TEXT",
                        "PREVIEW_PICTURE"
                    )
                );
                while ($arNews = $rsNews->GetNext()) {
                    $arNews["DETAIL_PAGE_URL"] = $siteUrl . $arNews["DETAIL_PAGE_URL"];
                    $arNews["PREVIEW_PICTURE"] = CFile::GetFileArray($arNews["PREVIEW_PICTURE"]);
                    $arNews["DETAIL_PICTURE"] = CFile::GetFileArray($arNews["DETAIL_PICTURE"]);
                    $arObject["OBJECT_NEWS"]["NEWS"][] = $arNews;
                }

                if ($arObject["UF_BUILDER"]) {
                    //Получаем 3 последних новости связанных с застройщиком объекта
                    $arFilterNews = array(
                        "IBLOCK_ID" => NEWS_IBLOCK_ID,
                        "ACTIVE" => "Y"

                    );
                    switch ($cityCode){
                        case moskva:
                            $arFilterNews["=PROPERTY_BUILDER_MSK"] = $arObject["UF_BUILDER"]["UF_ID"];

                            break;
                        case spb:
                            $arFilterNews["=PROPERTY_BUILDER_SPB"] = $arObject["UF_BUILDER"]["UF_ID"];
                            break;
                    }

                    $rsNews = CIBlockElement::GetList(
                        array(
                            "ACTIVE_FROM" => "DESC"
                        ),
                        $arFilterNews,
                        false,
                        array("nTopCount" => 3),
                        array(
                            "ID",
                            "IBLOCK_ID",
                            "IBLOCK_SECTION_ID",
                            "CODE",
                            "NAME",
                            "DETAIL_PAGE_URL",
                            "PREVIEW_TEXT",
                            "PREVIEW_PICTURE"
                        )
                    );
                    while ($arNews = $rsNews->GetNext()) {
                        $arNews["DETAIL_PAGE_URL"] = $siteUrl . $arNews["DETAIL_PAGE_URL"];
                        $arNews["PREVIEW_PICTURE"] = CFile::GetFileArray($arNews["PREVIEW_PICTURE"]);
                        $arNews["DETAIL_PICTURE"] = CFile::GetFileArray($arNews["DETAIL_PICTURE"]);
                        $arObject["OBJECT_NEWS"]["BUILDER"][] = $arNews;
                    }
                }

                //Находим Seo-шаблон

                $rsSeoTemplate = CIBlockElement::GetList(
                    false,
                    array("IBLOCK_ID" => SEO_IBLOCK_ID, "CODE" => $this->arParams['SEO_TEMPLATE_CODE']),
                    false,
                    false,
                    [
                        "ID",
                        "IBLOCK_ID",
                        "IBLOCK_SECTION_ID",
                        "CODE",
                        "NAME",
                        "PREVIEW_TEXT",
                        "DETAIL_TEXT",
                        "PROPERTY_VARIABLES",
                    ]
                )->Fetch();

                foreach ($rsSeoTemplate["PROPERTY_VARIABLES_DESCRIPTION"] as $keySeoProperty => $valSeoProperty) {
                    if ($valSeoProperty == "UF_AREA_NAME"){
                        $rsSeoTemplate["PROPERTY_VARIABLES_REPLACE"][$keySeoProperty] = $arObject["UF_AREA"]["NAME"];
                    }
                    elseif ($valSeoProperty == "HOUSE_ICON") {
                        $rsSeoTemplate["PROPERTY_VARIABLES_REPLACE"][$keySeoProperty] = "🏠";
                    }

                    elseif ($valSeoProperty == "STAR_ICON") {
                        $rsSeoTemplate["PROPERTY_VARIABLES_REPLACE"][$keySeoProperty] = "⭐";
                    }
                    elseif ($valSeoProperty == "CITY") {
                        $rsSeoTemplate["PROPERTY_VARIABLES_REPLACE"][$keySeoProperty] = SITE_ID == "s1" ? "Москве": "Санкт-Петербурге" ;
                    }
                    else {
                        $rsSeoTemplate["PROPERTY_VARIABLES_REPLACE"][$keySeoProperty] = $arObject[$valSeoProperty];
                    }
                }

                $rsSeoTemplate['NEW_TITLE'] = str_replace($rsSeoTemplate["PROPERTY_VARIABLES_VALUE"],$rsSeoTemplate["PROPERTY_VARIABLES_REPLACE"],$rsSeoTemplate["PREVIEW_TEXT"]);
                $rsSeoTemplate['NEW_DESCRIPTION'] = str_replace($rsSeoTemplate["PROPERTY_VARIABLES_VALUE"],$rsSeoTemplate["PROPERTY_VARIABLES_REPLACE"],$rsSeoTemplate["DETAIL_TEXT"]);

                $arObject["UF_TITLE"] = $rsSeoTemplate['NEW_TITLE'];
                $arObject["UF_DESCRIPTION"] = $rsSeoTemplate['NEW_DESCRIPTION'];

                $obFlat = CIBlockElement::GetList(
                    $arApartmentsSort,
                    $arFilterApartments,
                    false,
                    false,
                    array(
                        "ID",
                        "IBLOCK_ID",
                        "IBLOCK_SECTION_ID",
                        "CODE",
                        "NAME",
                        "SHOW_COUNTER",
                        "PREVIEW_PICTURE",
                        "DETAIL_PICTURE",
                        "PROPERTY_HOUSE",
                        "PROPERTY_FLOOR",
                        "PROPERTY_FLOORS_COUNT",
                        "PROPERTY_SQUARE",
                        "PROPERTY_KITCHEN_SQUARE",
                        "PROPERTY_HIGH_CEILINGS",
                        "PROPERTY_TERRACE",
                        "PROPERTY_ROOMS",
                        "PROPERTY_PRICE_RUB",
                        "PROPERTY_PRICE_USD",
                        "PROPERTY_PRICE_EUR",
                        "PROPERTY_PRICE_GBP",
                        "PROPERTY_BUILDING",
                        "PROPERTY_DECORATION",
                        "PROPERTY_TWO_TIER",
                        "PROPERTY_BUILD_TYPE"
                    )
                );

                while ($arFlat = $obFlat->GetNext()) {
                    if (!empty($arFlat["PREVIEW_PICTURE"])) $arFlat["PREVIEW_PICTURE"] = CFile::GetFileArray($arFlat["PREVIEW_PICTURE"]);
                    if (!empty($arFlat["DETAIL_PICTURE"])) $arFlat["DETAIL_PICTURE"] = CFile::GetFileArray($arFlat["DETAIL_PICTURE"]);
                    if (!empty($arFlat["PROPERTY_DECORATION_VALUE"])) $arFlat["PROPERTY_DECORATION_VALUE"] = $arFinishes[$arFlat["PROPERTY_DECORATION_VALUE"]]["UF_NAME"];

                    $arObject["ELEMENT_BUILD_TYPES"][$arFlat["PROPERTY_BUILD_TYPE_VALUE"]] ++;

                    $arFlat['PROPERTY_MAX_FLOOR_VALUE'] = $arBuilding[$arFlat["PROPERTY_BUILDING_VALUE"]]['UF_FLOORS']
                        ? $arBuilding[$arFlat["PROPERTY_BUILDING_VALUE"]]['UF_FLOORS']
                        : $arFlat["PROPERTY_FLOORS_COUNT_VALUE"];
                    $arFlats[] = $arFlat;
                }

                $allFlatsCount = count($arFlats);
                $arFilterRanges["ALL_COUNT"] = $allFlatsCount;

                $arFlatsListInitial = $arFlats;


                $arFlats = array(
                    "ITEMS"     => array_slice($arFlatsListInitial, ($page-1)*$this->arParams["OBJECTS_DETAIL_PAGE_COUNT_LIST"], $this->arParams["OBJECTS_DETAIL_PAGE_COUNT_LIST"]),
                    "SHOW_MORE" => (($page-1)*$this->arParams["OBJECTS_DETAIL_PAGE_COUNT_LIST"] + $this->arParams["OBJECTS_DETAIL_PAGE_COUNT_LIST"] >= $allFlatsCount) ? false : true
                );

                //Номера телефонов
                $arFilterSEO = array("IBLOCK_ID" => CONTACTS_IBLOCK_ID, "ACTIVE" => "Y", "=CODE" => $cityCode);
                $obSEO = CIBlockElement::GetList(
                    false,
                    $arFilterSEO,
                    false,
                    false,
                    array(
                        "ID",
                        "IBLOCK_ID",
                        "IBLOCK_SECTION_ID",
                        "CODE",
                        "NAME",
                        "PREVIEW_PICTURE",
                        "DETAIL_PICTURE",
                        "PROPERTY_H_NEW_BUILD",
                        "PROPERTY_DESC_NEW_BUILD",
                        "PROPERTY_PHONE",
                        "PROPERTY_PHONE_NEW_BUILD",
                        "PROPERTY_PHONE_RESALE",
                        "PROPERTY_PHONE_COUNTRY",
                        "PROPERTY_PHONE_OVERSEAS",
                    )
                )->getNext();


                $arOutput = array(
                    "OBJECT" => $arObject,
                    "FILTER_RANGES" => $arFilterRanges,
                    "FILTER_VALUES" => $arFilterValues,
                    "ROOMS_TYPES" => $arRooms,
                    "OBJECT_ROOMS_TYPES" => $arFlatRooms,
                    "INFRASTRUCTURE_OBJECTS" => $resInfrastructure,
                    "FLATS"  => $arFlats,
                    "IS_AJAX" => $isAjax,
                    "HIDE_PRICE" => $arFieldsIb["UF_HIDE_PRICE"],
                    "SHOW_MORE" => $showMore,
                    "SIMILAR_OBJECTS" => $arSimilarObjects,
                    "SEO" => $obSEO,
                );

                if($isAjax && $getCount) {
                    global $APPLICATION;
                    $APPLICATION->RestartBuffer();
                    echo $arFilterRanges["ALL_COUNT"];
                    exit;
                }

                $this->templateName = 'detail';
            }
            /*elseif ($isNotObject && (!empty($arLocation) || !empty($arSubway))) {
                $arOutput = array(
                    "LOCATION" => $arLocation ? $arLocation : $arSubway
                );
                $this->templateName = 'location';
            }*/
            else {

//                LocalRedirect('/404.php', false, '301 Moved permanently');
                LocalRedirect("/{$cityCode}/novostroyki/", false, '301 Moved permanently');
            }

        } else {
            $isAjax = $request->isAjaxRequest();
            $isApartmentRequest = false;

            //$sort     = ($_GET['sort'] && $_GET['sort'] != 'default')? $_GET['sort'] : $this->arParams["SORT_DEFAULT"];
            //$sortBy   = ($_GET['sortBy'] && $_GET['sortBy'] != 'default')? $_GET['sortBy'] : $this->arParams["SORT_BY_DEFAULT"];
            $sort     = ($_GET['sort'] && $_GET['sort'] != 'default')? $_GET['sort'] : "price";
            $sortBy   = ($_GET['sortBy'] && $_GET['sortBy'] != 'default')? $_GET['sortBy'] : "desc";
            $getCount = ($_GET['getCount']) ? $_GET['getCount'] : false;
            $type = $_GET['type'];
            $page = ($_GET['page']) ? $_GET['page'] : 1;
            $showMore = ($_GET['showMore']) ? $_GET['showMore'] : false;
            $arFilterObjects = array('IBLOCK_ID' => $iblockID, 'ACTIVE' => 'Y', 'CNT_ACTIVE' => 'Y');
            $arFilterSimilarObjects = array('IBLOCK_ID' => $iblockID, 'ACTIVE' => 'Y', 'CNT_ACTIVE' => 'Y');
            $arFilterApartmentsNoParams = array('IBLOCK_ID' => $iblockID, 'ACTIVE' => 'Y');
            $arFilterRanges = array();

            $search = ($_GET['search']) ? $_GET['search'] : null ;
            //$deadline = ($_GET['deadline'] && is_numeric($_GET['deadline'])) ? $_GET['deadline'] : null ;
            $deadline = ($_GET['deadline']) ? $_GET['deadline'] : null ;
            $deadlineYear = explode("кв. ",$deadline);

            $priceMin = ($_GET['price_min']) ? $_GET['price_min'] : null ;
            $priceMax = ($_GET['price_max']) ? $_GET['price_max'] : null ;
            $features = ($_GET['features']) ? $_GET['features'] : null ;
            $squareMin = ($_GET['square_min']) ? $_GET['square_min'] : null ;
            $squareMax = ($_GET['square_max']) ? $_GET['square_max'] : null ;
            $kitchenSquareMin = ($_GET['kitchensizemin']) ? $_GET['kitchensizemin'] : null ;
            $kitchenSquareMax = ($_GET['kitchensizemax']) ? $_GET['kitchensizemax'] : null ;
            $floorMin = ($_GET['floor_min']) ? $_GET['floor_min'] : null ;
            $floorMax = ($_GET['floor_max']) ? $_GET['floor_max'] : null ;
            $finish = ($_GET['finish']) ? $_GET['finish'] : null ;
            $apartmenttype = ($_GET['rooms']) ? $_GET['rooms'] : null ;
            $buildtype = ($_GET['buildtype']) ? $_GET['buildtype'] : null ;
            //$arNavStartParams = array('nPageSize' => $this->arParams["OBJECTS_PAGE_COUNT_LIST"], 'iNumPage' => $page);
            //$curCity = $_COOKIE['city'] ? $_COOKIE['city'] : "moskva";

            $arFilterArea = array('IBLOCK_ID' => 14, 'ACTIVE' => 'Y', '=SECTION_ID' => $rsCurCity['ID']);
            $arSelectArea = array('ID', 'NAME');

            // Поиск дефолтных значений для фильтра

            $cacheObjectsParamsDefault = Cache::createInstance(); // получаем экземпляр класса
            if ($cacheObjectsParamsDefault->initCache(86400, "cache_data_objects_default_params_new_build".$rsCurCity["CODE"], "/")) { // проверяем кеш и задаём настройки
                $arObjectsParamsDefault = $cacheObjectsParamsDefault->getVars(); // достаем переменные из кеша
            }
            elseif ($cacheObjectsParamsDefault->startDataCache()) {
                $arDeadLine = ["Сдан"];
                $arFlatDecorations = [];
                $arFlatTypes = [];
                $arFlatRoomTypes = [];

                $rsAreas = CIBlockSection::GetTreeList($arFilterArea, $arSelectArea); //находим районы выбранного города

                while($arArea = $rsAreas->Fetch()) {
                    $arObjectsParamsDefault["AREAS"][$arArea['ID']] = $arArea;
                }

                $rsObjectsNoParams = \CIBlockSection::GetList(
                    array("UF_DEADLINE_YEAR" => "ASC", "UF_DEADLINE" => "ASC"),
                    $arFilterObjects,
                    true,
                    array(
                        "ACTIVE",
                        "ACTIVE_FROM",
                        "CREATED_DATE",
                        "NAME",
                        "CODE",
                        "IBLOCK_ID",
                        "IBLOCK_SECTION_ID",
                        "ID",
                        "PICTURE",
                        "DETAIL_TEXT",
                        "UF_*"
                    ),
                    false
                );
                while ($arObjectNoParams = $rsObjectsNoParams->Fetch()) {
                    $arObjectsNoParamsSectionActiveID[] = $arObjectNoParams["ID"];
                    if (!in_array($arObjectNoParams["UF_DEADLINE"],$arDeadLine) && !empty($arObjectNoParams["UF_DEADLINE"]))
                        $arDeadLine[] = $arObjectNoParams["UF_DEADLINE"];
                }

                $arObjectsParamsDefault["OBJECTS_ACTIVE_ID"] = $arObjectsNoParamsSectionActiveID;

                $arObjectsParamsDefault["DEADLINES"] = $arDeadLine;

                $obFlatNoParams = CIBlockElement::GetList(
                    false,
                    $arFilterApartmentsNoParams,
                    false,
                    false,
                    array(
                        "ID",
                        "IBLOCK_ID",
                        "IBLOCK_SECTION_ID",
                        "CODE",
                        "NAME",
                        "PROPERTY_ROOMS",
                        "PROPERTY_BUILDING",
                        "PROPERTY_DECORATION",
                        "PROPERTY_TWO_TIER",
                        "PROPERTY_BUILD_TYPE"
                    )
                );

                while ($arFlatNoParams = $obFlatNoParams->GetNext()) {
                    if (!in_array($arFlatNoParams["PROPERTY_DECORATION_VALUE"],$arFlatDecorations) && !empty($arFlatNoParams["PROPERTY_DECORATION_VALUE"]))
                        $arFlatDecorations[] = $arFlatNoParams["PROPERTY_DECORATION_VALUE"];

                    if (!in_array($arFlatNoParams["PROPERTY_BUILD_TYPE_VALUE"],$arFlatTypes) && !empty($arFlatNoParams["PROPERTY_BUILD_TYPE_VALUE"]))
                        $arFlatTypes[] = $arFlatNoParams["PROPERTY_BUILD_TYPE_VALUE"];
                    if (!in_array($arFlatNoParams["PROPERTY_ROOMS_VALUE"],$arFlatRoomTypes) && !empty($arFlatNoParams["PROPERTY_ROOMS_VALUE"]))
                        $arFlatRoomTypes[] = $arFlatNoParams["PROPERTY_ROOMS_VALUE"];
                }

                //находим Отделки

                $hlblFinish = DECORATIONS_HIGHLOADBLOCK_ID; // Указываем ID highloadblock типов отделок
                $hlblock = HL\HighloadBlockTable::getById($hlblFinish)->fetch();

                $entity = HL\HighloadBlockTable::compileEntity($hlblock);
                $entity_data_class = $entity->getDataClass();

                $rsData = $entity_data_class::getList(array(
                    "select" => array("*"),
                    "order" => array("ID" => "ASC"),
                    "filter" => array("UF_ID" => $arFlatDecorations)  // Задаем параметры фильтра выборки
                ));

                while($arData = $rsData->Fetch()){
                    $arObjectsParamsDefault["FINISHES"][$arData['UF_ID']] = $arData;
                }

                //находим типы комнат
                $property_rooms_enums = CIBlockPropertyEnum::GetList(Array("SORT"=>"ASC", "ID"=>"ASC"), Array("IBLOCK_ID"=>$iblockID, "CODE"=>"ROOMS"));
                while($room_enum_fields = $property_rooms_enums->GetNext())
                {
                    if (in_array($room_enum_fields["VALUE"],$arFlatRoomTypes))
                        $arObjectsParamsDefault["APARTMENT_TYPES"][$room_enum_fields["ID"]] = $room_enum_fields;
                }

                //находим Застройщиков

                $hlblBuilder = $cityCode == 'spb' ? BUILDERS_SPB_HIGHLOADBLOCK_ID : BUILDERS_HIGHLOADBLOCK_ID; // Указываем ID highloadblock застройщиков
                $hlblock = HL\HighloadBlockTable::getById($hlblBuilder)->fetch();

                $entity = HL\HighloadBlockTable::compileEntity($hlblock);
                $entity_data_class = $entity->getDataClass();

                $rsBuilder = $entity_data_class::getList(array(
                    "select" => array("*"),
                    "order" => array("ID" => "ASC"),
                ));


                while($arBuilder = $rsBuilder->Fetch()){
                    $arObjectsParamsDefault["BUILDERS"][$arBuilder['ID']] = $arBuilder;
                }

                //находим классы ЖК

                $hlblObjectLiveClass = LIVE_CLASSES_HIGHLOADBLOCK_ID; // Указываем ID highloadblock типов жк
                $hlblock = HL\HighloadBlockTable::getById($hlblObjectLiveClass)->fetch();

                $entity = HL\HighloadBlockTable::compileEntity($hlblock);
                $entity_data_class = $entity->getDataClass();

                $rsDataObjectLiveClass = $entity_data_class::getList(array(
                    "select" => array("*"),
                    "order" => array("ID" => "ASC"),
                ));

                while($arDataObjectLiveClass = $rsDataObjectLiveClass->Fetch()){
                    $arObjectsParamsDefault["OBJECT_CLASSES"][$arDataObjectLiveClass['UF_ID']] = $arDataObjectLiveClass["UF_NAME"];
                }

                //находим тип объекта

                $hlblObjectType = OBJECT_TYPE_HIGHLOADBLOCK_ID; // Указываем ID highloadblock типов объектов
                $hlblock = HL\HighloadBlockTable::getById($hlblObjectType)->fetch();

                $entity = HL\HighloadBlockTable::compileEntity($hlblock);
                $entity_data_class = $entity->getDataClass();

                $rsDataObjectType = $entity_data_class::getList(array(
                    "select" => array("*"),
                    "order" => array("ID" => "ASC"),
                ));

                while($arDataObjectType = $rsDataObjectType->Fetch()){
                    $arObjectsParamsDefault["OBJECT_TYPES"][$arDataObjectType['UF_ID']] = $arDataObjectType['UF_NAME'];
                }

                //находим типы недвижимости

                $hlblBuildType = BUILDING_VARIANTS_HIGHLOADBLOCK_ID; // Указываем ID highloadblock типов недвижимости
                $hlblock = HL\HighloadBlockTable::getById($hlblBuildType)->fetch();

                $entity = HL\HighloadBlockTable::compileEntity($hlblock);
                $entity_data_class = $entity->getDataClass();

                $rsData = $entity_data_class::getList(array(
                    "select" => array("*"),
                    "order" => array("ID" => "ASC"),
                    "filter" => array("UF_XML_ID" => $arFlatTypes)  // Задаем параметры фильтра выборки
                ));

                while($arData = $rsData->Fetch()){
                    $arObjectsParamsDefault["BUILD_TYPES"][$arData['UF_XML_ID']] = $arData;
                }

                //находим Иконки для объектов

                $hlblObjectIcons = ICONS_HIGHLOADBLOCK_ID; // Указываем ID highloadblock иконок
                $hlblock = HL\HighloadBlockTable::getById($hlblObjectIcons)->fetch();

                $entity = HL\HighloadBlockTable::compileEntity($hlblock);
                $entity_data_class = $entity->getDataClass();

                $rsObjectIcons = $entity_data_class::getList(array(
                    "select" => array("*"),
                    "order" => array("ID" => "ASC"),
                ));


                while($arObjectIcon = $rsObjectIcons->Fetch()){
                    $arObjectsParamsDefault["OBJECT_ICONS"][$arObjectIcon['UF_XML_ID']] = $arObjectIcon;
                }

                //находим станции метро

                $hlblSubway = $cityCode == 'spb' ? SUBWAYS_SPB_HIGHLOADBLOCK_ID : SUBWAYS_HIGHLOADBLOCK_ID; // Указываем ID highloadblock метро
                $hlblock = HL\HighloadBlockTable::getById($hlblSubway)->fetch();

                $entity = HL\HighloadBlockTable::compileEntity($hlblock);
                $entity_data_class = $entity->getDataClass();

                $rsSubway = $entity_data_class::getList(array(
                    "select" => array("*"),
                    "order" => array("ID" => "ASC"),
                ));


                while($arSubway = $rsSubway->Fetch()){
                    $arObjectsParamsDefault["SUBWAYS"][$arSubway['ID']] = $arSubway;
                }

                $cacheObjectsParamsDefault->endDataCache($arObjectsParamsDefault); // записываем в кеш
            }

            $arObjectsActiveID = $arObjectsParamsDefault["OBJECTS_ACTIVE_ID"];
            $arAreas = $arObjectsParamsDefault["AREAS"];
            $arFinishes = $arObjectsParamsDefault["FINISHES"];
            $arSubways = $arObjectsParamsDefault["SUBWAYS"];
            $arApartmentTypes = $arObjectsParamsDefault["APARTMENT_TYPES"];
            $arBuilders = $arObjectsParamsDefault["BUILDERS"];
            $arObjectIcons = $arObjectsParamsDefault["OBJECT_ICONS"];
            $arObjectClasses = $arObjectsParamsDefault["OBJECT_CLASSES"];
            $arObjectTypes = $arObjectsParamsDefault["OBJECT_TYPES"];
            $arBuildTypes = $arObjectsParamsDefault["BUILD_TYPES"];
            $arDeadLines = $arObjectsParamsDefault["DEADLINES"];

            // Параметры фильтра

            // Сортировка
            if($sort && $sortBy) {
                switch($sort) {
                    case 'price':
                        //$propertySort = 'PROPERTY_PRICE_RUB';
                        $sortName = 'по цене';
                        break;
                    case 'square':
                        //$propertySort = 'PROPERTY_SQUARE';
                        $sortName = 'по площади';
                        break;
                    case 'deadline':
                        $propertySort = 'UF_DEADLINE_YEAR';
                        $propertySortSecond = 'UF_DEADLINE';
                        $sortName = 'по сроку сдачи';
                        break;
                    default :
                        $propertySort = $sort;
                        $sortName = 'по умолчанию';
                        break;
                }

                if ($sort != $this->arParams['SORT_DEFAULT'])
                    $sortName.= $sortBy == 'asc' ? " (возрастание)" : " (убывание)";

                $arSortObjects = array(
                    $propertySort => strtoupper($sortBy),
                );

                if ($propertySortSecond){
                    $arSortObjects[$propertySortSecond] = strtoupper($sortBy);
                }
            }

            if ($search != null) {
                $decodeSearch = json_decode($search,true);
                foreach ($decodeSearch as $arSearchTag) {
                    $area = str_replace("area_","", $arSearchTag["id"], $areaCount);
                    $subway = str_replace("subway_","", $arSearchTag["id"], $subwayCount);
                    $street = str_replace("street_","", $arSearchTag["id"], $streetCount);
                    $object = str_replace("object_","", $arSearchTag["id"], $objectCount);
                    $builder = str_replace("builder_","", $arSearchTag["id"], $builderCount);
                    if ($areaCount > 0) {
                        if ($arSearchTag["exclude"]) {
                            $reqnotAreas[] = $area;
                        }
                        else {
                            $reqAreas[] = $area;
                        }
                    }
                    elseif ($subwayCount > 0) {
                        if ($arSearchTag["exclude"]) {
                            $reqnotSubways[] = $subway;
                        }
                        else {
                            $reqSubways[] = $subway;
                        }
                    }
                    elseif ($streetCount > 0) {
                        if ($arSearchTag["exclude"]) {
                            $reqnotStreets[] = $street;
                        }
                        else {
                            $reqStreets[] = $street;
                        }
                    }
                    elseif ($objectCount > 0) {
                        if ($arSearchTag["exclude"]) {
                            $reqnotObjects[] = $object;
                        }
                        else {
                            $reqObjects[] = $object;
                        }
                    }
                    elseif ($builderCount > 0) {
                        if ($arSearchTag["exclude"]) {
                            $reqnotBuilders[] = $builder;
                        }
                        else {
                            $reqBuilders[] = $builder;
                        }
                    }
                }
                if(count($reqnotAreas) > 0) {
                    $arFilterObjects["!UF_AREA"] = array_merge(array("LOGIC" => "OR"), $reqnotAreas);
                    $arFilterSimilarObjects["!UF_AREA"] = array_merge(array("LOGIC" => "OR"), $reqnotAreas);
                }
                if(count($reqAreas) > 0) {
                    $arFilterObjects["UF_AREA"] = array_merge(array("LOGIC" => "OR"), $reqAreas);
                    $arFilterObjects["!UF_AREA"] = false;
                    $arFilterSimilarObjects["UF_AREA"] = array_merge(array("LOGIC" => "OR"), $reqAreas);
                    $arFilterSimilarObjects["!UF_AREA"] = false;
                }
                if(count($reqnotSubways) > 0) {
                    $arFilterObjects["!UF_METRO_HL"] = array_merge(array("LOGIC" => "OR"), $reqnotSubways);
                }
                if(count($reqSubways) > 0) {
                    $arFilterObjects["UF_METRO_HL"] = array_merge(array("LOGIC" => "OR"), $reqSubways);
                    $arFilterObjects["!UF_METRO_HL"] = false;
                }
                if(count($reqnotStreets) > 0) {
                    $arFilterObjects["!%UF_ADDRESS"] = $reqnotStreets;
                }
                if(count($reqStreets) > 0) {
                    $arFilterObjects["%UF_ADDRESS"] = $reqStreets;
                }
                if(count($reqnotObjects) > 0) {
                    $arFilterObjects["!ID"] = array_merge(array("LOGIC" => "OR"), $reqnotObjects);
                }
                if(count($reqObjects) > 0) {
                    $arFilterObjects["ID"] = array_merge(array("LOGIC" => "OR"), $reqObjects);
                }
                if(count($reqnotBuilders) > 0) {
                    $arFilterObjects["!UF_BUILD"] = array_merge(array("LOGIC" => "OR"), $reqnotBuilders);

                }
                if(count($reqBuilders) > 0) {
                    $arFilterObjects["UF_BUILD"] = array_merge(array("LOGIC" => "OR"), $reqBuilders);
                    $arFilterObjects["!UF_BUILD"] = false;
                }
                $arFilterValues["SEARCH"] = json_encode($decodeSearch, JSON_UNESCAPED_UNICODE);
            }

            if($deadline != null) {
                if (!empty($deadlineYear[1])) {
                    $arFilterObjects[] = array(
                        "LOGIC" => "OR",
                        array("=UF_DEADLINE" => "Сдан"),
                        array("=UF_DEADLINE_YEAR" => $deadlineYear[1], "<=UF_DEADLINE" => $deadline),
                        array("<UF_DEADLINE_YEAR" => $deadlineYear[1]),
                    );
                }
                else {
                    $arFilterObjects["=UF_DEADLINE"] = $deadline;
                }
            }

            if($priceMin != null) {
                $isApartmentRequest == false ? $isApartmentRequest = true : null;
                $arFilterSimilarApartments['>=PRICE_RUB'] = floatval($priceMin)*1000000;
                $arFilterApartments['>=PRICE_RUB'] = floatval($priceMin)*1000000;
                //$paramsValues .= $paramsValues
                $arFilterValues['PRICE'][0] = $priceMin;

            }
            if($priceMax != null) {
                $isApartmentRequest == false ? $isApartmentRequest = true : null;
                $arFilterSimilarApartments['<=PRICE_RUB'] = floatval($priceMax)*1000000;
                $arFilterApartments['<=PRICE_RUB'] = floatval($priceMax)*1000000;
                $arFilterValues['PRICE'][1] = $priceMax;

            }

            if($features != null) {
                $isApartmentRequest == false ? $isApartmentRequest = true : null;
                $reqFeatures = (is_array($features)) ? $features : explode(';', $features);
                if(count($reqFeatures) > 0) {
                    foreach ($reqFeatures as $option) {
                        switch ($option) {
                            case 'terrace':
                                $arFilterApartments["TERRACE"] = $cityCode == 'spb' ? 34 : 4;
                                break;
                            case 'twotier':
                                $arFilterApartments["TWO_TIER"] = $cityCode == 'spb' ? 27 : 10;
                                break;
                            case 'highceilings':
                                $arFilterApartments["HIGH_CEILINGS"] = $cityCode == 'spb' ? 26 : 3;
                                break;
                        }
                    }
                    $arFilterValues['FEATURES'] = implode(";",$reqFeatures);
                }
            }

            if($squareMin != null) {
                $isApartmentRequest == false ? $isApartmentRequest = true : null;
                $arFilterApartments['>=SQUARE'] = floatval($squareMin);
                $arFilterValues['SQUARE'][0] = $squareMin;

            }
            if($squareMax != null) {
                $isApartmentRequest == false ? $isApartmentRequest = true : null;
                $arFilterApartments['<=SQUARE'] = floatval($squareMax);
                $arFilterValues['SQUARE'][1] = $squareMax;

            }

            if($kitchenSquareMin != null) {
                $isApartmentRequest == false ? $isApartmentRequest = true : null;
                $arFilterApartments['>=KITCHEN_SQUARE'] = floatval($kitchenSquareMin);
                $arFilterValues['KITCHEN_SQUARE'][0] = $kitchenSquareMin;

            }
            if($kitchenSquareMax != null) {
                $isApartmentRequest == false ? $isApartmentRequest = true : null;
                $arFilterApartments['<=KITCHEN_SQUARE'] = floatval($kitchenSquareMax);
                $arFilterValues['KITCHEN_SQUARE'][1] = $kitchenSquareMax;

            }

            if($floorMin != null) {
                $isApartmentRequest == false ? $isApartmentRequest = true : null;
                $arFilterApartments['>=FLOOR'] = intval($floorMin);
                $arFilterValues['FLOOR'][0] = $floorMin;

            }
            if($floorMax != null) {
                $isApartmentRequest == false ? $isApartmentRequest = true : null;
                $arFilterApartments['<=FLOOR'] = intval($floorMax);
                $arFilterValues['FLOOR'][1] = $floorMax;

            }

            if($apartmenttype != null) {
                $isApartmentRequest == false ? $isApartmentRequest = true : null;
                $reqApartmenttype = (is_array($apartmenttype)) ? $apartmenttype : explode(';', $apartmenttype);
                if(count($reqApartmenttype) > 0) {
                    $keyApartmentType = array_search('s', $reqApartmenttype);
                    if ($keyApartmentType !== false )
                        $reqApartmenttype[$keyApartmentType] = 0;
                    $arFilterSimilarApartments["ROOMS"] = array_merge(array("LOGIC" => "OR"), $reqApartmenttype);
                    $arFilterApartments["ROOMS"] = array_merge(array("LOGIC" => "OR"), $reqApartmenttype);
                    $arFilterValues["APARTMENT_TYPE"] = implode(";",$reqApartmenttype);
                }
            }

            if($finish != null) {
                $isApartmentRequest == false ? $isApartmentRequest = true : null;
                $reqFinish = (is_array($finish)) ? $finish : explode(';', $finish);
                if(count($reqFinish) > 0) {
                    $arFilterApartments["DECORATION"] = array_merge(array("LOGIC" => "OR"), $reqFinish);
                    $arFilterValues["DECORATION"] = implode(";",$reqFinish);
                }
            }

            if($buildtype != null) {
                $isApartmentRequest == false ? $isApartmentRequest = true : null;
                $reqBuildType = (is_array($buildtype)) ? $buildtype : explode(';', $buildtype);
                if(count($reqBuildType) > 0) {
                    $arFilterApartments["BUILD_TYPE"] = array_merge(array("LOGIC" => "OR"), $reqBuildType);
                    $arFilterValues["BUILD_TYPES"] = implode(";",$reqBuildType);
                }
            }

            // Поиск дефолтных значений для фильтра

            $cacheParamsDefault = Cache::createInstance(); // получаем экземпляр класса
            if ($cacheParamsDefault->initCache(86400, "cache_data_default_filter_new_build".$rsCurCity["CODE"], "/")) { // проверяем кеш и задаём настройки
                $arFilterParamsDefault = $cacheParamsDefault->getVars(); // достаем переменные из кеша
            }
            elseif ($cacheParamsDefault->startDataCache()) {
                $arMinPriceApartment = CIBlockElement::GetList(
                    array("PROPERTY_PRICE_RUB" => "ASC"),
                    array(
                        "IBLOCK_ID"                => $iblockID,
                        "ACTIVE"                   => "Y",
                        ">PROPERTY_PRICE_RUB"      => 0,
                        "IBLOCK_SECTION_ID"        => $arObjectsActiveID
                    ),
                    false,
                    array("nTopCount" => 1),
                    array(
                        "ID",
                        "IBLOCK_ID",
                        "IBLOCK_SECTION_ID",
                        "CODE",
                        "NAME",
                        "PROPERTY_PRICE_RUB",
                    )
                )->Fetch();

                $arMaxPriceApartment = CIBlockElement::GetList(
                    array("PROPERTY_PRICE_RUB" => "DESC"),
                    array(
                        "IBLOCK_ID"                => $iblockID,
                        "ACTIVE"                   => "Y",
                        ">PROPERTY_PRICE_RUB"      => 0,
                        "IBLOCK_SECTION_ID"        => $arObjectsActiveID
                    ),
                    false,
                    array("nTopCount" => 1),
                    array(
                        "ID",
                        "IBLOCK_ID",
                        "IBLOCK_SECTION_ID",
                        "CODE",
                        "NAME",
                        "PROPERTY_PRICE_RUB",
                    )
                )->Fetch();

                $arMinSquareApartment = CIBlockElement::GetList(
                    array("PROPERTY_SQUARE"=>"ASC"),
                    array(
                        "IBLOCK_ID"                => $iblockID,
                        "ACTIVE"                   => "Y",
                        ">PROPERTY_PRICE_RUB"      => 0,
                        ">PROPERTY_SQUARE"         => 0,
                        "IBLOCK_SECTION_ID"        => $arObjectsActiveID
                    ),
                    false,
                    array("nTopCount" => 1),
                    array(
                        "ID",
                        "IBLOCK_ID",
                        "IBLOCK_SECTION_ID",
                        "CODE",
                        "NAME",
                        "PROPERTY_SQUARE",
                    )
                )->Fetch();

                $arMaxSquareApartment = CIBlockElement::GetList(
                    array("PROPERTY_SQUARE"=>"DESC"),
                    array(
                        "IBLOCK_ID"                => $iblockID,
                        "ACTIVE"                   => "Y",
                        ">PROPERTY_PRICE_RUB"      => 0,
                        ">PROPERTY_SQUARE"         => 0,
                        "IBLOCK_SECTION_ID"        => $arObjectsActiveID
                    ),
                    false,
                    array("nTopCount" => 1),
                    array(
                        "ID",
                        "IBLOCK_ID",
                        "IBLOCK_SECTION_ID",
                        "CODE",
                        "NAME",
                        "PROPERTY_SQUARE",
                    )
                )->Fetch();

                $arMinKitchenSquareApartment = CIBlockElement::GetList(
                    array("PROPERTY_KITCHEN_SQUARE"=>"ASC"),
                    array(
                        "IBLOCK_ID"                => $iblockID,
                        "ACTIVE"                   => "Y",
                        ">PROPERTY_PRICE_RUB"      => 0,
                        ">PROPERTY_KITCHEN_SQUARE" => 0,
                        "IBLOCK_SECTION_ID"        => $arObjectsActiveID
                    ),
                    false,
                    array("nTopCount" => 1),
                    array(
                        "ID",
                        "IBLOCK_ID",
                        "IBLOCK_SECTION_ID",
                        "CODE",
                        "NAME",
                        "PROPERTY_KITCHEN_SQUARE",
                    )
                )->Fetch();

                $arMaxKitchenSquareApartment = CIBlockElement::GetList(
                    array("PROPERTY_KITCHEN_SQUARE"=>"DESC"),
                    array(
                        "IBLOCK_ID"                => $iblockID,
                        "ACTIVE"                   => "Y",
                        ">PROPERTY_PRICE_RUB"      => 0,
                        ">PROPERTY_KITCHEN_SQUARE" => 0,
                        "IBLOCK_SECTION_ID"        => $arObjectsActiveID
                    ),
                    false,
                    array("nTopCount" => 1),
                    array(
                        "ID",
                        "IBLOCK_ID",
                        "IBLOCK_SECTION_ID",
                        "CODE",
                        "NAME",
                        "PROPERTY_KITCHEN_SQUARE",
                    )
                )->Fetch();

                $arMinFloorApartment = CIBlockElement::GetList(
                    array("PROPERTY_FLOOR"=>"ASC"),
                    array(
                        "IBLOCK_ID"                => $iblockID,
                        "ACTIVE"                   => "Y",
                        ">PROPERTY_PRICE_RUB"      => 0,
                        ">PROPERTY_FLOOR"          => 0,
                        "IBLOCK_SECTION_ID"        => $arObjectsActiveID
                    ),
                    false,
                    array("nTopCount" => 1),
                    array(
                        "ID",
                        "IBLOCK_ID",
                        "IBLOCK_SECTION_ID",
                        "CODE",
                        "NAME",
                        "PROPERTY_FLOOR",
                    )
                )->Fetch();

                $arMaxFloorApartment = CIBlockElement::GetList(
                    array("PROPERTY_FLOOR"=>"DESC"),
                    array(
                        "IBLOCK_ID"                => $iblockID,
                        "ACTIVE"                   => "Y",
                        ">PROPERTY_PRICE_RUB"      => 0,
                        ">PROPERTY_FLOOR"          => 0,
                        "IBLOCK_SECTION_ID"        => $arObjectsActiveID
                    ),
                    false,
                    array("nTopCount" => 1),
                    array(
                        "ID",
                        "IBLOCK_ID",
                        "IBLOCK_SECTION_ID",
                        "CODE",
                        "NAME",
                        "PROPERTY_FLOOR",
                    )
                )->Fetch();

                $arTerraceApartment = CIBlockElement::GetList(
                    array("PROPERTY_TERRACE"=>"ASC"),
                    array(
                        "IBLOCK_ID"                => $iblockID,
                        "ACTIVE"                   => "Y",
                        ">PROPERTY_PRICE_RUB"      => 0,
                        "=PROPERTY_TERRACE_VALUE"  => "Y",
                        "IBLOCK_SECTION_ID"        => $arObjectsActiveID
                    ),
                    false,
                    array("nTopCount" => 1),
                    array(
                        "ID",
                        "IBLOCK_ID",
                        "IBLOCK_SECTION_ID",
                        "CODE",
                        "NAME",
                        "PROPERTY_TERRACE",
                    )
                )->Fetch();

                $arTwoTierApartment = CIBlockElement::GetList(
                    array("PROPERTY_TWO_TIER"=>"ASC"),
                    array(
                        "IBLOCK_ID"                => $iblockID,
                        "ACTIVE"                   => "Y",
                        ">PROPERTY_PRICE_RUB"      => 0,
                        "=PROPERTY_TWO_TIER_VALUE"  => "Y",
                        "IBLOCK_SECTION_ID"        => $arObjectsActiveID
                    ),
                    false,
                    array("nTopCount" => 1),
                    array(
                        "ID",
                        "IBLOCK_ID",
                        "IBLOCK_SECTION_ID",
                        "CODE",
                        "NAME",
                        "PROPERTY_TERRACE",
                    )
                )->Fetch();

                $arHighCeilingsApartment = CIBlockElement::GetList(
                    array("PROPERTY_HIGH_CEILINGS"=>"ASC"),
                    array(
                        "IBLOCK_ID"                => $iblockID,
                        "ACTIVE"                   => "Y",
                        ">PROPERTY_PRICE_RUB"      => 0,
                        "=PROPERTY_HIGH_CEILINGS_VALUE"  => "Y",
                        "IBLOCK_SECTION_ID"        => $arObjectsActiveID
                    ),
                    false,
                    array("nTopCount" => 1),
                    array(
                        "ID",
                        "IBLOCK_ID",
                        "IBLOCK_SECTION_ID",
                        "CODE",
                        "NAME",
                        "PROPERTY_TERRACE",
                    )
                )->Fetch();

                $arFilterParamsDefault['MIN_PRICE'] = $arMinPriceApartment['PROPERTY_PRICE_RUB_VALUE'];
                $arFilterParamsDefault['MAX_PRICE'] = $arMaxPriceApartment['PROPERTY_PRICE_RUB_VALUE'];
                $arFilterParamsDefault['MIN_SQUARE'] = $arMinSquareApartment['PROPERTY_SQUARE_VALUE'];
                $arFilterParamsDefault['MAX_SQUARE'] = $arMaxSquareApartment['PROPERTY_SQUARE_VALUE'];
                $arFilterParamsDefault['MIN_KITCHEN_SQUARE'] = $arMinKitchenSquareApartment['PROPERTY_KITCHEN_SQUARE_VALUE'];
                $arFilterParamsDefault['MAX_KITCHEN_SQUARE'] = $arMaxKitchenSquareApartment['PROPERTY_KITCHEN_SQUARE_VALUE'];
                $arFilterParamsDefault['MIN_FLOOR'] = $arMinFloorApartment['PROPERTY_FLOOR_VALUE'];
                $arFilterParamsDefault['MAX_FLOOR'] = $arMaxFloorApartment['PROPERTY_FLOOR_VALUE'];
                $arFilterParamsDefault['TERRACE'] = $arTerraceApartment['PROPERTY_TERRACE_VALUE'];
                $arFilterParamsDefault['TWO_TIER'] = $arTwoTierApartment['PROPERTY_TWO_TIER_VALUE'];
                $arFilterParamsDefault['HIGH_CEILINGS'] = $arHighCeilingsApartment['PROPERTY_HIGH_CEILINGS_VALUE'];

                $cacheParamsDefault->endDataCache($arFilterParamsDefault); // записываем в кеш
            }

            $arFilterRanges = array_merge($arFilterRanges, $arFilterParamsDefault);

            //if (!$arSortApartments) {
            if ($arFilterApartments)
                $arFilterSimilarObjects["PROPERTY"] = $arFilterSimilarApartments;
            $arFilterObjects["PROPERTY"] = $arFilterApartments;

            if (!empty($_GET)) {
                $paramsValues = "";
                $paramsSimilarValues = "";
                foreach ($_GET as $keyPar => $valPar) {
                    if ($keyPar == 'price_min'
                        ||
                        $keyPar == 'price_max'
                        ||
                        $keyPar == 'square_min'
                        ||
                        $keyPar == 'square_max'
                        ||
                        $keyPar == 'kitchensizemin'
                        ||
                        $keyPar == 'kitchensizemax'
                        ||
                        $keyPar == 'finish'
                        ||
                        $keyPar == 'floor_min'
                        ||
                        $keyPar == 'floor_max'
                        ||
                        $keyPar == 'rooms'
                        ||
                        $keyPar == 'features'
                        ||
                        ($keyPar == 'buildtype' && $valPar == 7)
                    )
                    {
                        if (!empty($valPar)) {
                            if ($keyPar == 'features') $keyPar = "additional";
                            if ($keyPar == 'finish') $keyPar = "decoration";
                            if (strpos($paramsValues,"?") === false) {
                                $paramsValues .= "?".$keyPar."=".str_replace(";",",",$valPar);
                                if (in_array($keyPar, array("additional", "decoration", "features")))
                                    continue;
                                $paramsSimilarValues .= "?".$keyPar."=".str_replace(";",",",$valPar);
                            }
                            else {
                                $paramsValues .= "&".$keyPar."=".str_replace(";",",",$valPar);
                                if (in_array($keyPar, array("additional", "decoration", "features")))
                                    continue;
                                $paramsSimilarValues .= "&".$keyPar."=".str_replace(";",",",$valPar);
                            }
                        }
                    }
                    else{
                        continue;
                    }
                }
            }

            $rsObjects = \CIBlockSection::GetList(
                $arSortObjects,
                $arFilterObjects,
                true,
                array(
                    "ACTIVE",
                    "ACTIVE_FROM",
                    "CREATED_DATE",
                    "NAME",
                    "CODE",
                    "IBLOCK_ID",
                    "IBLOCK_SECTION_ID",
                    "ID",
                    "PICTURE",
                    "DETAIL_TEXT",
                    "UF_*"
                ),
                false
            //$arNavStartParams
            );

            $arObjects = array();
            while ($objects = $rsObjects->GetNext()) {
                if ($objects['ELEMENT_CNT']  ==  0)
                    continue;

                $cacheSectionsRes = Cache::createInstance(); // получаем экземпляр класса
                if ($cacheSectionsRes->initCache(86400, "cache_data_section_filter_new_build".md5(implode(",", $objects)), "/")) { // проверяем кеш и задаём настройки
                    $objects = $cacheSectionsRes->getVars(); // достаем переменные из кеша
                } elseif ($cacheSectionsRes->startDataCache()) {
                    $objects["UF_AREA"] = $arAreas[$objects["UF_AREA"]]["NAME"];
                    $objects["PICTURE"] = CFile::GetFileArray($objects["PICTURE"]);
                    $objects["PICTURE_RESIZE"] = CFile::ResizeImageGet($objects["PICTURE"]["ID"], array('width' => 486, 'height' => 264), BX_RESIZE_IMAGE_EXACT)['src'];
                    if (!empty($objects["UF_PHOTOS"])) {
                        $objects["UF_PHOTOS"] = array_slice($objects["UF_PHOTOS"], 0, $this->arParams['NUMBER_OF_OBJECT_PHOTOS']-1);
                        foreach ($objects["UF_PHOTOS"] as $photoKey => $arPhotoId) {
                            $objects["UF_PHOTOS"][$photoKey] = CFile::GetFileArray($arPhotoId);
                            $objects["UF_PHOTOS"][$photoKey]["PHOTO_RESIZE"] = CFile::ResizeImageGet($objects["UF_PHOTOS"][$photoKey]["ID"], array('width' => 486, 'height' => 264), BX_RESIZE_IMAGE_EXACT)['src'];
                        }
                    }

                    $arMinPriceObjectApartment = CIBlockElement::GetList(
                        array("PROPERTY_PRICE_RUB" => "ASC"),
                        array(
                            "IBLOCK_ID"                => $iblockID,
                            "IBLOCK_SECTION_ID"        => $objects["ID"],
                            "ACTIVE"                   => "Y",
                            ">PROPERTY_PRICE_RUB"      => 0,
                        ),
                        false,
                        array("nTopCount" => 1),
                        array(
                            "ID",
                            "IBLOCK_ID",
                            "IBLOCK_SECTION_ID",
                            "CODE",
                            "NAME",
                            "PROPERTY_PRICE_RUB",
                        )
                    )->Fetch();
                    $arMaxPriceObjectApartment = CIBlockElement::GetList(
                        array("PROPERTY_PRICE_RUB" => "DESC"),
                        array(
                            "IBLOCK_ID"                => $iblockID,
                            "IBLOCK_SECTION_ID"        => $objects["ID"],
                            "ACTIVE"                   => "Y",
                            ">PROPERTY_PRICE_RUB"      => 0,
                        ),
                        false,
                        array("nTopCount" => 1),
                        array(
                            "ID",
                            "IBLOCK_ID",
                            "IBLOCK_SECTION_ID",
                            "CODE",
                            "NAME",
                            "PROPERTY_PRICE_RUB",
                        )
                    )->Fetch();
                    
                    $arMinSquareObjectApartment = CIBlockElement::GetList(
                        array("PROPERTY_SQUARE" => "ASC"),
                        array(
                            "IBLOCK_ID"                => $iblockID,
                            "IBLOCK_SECTION_ID"        => $objects["ID"],
                            "ACTIVE"                   => "Y",
                            ">PROPERTY_PRICE_RUB"      => 0,
                        ),
                        false,
                        array("nTopCount" => 1),
                        array(
                            "ID",
                            "IBLOCK_ID",
                            "IBLOCK_SECTION_ID",
                            "CODE",
                            "NAME",
                            "PROPERTY_PRICE_RUB",
                        )
                    )->Fetch();
                    
                    $arMaxSquareObjectApartment = CIBlockElement::GetList(
                        array("PROPERTY_SQUARE" => "DESC"),
                        array(
                            "IBLOCK_ID"                => $iblockID,
                            "IBLOCK_SECTION_ID"        => $objects["ID"],
                            "ACTIVE"                   => "Y",
                            ">PROPERTY_PRICE_RUB"      => 0,
                        ),
                        false,
                        array("nTopCount" => 1),
                        array(
                            "ID",
                            "IBLOCK_ID",
                            "IBLOCK_SECTION_ID",
                            "CODE",
                            "NAME",
                            "PROPERTY_PRICE_RUB",
                        )
                    )->Fetch();
                    $objects["MIN_PRICE_APARTMENT"] = $arMinPriceObjectApartment["PROPERTY_PRICE_RUB_VALUE"];
                    $objects["MAX_PRICE_APARTMENT"] = $arMaxPriceObjectApartment["PROPERTY_PRICE_RUB_VALUE"];
                    $objects["MIN_SQUARE_APARTMENT"] = $arMinSquareObjectApartment["PROPERTY_SQUARE_VALUE"];
                    $objects["MAX_SQUARE_APARTMENT"] = $arMaxSquareObjectApartment["PROPERTY_SQUARE_VALUE"];

                    $cacheSectionsRes->endDataCache($objects);
                }
                $objects["SECTION_PAGE_URL"] = "/".$cityCode."/novostroyki/".$objects['CODE']."/";
                if (!empty($paramsValues)) $objects["SECTION_PAGE_URL"] .= $paramsValues;
                $arObjects[] = $objects;
            }

            if (empty($arObjects)){
                $rsSimilarObjects = \CIBlockSection::GetList(
                    $arSortObjects,
                    $arFilterSimilarObjects,
                    true,
                    array(
                        "ACTIVE",
                        "ACTIVE_FROM",
                        "CREATED_DATE",
                        "NAME",
                        "CODE",
                        "IBLOCK_ID",
                        "IBLOCK_SECTION_ID",
                        "ID",
                        "PICTURE",
                        "DETAIL_TEXT",
                        "UF_*"
                    ),
                    array("nTopCount" => 4)
                //$arNavStartParams
                );

                if ($arFilterSimilarObjects["UF_AREA"])
                    $isSimilarArea = true;

                $arSimilarObjects = array();
                while ($similarObjects = $rsSimilarObjects->GetNext()) {
                    if ($similarObjects['ELEMENT_CNT']  ==  0)
                        continue;
                    $cacheSectionsRes = Cache::createInstance(); // получаем экземпляр класса
                    if ($cacheSectionsRes->initCache(86400, "cache_data_section_filter_new_build_similar_objects".md5(implode(",", $similarObjects)), "/")) { // проверяем кеш и задаём настройки
                        $similarObjects = $cacheSectionsRes->getVars(); // достаем переменные из кеша
                    } elseif ($cacheSectionsRes->startDataCache()) {

                        $similarObjects["UF_AREA"] = $arAreas[$similarObjects["UF_AREA"]]["NAME"];
                        $similarObjects["PICTURE"] = CFile::GetFileArray($similarObjects["PICTURE"]);
                        if ($similarObjects["PICTURE"]["WIDTH"] > 640)
                            $similarObjects["PICTURE_RESIZE"] = CFile::ResizeImageGet($similarObjects["PICTURE"]["ID"], array('width' => 640, 'height' => 480),
                                BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, 70)['src'];
                        if (!empty($similarObjects["UF_PHOTOS"])) {
                            $similarObjects["UF_PHOTOS"] = array_slice($similarObjects["UF_PHOTOS"], 0, $this->arParams['NUMBER_OF_OBJECT_PHOTOS']-1);
                            foreach ($similarObjects["UF_PHOTOS"] as $photoKey => $arPhotoId) {
                                $similarObjects["UF_PHOTOS"][$photoKey] = CFile::GetFileArray($arPhotoId);
                                if ($similarObjects["UF_PHOTOS"][$photoKey]["WIDTH"] > 640)
                                    $similarObjects["UF_PHOTOS"][$photoKey]["PHOTO_RESIZE"] = CFile::ResizeImageGet($similarObjects["UF_PHOTOS"][$photoKey]["ID"], array('width' => 640, 'height' => 480),
                                        BX_RESIZE_IMAGE_PROPORTIONAL, false, false, false, 70)['src'];
                            }
                        }

                        $arMinPriceObjectApartment = CIBlockElement::GetList(
                            array("PROPERTY_PRICE_RUB" => "ASC"),
                            array(
                                "IBLOCK_ID"                => $iblockID,
                                "IBLOCK_SECTION_ID"        => $similarObjects["ID"],
                                "ACTIVE"                   => "Y",
                                ">PROPERTY_PRICE_RUB"      => 0,
                            ),
                            false,
                            array("nTopCount" => 1),
                            array(
                                "ID",
                                "IBLOCK_ID",
                                "IBLOCK_SECTION_ID",
                                "CODE",
                                "NAME",
                                "PROPERTY_PRICE_RUB",
                            )
                        )->Fetch();
                        $arMinSquareObjectApartment = CIBlockElement::GetList(
                            array("PROPERTY_SQUARE" => "ASC"),
                            array(
                                "IBLOCK_ID"                => $iblockID,
                                "IBLOCK_SECTION_ID"        => $similarObjects["ID"],
                                "ACTIVE"                   => "Y",
                                ">PROPERTY_PRICE_RUB"      => 0,
                            ),
                            false,
                            array("nTopCount" => 1),
                            array(
                                "ID",
                                "IBLOCK_ID",
                                "IBLOCK_SECTION_ID",
                                "CODE",
                                "NAME",
                                "PROPERTY_PRICE_RUB",
                            )
                        )->Fetch();
                        $similarObjects["MIN_PRICE_APARTMENT"] = $arMinPriceObjectApartment["PROPERTY_PRICE_RUB_VALUE"];
                        $similarObjects["MIN_SQUARE_APARTMENT"] = $arMinSquareObjectApartment["PROPERTY_SQUARE_VALUE"];

                        $cacheSectionsRes->endDataCache($similarObjects);
                    }

                    $similarObjects["SECTION_PAGE_URL"] = "/".$cityCode."/novostroyki/".$similarObjects['CODE']."/";
                    if (!empty($paramsSimilarValues)) $similarObjects["SECTION_PAGE_URL"] .= $paramsSimilarValues;
                    $arSimilarObjects[] = $similarObjects;
                }
            }

            if ($sort == 'square') {
                if ($sortBy == 'asc') {
                    usort($arObjects, function ($a, $b) {
                        return ($a['MIN_SQUARE_APARTMENT']-$b['MIN_SQUARE_APARTMENT']);
                    });
                }
                else {
                    usort($arObjects, function ($a, $b) {
                        return ($b['MAX_SQUARE_APARTMENT']-$a['MAX_SQUARE_APARTMENT']);
                    });
                }
            }

            if ($sort == 'price') {
                if ($sortBy == 'asc') {
                    usort($arObjects, function ($a, $b) {
                        return ($a['MIN_PRICE_APARTMENT'] - $b['MIN_PRICE_APARTMENT']);
                    });
                }
                else {
                    usort($arObjects, function ($a, $b) {
                        return ($b['MAX_PRICE_APARTMENT'] - $a['MAX_PRICE_APARTMENT']);
                    });
                }
            }


            $allObjectsCount = count($arObjects);
            global $profi_seo_page_id;
            if ($allObjectsCount == 0 && !empty($profi_seo_page_id))
            {
//                LocalRedirect('/404.php', false, '301 Moved permanently');
                LocalRedirect("/{$cityCode}/novostroyki/", false, '301 Moved permanently');
            }


            $arFilterRanges["ALL_COUNT"] = $allObjectsCount;

            $arObjectsListInitial = $arObjects;
            $allObjects = $arObjects;

            //if($page > 1){
                $arObjects = array(
                    "ITEMS"     => array_slice($arObjectsListInitial, ($page-1)*10+2, 10),
                    "SHOW_MORE" => (($page-1)*10 + 10 >= $allObjectsCount) ? false : true
                );
            //} else {
                $arObjects = array(
                    "ITEMS"     => array_slice($arObjectsListInitial, ($page-1)*$this->arParams["OBJECTS_PAGE_COUNT_LIST"], $this->arParams["OBJECTS_PAGE_COUNT_LIST"]),
                    "SHOW_MORE" => (($page-1)*$this->arParams["OBJECTS_PAGE_COUNT_LIST"] + $this->arParams["OBJECTS_PAGE_COUNT_LIST"] >= $allObjectsCount) ? false : true
                );
            //}

            //Заголовок и текст
            $arFilterSEO = array("IBLOCK_ID" => CONTACTS_IBLOCK_ID, "ACTIVE" => "Y", "=CODE" => $cityCode);
            $obSEO = CIBlockElement::GetList(
                false,
                $arFilterSEO,
                false,
                false,
                array(
                    "ID",
                    "IBLOCK_ID",
                    "IBLOCK_SECTION_ID",
                    "CODE",
                    "NAME",
                    "PREVIEW_PICTURE",
                    "DETAIL_PICTURE",
                    "PROPERTY_H_NEW_BUILD",
                    "PROPERTY_DESC_NEW_BUILD",
                    "PROPERTY_PHONE",
                    "PROPERTY_PHONE_NEW_BUILD",
                    "PROPERTY_PHONE_RESALE",
                    "PROPERTY_PHONE_COUNTRY",
                    "PROPERTY_PHONE_OVERSEAS",
                )
            )->getNext();

            $arOutput = array(
                "SORT" => $arSortObjects,
                "SORT_DEFAULT" => $sortDefault,
                "DEADLINES" => $arDeadLines,
                "DEFAULT_BROKER" => $rsManager,
                "OBJECTS" => $arObjects,
                "SIMILAR_OBJECTS" => $arSimilarObjects,
                "SIMILAR_AREA" => $isSimilarArea ? $isSimilarArea : null,
                "AREAS" => $arAreas,
                "SUBWAYS" => $arSubways,
                "BUILDERS" => $arBuilders,
                "OBJECT_CLASSES" => $arObjectClasses,
                "OBJECT_TYPES" => $arObjectTypes,
                "OBJECT_ICONS" => $arObjectIcons,
                "BANKS" => $arBanks,
                "FINISHES" => $arFinishes,
                "APARTMENT_TYPES" => $arApartmentTypes,
                "BUILDING_TYPES"  => $arBuildTypes,
                "APARTMENTS" => $arApartments,
                "IS_AJAX" => $isAjax,
                "IS_REQUEST" => $isRequest,
                "IS_APARTMENT_REQUEST" => $isApartmentRequest,
                "NAV_RESULT" => $rsObjects,
                "FILTER_OBJECTS" => $arFilterObjects,
                "FILTER_RANGES" => $arFilterRanges,
                "FILTER_VALUES" => $arFilterValues,
                "SHOW_MORE" => $showMore,
                "PAGE" => $page,
                "ALL_OBJECTS" => $allObjects,
                "SEO" => $obSEO,

                "SORT" => array(
                    "TYPE" => $sort == $this->arParams["SORT_DEFAULT"] ? 'default' : $sort,
                    "BY"   => strtolower($sortBy),
                    "NAME" => $sortName
                )
            );

            if($isAjax && $getCount) {
                global $APPLICATION;
                $APPLICATION->RestartBuffer();
                echo $arFilterRanges["ALL_COUNT"];
                exit;
            }

            switch ($type) {
                case '':
                    $this->templateName = 'main';
                    break;

                case 'parametrical':
                    $this->templateName = 'parametrical';
                    break;
            }

        }

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
            $this->includeComponentTemplate($this->templateName);
            $this->executeEpilog();
        } catch (Exception $e) {
            ShowError($e->getMessage());
        }
    }
}