<?
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;
use Bitrix\Main\Loader;

ini_set("max_execution_time", 0);

if (!$_SERVER['DOCUMENT_ROOT']) {
    $_SERVER['DOCUMENT_ROOT'] = realpath(__DIR__ . '/../../../');
}
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
require(dirname(__FILE__) . '/xml.class.php');

CModule::IncludeModule("iblock");
Loader::includeModule("highloadblock");
$el = new CIBlockElement();

$xmlUrl = "https://towergroup.ru/3bita/data_spb.xml";
//$xmlUrl = $_SERVER['DOCUMENT_ROOT']."/3bita/data_spb.xml";



/*var_dump(libxml_use_internal_errors(true));
// Read entire file into string variable
$xmlfile = file_get_contents($xmlUrl);

// Convert xml string into an PHP object
$xml_obj = simplexml_load_string($xmlfile, 'SimpleXMLElement', LIBXML_COMPACT | LIBXML_PARSEHUGE);
foreach (libxml_get_errors() as $error) {
    xprint($error);
}
// Convert into json Data
$json_data = json_encode($xml_obj);

// Convert into associative array
$arr_data = json_decode($json_data, true);


xprint($arDataFile['Ads']['Builders']['Builder']);
die();*/
$loadFileEncode = file_get_contents($xmlUrl);
$arDataFile = XML2Array::createArray($loadFileEncode);

/**
 * Застройщики: получение и добавление!
 */

$hlbl = 9; // Указываем ID нашего highloadblock блока к которому будет делать запросы.
$hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();

$entity = HL\HighloadBlockTable::compileEntity($hlblock);
$entity_data_class = $entity->getDataClass();

$rsData = $entity_data_class::getList([
    "select" => ["*"],
    "order" => ["ID" => "ASC"],
]);

while ($arData = $rsData->Fetch()) {
    $arBuilder[$arData['UF_ID']] = $arData['ID'];
}

foreach ($arDataFile['Ads']['Builders']['Builder'] as $builder) {
    if(empty($arBuilder[$builder['@attributes']['id']])) {
        $data = array(
            "UF_ID" => $builder['@attributes']['id'],
            "UF_XML_ID" => $builder['@attributes']['id'],
            "UF_NAME" => $builder['@attributes']['name'],
        );

        $result = $entity_data_class::add($data);
    }
}

/**
 * Типы недвижимости: получение и добавление!
 */

$hlbl = 2; // Указываем ID нашего highloadblock блока к которому будет делать запросы.
$hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();

$entity = HL\HighloadBlockTable::compileEntity($hlblock);
$entity_data_class = $entity->getDataClass();

$rsData = $entity_data_class::getList([
    "select" => ["*"],
    "order" => ["ID" => "ASC"],
]);

while ($arData = $rsData->Fetch()) {
    $arType[$arData['UF_ID']] = $arData['ID'];
}

/*foreach ($arDataFile['Ads']['RoomTypes']['RoomType'] as $roomType) {
// Массив полей для добавления
    $data = array(
        "UF_ID" => $roomType['@attributes']['id'],
        "UF_NAME" => $roomType['@attributes']['name'],
    );

    $result = $entity_data_class::add($data);
}*/

/**
 * Типы отделки: получение и добавление!
 */

$hlbl = 3; // Указываем ID нашего highloadblock блока к которому будет делать запросы.
$hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();

$entity = HL\HighloadBlockTable::compileEntity($hlblock);
$entity_data_class = $entity->getDataClass();

$rsData = $entity_data_class::getList([
    "select" => ["*"],
    "order" => ["ID" => "ASC"],
]);

while ($arData = $rsData->Fetch()) {
    $arDecor[$arData['UF_ID']] = $arData['ID'];
}

/*foreach ($arDataFile['Ads']['Decorations']['Decoration'] as $decoration) {
// Массив полей для добавления
    $data = array(
        "UF_ID" => $decoration['@attributes']['id'],
        "UF_NAME" => $decoration['@attributes']['name'],
    );

    $result = $entity_data_class::add($data);
}*/

