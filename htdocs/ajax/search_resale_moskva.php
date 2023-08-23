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
        "IBLOCK_ID" => REGION_IBLOCK_ID,
        "ACTIVE" => "Y",
        "SECTION_ID" => 2,
        "DEPTH_LEVEL" => 2
    ];

    $arFilterStreet = [
        "IBLOCK_ID" => RESALE_IBLOCK_ID,
        "ACTIVE" => "Y",
    ];

    if($text != null) {
        $reqText = (is_array($text)) ? $text : explode(',', $text);
        if(count($reqText) > 0) {
            $arFilterArea["%NAME"] = $reqText;
            $arFilterStreet["%PROPERTY_ADDRESS"] = $reqText;
            $arFilterSubway["%UF_NAME"] = $reqText;
            $arFilterBuilder["%UF_NAME"] = $reqText;
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

    $resAreas = CIBlockSection::GetList(
        array(),
        $arFilterArea,
        false,
        array(
            "ID",
            "IBLOCK_ID",
            "CODE",
            "NAME",
            "PICTURE",
            "UF_*"
        ),
        false
    );

    while($arArea = $resAreas->GetNext()){
        if (!in_array('area_'.$arArea['ID'],$textId)) {
            $arAreas['options'][] = [
                'id' => 'area_'.$arArea['ID'],
                'title' => $arArea['NAME']
            ];
        }
    }

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

    $hlblBanks = BUILDERS_HIGHLOADBLOCK_ID; // Указываем ID highloadblock застройщиков
    $hlblock = HL\HighloadBlockTable::getById($hlblBanks)->fetch();

    $entity = HL\HighloadBlockTable::compileEntity($hlblock);
    $entity_data_class = $entity->getDataClass();

    $rsData = $entity_data_class::getList(array(
        "select" => array("*"),
        "order" => array("ID" => "ASC"),
        "filter" => $arFilterBuilder  // Задаем параметры фильтра выборки
    ));

    while($arData = $rsData->Fetch()){
        if (!in_array('builder_'.$arData['UF_XML_ID'],$textId)) {
            $arBuilders['options'][] = [
                'id' => 'builder_'.$arData['UF_XML_ID'],
                'title' => $arData['UF_NAME']
            ];
        }
    }


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