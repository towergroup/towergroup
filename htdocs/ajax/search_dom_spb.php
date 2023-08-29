<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main;
use Bitrix\Main\Application;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;


CModule::IncludeModule('iblock');

if (!Main\Loader::includeModule('highloadblock')) {
    throw new Main\LoaderException(Loc::getMessage('IBLOCK_MODULE_NOT_INSTALLED'));
}

$request = Application::getInstance()->getContext()->getRequest();


/**
 * Sorting item flats
 */
if ($request->get('text') != null){
    $text = urldecode($request->get('text'));
}


if ($request->get('id') != null){
    $textId = json_decode(($request->get('id')));
}

if ($text || $textId)
{
    $arFilterArea = [
        "IBLOCK_ID" => COUNTRY_SPB_IBLOCK_ID,
        "ACTIVE" => "Y",
    ];

    $arFilterStreet = [
        "IBLOCK_ID" => COUNTRY_SPB_IBLOCK_ID,
        "ACTIVE" => "Y",
    ];

    $arFilterBuilder = [
        "IBLOCK_ID" => COUNTRY_SPB_IBLOCK_ID,
        "ACTIVE" => "Y",
    ];

    if($text != null) {
        $reqText = (is_array($text)) ? $text : explode(',', $text);
        if(count($reqText) > 0) {
            $arFilterArea["%PROPERTY_REGION"] = $reqText;
            $arFilterStreet["%PROPERTY_ADDRESS"] = $reqText;
            $arFilterSubway["%UF_NAME"] = $reqText;
            $arFilterBuilder["%PROPERTY_BUILDER"] = $reqText;
        }
    }

    $arAreas = [
        'group' => 'Район',
        'groupID' => 'area',
        'options' => [],
    ];

    $arSubways = [
        'group' => 'Метро',
        'groupID' => 'subway',
        'options' => [],
    ];

    $arStreets = [
        'group' => 'Улица',
        'groupID' => 'street',
        'options' => [],
    ];

    $arBuilders = [
        'group' => 'Застройщик',
        'groupID' => 'builder',
        'options' => [],
    ];

    //Район
    $resAreas = CIBlockElement::GetList(
        false,
        $arFilterArea,
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
            "PROPERTY_ADDRESS",
            "PROPERTY_REGION",
        )
    );

    while($arArea = $resAreas->GetNext()){
        if (!in_array('area_'.$arArea['PROPERTY_REGION_VALUE'],$textId)) {
            $areas = $arArea['PROPERTY_REGION_VALUE'];
            if (in_array($areas, $arAreas['options'])) continue;
            $arAreas['options'][] = [
                'id' => 'area_'.$areas,
                'title' => $areas
            ];
        }
    }
    $arAreas['options'] = array_unique($arAreas['options']);

//находим станции метро
    $hlblBanks = SUBWAYS_HIGHLOADBLOCK_ID; // Указываем ID highloadblock метро.
    $hlblock = HL\HighloadBlockTable::getById($hlblBanks)->fetch();

    $entity = HL\HighloadBlockTable::compileEntity($hlblock);
    $entity_data_class = $entity->getDataClass();

    $rsData = $entity_data_class::getList(array(
        "select" => array("*"),
        "order" => array("ID" => "ASC"),
        "filter" => $arFilterSubway  // Задаем параметры фильтра выборки
    ));

    while($arData = $rsData->Fetch()){
        if (!in_array('subway_'.$arData['UF_XML_ID'],$textId)) {
            $arSubways['options'][] = [
                'id' => 'subway_'.$arData['UF_XML_ID'],
                'title' => $arData['UF_NAME']
            ];
        }
    }
//улицы
    $resStreets = CIBlockElement::GetList(
        false,
        $arFilterStreet,
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
            "PROPERTY_ADDRESS",
        )
    );

    while($arStreet = $resStreets->GetNext()){
        if (!in_array('street_'.$arStreet['PROPERTY_ADDRESS_VALUE'],$textId)) {
            $street = explode(",",$arStreet['PROPERTY_ADDRESS_VALUE'])[0];
            if (in_array($street, $arStreets['options'])) continue;
            $arStreets['options'][] = [
                'id' => 'street_'.$street,
                'title' => $street
            ];
        }
    }
    $arStreets['options'] = array_unique($arStreets['options']);

    //xprint($arStreets);

//находим застройщиков
    $rsData = CIBlockElement::GetList(
        false,
        $arFilterBuilder,
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
            "PROPERTY_ADDRESS",
            "PROPERTY_REGION",
            "PROPERTY_BUILDER",
        )
    );

    while($arData = $rsData->GetNext()){
        if (!in_array('area_'.$arData['PROPERTY_BUILDER_VALUE'],$textId)) {
            $build = $arData['PROPERTY_BUILDER_VALUE'];
            if (in_array($build, $arBuilders['options'])) continue;
            $arBuilders['options'][] = [
                'id' => 'builder_'.$build,
                'title' => $build
            ];
        }
    }
    $arBuilders['options'] = array_unique($arBuilders['options']);

    if ($arAreas)$arReturn[] = $arAreas;
    if ($arSubways) $arReturn[] = $arSubways;
    if ($arStreets) $arReturn[] = $arStreets;
    if ($arBuilders) $arReturn[] = $arBuilders;

    ?>
    <?
    //xprint($arReturn);
    echo $encode = json_encode($arReturn, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}
else {
    echo "Повторите запрос";
}
?>