/**
 * Банки: добавление!
 */

$hlbl = 4; // Указываем ID нашего highloadblock блока к которому будет делать запросы.
$hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();

$entity = HL\HighloadBlockTable::compileEntity($hlblock);
$entity_data_class = $entity->getDataClass();

/*foreach ($arDataFile['Ads']['Banks']['Bank'] as $bank) {
// Массив полей для добавления
    $data = array(
        "UF_ID" => $bank['@attributes']['id'],
        "UF_NAME" => $bank['@attributes']['name'],
    );

    $result = $entity_data_class::add($data);
}*/

/**
 * Класс ЖК: получение и добавление!
 */

$hlbl = 5; // Указываем ID нашего highloadblock блока к которому будет делать запросы.
$hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();

$entity = HL\HighloadBlockTable::compileEntity($hlblock);
$entity_data_class = $entity->getDataClass();

$rsData = $entity_data_class::getList([
    "select" => ["*"],
    "order" => ["ID" => "ASC"],
]);

while ($arData = $rsData->Fetch()) {
    $arClass[$arData['UF_ID']] = $arData['ID'];
}
/*foreach ($arDataFile['Ads']['Classes']['Class'] as $class) {
// Массив полей для добавления
    $data = array(
        "UF_ID" => $class['@attributes']['id'],
        "UF_NAME" => $class['@attributes']['name'],
    );

    $result = $entity_data_class::add($data);
}*/

/**
 * Корпуса ЖК: получение, обновление и добавление!
 */

$hlbl = 6; // Указываем ID нашего highloadblock блока к которому будет делать запросы.
$hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();

$entity = HL\HighloadBlockTable::compileEntity($hlblock);
$entity_data_class = $entity->getDataClass();

$rsData = $entity_data_class::getList([
    "select" => ["*"],
    "order" => ["ID" => "ASC"],
]);

while ($arData = $rsData->Fetch()) {
    $arBuildings[$arData['UF_ID']] = $arData['UF_B_VARIANTS'];
    $arBuildingsUpdate[$arData['UF_ID']] = $arData['ID'];
}

foreach ($arDataFile['Ads']['BuildingTypes']['BuildingType'] as $buildingType) {
    $tempTypeBuilding[$buildingType['@attributes']['id']] = $buildingType['@attributes']['name'];
}

foreach ($arDataFile['Ads']['Buildings']['Building'] as $building) {
    $arEndPeriod[$building['@attributes']['complexid']] = $building['@attributes']['endingperiod'];

    $arTypeBuilding[$building['@attributes']['complexid']] = $tempTypeBuilding[$building['@attributes']['buildingtypeid']];

    if (empty($arBuildings[$building['@attributes']['id']])) {
        $data = [
            "UF_ID" => $building['@attributes']['id'],
            "UF_XML_ID" => $building['@attributes']['id'],
            "UF_COMPLEX" => $building['@attributes']['complexid'],
            "UF_B_VARIANTS" => $building['@attributes']['buildingvariantid'],
            "UF_NUMBER" => $building['@attributes']['number'],
            "UF_CORP" => $building['@attributes']['corp'],
            "UF_AREA" => $building['@attributes']['ares'],
            "UF_FLOORS" => $building['@attributes']['floors'],
            "UF_ENDPERIOD" => $building['@attributes']['endingperiod'],
        ];

        $result = $entity_data_class::add($data);
    } else {
        $data = [
            "UF_COMPLEX" => $building['@attributes']['complexid'],
            "UF_B_VARIANTS" => $building['@attributes']['buildingvariantid'],
            "UF_FLOORS" => $building['@attributes']['floors'],
            "UF_ENDPERIOD" => $building['@attributes']['endingperiod'],
        ];

        $result = $entity_data_class::update($arBuildingsUpdate[$building['@attributes']['id']], $data);
    }
}

