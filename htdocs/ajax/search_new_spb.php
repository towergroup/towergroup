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
        "SECTION_ID" => 1112,
        "DEPTH_LEVEL" => 2
    ];

    $arFilterStreet = [
        "IBLOCK_ID" => NEW_BUILD_SPB_IBLOCK_ID,
        "ACTIVE" => "Y",
        "UF_CITY" => "Санкт-Петербург",
    ];

    $arFilterObject = [
        "IBLOCK_ID" => NEW_BUILD_SPB_IBLOCK_ID,
        "ACTIVE" => "Y",
        "UF_CITY" => "Санкт-Петербург",
    ];

    if($text != null) {
        $reqText = (is_array($text)) ? $text : explode(',', $text);
        if(count($reqText) > 0) {
            $arFilterArea["%NAME"] = $reqText;
            $arFilterStreet["%UF_ADDRESS"] = $reqText;
            $arFilterObject["%NAME"] = $reqText;
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

    $arObjects = [
        'group' => 'ЖК',
        'groupID' => 'object',
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
        if (!in_array('area_'.$arArea['ID'],$textId) ) {
            $arAreas['options'][] = [
                'id' => 'area_'.$arArea['ID'],
                'title' => $arArea['NAME']
            ];
        }
    }

//находим станции метро
    $hlblBanks = SUBWAYS_SPB_HIGHLOADBLOCK_ID; // Указываем ID highloadblock метро.
    $hlblock = HL\HighloadBlockTable::getById($hlblBanks)->fetch();

    $entity = HL\HighloadBlockTable::compileEntity($hlblock);
    $entity_data_class = $entity->getDataClass();

    $rsData = $entity_data_class::getList(array(
        "select" => array("*"),
        "order" => array("ID" => "ASC"),
        "filter" => $arFilterSubway  // Задаем параметры фильтра выборки
    ));

    while($arData = $rsData->Fetch()){
        if (!in_array('subway_'.$arData['ID'],$textId)) {
            $arSubways['options'][] = [
                'id' => 'subway_'.$arData['ID'],
                'title' => $arData['UF_NAME']
            ];
        }
    }

    $resStreets = CIBlockSection::GetList(
        array(),
        $arFilterStreet,
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

    while($arStreet = $resStreets->GetNext()){
        if (!in_array('street_'.$arStreet['UF_ADDRESS'],$textId)) {
            $exp = explode(",",$arStreet['UF_ADDRESS']);
            $street = $exp[1] ? $exp[1] : $exp[0];
            if (in_array($street, $arStreets['options'])) continue;
            $arStreets['options'][] = [
                'id' => 'street_'.$street,
                'title' => $street
            ];
        }
    }
    $arStreets['options'] = array_unique($arStreets['options']);

    //находим объекты

    $resObjects = CIBlockSection::GetList(
        array(),
        $arFilterObject,
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

    while($arObject = $resObjects->GetNext()){
        if (!in_array('object_'.$arObject['ID'],$textId)) {
            $arObjects['options'][] = [
                'id' => 'object_'.$arObject['ID'],
                'title' => $arObject['NAME']
            ];
        }
    }

//находим застройщиков

    $hlblBanks = BUILDERS_SPB_HIGHLOADBLOCK_ID; // Указываем ID highloadblock застройщиков
    $hlblock = HL\HighloadBlockTable::getById($hlblBanks)->fetch();

    $entity = HL\HighloadBlockTable::compileEntity($hlblock);
    $entity_data_class = $entity->getDataClass();

    $rsData = $entity_data_class::getList(array(
        "select" => array("*"),
        "order" => array("ID" => "ASC"),
        "filter" => $arFilterBuilder  // Задаем параметры фильтра выборки
    ));

    while($arData = $rsData->Fetch()){
        if (!in_array('builder_'.$arData['ID'],$textId)) {
            $arBuilders['options'][] = [
                'id' => 'builder_'.$arData['ID'],
                'title' => $arData['UF_NAME']
            ];
        }
    }


    if ($arAreas)$arReturn[] = $arAreas;
    if ($arSubways) $arReturn[] = $arSubways;
    if ($arStreets) $arReturn[] = $arStreets;
    if ($arObjects) $arReturn[] = $arObjects;
    if ($arBuilders) $arReturn[] = $arBuilders;

    ?>
    <?
    echo $encode = json_encode($arReturn, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}
else {
    echo "Повторите запрос";
}
?>

