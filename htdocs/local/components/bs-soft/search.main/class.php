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

class SearchMainComponent extends CBitrixComponent
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

        //$iblockID = $this->arParams["IBLOCK_ID"];
        $request = Application::getInstance()->getContext()->getRequest();
        $isAjax = $request->isAjaxRequest();
        $cityCode = $this->arParams["CITY_CODE"];
        $category = $_GET['categoryCode'] ? $_GET['categoryCode'] : "novostroyki";
        $arFilterValues["CATEGORY"] = $category;

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

        if ($cityCode == 'moskva'){
            $arIblockIDs = [
                "novostroyki" => NEW_BUILD_IBLOCK_ID,
                "kupit-kvartiru" => RESALE_IBLOCK_ID,
                "kupit-dom" => COUNTRY_IBLOCK_ID,
                "nedvizhimost-za-rubezhom" => FOREIGN_IBLOCK_ID
            ];
        }
        else {
            $arIblockIDs = [
                "novostroyki" => NEW_BUILD_SPB_IBLOCK_ID,
                "kupit-kvartiru" => RESALE_SPB_IBLOCK_ID,
                "kupit-dom" => COUNTRY_SPB_IBLOCK_ID,
                "nedvizhimost-za-rubezhom" => FOREIGN_IBLOCK_ID
            ];
        }

        $arCategories = [
            "novostroyki" => "Новостройки",
            "kupit-kvartiru" => "Вторичная",
            "kupit-dom" => "Загородная",
            "nedvizhimost-za-rubezhom" => "Зарубежная",
        ];

        switch ($category){
            case "novostroyki":
                $searchUrl = "/ajax/search_new_". $cityCode .".php" ;
                break;
            case "kupit-kvartiru":
                $searchUrl = "/ajax/search_resale_". $cityCode .".php" ;
                break;
            case "kupit-dom":
                $searchUrl = "/ajax/search_dom_". $cityCode .".php" ;
                break;
            case "nedvizhimost-za-rubezhom":
                $searchUrl = "/ajax/search_overseas.php" ;
                break;
        }

            $isAjax = $request->isAjaxRequest();
            $isApartmentRequest = false;

            $getCount = ($_GET['getCount']) ? $_GET['getCount'] : false;
            $type = $_GET['type'];
            $page = ($_GET['page']) ? $_GET['page'] : 1;
            $showMore = ($_GET['showMore']) ? $_GET['showMore'] : false;
            $arFilterObjects = array('IBLOCK_ID' => $arIblockIDs["novostroyki"], 'ACTIVE' => 'Y', 'CNT_ACTIVE' => 'Y');
            $arFilterApartmentsNoParams = array('IBLOCK_ID' => $arIblockIDs["novostroyki"], 'ACTIVE' => 'Y');
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

            // Поиск дефолтных значений для фильтра новостроек

            $cacheObjectsParamsDefault = Cache::createInstance(); // получаем экземпляр класса
            if ($cacheObjectsParamsDefault->initCache(86400, "cache_data_objects_default_params_new_build_main".$rsCurCity["CODE"], "/")) { // проверяем кеш и задаём настройки
                $arObjectParamsDefaultNewbuild = $cacheObjectsParamsDefault->getVars(); // достаем переменные из кеша
            }
            elseif ($cacheObjectsParamsDefault->startDataCache()) {
                $arDeadLine = ["Сдан"];
                $arFlatDecorations = [];
                $arFlatTypes = [];
                $arFlatRoomTypes = [];

                $rsAreas = CIBlockSection::GetTreeList($arFilterArea, $arSelectArea); //находим районы выбранного города

                while($arArea = $rsAreas->Fetch()) {
                    $arObjectParamsDefaultNewbuild["AREAS"][$arArea['ID']] = $arArea;
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

                $arObjectParamsDefaultNewbuild["OBJECTS_ACTIVE_ID"] = $arObjectsNoParamsSectionActiveID;

                $arObjectParamsDefaultNewbuild["DEADLINES"] = $arDeadLine;

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

                while ($arFlatNoParams = $obFlatNoParams->Fetch()) {
                    if (!in_array($arFlatNoParams["PROPERTY_DECORATION_VALUE"],
                            $arFlatDecorations) && !empty($arFlatNoParams["PROPERTY_DECORATION_VALUE"])) {
                        $arFlatDecorations[] = $arFlatNoParams["PROPERTY_DECORATION_VALUE"];
                    }

                    if (!in_array($arFlatNoParams["PROPERTY_BUILD_TYPE_VALUE"],
                            $arFlatTypes) && !empty($arFlatNoParams["PROPERTY_BUILD_TYPE_VALUE"])) {
                        $arFlatTypes[] = $arFlatNoParams["PROPERTY_BUILD_TYPE_VALUE"];
                    }
                    if (!in_array($arFlatNoParams["PROPERTY_ROOMS_VALUE"],
                            $arFlatRoomTypes) && !empty($arFlatNoParams["PROPERTY_ROOMS_VALUE"])) {
                        $arFlatRoomTypes[] = $arFlatNoParams["PROPERTY_ROOMS_VALUE"];
                    }
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
                    $arObjectParamsDefaultNewbuild["FINISHES"][$arData['UF_ID']] = $arData;
                }

                //находим типы комнат
                $property_rooms_enums = CIBlockPropertyEnum::GetList(Array("SORT"=>"ASC", "ID"=>"ASC"), Array("IBLOCK_ID"=>$arIblockIDs["novostroyki"], "CODE"=>"ROOMS"));
                while($room_enum_fields = $property_rooms_enums->Fetch())
                {
                    if (in_array($room_enum_fields["VALUE"],$arFlatRoomTypes))
                        $arObjectParamsDefaultNewbuild["APARTMENT_TYPES"][$room_enum_fields["ID"]] = $room_enum_fields;
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
                    $arObjectParamsDefaultNewbuild["BUILDERS"][$arBuilder['UF_XML_ID']] = $arBuilder;
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
                    $arObjectParamsDefaultNewbuild["OBJECT_CLASSES"][$arDataObjectLiveClass['UF_ID']] = $arDataObjectLiveClass["UF_NAME"];
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
                    $arObjectParamsDefaultNewbuild["OBJECT_TYPES"][$arDataObjectType['UF_ID']] = $arDataObjectType['UF_NAME'];
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
                    $arObjectParamsDefaultNewbuild["BUILD_TYPES"][$arData['UF_XML_ID']] = $arData;
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
                    $arObjectParamsDefaultNewbuild["OBJECT_ICONS"][$arObjectIcon['UF_XML_ID']] = $arObjectIcon;
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
                    $arObjectParamsDefaultNewbuild["SUBWAYS"][$arSubway['ID']] = $arSubway;
                }

                $cacheObjectsParamsDefault->endDataCache($arObjectParamsDefaultNewbuild); // записываем в кеш
            }

        $arDefaultParams["novostroyki"] = [
            "OBJECT_ACTIVE" => $arObjectParamsDefaultNewbuild["OBJECTS_ACTIVE_ID"],
            "AREAS" => $arObjectParamsDefaultNewbuild["AREAS"],
            "FINISHES" => $arObjectParamsDefaultNewbuild["FINISHES"],
            "SUBWAYS" => $arObjectParamsDefaultNewbuild["SUBWAYS"],
            "APARTMENT_TYPES" => $arObjectParamsDefaultNewbuild["APARTMENT_TYPES"],
            "BUILDERS" => $arObjectParamsDefaultNewbuild["BUILDERS"],
            "OBJECT_ICONS" => $arObjectParamsDefaultNewbuild["OBJECT_ICONS"],
            "OBJECT_CLASSES" => $arObjectParamsDefaultNewbuild["OBJECT_CLASSES"],
            "OBJECT_TYPES" => $arObjectParamsDefaultNewbuild["OBJECT_TYPES"],
            "BUILDING_TYPES" => $arObjectParamsDefaultNewbuild["BUILD_TYPES"],
            "DEADLINES" => $arObjectParamsDefaultNewbuild["DEADLINES"]
        ];

        // Поиск дефолтных значений для фильтра Вторичной
        $arFilterObjects["IBLOCK_ID"] = $arIblockIDs["kupit-kvartiru"];

        $cacheObjectsParamsDefault = Cache::createInstance(); // получаем экземпляр класса
        if ($cacheObjectsParamsDefault->initCache(86400, "cache_data_objects_default_params_resale".$rsCurCity["CODE"])) { // проверяем кеш и задаём настройки
            $arObjectsParamsDefaultResale = $cacheObjectsParamsDefault->getVars(); // достаем переменные из кеша
        }
        elseif ($cacheObjectsParamsDefault->startDataCache()) {
            $arFlatDecorations = [];
            $arFlatTypes = [];
            $arFlatRoomTypes = [];
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
                $arObjectsParamsDefaultResale["FINISHES"][$arData['UF_ID']] = $arData;
            }

            //находим типы комнат
            $property_rooms_enums = CIBlockPropertyEnum::GetList(Array("SORT" => "ASC", "ID" => "ASC"),
                Array("IBLOCK_ID" => $arIblockIDs["kupit-kvartiru"], "CODE" => "ROOMS"));
            while ($room_enum_fields = $property_rooms_enums->GetNext()) {
                if (in_array($room_enum_fields["VALUE"],$arFlatRoomTypes))
                    $arObjectsParamsDefaultResale["APARTMENT_TYPES"][$room_enum_fields["ID"]] = $room_enum_fields;
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
                $arObjectsParamsDefaultResale["BUILD_TYPES"][$arData['UF_XML_ID']] = $arData;
            }

            $cacheObjectsParamsDefault->endDataCache($arObjectsParamsDefaultResale); // записываем в кеш
        }

        $arDefaultParams["kupit-kvartiru"] = [
            "FINISHES" => $arObjectsParamsDefaultResale["FINISHES"],
            "APARTMENT_TYPES" => $arObjectsParamsDefaultResale["APARTMENT_TYPES"],
            "BUILDING_TYPES" => $arObjectsParamsDefaultResale["BUILD_TYPES"],
        ];

        // Поиск дефолтных значений для фильтра Загородной
        $arFilterObjects["IBLOCK_ID"] = $arIblockIDs["kupit-dom"];

        $cacheObjectsParamsDefault = Cache::createInstance(); // получаем экземпляр класса
        if ($cacheObjectsParamsDefault->initCache(86400, "cache_data_objects_default_params_country_".$rsCurCity["CODE"])) { // проверяем кеш и задаём настройки
            $arObjectsParamsDefaultCountry = $cacheObjectsParamsDefault->getVars(); // достаем переменные из кеша
        }
        elseif ($cacheObjectsParamsDefault->startDataCache()) {
            $arFlatDecorations = [];
            $arFlatTypes = [];
            $arFlatRoomTypes = [];
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
                    "PROPERTY_FLOOR_MAX",
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
                $arObjectsParamsDefaultCountry["FINISHES"][$arData['UF_ID']] = $arData;
            }

            //находим типы комнат
            $property_rooms_enums = CIBlockPropertyEnum::GetList(Array("SORT" => "ASC", "ID" => "ASC"),
                Array("IBLOCK_ID" => $arIblockIDs["kupit-dom"], "CODE" => "ROOMS"));
            while ($room_enum_fields = $property_rooms_enums->GetNext()) {
                if (in_array($room_enum_fields["VALUE"],$arFlatRoomTypes))
                    $arObjectsParamsDefaultCountry["APARTMENT_TYPES"][$room_enum_fields["ID"]] = $room_enum_fields;
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
                $arObjectsParamsDefaultCountry["BUILD_TYPES"][$arData['UF_XML_ID']] = $arData;
            }

            $cacheObjectsParamsDefault->endDataCache($arObjectsParamsDefaultCountry); // записываем в кеш
        }

        $arDefaultParams["kupit-dom"] = [
            "FINISHES" => $arObjectsParamsDefaultCountry["FINISHES"],
            "APARTMENT_TYPES" => $arObjectsParamsDefaultCountry["APARTMENT_TYPES"],
            "BUILDING_TYPES" => $arObjectsParamsDefaultCountry["BUILD_TYPES"],
        ];

        // Поиск дефолтных значений для фильтра зарубежных

        $arFilterObjects["IBLOCK_ID"] = $arIblockIDs["nedvizhimost-za-rubezhom"];

        $cacheObjectsParamsDefault = Cache::createInstance(); // получаем экземпляр класса
        if ($cacheObjectsParamsDefault->initCache(86400, "cache_data_objects_default_params_foreign")) { // проверяем кеш и задаём настройки
            $arObjectsParamsDefaultForeign = $cacheObjectsParamsDefault->getVars(); // достаем переменные из кеша
        }
        elseif ($cacheObjectsParamsDefault->startDataCache()) {
            $arFlatDecorations = [];
            $arFlatTypes = [];
            $arObjectsParamsDefaultForeign["CITIES"] = [];
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
                    "PROPERTY_BUILDING",
                    "PROPERTY_TYPE_FLATS",
                    "PROPERTY_CITY"
                )
            );

            while ($arObjectApartmentNoParams = $rsObjectApartmentNoParams->Fetch()) {
                if (!in_array($arObjectApartmentNoParams["PROPERTY_DECORATION_VALUE"],$arFlatDecorations) && !empty($arObjectApartmentNoParams["PROPERTY_DECORATION_VALUE"]))
                    $arFlatDecorations[] = $arObjectApartmentNoParams["PROPERTY_DECORATION_VALUE"];

                if (!in_array($arObjectApartmentNoParams["PROPERTY_TYPE_FLATS_VALUE"],$arFlatTypes) && !empty($arObjectApartmentNoParams["PROPERTY_TYPE_FLATS_VALUE"]))
                    $arFlatTypes[] = $arObjectApartmentNoParams["PROPERTY_TYPE_FLATS_VALUE"];
                if (!in_array($arObjectApartmentNoParams["PROPERTY_ROOMS_VALUE"],$arFlatRoomTypes) && !empty($arObjectApartmentNoParams["PROPERTY_ROOMS_VALUE"]))
                    $arFlatRoomTypes[] = $arObjectApartmentNoParams["PROPERTY_ROOMS_VALUE"];
                if (!in_array($arObjectApartmentNoParams["PROPERTY_CITY_VALUE"],$arObjectsParamsDefaultForeign["CITIES"]))
                    $arObjectsParamsDefaultForeign["CITIES"][] = $arObjectApartmentNoParams["PROPERTY_CITY_VALUE"];
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
                $arObjectsParamsDefaultForeign["FINISHES"][$arData['UF_ID']] = $arData;
            }

            //находим типы комнат
            $property_rooms_enums = CIBlockPropertyEnum::GetList(Array("SORT" => "ASC", "ID" => "ASC"),
                Array("IBLOCK_ID" => $arIblockIDs["nedvizhimost-za-rubezhom"], "CODE" => "ROOMS"));
            while ($room_enum_fields = $property_rooms_enums->GetNext()) {
                if (in_array($room_enum_fields["VALUE"],$arFlatRoomTypes))
                    $arObjectsParamsDefaultForeign["APARTMENT_TYPES"][$room_enum_fields["ID"]] = $room_enum_fields;
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
                $arObjectsParamsDefaultForeign["BUILD_TYPES"][$arData['UF_XML_ID']] = $arData;
            }

            $cacheObjectsParamsDefault->endDataCache($arObjectsParamsDefaultForeign); // записываем в кеш
        }

        $arDefaultParams["nedvizhimost-za-rubezhom"] = [
            "FINISHES" => $arObjectsParamsDefaultForeign["FINISHES"],
            "APARTMENT_TYPES" => $arObjectsParamsDefaultForeign["APARTMENT_TYPES"],
            "CITIES" => $arObjectsParamsDefaultForeign["CITIES"],
            "BUILDING_TYPES" => $arObjectsParamsDefaultForeign["BUILD_TYPES"],
        ];

        // Поиск минимальных значений для фильтра новостроек

        $cacheParamsDefault = Cache::createInstance(); // получаем экземпляр класса
        if ($cacheParamsDefault->initCache(86400, "cache_data_default_filter_new_build_main".$rsCurCity["CODE"], "/")) { // проверяем кеш и задаём настройки
            $arFilterParamsDefaultNewbuild = $cacheParamsDefault->getVars(); // достаем переменные из кеша
        }
        elseif ($cacheParamsDefault->startDataCache()) {
            $arMinPriceApartment = CIBlockElement::GetList(
                array("PROPERTY_PRICE_RUB" => "ASC"),
                array(
                    "IBLOCK_ID"                => $arIblockIDs["novostroyki"],
                    "ACTIVE"                   => "Y",
                    ">PROPERTY_PRICE_RUB"      => 0,
                    "IBLOCK_SECTION_ID"        => $arDefaultParams["novostroyki"]["OBJECT_ACTIVE"]
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
                    "IBLOCK_ID"                => $arIblockIDs["novostroyki"],
                    "ACTIVE"                   => "Y",
                    ">PROPERTY_PRICE_RUB"      => 0,
                    "IBLOCK_SECTION_ID"        => $arDefaultParams["novostroyki"]["OBJECT_ACTIVE"]
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
                    "IBLOCK_ID"                => $arIblockIDs["novostroyki"],
                    "ACTIVE"                   => "Y",
                    ">PROPERTY_PRICE_RUB"      => 0,
                    ">PROPERTY_SQUARE"         => 0,
                    "IBLOCK_SECTION_ID"        => $arDefaultParams["novostroyki"]["OBJECT_ACTIVE"]
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
                    "IBLOCK_ID"                => $arIblockIDs["novostroyki"],
                    "ACTIVE"                   => "Y",
                    ">PROPERTY_PRICE_RUB"      => 0,
                    ">PROPERTY_SQUARE"         => 0,
                    "IBLOCK_SECTION_ID"        => $arDefaultParams["novostroyki"]["OBJECT_ACTIVE"]
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
                    "IBLOCK_ID"                => $arIblockIDs["novostroyki"],
                    "ACTIVE"                   => "Y",
                    ">PROPERTY_PRICE_RUB"      => 0,
                    ">PROPERTY_KITCHEN_SQUARE" => 0,
                    "IBLOCK_SECTION_ID"        => $arDefaultParams["novostroyki"]["OBJECT_ACTIVE"]
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
                    "IBLOCK_ID"                => $arIblockIDs["novostroyki"],
                    "ACTIVE"                   => "Y",
                    ">PROPERTY_PRICE_RUB"      => 0,
                    ">PROPERTY_KITCHEN_SQUARE" => 0,
                    "IBLOCK_SECTION_ID"        => $arDefaultParams["novostroyki"]["OBJECT_ACTIVE"]
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
                    "IBLOCK_ID"                => $arIblockIDs["novostroyki"],
                    "ACTIVE"                   => "Y",
                    ">PROPERTY_PRICE_RUB"      => 0,
                    ">PROPERTY_FLOOR"          => 0,
                    "IBLOCK_SECTION_ID"        => $arDefaultParams["novostroyki"]["OBJECT_ACTIVE"]
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
                    "IBLOCK_ID"                => $arIblockIDs["novostroyki"],
                    "ACTIVE"                   => "Y",
                    ">PROPERTY_PRICE_RUB"      => 0,
                    ">PROPERTY_FLOOR"          => 0,
                    "IBLOCK_SECTION_ID"        => $arDefaultParams["novostroyki"]["OBJECT_ACTIVE"]
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
                    "IBLOCK_ID"                => $arIblockIDs["novostroyki"],
                    "ACTIVE"                   => "Y",
                    ">PROPERTY_PRICE_RUB"      => 0,
                    "=PROPERTY_TERRACE_VALUE"  => "Y",
                    "IBLOCK_SECTION_ID"        => $arDefaultParams["novostroyki"]["OBJECT_ACTIVE"]
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
                    "IBLOCK_ID"                => $arIblockIDs["novostroyki"],
                    "ACTIVE"                   => "Y",
                    ">PROPERTY_PRICE_RUB"      => 0,
                    "=PROPERTY_TWO_TIER_VALUE"  => "Y",
                    "IBLOCK_SECTION_ID"        => $arDefaultParams["novostroyki"]["OBJECT_ACTIVE"]
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
                    "IBLOCK_ID"                => $arIblockIDs["novostroyki"],
                    "ACTIVE"                   => "Y",
                    ">PROPERTY_PRICE_RUB"      => 0,
                    "=PROPERTY_HIGH_CEILINGS_VALUE"  => "Y",
                    "IBLOCK_SECTION_ID"        => $arDefaultParams["novostroyki"]["OBJECT_ACTIVE"]
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

            $arFilterParamsDefaultNewbuild['MIN_PRICE'] = $arMinPriceApartment['PROPERTY_PRICE_RUB_VALUE'];
            $arFilterParamsDefaultNewbuild['MAX_PRICE'] = $arMaxPriceApartment['PROPERTY_PRICE_RUB_VALUE'];
            $arFilterParamsDefaultNewbuild['MIN_SQUARE'] = $arMinSquareApartment['PROPERTY_SQUARE_VALUE'];
            $arFilterParamsDefaultNewbuild['MAX_SQUARE'] = $arMaxSquareApartment['PROPERTY_SQUARE_VALUE'];
            $arFilterParamsDefaultNewbuild['MIN_KITCHEN_SQUARE'] = $arMinKitchenSquareApartment['PROPERTY_KITCHEN_SQUARE_VALUE'];
            $arFilterParamsDefaultNewbuild['MAX_KITCHEN_SQUARE'] = $arMaxKitchenSquareApartment['PROPERTY_KITCHEN_SQUARE_VALUE'];
            $arFilterParamsDefaultNewbuild['MIN_FLOOR'] = $arMinFloorApartment['PROPERTY_FLOOR_VALUE'];
            $arFilterParamsDefaultNewbuild['MAX_FLOOR'] = $arMaxFloorApartment['PROPERTY_FLOOR_VALUE'];
            $arFilterParamsDefaultNewbuild['TERRACE'] = $arTerraceApartment['PROPERTY_TERRACE_VALUE'];
            $arFilterParamsDefaultNewbuild['TWO_TIER'] = $arTwoTierApartment['PROPERTY_TWO_TIER_VALUE'];
            $arFilterParamsDefaultNewbuild['HIGH_CEILINGS'] = $arHighCeilingsApartment['PROPERTY_HIGH_CEILINGS_VALUE'];

            $cacheParamsDefault->endDataCache($arFilterParamsDefaultNewbuild); // записываем в кеш
        }

        //

        // Поиск минимальных значений для фильтра вторички
        $cacheParamsDefault = Cache::createInstance(); // получаем экземпляр класса
        if ($cacheParamsDefault->initCache(86400, "cache_data_default_filter_resale_main".$rsCurCity["CODE"], "/")) { // проверяем кеш и задаём настройки
            $arFilterParamsDefaultResale = $cacheParamsDefault->getVars(); // достаем переменные из кеша
        }
        elseif ($cacheParamsDefault->startDataCache()) {
            $arMinPriceApartment = CIBlockElement::GetList(
                array("PROPERTY_PRICE_RUB" => "ASC"),
                array(
                    "IBLOCK_ID" => $arIblockIDs["kupit-kvartiru"],
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
                    "IBLOCK_ID" => $arIblockIDs["kupit-kvartiru"],
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
                    "IBLOCK_ID" => $arIblockIDs["kupit-kvartiru"],
                    "ACTIVE" => "Y",
                    ">PROPERTY_PRICE_RUB" => 0,
                    ">PROPERTY_SQUARE" => 0,
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
                    "IBLOCK_ID" => $arIblockIDs["kupit-kvartiru"],
                    "ACTIVE" => "Y",
                    ">PROPERTY_PRICE_RUB" => 0,
                    ">PROPERTY_SQUARE" => 0,
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
                    "IBLOCK_ID" => $arIblockIDs["kupit-kvartiru"],
                    "ACTIVE" => "Y",
                    ">PROPERTY_PRICE_RUB" => 0,
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
                    "IBLOCK_ID" => $arIblockIDs["kupit-kvartiru"],
                    "ACTIVE" => "Y",
                    ">PROPERTY_PRICE_RUB" => 0,
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

            $arMinFloorApartment = CIBlockElement::GetList(
                array("PROPERTY_FLOOR" => "ASC"),
                array(
                    "IBLOCK_ID" => $arIblockIDs["kupit-kvartiru"],
                    "ACTIVE" => "Y",
                    ">PROPERTY_PRICE_RUB" => 0,
                    ">PROPERTY_FLOOR" => 0,
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
                    "IBLOCK_ID" => $arIblockIDs["kupit-kvartiru"],
                    "ACTIVE" => "Y",
                    ">PROPERTY_PRICE_RUB" => 0,
                    ">PROPERTY_FLOOR" => 0,
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
                    "IBLOCK_ID"                => $arIblockIDs["kupit-kvartiru"],
                    "ACTIVE"                   => "Y",
                    ">PROPERTY_PRICE_RUB"      => 0,
                    "=PROPERTY_TERRACE_VALUE"  => "Y",
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
                    "IBLOCK_ID"                => $arIblockIDs["kupit-kvartiru"],
                    "ACTIVE"                   => "Y",
                    ">PROPERTY_PRICE_RUB"      => 0,
                    "=PROPERTY_TWO_TIER_VALUE"  => "Y",
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
                    "IBLOCK_ID"                => $arIblockIDs["kupit-kvartiru"],
                    "ACTIVE"                   => "Y",
                    ">PROPERTY_PRICE_RUB"      => 0,
                    "=PROPERTY_HIGH_CEILINGS_VALUE"  => "Y",
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

            $arFilterParamsDefaultResale['MIN_PRICE'] = $arMinPriceApartment['PROPERTY_PRICE_RUB_VALUE'];
            $arFilterParamsDefaultResale['MAX_PRICE'] = $arMaxPriceApartment['PROPERTY_PRICE_RUB_VALUE'];
            $arFilterParamsDefaultResale['MIN_SQUARE'] = $arMinSquareApartment['PROPERTY_SQUARE_VALUE'];
            $arFilterParamsDefaultResale['MAX_SQUARE'] = $arMaxSquareApartment['PROPERTY_SQUARE_VALUE'];
            $arFilterParamsDefaultResale['MIN_KITCHEN_SQUARE'] = $arMinKitchenSquareApartment['PROPERTY_KITCHEN_SQUARE_VALUE'];
            $arFilterParamsDefaultResale['MAX_KITCHEN_SQUARE'] = $arMaxKitchenSquareApartment['PROPERTY_KITCHEN_SQUARE_VALUE'];
            $arFilterParamsDefaultResale['MIN_FLOOR'] = $arMinFloorApartment['PROPERTY_FLOOR_VALUE'];
            $arFilterParamsDefaultResale['MAX_FLOOR'] = $arMaxFloorApartment['PROPERTY_FLOOR_VALUE'];

            $arFilterParamsDefaultResale['TERRACE'] = $arTerraceApartment['PROPERTY_TERRACE_VALUE'];
            $arFilterParamsDefaultResale['TWO_TIER'] = $arTwoTierApartment['PROPERTY_TWO_TIER_VALUE'];
            $arFilterParamsDefaultResale['HIGH_CEILINGS'] = $arHighCeilingsApartment['PROPERTY_HIGH_CEILINGS_VALUE'];

            $cacheParamsDefault->endDataCache($arFilterParamsDefaultResale); // записываем в кеш
        }

        //

        // Поиск минимальных значений для фильтра загородной

        $cacheParamsDefault = Cache::createInstance(); // получаем экземпляр класса
        if ($cacheParamsDefault->initCache(86400, "cache_data_default_filter_country_main".$rsCurCity["CODE"], "/")) { // проверяем кеш и задаём настройки
            $arFilterParamsDefaultCountry = $cacheParamsDefault->getVars(); // достаем переменные из кеша
        }
        elseif ($cacheParamsDefault->startDataCache()) {
            $arMinPriceApartment = CIBlockElement::GetList(
                array("PROPERTY_PRICE_RUB" => "ASC"),
                array(
                    "IBLOCK_ID" => $arIblockIDs["kupit-dom"],
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
                    "IBLOCK_ID" => $arIblockIDs["kupit-dom"],
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
                    "IBLOCK_ID" => $arIblockIDs["kupit-dom"],
                    "ACTIVE" => "Y",
                    //">PROPERTY_PRICE_RUB" => 0,
                    ">PROPERTY_SQUARE" => 0
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
                    "IBLOCK_ID" => $arIblockIDs["kupit-dom"],
                    "ACTIVE" => "Y",
                    //">PROPERTY_PRICE_RUB"  => 0,
                    ">PROPERTY_SQUARE" => 0
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
                    "IBLOCK_ID" => $arIblockIDs["kupit-dom"],
                    "ACTIVE" => "Y",
                    //">PROPERTY_PRICE_RUB" => 0,
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
                    "IBLOCK_ID" => $arIblockIDs["kupit-dom"],
                    "ACTIVE" => "Y",
                    //">PROPERTY_PRICE_RUB"          => 0,
                    ">PROPERTY_KITCHEN_SQUARE" => 0
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
                array("PROPERTY_FLOOR_MAX" => "ASC"),
                array(
                    "IBLOCK_ID" => $arIblockIDs["kupit-dom"],
                    "ACTIVE" => "Y",
                    //">PROPERTY_PRICE_RUB" => 0,
                    ">PROPERTY_FLOOR_MAX" => 0
                ),
                false,
                array("nTopCount" => 1),
                array(
                    "ID",
                    "IBLOCK_ID",
                    "IBLOCK_SECTION_ID",
                    "CODE",
                    "NAME",
                    "PROPERTY_FLOOR_MAX",
                )
            )->Fetch();

            $arMaxFloorApartment = CIBlockElement::GetList(
                array("PROPERTY_FLOOR_MAX" => "DESC"),
                array(
                    "IBLOCK_ID" => $arIblockIDs["kupit-dom"],
                    "ACTIVE" => "Y",
                    //">PROPERTY_PRICE_RUB" => 0,
                    ">PROPERTY_FLOOR_MAX" => 0
                ),
                false,
                array("nTopCount" => 1),
                array(
                    "ID",
                    "IBLOCK_ID",
                    "IBLOCK_SECTION_ID",
                    "CODE",
                    "NAME",
                    "PROPERTY_FLOOR_MAX",
                )
            )->Fetch();

            $arTerraceApartment = CIBlockElement::GetList(
                array("PROPERTY_TERRACE"=>"ASC"),
                array(
                    "IBLOCK_ID"                => $arIblockIDs["kupit-dom"],
                    "ACTIVE"                   => "Y",
                    ">PROPERTY_PRICE_RUB"      => 0,
                    "=PROPERTY_TERRACE_VALUE"  => "Y",
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
                    "IBLOCK_ID"                => $arIblockIDs["kupit-dom"],
                    "ACTIVE"                   => "Y",
                    ">PROPERTY_PRICE_RUB"      => 0,
                    "=PROPERTY_TWO_TIER_VALUE"  => "Y",
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
                    "IBLOCK_ID"                => $arIblockIDs["kupit-dom"],
                    "ACTIVE"                   => "Y",
                    ">PROPERTY_PRICE_RUB"      => 0,
                    "=PROPERTY_HIGH_CEILINGS_VALUE"  => "Y",
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

            $arFilterParamsDefaultCountry['MIN_PRICE'] = $arMinPriceApartment['PROPERTY_PRICE_RUB_VALUE'];
            $arFilterParamsDefaultCountry['MAX_PRICE'] = $arMaxPriceApartment['PROPERTY_PRICE_RUB_VALUE'];
            $arFilterParamsDefaultCountry['MIN_SQUARE'] = $arMinSquareApartment['PROPERTY_SQUARE_VALUE'];
            $arFilterParamsDefaultCountry['MAX_SQUARE'] = $arMaxSquareApartment['PROPERTY_SQUARE_VALUE'];
            $arFilterParamsDefaultCountry['MIN_KITCHEN_SQUARE'] = $arMinKitchenSquareApartment['PROPERTY_KITCHEN_SQUARE_VALUE'];
            $arFilterParamsDefaultCountry['MAX_KITCHEN_SQUARE'] = $arMaxKitchenSquareApartment['PROPERTY_KITCHEN_SQUARE_VALUE'];
            $arFilterParamsDefaultCountry['MIN_FLOOR'] = $arMinFloorApartment['PROPERTY_FLOOR_MAX_VALUE'];
            $arFilterParamsDefaultCountry['MAX_FLOOR'] = $arMaxFloorApartment['PROPERTY_FLOOR_MAX_VALUE'];
            $arFilterParamsDefaultCountry['SQUARE_CURRENCY'] = "м²";

            $arFilterParamsDefaultCountry['TERRACE'] = $arTerraceApartment['PROPERTY_TERRACE_VALUE'];
            $arFilterParamsDefaultCountry['TWO_TIER'] = $arTwoTierApartment['PROPERTY_TWO_TIER_VALUE'];
            $arFilterParamsDefaultCountry['HIGH_CEILINGS'] = $arHighCeilingsApartment['PROPERTY_HIGH_CEILINGS_VALUE'];

            $cacheParamsDefault->endDataCache($arFilterParamsDefaultCountry); // записываем в кеш
        }

        // Поиск минимальных значений для фильтра зарубежной

        $cacheParamsDefault = Cache::createInstance(); // получаем экземпляр класса
        if ($cacheParamsDefault->initCache(86400, "cache_data_default_filter_foreign_main".$rsCurCity["CODE"], "/")) { // проверяем кеш и задаём настройки
            $arFilterParamsDefaultForeign = $cacheParamsDefault->getVars(); // достаем переменные из кеша
        }
        elseif ($cacheParamsDefault->startDataCache()) {
            $arMinPriceApartment = CIBlockElement::GetList(
                array("PROPERTY_PRICE_RUB" => "ASC"),
                array(
                    "IBLOCK_ID" => $arIblockIDs["nedvizhimost-za-rubezhom"],
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
                    "IBLOCK_ID" => $arIblockIDs["nedvizhimost-za-rubezhom"],
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
                    "IBLOCK_ID" => $arIblockIDs["nedvizhimost-za-rubezhom"],
                    "ACTIVE" => "Y",
                    //">PROPERTY_PRICE_RUB" => 0,
                    ">PROPERTY_SQUARE" => 0
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
                    "IBLOCK_ID" => $arIblockIDs["nedvizhimost-za-rubezhom"],
                    "ACTIVE" => "Y",
                    //">PROPERTY_PRICE_RUB"  => 0,
                    ">PROPERTY_SQUARE" => 0
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

            $arMinFloorApartment = CIBlockElement::GetList(
                array("PROPERTY_FLOOR_MAX" => "ASC"),
                array(
                    "IBLOCK_ID" => $arIblockIDs["nedvizhimost-za-rubezhom"],
                    "ACTIVE" => "Y",
                    //">PROPERTY_PRICE_RUB" => 0,
                    ">PROPERTY_FLOOR_MAX" => 0
                ),
                false,
                array("nTopCount" => 1),
                array(
                    "ID",
                    "IBLOCK_ID",
                    "IBLOCK_SECTION_ID",
                    "CODE",
                    "NAME",
                    "PROPERTY_FLOOR_MAX",
                )
            )->Fetch();

            $arMaxFloorApartment = CIBlockElement::GetList(
                array("PROPERTY_FLOOR_MAX" => "DESC"),
                array(
                    "IBLOCK_ID" => $arIblockIDs["nedvizhimost-za-rubezhom"],
                    "ACTIVE" => "Y",
                    //">PROPERTY_PRICE_RUB" => 0,
                    ">PROPERTY_FLOOR_MAX" => 0
                ),
                false,
                array("nTopCount" => 1),
                array(
                    "ID",
                    "IBLOCK_ID",
                    "IBLOCK_SECTION_ID",
                    "CODE",
                    "NAME",
                    "PROPERTY_FLOOR_MAX",
                )
            )->Fetch();

            $arTerraceApartment = CIBlockElement::GetList(
                array("PROPERTY_TERRACE"=>"ASC"),
                array(
                    "IBLOCK_ID"                => $arIblockIDs["nedvizhimost-za-rubezhom"],
                    "ACTIVE"                   => "Y",
                    ">PROPERTY_PRICE_RUB"      => 0,
                    "=PROPERTY_TERRACE_VALUE"  => "Y",
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
                    "IBLOCK_ID"                => $arIblockIDs["nedvizhimost-za-rubezhom"],
                    "ACTIVE"                   => "Y",
                    ">PROPERTY_PRICE_RUB"      => 0,
                    "=PROPERTY_TWO_TIER_VALUE"  => "Y",
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
                    "IBLOCK_ID"                => $arIblockIDs["nedvizhimost-za-rubezhom"],
                    "ACTIVE"                   => "Y",
                    ">PROPERTY_PRICE_RUB"      => 0,
                    "=PROPERTY_HIGH_CEILINGS_VALUE"  => "Y",
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

            $arFilterParamsDefaultForeign['MIN_PRICE'] = $arMinPriceApartment['PROPERTY_PRICE_RUB_VALUE'];
            $arFilterParamsDefaultForeign['MAX_PRICE'] = $arMaxPriceApartment['PROPERTY_PRICE_RUB_VALUE'];
            $arFilterParamsDefaultForeign['MIN_SQUARE'] = $arMinSquareApartment['PROPERTY_SQUARE_VALUE'];
            $arFilterParamsDefaultForeign['MAX_SQUARE'] = $arMaxSquareApartment['PROPERTY_SQUARE_VALUE'];
            $arFilterParamsDefaultForeign['MIN_FLOOR'] = $arMinFloorApartment['PROPERTY_FLOOR_MAX_VALUE'];
            $arFilterParamsDefaultForeign['MAX_FLOOR'] = $arMaxFloorApartment['PROPERTY_FLOOR_MAX_VALUE'];

            $arFilterParamsDefaultForeign['TERRACE'] = $arTerraceApartment['PROPERTY_TERRACE_VALUE'];
            $arFilterParamsDefaultForeign['TWO_TIER'] = $arTwoTierApartment['PROPERTY_TWO_TIER_VALUE'];
            $arFilterParamsDefaultForeign['HIGH_CEILINGS'] = $arHighCeilingsApartment['PROPERTY_HIGH_CEILINGS_VALUE'];

            $cacheParamsDefault->endDataCache($arFilterParamsDefaultForeign); // записываем в кеш
        }

        //

        $arFilterParamsDefault = [
            "novostroyki" => $arFilterParamsDefaultNewbuild,
            "kupit-kvartiru" => $arFilterParamsDefaultResale,
            "kupit-dom" => $arFilterParamsDefaultCountry,
            "nedvizhimost-za-rubezhom" => $arFilterParamsDefaultForeign,
        ];

        $arFilterRanges = $arFilterParamsDefault[$category];

        $arOutput = array(
            "CATEGORIES" => $arCategories,
            "DEFAULT_PARAMS" => $arDefaultParams[$category],
            "IS_AJAX" => $isAjax,
            "IS_APARTMENT_REQUEST" => $isApartmentRequest,
            "FILTER_OBJECTS" => $arFilterObjects,
            "FILTER_RANGES" => $arFilterRanges,
            "FILTER_VALUES" => $arFilterValues,
            "SEARCH_URL" => $searchUrl
        );

            $this->templateName = 'main';

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