/**
 * Тип помещений: добавление и получение!
 */

$hlbl = 8; // Указываем ID нашего highloadblock блока к которому будет делать запросы.
$hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();

$entity = HL\HighloadBlockTable::compileEntity($hlblock);
$entity_data_class = $entity->getDataClass();

/*$rsData = $entity_data_class::getList([
    "select" => ["*"],
    "order" => ["ID" => "ASC"],
]);

while($arData = $rsData->Fetch()){
    $arBuildingVariant[$arData['UF_ID']] = $arData['ID'];
}*/

/*foreach ($arDataFile['Ads']['BuildingVariants']['BuildingVariant'] as $buildingVariant) {
// Массив полей для добавления
    $data = [
        "UF_XML_ID" => $buildingVariant['@attributes']['id'],
        "UF_NAME" => $buildingVariant['@attributes']['name'],
    ];

    $result = $entity_data_class::add($data);
}*/

/**
 * Районы: получение!
 */

$arFilter = ['IBLOCK_ID' => REGION_IBLOCK_ID, 'DEPTH_LEVEL' => 2, "IBLOCK_SECTION_ID" => 1112];
$arSelect = ['UF_ID'];
$rsSect = CIBlockSection::GetList(false, $arFilter, false, $arSelect);
while ($arSect = $rsSect->GetNext()) {
    if (!empty($arSect['UF_ID'])) {
        $arRegion[$arSect['UF_ID']] = $arSect['ID'];
    }
}

/**
 * Районы: добавление!
 */

foreach ($arDataFile['Ads']['Regions']['Region'] as $region) {
    if (empty($arRegion[$region['@attributes']['id']])) {
        $section = new CIBlockSection;
        $arFields = [
            "ACTIVE" => 'Y',
            "IBLOCK_SECTION_ID" => 1112,
            "IBLOCK_ID" => REGION_IBLOCK_ID,
            "NAME" => $region['@attributes']['name'],
            "UF_ID" => $region['@attributes']['id'],
            "UF_REGION" => $region['@attributes']['isregion'] == 1 ? true : false,
        ];

        $arParams = [
            "max_len" => "100", // обрезаем символьный код до 100 символов
            "change_case" => "L", // приводим к нижнему регистру
            "replace_space" => "-", // меняем пробелы на тире
            "replace_other" => "-", // меняем плохие символы на тире
            "delete_repeat_replace" => "true", // удаляем повторяющиеся тире
            "use_google" => "false", // отключаем использование google
        ];
        $arFields["CODE"] = Cutil::translit($region['@attributes']['name'], "ru", $arParams);

        $section->Add($arFields);
    }
}


/**
 * Метро: добавление и получение!
 */

$hlbl = 10; // Указываем ID нашего highloadblock блока к которому будет делать запросы.
$hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();

$entity = HL\HighloadBlockTable::compileEntity($hlblock);
$entity_data_class = $entity->getDataClass();

$rsData = $entity_data_class::getList([
    "select" => ["*"],
    "order" => ["ID" => "ASC"],
]);

while ($arData = $rsData->Fetch()) {
    $arMetro[$arData['UF_ID']] = $arData['ID'];
}

foreach ($arDataFile['Ads']['Subways']['Subway'] as $subway) {
    if (empty($arMetro['spb-'.$subway['@attributes']['id']])) {
        $data = [
            "UF_ID" => 'spb-'.$subway['@attributes']['id'],
            "UF_XML_ID" => 'spb-'.$subway['@attributes']['id'],
            "UF_NAME" => $subway['@attributes']['name'],
            "UF_INDEX" => $subway['@attributes']['index'],
        ];

        $result = $entity_data_class::add($data);
    }
}

