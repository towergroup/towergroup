<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Highloadblock as HL;
use Bitrix\Main;
use Bitrix\Main\Application;
use Bitrix\Main\Entity;
use Bitrix\Main\Localization\Loc as Loc;
use \Bitrix\Main\Data\Cache;

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
        $cityCode = $request->getQuery('CITY_CODE') ? $request->getQuery('CITY_CODE') : $this->arParams["CITY_CODE"];
        $objectCode = $request->getQuery('OBJECT_CODE');
        /*if (!$cityCode || (!$request->getQuery('CITY_CODE') && $this->arParams["CITY_CODE"] == 'moskva')) {
            LocalRedirect('/moskva/kupit-kvartiru/', false, '301 Moved permanently');
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


        $arFilterCurCity = array('CODE' => $cityCode, 'IBLOCK_ID' => 14, 'ACTIVE' => 'Y', 'CNT_ACTIVE' => 'Y');
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
            LocalRedirect('/moskva/kupit-kvartiru/', false, '301 Moved permanently');
        }

        if (!empty($cityCode) && !empty($objectCode)) { // Страница объекта
            $arFilterObject = ['IBLOCK_ID' => $iblockID, 'CODE' => $objectCode];

            $arFlat = CIBlockElement::GetList(
                false,
                $arFilterObject,
                false,
                false,
                [
                    "ID",
                    "IBLOCK_ID",
                    "ACTIVE",
                    "IBLOCK_SECTION_ID",
                    "CODE",
                    "NAME",
                    "SHOW_COUNTER",
                    "PREVIEW_PICTURE",
                    "DETAIL_PICTURE",
                    "DETAIL_TEXT",
                    "PROPERTY_HOUSE",
                    "PROPERTY_FLOOR",
                    "PROPERTY_FLOOR_MAX",
                    "PROPERTY_SQUARE",
                    "PROPERTY_HIGH_CEILINGS",
                    "PROPERTY_TERRACE",
                    "PROPERTY_ROOMS",
                    "PROPERTY_ROOMS_NUMBER",
                    "PROPERTY_CITY",
                    "PROPERTY_REGION",
                    "PROPERTY_ADDRESS",
                    "PROPERTY_PRICE_RUB",
                    "PROPERTY_PRICE_USD",
                    "PROPERTY_PRICE_EUR",
                    "PROPERTY_PRICE_GBP",
                    "PROPERTY_BUILDING",
                    "PROPERTY_DECORATION",
                    "PROPERTY_TWO_TIER",
                    "PROPERTY_PHOTOS",
                    "PROPERTY_MAP",
                    "PROPERTY_MAP_STREET",
                    "PROPERTY_SIMILAR_FLATS",
                    "PROPERTY_FEATURE",
                    "PROPERTY_TYPE_FLATS",
                    "PROPERTY_DEADLINE_YEAR",
                    "PROPERTY_INFRASTRUCTURE",
                    "PROPERTY_INFRASTRUCTURE_DESCRIPTION",
                    "PROPERTY_MAP_BORDER"
                ]
            )->GetNext();
            if (!empty($arFlat)) {
                if($arFlat["ACTIVE"] == 'N'){
                    LocalRedirect('/'.$cityCode.'/kupit-kvartiru/', false, '301 Moved permanently');
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

                while ($arData = $rsData->Fetch()) {
                    $arFinishes[$arData['UF_ID']] = $arData;
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

                while ($arData = $rsData->Fetch()) {
                    $arBuilding[$arData['UF_ID']] = $arData;
                }

                //находим типы недвижимости
                $hlblBuildType = BUILDING_VARIANTS_HIGHLOADBLOCK_ID; // Указываем ID highloadblock типов недвижимости
                $hlblock = HL\HighloadBlockTable::getById($hlblBuildType)->fetch();

                $entity = HL\HighloadBlockTable::compileEntity($hlblock);
                $entity_data_class = $entity->getDataClass();

                $rsData = $entity_data_class::getList(array(
                    "select" => array("*"),
                    "order" => array("ID" => "ASC"),
                    "filter" => array()  // Задаем параметры фильтра выборки
                ));

                while ($arData = $rsData->Fetch()) {
                    $arBuildTypes[$arData['UF_XML_ID']] = $arData;
                }

                //Объект
                //while ($arFlat = $obFlat->GetNext()) {
                if (!empty($arFlat["PREVIEW_PICTURE"])) {
                    $arFlat["PREVIEW_PICTURE"] = CFile::GetFileArray($arFlat["PREVIEW_PICTURE"]);
                }
                if (!empty($arFlat["DETAIL_PICTURE"])) {
                    $arFlat["DETAIL_PICTURE"] = CFile::GetFileArray($arFlat["DETAIL_PICTURE"]);
                }
                if (!empty($arFlat["PROPERTY_DECORATION_VALUE"])) {
                    $arFlat["PROPERTY_DECORATION_VALUE"] = $arFinishes[$arFlat["PROPERTY_DECORATION_VALUE"]]["UF_NAME"];
                }
                if (!empty($arFlat["PROPERTY_TYPE_FLATS_VALUE"])) {
                    $arFlat["PROPERTY_TYPE_FLATS_VALUE"] = $arBuildTypes[$arFlat["PROPERTY_TYPE_FLATS_VALUE"]]["UF_NAME"];
                }

                if (!empty($arFlat["PROPERTY_REGION_VALUE"])) {

                    $arFilter = ['IBLOCK_ID' => REGION_IBLOCK_ID, 'DEPTH_LEVEL' => 2, "ID" => $arFlat["PROPERTY_REGION_VALUE"]];
                    $arSelect = ['NAME', 'UF_ID', 'DESCRIPTION'];
                    $arSect = CIBlockSection::GetList(false, $arFilter, false, $arSelect)->Fetch();

                    $arFlat["PROPERTY_REGION_VALUE"] = $arSect["NAME"];
                    $arFlat["PROPERTY_REGION_DESCRIPTION"] = $arSect["DESCRIPTION"];
                }

                if (!empty($arFlat["PROPERTY_INFRASTRUCTURE_VALUE"])) {
                    $resInfrastructure = [];
                    $arFilter = ['IBLOCK_ID' => INFRASTRUCTURE_IBLOCK_ID, 'DEPTH_LEVEL' => 2, "SECTION_ID" => $arFlat["PROPERTY_INFRASTRUCTURE_VALUE"]];
                    $arSelect = ['NAME', 'ID', 'DESCRIPTION', 'CODE', 'UF_*'];
                    $rsSect = CIBlockSection::GetList(['SORT' => 'ASC'], $arFilter, false, $arSelect);

                    while ($arSect = $rsSect->Fetch()){
                        $resInfrastructure['CATEGORY'][$arSect["CODE"]] = $arSect;
                        $rsMapObjects = ['IBLOCK_ID' => INFRASTRUCTURE_IBLOCK_ID, 'SECTION_ID' => $arSect['ID'], 'ACTIVE' => 'Y'];
                        $rsMaprObjects = CIBlockElement::GetList(
                            false,
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

                if (!empty($arFlat["PROPERTY_PHOTOS_VALUE"])) {
                    foreach ($arFlat["PROPERTY_PHOTOS_VALUE"] as $photoId){
                        $arFlat["PROPERTY_SLIDER_VALUE"][] = CFile::GetFileArray($photoId)["SRC"];
                        $arFlat["PROPERTY_SLIDER_RESIZE_VALUE"][] = CFile::ResizeImageGet($photoId, array('width'=>690, 'height'=>400), BX_RESIZE_IMAGE_EXACT)["src"];
                        $arFlat["PROPERTY_SLIDER_MINI_VALUE"][] = CFile::ResizeImageGet($photoId, array('width'=>128, 'height'=>128), BX_RESIZE_IMAGE_PROPORTIONAL, true)["src"];
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

                $roomsAccusative = "";
                $roomsNominative = "";

                switch($arFlat["PROPERTY_ROOMS_NUMBER_VALUE"]){
                    case 1 :
                        $roomsAccusative = "однокомнатную";
                        $roomsNominative = "однокомнатная";
                        break;
                    case 2 :
                        $roomsAccusative = "двухкомнатную";
                        $roomsNominative = "двухкомнатная";
                        break;
                    case 3 :
                        $roomsAccusative = "трехкомнатную";
                        $roomsNominative = "трехкомнатная";
                        break;
                    case 4 :
                        $roomsAccusative = "четырехкомнатную";
                        $roomsNominative = "четырехконатная";
                        break;
                    case 5 :
                        $roomsAccusative = "пятикомнатную";
                        $roomsNominative = "пятикомнатная";
                        break;
                    case 6 :
                        $roomsAccusative = "шестикомнатную";
                        $roomsNominative = "шестикомнатная";
                        break;
                    case "Студия" :
                        $roomsAccusative = "студию";
                        $roomsNominative = "студия";
                        break;
                }

                foreach ($rsSeoTemplate["PROPERTY_VARIABLES_DESCRIPTION"] as $keySeoProperty => $valSeoProperty) {
                    if ($valSeoProperty == "PROPERTY_ROOMS_ACCUSATIVE"){
                        $rsSeoTemplate["PROPERTY_VARIABLES_REPLACE"][$keySeoProperty] = $roomsAccusative;
                    }
                    elseif ($valSeoProperty == "PROPERTY_ROOMS_NOMINATIVE") {
                        $rsSeoTemplate["PROPERTY_VARIABLES_REPLACE"][$keySeoProperty] = $roomsNominative;
                    }
                    elseif ($valSeoProperty == "PROPERTY_PRICE_RUB_VALUE") {
                        $rsSeoTemplate["PROPERTY_VARIABLES_REPLACE"][$keySeoProperty] = number_format($arFlat["PROPERTY_PRICE_RUB_VALUE"], 0, '.', ' ');
                    }
                    elseif ($valSeoProperty == "CURRENCY") {
                        $rsSeoTemplate["PROPERTY_VARIABLES_REPLACE"][$keySeoProperty] = "₽";
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
                        $rsSeoTemplate["PROPERTY_VARIABLES_REPLACE"][$keySeoProperty] = $arFlat[$valSeoProperty];
                    }
                }

                $rsSeoTemplate['NEW_TITLE'] = str_replace($rsSeoTemplate["PROPERTY_VARIABLES_VALUE"],$rsSeoTemplate["PROPERTY_VARIABLES_REPLACE"],$rsSeoTemplate["PREVIEW_TEXT"]);
                $rsSeoTemplate['NEW_DESCRIPTION'] = str_replace($rsSeoTemplate["PROPERTY_VARIABLES_VALUE"],$rsSeoTemplate["PROPERTY_VARIABLES_REPLACE"],$rsSeoTemplate["DETAIL_TEXT"]);

                $arFlat["SEO_TITLE"] = $rsSeoTemplate['NEW_TITLE'];
                $arFlat["SEO_DESCRIPTION"] = $rsSeoTemplate['NEW_DESCRIPTION'];

                $arObject['FLAT'] = $arFlat;
                //}

                if (!empty($arObject['FLAT']['PROPERTY_SIMILAR_FLATS_VALUE'])) {
                    $rsSimilarObjects = ['IBLOCK_ID' => $iblockID, 'ID' => $arObject['FLAT']['PROPERTY_SIMILAR_FLATS_VALUE'], 'ACTIVE' => 'Y'];
                    $rsSimilarObjects = CIBlockElement::GetList(
                        false,
                        $rsSimilarObjects,
                        false,
                        false,
                        [
                            "ID",
                            "IBLOCK_ID",
                            "IBLOCK_SECTION_ID",
                            "CODE",
                            "NAME",
                            "SHOW_COUNTER",
                            "PREVIEW_PICTURE",
                            "DETAIL_PICTURE",
                            "DETAIL_TEXT",
                            "PROPERTY_HOUSE",
                            "PROPERTY_FLOOR",
                            "PROPERTY_FLOOR_MAX",
                            "PROPERTY_SQUARE",
                            "PROPERTY_HIGH_CEILINGS",
                            "PROPERTY_TERRACE",
                            "PROPERTY_ROOMS",
                            "PROPERTY_ROOMS_NUMBER",
                            "PROPERTY_CITY",
                            "PROPERTY_REGION",
                            "PROPERTY_ADDRESS",
                            "PROPERTY_PRICE_RUB",
                            "PROPERTY_PRICE_USD",
                            "PROPERTY_PRICE_EUR",
                            "PROPERTY_PRICE_GBP",
                            "PROPERTY_BUILDING",
                            "PROPERTY_DECORATION",
                            "PROPERTY_TWO_TIER",
                            "PROPERTY_PHOTOS",
                            "PROPERTY_MAP",
                            "PROPERTY_MAP_STREET",
                            "PROPERTY_SIMILAR_FLATS",
                            "PROPERTY_FEATURE",
                            "PROPERTY_TYPE_FLATS",
                            "PROPERTY_DEADLINE_YEAR",
                        ]
                    );

                    while ($rsSimilarObject = $rsSimilarObjects->GetNext()){
                        if (!empty($rsSimilarObject["PROPERTY_PHOTOS_VALUE"])) {
                            $rsSimilarObject["PROPERTY_SLIDER_MINI_VALUE"] = CFile::ResizeImageGet($rsSimilarObject["PROPERTY_PHOTOS_VALUE"][0], array('width'=>570, 'height'=>315), BX_RESIZE_IMAGE_PROPORTIONAL, true, false, false, 70)["src"];
                        }
                        if (!empty($rsSimilarObject["PROPERTY_DECORATION_VALUE"])) {
                            $rsSimilarObject["PROPERTY_DECORATION_VALUE"] = $arFinishes[$rsSimilarObject["PROPERTY_DECORATION_VALUE"]]["UF_NAME"];
                        }
                        $rsSimilarObject["SECTION_PAGE_URL"] = "/" . $cityCode . "/kupit-kvartiru/" . $rsSimilarObject['CODE'] . "/";
                        $arSimilarObjects[] = $rsSimilarObject;
                    }
                }

                $arObject["UF_MANAGER"] = $rsManager;

                //  Контакты город
                $rsContact = CIBlockElement::GetList(
                    false,
                    [
                        "IBLOCK_ID" => CONTACTS_IBLOCK_ID,
                        "ACTIVE" => "Y",
                        "CODE" => $cityCode,
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
                        "PROPERTY_PHONE",
                        "PROPERTY_PHONE_NEW_BUILD",
                        "PROPERTY_PHONE_RESALE",
                        "PROPERTY_PHONE_COUNTRY",
                        "PROPERTY_PHONE_OVERSEAS",
                    ]
                )->Fetch();

                $arObject["CONTACT"] = $rsContact;

                $arOutput = array(
                    "OBJECT" => $arObject,
                    "SIMILAR_OBJECTS" => $arSimilarObjects,
                    "INFRASTRUCTURE_OBJECTS" => $resInfrastructure
                );

                $this->templateName = 'detail';
            } else {
                LocalRedirect('/'.$cityCode.'/kupit-kvartiru/', false, '301 Moved permanently');
//                LocalRedirect('/404.php', false, '301 Moved permanently');
            }

        } else {
            $isAjax = $request->isAjaxRequest();
            $isApartmentRequest = false;

//            $sort     = ($_GET['sort'] && $_GET['sort'] != 'default')? $_GET['sort'] : $this->arParams["SORT_DEFAULT"];
//            $sortBy   = ($_GET['sortBy'] && $_GET['sortBy'] != 'default')? $_GET['sortBy'] : $this->arParams["SORT_BY_DEFAULT"];
            $sort     = ($_GET['sort'] && $_GET['sort'] != 'default')? $_GET['sort'] : "price";
            $sortBy   = ($_GET['sortBy'] && $_GET['sortBy'] != 'default')? $_GET['sortBy'] : "desc";
            $getCount = ($_GET['getCount']) ? $_GET['getCount'] : false;
            $type = $_GET['type'];
            $page = ($_GET['page']) ? $_GET['page'] : 1;
            $showMore = ($_GET['showMore']) ? $_GET['showMore'] : false;
            $arFilterObjects = array('IBLOCK_ID' => $iblockID, 'ACTIVE' => 'Y');
            $arFilterApartments = array('IBLOCK_ID' => $iblockID, 'ACTIVE' => 'Y');
            $arFilterRanges = array();

            $search = ($_GET['search']) ? $_GET['search'] : null ;
            $deadline = ($_GET['deadline'] && is_numeric($_GET['deadline'])) ? $_GET['deadline'] : null ;

            $priceMin = ($_GET['pricemin']) ? $_GET['pricemin'] : null ;
            $priceMax = ($_GET['pricemax']) ? $_GET['pricemax'] : null ;
            $features = ($_GET['features']) ? $_GET['features'] : null ;
            $squareMin = ($_GET['sizemin']) ? $_GET['sizemin'] : null ;
            $squareMax = ($_GET['sizemax']) ? $_GET['sizemax'] : null ;
            $kitchenSquareMin = ($_GET['kitchensizemin']) ? $_GET['kitchensizemin'] : null ;
            $kitchenSquareMax = ($_GET['kitchensizemax']) ? $_GET['kitchensizemax'] : null ;
            $floorMin = ($_GET['floormin']) ? $_GET['floormin'] : null ;
            $floorMax = ($_GET['floormax']) ? $_GET['floormax'] : null ;
            $finish = ($_GET['finish']) ? $_GET['finish'] : null ;
            $apartmenttype = ($_GET['apartmenttype']) ? $_GET['apartmenttype'] : null ;
            $buildtype = ($_GET['buildtype']) ? $_GET['buildtype'] : null ;

            // Поиск дефолтных значений для фильтра

            $cacheObjectsParamsDefault = Cache::createInstance(); // получаем экземпляр класса
            if ($cacheObjectsParamsDefault->initCache(86400, "cache_data_objects_default_params_resale".$rsCurCity["CODE"])) { // проверяем кеш и задаём настройки
                $arObjectsParamsDefault = $cacheObjectsParamsDefault->getVars(); // достаем переменные из кеша
            }
            elseif ($cacheObjectsParamsDefault->startDataCache()) {
                $rsObjectApartmentNoParams = CIBlockElement::GetList(
                    false,
                    $arFilterObjects,
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
                        "PROPERTY_SQUARE",
                        "PROPERTY_HIGH_CEILINGS",
                        "PROPERTY_TERRACE",
                        "PROPERTY_ROOMS",
                        "PROPERTY_ROOMS_NUMBER",
                        "PROPERTY_PRICE_RUB",
                        "PROPERTY_PRICE_USD",
                        "PROPERTY_PRICE_EUR",
                        "PROPERTY_PRICE_GBP",
                        "PROPERTY_BUILDING",
                        "PROPERTY_TYPE_FLATS",
                        "PROPERTY_DECORATION",
                        "PROPERTY_TWO_TIER",
                        "PROPERTY_DEADLINE_YEAR",
                        "PROPERTY_PHOTOS",
                        "PROPERTY_MAP",
                        "PROPERTY_SUBWAY",
                    )
                );

                while ($arObjectApartmentNoParams = $rsObjectApartmentNoParams->Fetch()) {
                    if (!in_array($arObjectApartmentNoParams["PROPERTY_DECORATION_VALUE"],$arFlatDecorations) && !empty($arObjectApartmentNoParams["PROPERTY_DECORATION_VALUE"]))
                        $arFlatDecorations[] = $arObjectApartmentNoParams["PROPERTY_DECORATION_VALUE"];

                    if (!in_array($arObjectApartmentNoParams["PROPERTY_TYPE_FLATS_VALUE"],$arFlatTypes) && !empty($arObjectApartmentNoParams["PROPERTY_TYPE_FLATS_VALUE"]))
                        $arFlatTypes[] = $arObjectApartmentNoParams["PROPERTY_TYPE_FLATS_VALUE"];
                    if (!in_array($arObjectApartmentNoParams["PROPERTY_ROOMS_VALUE"],$arFlatRoomTypes) && !empty($arObjectApartmentNoParams["PROPERTY_ROOMS_VALUE"]))
                        $arFlatRoomTypes[] = $arObjectApartmentNoParams["PROPERTY_ROOMS_VALUE"];
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

                while ($arData = $rsData->Fetch()) {
                    $arObjectsParamsDefault["FINISHES"][$arData['UF_ID']] = $arData;
                }

                //находим типы комнат
                $property_rooms_enums = CIBlockPropertyEnum::GetList(Array("SORT" => "ASC", "ID" => "ASC"),
                    Array("IBLOCK_ID" => $iblockID, "CODE" => "ROOMS"));
                while ($room_enum_fields = $property_rooms_enums->GetNext()) {
                    if (in_array($room_enum_fields["VALUE"],$arFlatRoomTypes))
                        $arObjectsParamsDefault["APARTMENT_TYPES"][$room_enum_fields["ID"]] = $room_enum_fields;
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

                while ($arData = $rsData->Fetch()) {
                    $arObjectsParamsDefault["BUILD_TYPES"][$arData['UF_XML_ID']] = $arData;
                }

                $cacheObjectsParamsDefault->endDataCache($arObjectsParamsDefault); // записываем в кеш
            }

            $arFinishes = $arObjectsParamsDefault["FINISHES"];
            $arApartmentTypes = $arObjectsParamsDefault["APARTMENT_TYPES"];
            $arBuildTypes = $arObjectsParamsDefault["BUILD_TYPES"];

            // Параметры фильтра

            // Сортировка
            if ($sort && $sortBy) {
                switch ($sort) {
                    case 'price':
                        $propertySort = 'PROPERTY_PRICE_RUB';
                        $sortName = 'по цене';
                        break;
                    case 'square':
                        $propertySort = 'PROPERTY_SQUARE';
                        $sortName = 'по площади';
                        break;
                    case 'deadline':
                        $propertySort = 'PROPERTY_DEADLINE_YEAR';
                        $sortName = 'по сроку сдачи';
                        break;
                    default :
                        $propertySort = $sort;
                        $sortName = 'по умолчанию';
                        break;
                }

                if ($sort != $this->arParams['SORT_DEFAULT']) {
                    $sortName .= $sortBy == 'asc' ? " (возрастание)" : " (убывание)";
                }

                $arSortObjects = array(
                    $propertySort => strtoupper($sortBy)
                );
            }

            if ($search != null) {
                $decodeSearch = json_decode($search,true);
                foreach ($decodeSearch as $arSearchTag) {
                    $area = str_replace("area_","", $arSearchTag["id"], $areaCount);
                    $subway = str_replace("subway_","", $arSearchTag["id"], $subwayCount);
                    $street = str_replace("street_","", $arSearchTag["id"], $streetCount);
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
                    elseif ($builderCount > 0) {
                        if ($arSearchTag["exclude"]) {
                            $reqnotBuilders[] = $builder;
                        }
                        else {
                            $reqBuilders[] = $builder;
                        }
                    }
                }
                $arFilterApartments = [];
                if(count($reqnotAreas) > 0) {
                    $arFilterApartments["!REGION"] = array_merge(array("LOGIC" => "OR"), $reqnotAreas);
                }
                if(count($reqAreas) > 0) {
                    $arFilterApartments["REGION"] = array_merge(array("LOGIC" => "OR"), $reqAreas);
                    $arFilterApartments["!REGION"] = false;
                }
                if(count($reqnotSubways) > 0) {
                    $arFilterApartments["!SUBWAY"] =  $reqnotSubways;
                }
                if(count($reqSubways) > 0) {
                    $arFilterApartments["SUBWAY"] =  $reqSubways;
                    $arFilterApartments["!SUBWAY"] = false;
                }
                if(count($reqnotStreets) > 0) {
                    $arFilterApartments["!%ADDRESS"] = array_merge(array("LOGIC" => "OR"), $reqnotStreets);
                }
                if(count($reqStreets) > 0) {
                    $arFilterApartments["%ADDRESS"] = array_merge(array("LOGIC" => "OR"), $reqStreets);
                }
                if(count($reqnotBuilders) > 0) {
                    $arFilterApartments["!BUILDER"] = array_merge(array("LOGIC" => "OR"), $reqnotBuilders);
                }
                if(count($reqBuilders) > 0) {
                    $arFilterApartments["BUILDER"] = array_merge(array("LOGIC" => "OR"), $reqBuilders);
                    $arFilterApartments["!BUILDER"] = false;
                }
                $arFilterValues["SEARCH"] = json_encode($decodeSearch, JSON_UNESCAPED_UNICODE);
            }

            if($deadline != null) {
                $arFilterApartments['DEADLINE_YEAR'] = $deadline;
            }

            if($priceMin != null) {
                $isApartmentRequest == false ? $isApartmentRequest = true : null;
                $arFilterApartments['>=PRICE_RUB'] = floatval($priceMin)*1000000;
                $arFilterValues['PRICE'][0] = $priceMin;

            }

            if($priceMax != null) {
                $isApartmentRequest == false ? $isApartmentRequest = true : null;
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
                                $arFilterApartments["TERRACE"] = $cityCode == 'spb' ? 45 : 35;
                                break;
                            case 'twotier':
                                $arFilterApartments["TWO_TIER"] = $cityCode == 'spb' ? 39 : 36;
                                break;
                            case 'highceilings':
                                $arFilterApartments["HIGH_CEILINGS"] = $cityCode == 'spb' ? 38 : 37;
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
                    if ($keyApartmentType !== false ) $reqApartmenttype[$keyApartmentType] = 0;
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

            if ($areas || $notareas || $subways || $notsubways || $builders || $notbuilders || $banks || $notbanks || $deadline) {
                $isRequest = true;
            }

            // Поиск дефолтных значений для фильтра

            $arMinPriceApartment = CIBlockElement::GetList(
                array("PROPERTY_PRICE_RUB" => "ASC"),
                array(
                    "IBLOCK_ID" => $iblockID,
                    "ACTIVE" => "Y",
                    ">PROPERTY_PRICE_RUB" => 0,
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
                    "IBLOCK_ID" => $iblockID,
                    "ACTIVE" => "Y",
                    ">PROPERTY_PRICE_RUB" => 0,
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
                array("PROPERTY_SQUARE" => "ASC"),
                array(
                    "IBLOCK_ID" => $iblockID,
                    "ACTIVE" => "Y",
                    ">PROPERTY_PRICE_RUB" => 0,
                    ">PROPERTY_SQUARE"    => 0,
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
                array("PROPERTY_SQUARE" => "DESC"),
                array(
                    "IBLOCK_ID" => $iblockID,
                    "ACTIVE" => "Y",
                    ">PROPERTY_PRICE_RUB" => 0,
                    ">PROPERTY_SQUARE"    => 0,
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
                array("PROPERTY_KITCHEN_SQUARE" => "ASC"),
                array(
                    "IBLOCK_ID" => $iblockID,
                    "ACTIVE" => "Y",
                    ">PROPERTY_PRICE_RUB"      => 0,
                    ">PROPERTY_KITCHEN_SQUARE" => 0,
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
                array("PROPERTY_KITCHEN_SQUARE" => "DESC"),
                array(
                    "IBLOCK_ID" => $iblockID,
                    "ACTIVE" => "Y",
                    ">PROPERTY_PRICE_RUB"          => 0,
                    ">PROPERTY_KITCHEN_SQUARE"     => 0,
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
                array("PROPERTY_FLOOR" => "ASC"),
                array(
                    "IBLOCK_ID" => $iblockID,
                    "ACTIVE" => "Y",
                    ">PROPERTY_PRICE_RUB" => 0,
                    ">PROPERTY_FLOOR"     => 0,
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
                array("PROPERTY_FLOOR" => "DESC"),
                array(
                    "IBLOCK_ID" => $iblockID,
                    "ACTIVE" => "Y",
                    ">PROPERTY_PRICE_RUB" => 0,
                    ">PROPERTY_FLOOR"     => 0,
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

            $arFilterRanges['MIN_PRICE'] = $arMinPriceApartment['PROPERTY_PRICE_RUB_VALUE'];
            $arFilterRanges['MAX_PRICE'] = $arMaxPriceApartment['PROPERTY_PRICE_RUB_VALUE'];
            $arFilterRanges['MIN_SQUARE'] = $arMinSquareApartment['PROPERTY_SQUARE_VALUE'];
            $arFilterRanges['MAX_SQUARE'] = $arMaxSquareApartment['PROPERTY_SQUARE_VALUE'];
            $arFilterRanges['MIN_KITCHEN_SQUARE'] = $arMinKitchenSquareApartment['PROPERTY_KITCHEN_SQUARE_VALUE'];
            $arFilterRanges['MAX_KITCHEN_SQUARE'] = $arMaxKitchenSquareApartment['PROPERTY_KITCHEN_SQUARE_VALUE'];
            $arFilterRanges['MIN_FLOOR'] = $arMinFloorApartment['PROPERTY_FLOOR_VALUE'];
            $arFilterRanges['MAX_FLOOR'] = $arMaxFloorApartment['PROPERTY_FLOOR_VALUE'];



            $arObjects = array();

            $arFilterObjects['PROPERTY'] = $arFilterApartments;
            //xprint($arFilterObjects);

            $rsObjectApartment = CIBlockElement::GetList(
                $arSortObjects,
                $arFilterObjects,
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
                    "PROPERTY_SQUARE",
                    "PROPERTY_HIGH_CEILINGS",
                    "PROPERTY_TERRACE",
                    "PROPERTY_ROOMS",
                    "PROPERTY_ROOMS_NUMBER",
                    "PROPERTY_PRICE_RUB",
                    "PROPERTY_PRICE_USD",
                    "PROPERTY_PRICE_EUR",
                    "PROPERTY_PRICE_GBP",
                    "PROPERTY_BUILDING",
                    "PROPERTY_DECORATION",
                    "PROPERTY_TWO_TIER",
                    "PROPERTY_DEADLINE_YEAR",
                    "PROPERTY_PHOTOS",
                    "PROPERTY_MAP",
                    "PROPERTY_SUBWAY",
                )
            );

            $arDeadline = [];
            while ($arObjectApartment = $rsObjectApartment->Fetch()) {
                //xprint($arObjectApartment);
                $arObjectApartment["SECTION_PAGE_URL"] = "/" . $cityCode . "/kupit-kvartiru/" . $arObjectApartment['CODE'] . "/";
                $arObjectApartment["DETAIL_PICTURE"] = CFile::GetFileArray($arObjectApartment["DETAIL_PICTURE"]);

                if (!in_array($arObjectApartment['PROPERTY_DEADLINE_YEAR_VALUE'], $arDeadline)) {
                    $arDeadline[] = $arObjectApartment['PROPERTY_DEADLINE_YEAR_VALUE'];
                }

                if (!empty($arObjectApartment["PROPERTY_PHOTOS_VALUE"])) {
                    $numberPhotos = 1;
                    foreach ($arObjectApartment["PROPERTY_PHOTOS_VALUE"] as $photoId){
                        if ($numberPhotos > $this->arParams['NUMBER_OF_OBJECT_PHOTOS'])
                            break;
                        //$arObjectApartment["PROPERTY_SLIDER_MINI_VALUE"][] = CFile::ResizeImageGet($photoId, array('width'=>760, 'height'=>480), BX_RESIZE_IMAGE_PROPORTIONAL, true)["src"];
                        //$arObjectApartment["PROPERTY_SLIDER_MINI_VALUE"][] = CFile::ResizeImageGet($photoId, array('width'=>384, 'height'=>240), BX_RESIZE_IMAGE_PROPORTIONAL, true)["src"];
                        $arObjectApartment["PROPERTY_SLIDER_MINI_VALUE"][] = CFile::ResizeImageGet($photoId, array('width'=>570, 'height'=>360), BX_RESIZE_IMAGE_PROPORTIONAL, true, false, false, 70)["src"];
                        $numberPhotos ++;
                    }
                }

                $arObjects[] = $arObjectApartment;
            }
            //}

            if ($sort == 'square') {
                if ($sortBy == 'asc') {
                    usort($arObjects, function ($a, $b) {
                        return ($a['MIN_SQUARE_APARTMENT'] - $b['MIN_SQUARE_APARTMENT']);
                    });
                } else {
                    usort($arObjects, function ($a, $b) {
                        return ($b['MIN_SQUARE_APARTMENT'] - $a['MIN_SQUARE_APARTMENT']);
                    });
                }
            }

            if ($sort == 'price') {
                if ($sortBy == 'asc') {
                    usort($arObjects, function ($a, $b) {
                        return ($a['MIN_PRICE_APARTMENT'] - $b['MIN_PRICE_APARTMENT']);
                    });
                } else {
                    usort($arObjects, function ($a, $b) {
                        return ($b['MIN_PRICE_APARTMENT'] - $a['MIN_PRICE_APARTMENT']);
                    });
                }
            }

            $allObjectsCount = count($arObjects);
            $arFilterRanges["ALL_COUNT"] = $allObjectsCount;

            $arObjectsListInitial = $arObjects;
            $allObjects = $arObjects;

            $arObjects = array(
                "ITEMS" => array_slice($arObjectsListInitial, ($page - 1) * $this->arParams["OBJECTS_PAGE_COUNT_LIST"],
                    $this->arParams["OBJECTS_PAGE_COUNT_LIST"]),
                "SHOW_MORE" => (($page - 1) * $this->arParams["OBJECTS_PAGE_COUNT_LIST"] + $this->arParams["OBJECTS_PAGE_COUNT_LIST"] >= $allObjectsCount) ? false : true
            );

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
                    "PROPERTY_H_RESALE",
                    "PROPERTY_DESC_RESALE",
                )
            )->getNext();

            $arOutput = array(
                "SORT" => $arSortObjects,
                "SORT_DEFAULT" => $sortDefault,
                "DEFAULT_BROKER" => $rsManager,
                "OBJECTS" => $arObjects,
                "DEADLINE" => $arDeadline,
                "BANKS" => $arBanks,
                "FINISHES" => $arFinishes,
                "APARTMENT_TYPES" => $arApartmentTypes,
                "BUILDING_TYPES" => $arBuildTypes,
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
                "CITY" => $cityCode,
                "SEO" => $obSEO,

                "SORT" => array(
                    "TYPE" => $sort == $this->arParams["SORT_DEFAULT"] ? 'default' : $sort,
                    "BY" => strtolower($sortBy),
                    "NAME" => $sortName
                )
            );

            if ($isAjax && $getCount) {
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