/*foreach ($arDataFile['Ads']['Subways']['Subway'] as $subway) {
    $arProps = array(
        'EXT_ID' => $subway['@attributes']['id'],
        'LINE' => $subway['@attributes']['index'],
    );
    $arFields = array(
        'IBLOCK_ID' => REGION_IBLOCK_ID,
        "ACTIVE" => "Y",
        "IBLOCK_SECTION_ID" => $arRegion[$subway['@attributes']['id']],
        "NAME" => $subway['@attributes']['name'],
        "PROPERTY_VALUES" => $arProps
    );
    $arParams = [
        "max_len" => "100", // обрезаем символьный код до 100 символов
        "change_case" => "L", // приводим к нижнему регистру
        "replace_space" => "-", // меняем пробелы на тире
        "replace_other" => "-", // меняем плохие символы на тире
        "delete_repeat_replace" => "true", // удаляем повторяющиеся тире
        "use_google" => "false", // отключаем использование google
    ];

    $arFields["CODE"] = Cutil::translit($subway['@attributes']['name'], "ru", $arParams);

    $subwayId[] = $el->Add($arFields);
}*/

/**
 * Комплексы: Получение!
 */

$arFilter = ['IBLOCK_ID' => NEW_BUILD_SPB_IBLOCK_ID, 'DEPTH_LEVEL' => 1];
$arSelect = ['UF_ID', 'UF_NO_UPDATE'];
$rsSect = CIBlockSection::GetList(false, $arFilter, false, $arSelect);
while ($arSect = $rsSect->GetNext()) {
    if (!empty($arSect['UF_ID'])) {
        $arComplexs[$arSect['UF_ID']] = $arSect['ID'];
        $arComplexsUpdate[$arSect['UF_ID']] = $arSect['UF_NO_UPDATE_DESCRIPTION'];
        if($arSect['UF_NO_UPDATE'] != 1){
            $arComplexsDiff[$arSect['ID']] = $arSect['UF_ID'];
        }
    }
}

/**
 * Комплексы: Обновление и добавление!
 */

foreach ($arDataFile['Ads']['ComplexImages']['ComplexImage'] as $complexImage) {
    if ($complexImage['@attributes']['ismain'] == '1') {
        $mainPictureComplex[$complexImage['@attributes']['complexid']] = $_SERVER["DOCUMENT_ROOT"] . "/3bita/" . $complexImage['@attributes']['filename'];
    } else {
        $pictureComplex[$complexImage['@attributes']['complexid']][] = $_SERVER["DOCUMENT_ROOT"] . "/3bita/" . $complexImage['@attributes']['filename'];
    }
}

foreach ($arDataFile['Ads']['ComplexSubways']['ComplexSubway'] as $complexSubway) {
    $arMetroId[$complexSubway['@attributes']['complexid']][] = $arMetro['spb-'.$complexSubway['@attributes']['subwayid']];
}

foreach ($arDataFile['Ads']['Complexes']['Complex'] as $complex) {
    $arNewComplex[] = $complex['@attributes']['id']; //Все ЖК
    if (!empty($arComplexs[$complex['@attributes']['id']])) { // Обновление
        $section = new CIBlockSection;

        /*$mainFile = CFile::MakeFileArray($mainPictureComplex[$complex['@attributes']['id']]);*/
        /*$pictures = [];
        if (!empty($pictureComplex[$complex['@attributes']['id']])) {
            foreach ($pictureComplex[$complex['@attributes']['id']] as $picture) {
                $pictures[] = CFile::MakeFileArray($picture);
            }
        }*/
        $dedlineYear = 0;
        if ($arEndPeriod[$complex['@attributes']['id']] != 'Сдан') {
            $regexp = "/[0-9]{4}/";
            $match = [];
            preg_match($regexp, $arEndPeriod[$complex['@attributes']['id']], $match);
            $dedlineYear = (int)$match[0];
            $currYear = (int)date('Y');

            if($dedlineYear < $currYear){
                $arEndPeriod[$complex['@attributes']['id']] = 'Сдан';
                $dedlineYear = 0;
            }
        } elseif ($arEndPeriod[$complex['@attributes']['id']] == 'Сдан') {
            //$dedlineYear = 'Сдан';
        }

        $arFields = Array(
            "ACTIVE" => 'Y',
            "IBLOCK_ID" => NEW_BUILD_SPB_IBLOCK_ID,
            "NAME" => $complex['@attributes']['title'],
            //"PICTURE" => $mainFile,
            "UF_CITY" => "Санкт-Петербург",
            "UF_ID" => $complex['@attributes']['id'],
            "UF_ADDRESS" => $complex['@attributes']['address'],
            "UF_COORD" => $complex['@attributes']['latitude'] . ", " . $complex['@attributes']['longitude'],
            //"UF_CLASS" => $arClass[$complex['@attributes']['classid']],
            "UF_BUILD" => $arBuilder[$complex['@attributes']['builderid']],
            "UF_DEADLINE" => $arEndPeriod[$complex['@attributes']['id']],
            "UF_TYPE_BUILD" => $arTypeBuilding[$complex['@attributes']['id']],
            "UF_METRO_HL" => $arMetroId[$complex['@attributes']['id']],
            "UF_DEADLINE_YEAR" => $dedlineYear,
            "UF_AREA" => $arRegion[$complex['@attributes']['regionid']],
            //"UF_PHOTOS" => $pictures,
        );

        if($arComplexsUpdate[$complex['@attributes']['id']] != 1){
            $arFields["DESCRIPTION"] = $complex['@attributes']['note'];
        }

        $res = $section->Update($arComplexs[$complex['@attributes']['id']], $arFields);
    } else {
        $section = new CIBlockSection;

        $mainFile = CFile::MakeFileArray($mainPictureComplex[$complex['@attributes']['id']]);
        $pictures = [];
        if (!empty($pictureComplex[$complex['@attributes']['id']])) {
            foreach ($pictureComplex[$complex['@attributes']['id']] as $picture) {
                $pictures[] = CFile::MakeFileArray($picture);
            }
        }
        $dedlineYear = 0;
        if ($arEndPeriod[$complex['@attributes']['id']] != 'Сдан') {
            $regexp = "/[0-9]{4}/";
            $match = [];
            preg_match($regexp, $arEndPeriod[$complex['@attributes']['id']], $match);
            $dedlineYear = (int)$match[0];
            $currYear = (int)date('Y');

            if($dedlineYear < $currYear){
                $arEndPeriod[$complex['@attributes']['id']] = 'Сдан';
                $dedlineYear = 0;
            }
        } elseif ($arEndPeriod[$complex['@attributes']['id']] == 'Сдан') {
            //$dedlineYear = 'Сдан';
        }

        $arFields = Array(
            "ACTIVE" => 'Y',
            "IBLOCK_ID" => NEW_BUILD_SPB_IBLOCK_ID,
            "NAME" => $complex['@attributes']['title'],
            "DESCRIPTION" => $complex['@attributes']['note'],
            "PICTURE" => $mainFile,
            "UF_CITY" => "Санкт-Петербург",
            "UF_ID" => $complex['@attributes']['id'],
            "UF_ADDRESS" => $complex['@attributes']['address'],
            "UF_COORD" => $complex['@attributes']['latitude'] . ", " . $complex['@attributes']['longitude'],
            "UF_CLASS" => $arClass[$complex['@attributes']['classid']],
            "UF_BUILD" => $arBuilder[$complex['@attributes']['builderid']],
            "UF_DEADLINE" => $arEndPeriod[$complex['@attributes']['id']],
            "UF_TYPE_BUILD" => $arTypeBuilding[$complex['@attributes']['id']],
            "UF_METRO_HL" => $arMetroId[$complex['@attributes']['id']],
            "UF_DEADLINE_YEAR" => $dedlineYear,
            "UF_AREA" => $arRegion[$complex['@attributes']['regionid']],
            "UF_PHOTOS" => $pictures,
        );
        $arParams = array(
            "max_len" => "100", // обрезаем символьный код до 100 символов
            "change_case" => "L", // приводим к нижнему регистру
            "replace_space" => "-", // меняем пробелы на тире
            "replace_other" => "-", // меняем плохие символы на тире
            "delete_repeat_replace" => "true", // удаляем повторяющиеся тире
            "use_google" => "false", // отключаем использование google
        );
        $arFields["CODE"] = Cutil::translit($complex['@attributes']['title'], "ru", $arParams);

        $id = $section->Add($arFields);
        if(!$id)
            echo $section->LAST_ERROR;

        $arComplexs[$complex['@attributes']['id']] = $id;
    }
}

/**
 * Курс валют
 */
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
/**
 * Получение квартир
 */
$rsFlats = CIBlockElement::GetList(
    array('ID' => 'ASC'),
    array(
        'IBLOCK_ID' => NEW_BUILD_SPB_IBLOCK_ID
    ),
    false,
    false,
    array(
        'ID',
        'IBLOCK_SECTION_ID',
        'NAME',
        'CODE',
        'DETAIL_PICTURE',
        'PROPERTY_EXT_ID',
        'PROPERTY_NO_UPDATE'
    )
);
while ($arFlat = $rsFlats->Fetch()) {
    $arFlats[$arFlat['PROPERTY_EXT_ID_VALUE']] = $arFlat['ID'];
    $arFlatsUpdate[$arFlat['PROPERTY_EXT_ID_VALUE']] = $arFlat;
    if ($arFlat['PROPERTY_NO_UPDATE_VALUE'] != 'Y') {
        $arFlatsDiff[$arFlat['ID']] = $arFlat['PROPERTY_EXT_ID_VALUE'];
    }
}
/**
 * Квартиры: Обновление и добавление!
 */
$i = 0;
foreach ($arDataFile['Ads']['ApartmentsFirst']['Apartment'] as $apartment) {
    $arNewFlats[] = $apartment['@attributes']['id']; //Все объекты

    if (!empty($arFlats[$apartment['@attributes']['id']])) {
        if ($arFlatsUpdate[$apartment['@attributes']['id']]['PROPERTY_NO_UPDATE_VALUE'] == 'Y') {
            continue;
        }

        //Обновление планировки
        if(empty($arFlatsUpdate[$apartment['@attributes']['id']]['DETAIL_PICTURE'])){
            $el->Update($arFlats[$apartment['@attributes']['id']], array(
                "DETAIL_PICTURE" => CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"] . "/3bita/" . $apartment['@attributes']['flatplans'])
            ));
        }

        $el->Update($arFlats[$apartment['@attributes']['id']], array(
            'ACTIVE' => 'Y',
            'IBLOCK_SECTION_ID' => $arComplexs[$apartment['@attributes']['complexid']],
        ));

        if (empty($apartment['@attributes']['flatcostbase '])) {
            $apartment['@attributes']['flatcostbase'] = $apartment['@attributes']['flatcostwithdiscounts'];
        }

        $rooms = '';
        if ($apartment['@attributes']['roomtypeid'] == 0) {
            $rooms = 28;
        } elseif ($apartment['@attributes']['roomtypeid'] == 1) {
            $rooms = 30;
        } elseif ($apartment['@attributes']['roomtypeid'] == 2) {
            $rooms = 29;
        } elseif ($apartment['@attributes']['roomtypeid'] == 3) {
            $rooms = 32;
        } elseif ($apartment['@attributes']['roomtypeid'] == 4) {
            $rooms = 31;
        } elseif ($apartment['@attributes']['roomtypeid'] == 5) {
            $rooms = 31;
        } elseif ($apartment['@attributes']['roomtypeid'] == 21) {
            $rooms = 32;
        } elseif ($apartment['@attributes']['roomtypeid'] == 32) {
            $rooms = 31;
        } elseif ($apartment['@attributes']['roomtypeid'] == 22) {
            $rooms = 30;
        } elseif ($apartment['@attributes']['roomtypeid'] == 23) {
            $rooms = 29;
        } elseif ($apartment['@attributes']['roomtypeid'] == 25) {

        } elseif ($apartment['@attributes']['roomtypeid'] == 7) {
            $rooms = 31;
        } elseif ($apartment['@attributes']['roomtypeid'] == 6) {
            $rooms = 31;
        } elseif ($apartment['@attributes']['roomtypeid'] == 8) {
            $rooms = 31;
        }

        $height = '';
        if ($apartment['@attributes']['height'] >= 3) {
            $height = 26;
        }

        $terrace = '';
        if (strripos($apartment['@attributes']['sbalcony'], 'т')) {
            $terrace = 34;
        }

        $two = '';
        if ($apartment['@attributes']['levels'] > 1) {
            $two = 27;
        }

        $arProps = array(
            'SQUARE' => $apartment['@attributes']['stotal'],
            'KITCHEN_SQUARE' => $apartment['@attributes']['skitchen'],
            'NUMBER' => $apartment['@attributes']['number'],
            'SECTION' => $apartment['@attributes']['section'],
            'TYPE' => $apartment['@attributes']['flattypeid'],
            'ROOMS' => $rooms,
            'HIGH_CEILINGS' => $height,
            'TERRACE' => $terrace,
            'TWO_TIER' => $two,
            'DECORATION' => $apartment['@attributes']['decorationid'],
            'BUILD_TYPE' => $apartment['@attributes']['roomtypeid'] == 25 ? 7 : $arBuildings[$apartment['@attributes']['buildingid']],
            'PRICE_DISCOUNT' => $apartment['@attributes']['flatcostwithdiscounts'],
            'PRICE_BASE' => $apartment['@attributes']['flatcostbase'],
            'PRICE_RUB' => $apartment['@attributes']['flatcostbase'],
            'PRICE_USD' => ceil($apartment['@attributes']['flatcostbase'] / $rate['USD']),
            'PRICE_EUR' => ceil($apartment['@attributes']['flatcostbase'] / $rate['EUR']),
            'PRICE_GBP' => ceil($apartment['@attributes']['flatcostbase'] / $rate['GBP']),
        );

        CIBlockElement::SetPropertyValuesEx(
            $arFlats[$apartment['@attributes']['id']],
            NEW_BUILD_SPB_IBLOCK_ID,
            $arProps
        );
    } else {
        if (empty($apartment['@attributes']['flatcostbase'])) {
            $apartment['@attributes']['flatcostbase'] = $apartment['@attributes']['flatcostwithdiscounts'];
        }

        $rooms = '';
        if ($apartment['@attributes']['roomtypeid'] == 0) {
            $rooms = 28;
        } elseif ($apartment['@attributes']['roomtypeid'] == 1) {
            $rooms = 30;
        } elseif ($apartment['@attributes']['roomtypeid'] == 2) {
            $rooms = 29;
        } elseif ($apartment['@attributes']['roomtypeid'] == 3) {
            $rooms = 32;
        } elseif ($apartment['@attributes']['roomtypeid'] == 4) {
            $rooms = 31;
        } elseif ($apartment['@attributes']['roomtypeid'] == 5) {
            $rooms = 31;
        } elseif ($apartment['@attributes']['roomtypeid'] == 21) {
            $rooms = 32;
        } elseif ($apartment['@attributes']['roomtypeid'] == 32) {
            $rooms = 31;
        } elseif ($apartment['@attributes']['roomtypeid'] == 22) {
            $rooms = 30;
        } elseif ($apartment['@attributes']['roomtypeid'] == 23) {
            $rooms = 29;
        } elseif ($apartment['@attributes']['roomtypeid'] == 25) {

        } elseif ($apartment['@attributes']['roomtypeid'] == 7) {
            $rooms = 31;
        } elseif ($apartment['@attributes']['roomtypeid'] == 6) {
            $rooms = 31;
        } elseif ($apartment['@attributes']['roomtypeid'] == 8) {
            $rooms = 31;
        }

        $height = '';
        if ($apartment['@attributes']['height'] >= 3) {
            $height = 26;
        }

        $terrace = '';
        if (strripos($apartment['@attributes']['sbalcony'], 'т')) {
            $terrace = 34;
        }

        $two = '';
        if ($apartment['@attributes']['levels'] > 1) {
            $two = 27;
        }

        $arProps = array(
            'EXT_ID' => $apartment['@attributes']['id'],
            'FLOOR' => $apartment['@attributes']['floor'],
            'SQUARE' => $apartment['@attributes']['stotal'],
            'KITCHEN_SQUARE' => $apartment['@attributes']['skitchen'],
            'BUILDING' => $apartment['@attributes']['buildingid'],
            'NUMBER' => $apartment['@attributes']['number'],
            'SECTION' => $apartment['@attributes']['section'],
            'TYPE' => $apartment['@attributes']['flattypeid'],
            'ROOMS' => $rooms,
            'HIGH_CEILINGS' => $height,
            'TERRACE' => $terrace,
            'TWO_TIER' => $two,
            'DECORATION' => $apartment['@attributes']['decorationid'],
            'BUILD_TYPE' => $apartment['@attributes']['roomtypeid'] == 25 ? 7 : $arBuildings[$apartment['@attributes']['buildingid']],
            'PRICE_DISCOUNT' => $apartment['@attributes']['flatcostwithdiscounts'],
            'PRICE_BASE' => $apartment['@attributes']['flatcostbase'],
            'PRICE_RUB' => $apartment['@attributes']['flatcostbase'],
            'PRICE_USD' => ceil($apartment['@attributes']['flatcostbase'] / $rate['USD']),
            'PRICE_EUR' => ceil($apartment['@attributes']['flatcostbase'] / $rate['EUR']),
            'PRICE_GBP' => ceil($apartment['@attributes']['flatcostbase'] / $rate['GBP']),
        );
        $arFields = array(
            'IBLOCK_ID' => NEW_BUILD_SPB_IBLOCK_ID,
            "ACTIVE" => "Y",
            "DETAIL_PICTURE" => CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"] . "/3bita/" . $apartment['@attributes']['flatplans']),
            "IBLOCK_SECTION_ID" => $arComplexs[$apartment['@attributes']['complexid']],
            "NAME" => "Квартира " . $apartment['@attributes']['number'] . " Секция " . $apartment['@attributes']['section'],
            "PROPERTY_VALUES" => $arProps
        );
        $arParams = [
            "max_len" => "100", // обрезаем символьный код до 100 символов
            "change_case" => "L", // приводим к нижнему регистру
            "replace_space" => "-", // меняем пробелы на тире
            "replace_other" => "-", // меняем плохие символы на тире
            "delete_repeat_replace" => "true", // удаляем повторяющиеся тире
            "use_google" => "false", // отключаем использование google
        ];

        $arFields["CODE"] = Cutil::translit($arFields['NAME'] . $apartment['@attributes']['id'], "ru", $arParams);
        $el->Add($arFields);
    }
    $i++;
}

// Отключение квартир, которых нет в выгрузке.
$arDiff = [];
$arDiff = array_diff($arFlatsDiff, $arNewFlats);
foreach ($arDiff as $flatId => $externalId) {
    $el->Update($flatId, array(
        'ACTIVE' => 'N'
    ));
}

// Отключение ЖК, которых нет в выгрузке.
$section = new CIBlockSection;
$arDiff = [];
$arDiff = array_diff($arComplexsDiff, $arNewComplex);
foreach ($arDiff as $complexId => $externalId) {
    $section->Update($complexId, array(
        'ACTIVE' => 'N'
    ));
}
?>