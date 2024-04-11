<?
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;


$rsSection = CIBlockSection::GetList(
    array("SORT" => "ASC"),
    array(
        "IBLOCK_ID" => NEWS_IBLOCK_ID,
        "ACTIVE" => "Y",
        "ID" => $arResult["IBLOCK_SECTION_ID"]
    ),
    true,
    array(
        "ID",
        "IBLOCK_ID",
        "CODE",
        "NAME"
    ),
    false
)->Fetch();

$arResult["SECTION"] = $rsSection;

$arIblockSectionsIDs = [
    NEW_BUILD_IBLOCK_ID,
    NEW_BUILD_SPB_IBLOCK_ID
];

$arIblockElementIDs = [
    RESALE_IBLOCK_ID,
    RESALE_SPB_IBLOCK_ID,
    COUNTRY_IBLOCK_ID,
    COUNTRY_SPB_IBLOCK_ID,
    FOREIGN_IBLOCK_ID,
];

foreach ($arResult["PROPERTIES"] as $keyProperty => $arProperty) {
    if ($keyProperty == "HIGHLIGHT_NEWS" || $keyProperty == "IS_ACTION" || $keyProperty == "DATA_PUBLICATION_SEO") continue;
    if ($arProperty["VALUE"])
    {
        if ($arProperty["LINK_IBLOCK_ID"] != 0) {
            switch ($arProperty["CODE"]) {
                case "OBJECT_NEW_BUILD_MSK":
                    $city = "msk";
                    $cityReplace = "moskva";
                    $objectType = "section";
                    break;
                case "OBJECT_RESALE_MSK":
                case "OBJECT_COUNTRY_MSK":
                    $city = "msk";
                    $cityReplace = "moskva";
                    $objectType = "element";
                    break;
                case "OBJECT_NEW_BUILD_SPB":
                    $city = "spb";
                    $cityReplace = "spb";
                    $objectType = "section";
                    break;
                case "OBJECT_RESALE_SPB":
                case "OBJECT_COUNTRY_SPB":
                    $city = "spb";
                    $cityReplace = "spb";
                    $objectType = "element";
                    break;
                default:
                    if (SITE_ID == s1) {
                        $cityReplace = "moskva";
                    } elseif (SITE_ID == s2) {
                        $cityReplace = "spb";
                    }
                    $objectType = "element";
                    break;
            }
            if ($objectType == "section") {
                $arObject = CIBlockSection::GetList(
                    array(),
                    array(
                        "IBLOCK_ID" => $arProperty["LINK_IBLOCK_ID"],
                        "ACTIVE" => "Y",
                        "ID" => $arProperty["VALUE"],
                        "DEPTH_LEVEL" => 1,
                    ),
                    false,
                    array(
                        "ID",
                        "IBLOCK_ID",
                        "CODE",
                        "NAME",
                        "DESCRIPTION",
                        "SECTION_PAGE_URL",
                        "UF_*"
                    ),
                    false
                )->GetNext();
                $arObject["OBJECT_PAGE_URL"] = $arObject["SECTION_PAGE_URL"];
                $arObject["OBJECT_PAGE_URL"] = str_replace(array("spb/spb", "spb/moskva", "moskva/spb", "moskva/moskva"), $cityReplace ,$arObject["SECTION_PAGE_URL"]);
                //$arObject["OBJECT_PAGE_URL"] = str_replace(array("#SITE_DIR#", "#SECTION_CODE#"),array("/".$cityReplace,$arObject["CODE"]."/"),$arObject["SECTION_PAGE_URL"]);
            }
            else {
                $arObject = CIBlockElement::GetList(
                    false,
                    array(
                        "IBLOCK_ID" => $arProperty["LINK_IBLOCK_ID"],
                        "ACTIVE" => "Y",
                        "ID" => $arProperty["VALUE"],
                    ),
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
                        "DETAIL_PAGE_URL",
                    )
                )->GetNext();
                $arObject["OBJECT_PAGE_URL"] = $arObject["DETAIL_PAGE_URL"];
                $arObject["OBJECT_PAGE_URL"] = str_replace(array("spb/spb", "spb/moskva", "moskva/moskva"), $cityReplace ,$arObject["SECTION_PAGE_URL"]);
                //$arObject["OBJECT_PAGE_URL"] = str_replace(array("#SITE_DIR#", "#ELEMENT_CODE#"),array("/".$cityReplace,$arObject["CODE"]."/"),$arObject["DETAIL_PAGE_URL"]);
            }

            $arResult["OBJECT"] = $arObject;
        }
        else {
            if ($arProperty["CODE"] == "BUILDER_MSK") {
                $builderHLID =
                $cityReplace = "moskva";
            } elseif ($arProperty["CODE"] == "BUILDER_SPB") {
                $cityReplace = "spb";
            }
            //находим Застройщика

            $hlblObjectLiveClass = $arProperty["CODE"] == "BUILDER_SPB" ? BUILDERS_SPB_HIGHLOADBLOCK_ID : BUILDERS_HIGHLOADBLOCK_ID; // Указываем ID highloadblock застройщиков
            $hlblock = HL\HighloadBlockTable::getById($hlblObjectLiveClass)->fetch();

            $entity = HL\HighloadBlockTable::compileEntity($hlblock);
            $entity_data_class = $entity->getDataClass();

            $rsDataObjectLiveClass = $entity_data_class::getList(array(
                "select" => array("*"),
                "order" => array("ID" => "ASC"),
                "filter" => array("=UF_ID" => $arProperty["VALUE"])  // Задаем параметры фильтра выборки
            ))->Fetch();

            if ($rsDataObjectLiveClass) {
                $arResult["BUILDER"] = $rsDataObjectLiveClass;
                $arResult["BUILDER"]["OBJECTS_PAGE_URL"] = '/'.$cityReplace.'/novostroyki/?search=[{"id":"builder_'.$arResult["BUILDER"]["ID"].'","title":"'.$arResult["BUILDER"]["UF_NAME"].'","exclude":false}]';
            }
        }
        if ($arProperty["CODE"] == "COMMERCE_SECTION" || $arProperty["CODE"] == "COMMERCE_ELEMENT"){
            if ($arProperty["CODE"] == "COMMERCE_SECTION"){
                $rsObject = CIBlockSection::GetList(
                    array(),
                    array(
                        "IBLOCK_ID" => $arIblockSectionsIDs,
                        "ACTIVE" => "Y",
                        "ID" => $arProperty["VALUE"],
                        "DEPTH_LEVEL" => 1,
                    ),
                    false,
                    array(
                        "ID",
                        "IBLOCK_ID",
                        "CODE",
                        "NAME",
                        "DESCRIPTION",
                        "PICTURE",
                        "SECTION_PAGE_URL",
                    ),
                    false
                );
                while ($arObject = $rsObject->GetNext()) {
                    $arObject["PICTURE"] = CFile::GetFileArray($arObject["PICTURE"]);
                    $rsObjectUserField = CIBlockSection::GetList(
                        array(),
                        array(
                            "IBLOCK_ID" => $arObject["IBLOCK_ID"],
                            "ACTIVE" => "Y",
                            "ID" => $arObject["ID"],
                            "DEPTH_LEVEL" => 1,
                        ),
                        false,
                        array(
                            "ID",
                            "IBLOCK_ID",
                            "UF_H1",
                            "UF_ADDRESS",
                            "UF_AREA",
                            "UF_SUBWAY"
                        ),
                        false
                    )->GetNext();
                    $arObject["UF_PROPERTIES"] = $rsObjectUserField;
                    $arResult["COMMERCIAL_OBJECTS_ID"][$arObject["ID"]] = "#COMMERCE_" . $arObject["ID"] . "#";
                    $replaceText = '';
                    $replaceText = '
                    <div class="press-hero lazy" data-bg="'. $arObject["PICTURE"]["SRC"] .'">
                        <div class="press-hero-heading">
                            <div class="press-hero-title">'. $arObject["UF_PROPERTIES"]["UF_H1"] .'</div>
                            <a class="button button--light" href="'. $arObject["SECTION_PAGE_URL"] .'">Перейти на страницу ЖК</a>
                        </div>';
                    if ($arObject["UF_PROPERTIES"]["UF_SUBWAY"] || $arObject["UF_PROPERTIES"]["UF_ADDRESS"]){
                        $replaceText .= '
                        <div class="press-hero-data">
                                <ul class="list">';
                        if ($arObject["UF_PROPERTIES"]["UF_SUBWAY"]){
                            $replaceText .='
                                    <li class="list-item list-item--metro">
                                        <svg width="17" height="13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M13.015 2.402c1.146 1.08 1.854 2.585 1.854 4.23 0 2.752-1.972 5.08-4.65 5.786l.488.582h4.027C16.14 11.57 17 9.65 17 7.556 17 4.08 14.641 1.114 11.356 0L8.5 8.977 5.644 0C2.36 1.114 0 4.081 0 7.556 0 9.65.86 11.57 2.266 13h4.018l.497-.582h-.008c-2.679-.706-4.65-3.034-4.65-5.785 0-1.646.708-3.142 1.853-4.23.11-.126.329-.192.54-.159.21.042.412.183.513.49l3.15 9.9c.102.009.211.009.321.009.11 0 .21 0 .32-.009l3.15-9.908a.704.704 0 0 1 .473-.473.664.664 0 0 1 .572.15Z" fill="#fff" />
                                        </svg>
                                        <div class="list-item-label">Метро</div>
                                        <div class="list-item-value">'. $arObject["UF_PROPERTIES"]["UF_SUBWAY"] .'</div>
                                    </li>';
                        }
                        if ($arObject["UF_PROPERTIES"]["UF_ADDRESS"]){
                            $replaceText .='
                                    <li class="list-item list-item--marker">
                                        <svg width="12" height="17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M3.662.468A5.875 5.875 0 0 1 6 0c.83 0 1.609.156 2.338.468.729.313 1.364.738 1.906 1.276.542.538.97 1.166 1.284 1.883.315.718.472 1.488.472 2.312 0 1.116-.314 2.322-.943 3.617a28.668 28.668 0 0 1-2.067 3.598c-.75 1.102-1.435 2.02-2.057 2.75L6 17l-.933-1.096c-.622-.73-1.308-1.648-2.057-2.75A28.664 28.664 0 0 1 .943 9.556C.314 8.261 0 7.056 0 5.94c0-.824.157-1.594.472-2.312a6.049 6.049 0 0 1 1.284-1.883A6.063 6.063 0 0 1 3.662.468Zm.41 7.254a2.663 2.663 0 0 0 1.929.778c.752 0 1.395-.26 1.928-.778a2.526 2.526 0 0 0 .799-1.878c0-.733-.267-1.36-.8-1.878a2.663 2.663 0 0 0-1.927-.778c-.753 0-1.396.259-1.929.778a2.526 2.526 0 0 0-.799 1.878c0 .733.267 1.359.8 1.878Z" fill="#fff" />
                                        </svg>
                                        <div class="list-item-label">Адрес</div>
                                        <div class="list-item-value">'. $arObject["UF_PROPERTIES"]["UF_ADDRESS"] .'</div>
                                    </li>';
                        }
                        $replaceText .='            
                                </ul>
                            </div>';
                    }
                    $replaceText .= '</div>';
                    $arObject["UF_COMMERCE_BLOCK"] = $replaceText;
                    $arResult["COMMERCIAL_OBJECTS_REPLACE_ID"][$arObject["ID"]] = $arObject["UF_COMMERCE_BLOCK"];
                }
            }
            elseif ($arProperty["CODE"] == "COMMERCE_ELEMENT") {
                $rsObject = CIBlockElement::GetList(
                    false,
                    array(
                        "IBLOCK_ID" => $arIblockElementIDs,
                        "ACTIVE" => "Y",
                        "ID" => $arProperty["VALUE"],
                    ),
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
                        "DETAIL_PAGE_URL",
                    )
                );
                while ($arObject = $rsObject->GetNextElement()) {
                    $arFields = $arObject->GetFields();
                    $arProperties = $arObject->GetProperties();
                    $arFields["PREVIEW_PICTURE"] = CFile::GetFileArray($arProperties["PHOTOS"]["VALUE"][0]);
                    $replaceText = '';
                    $replaceText = '
                    <div class="press-hero lazy" data-bg="'. $arFields["PREVIEW_PICTURE"]["SRC"] .'">
                        <div class="press-hero-heading">
                            <div class="press-hero-title">'. $arFields["NAME"] .'</div>
                            <a class="button button--light" href="'. $arFields["DETAIL_PAGE_URL"] .'">Перейти на страницу объекта</a>
                        </div>';
                    if ($arProperties["SUBWAY"]["VALUE"] || $arProperties["ADDRESS"]["VALUE"]){
                        $replaceText .= '
                        <div class="press-hero-data">
                                <ul class="list">';
                        if ($arProperties["SUBWAY"]["VALUE"]){
                            $replaceText .='
                                    <li class="list-item list-item--metro">
                                        <svg width="17" height="13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M13.015 2.402c1.146 1.08 1.854 2.585 1.854 4.23 0 2.752-1.972 5.08-4.65 5.786l.488.582h4.027C16.14 11.57 17 9.65 17 7.556 17 4.08 14.641 1.114 11.356 0L8.5 8.977 5.644 0C2.36 1.114 0 4.081 0 7.556 0 9.65.86 11.57 2.266 13h4.018l.497-.582h-.008c-2.679-.706-4.65-3.034-4.65-5.785 0-1.646.708-3.142 1.853-4.23.11-.126.329-.192.54-.159.21.042.412.183.513.49l3.15 9.9c.102.009.211.009.321.009.11 0 .21 0 .32-.009l3.15-9.908a.704.704 0 0 1 .473-.473.664.664 0 0 1 .572.15Z" fill="#fff" />
                                        </svg>
                                        <div class="list-item-label">Метро</div>
                                        <div class="list-item-value">'. $arProperties["SUBWAY"]["VALUE"] .'</div>
                                    </li>';
                        }
                        if ($arProperties["ADDRESS"]["VALUE"]){
                            $replaceText .='
                                    <li class="list-item list-item--marker">
                                        <svg width="12" height="17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M3.662.468A5.875 5.875 0 0 1 6 0c.83 0 1.609.156 2.338.468.729.313 1.364.738 1.906 1.276.542.538.97 1.166 1.284 1.883.315.718.472 1.488.472 2.312 0 1.116-.314 2.322-.943 3.617a28.668 28.668 0 0 1-2.067 3.598c-.75 1.102-1.435 2.02-2.057 2.75L6 17l-.933-1.096c-.622-.73-1.308-1.648-2.057-2.75A28.664 28.664 0 0 1 .943 9.556C.314 8.261 0 7.056 0 5.94c0-.824.157-1.594.472-2.312a6.049 6.049 0 0 1 1.284-1.883A6.063 6.063 0 0 1 3.662.468Zm.41 7.254a2.663 2.663 0 0 0 1.929.778c.752 0 1.395-.26 1.928-.778a2.526 2.526 0 0 0 .799-1.878c0-.733-.267-1.36-.8-1.878a2.663 2.663 0 0 0-1.927-.778c-.753 0-1.396.259-1.929.778a2.526 2.526 0 0 0-.799 1.878c0 .733.267 1.359.8 1.878Z" fill="#fff" />
                                        </svg>
                                        <div class="list-item-label">Адрес</div>
                                        <div class="list-item-value">'. $arProperties["ADDRESS"]["VALUE"] .'</div>
                                    </li>';
                        }
                        $replaceText .='            
                                </ul>
                            </div>';
                    }
                    $replaceText .= '</div>';
                    $arFields["UF_COMMERCE_BLOCK"] = str_replace(
                        array(
                            "#BACKGROUND#",
                            "#TITLE#",
                            "#OBJECT_URL#",
                            "#SUBWAY#",
                            "#ADDRESS#"
                        ),
                        array(
                            $arFields["PREVIEW_PICTURE"]["SRC"],
                            $arFields["NAME"],
                            $arFields["DETAIL_PAGE_URL"],
                            $arProperties["SUBWAY"]["VALUE"],
                            $arProperties["ADDRESS"]["VALUE"]
                        ),
                        $replaceText
                    );
                    $arResult["COMMERCIAL_OBJECTS_ID"][$arFields["ID"]] = "#COMMERCE_" . $arFields["ID"] . "#";
                    $arResult["COMMERCIAL_OBJECTS_REPLACE_ID"][$arFields["ID"]] = $arFields["UF_COMMERCE_BLOCK"];
                }
            }
        }
    }
}
//xprint(array($arResult["COMMERCIAL_OBJECTS_ID"], $arResult["COMMERCIAL_OBJECTS_REPLACE_ID"]));
$arResult["~DETAIL_TEXT"] = str_replace($arResult["COMMERCIAL_OBJECTS_ID"], $arResult["COMMERCIAL_OBJECTS_REPLACE_ID"], $arResult["~DETAIL_TEXT"]);
//xprint($arResult["PROPERTIES"]);

$arSort = array(
    "ACTIVE_FROM" => "DESC"
);

$arSelect = array(
    "ID",
    "NAME",
    "ACTIVE_FROM",
    "DETAIL_PAGE_URL",
    "PREVIEW_TEXT",
    "PREVIEW_PICTURE",
    "PROPERTY_HIGHLIGHT_NEWS"
);

$arFilter = array (
    "!=ID" => $arResult["ID"],
    "IBLOCK_ID" => NEWS_IBLOCK_ID,
    "IBLOCK_SECTION_ID" => $arResult["IBLOCK_SECTION_ID"],
    //">=DATE_ACTIVE_FROM"=>ConvertTimeStamp(time(),"FULL"),
    "ACTIVE" => "Y",
);

$arNavParams = array(
    "nPageSize" => 3,
);

$rsElement = CIBlockElement::GetList($arSort, $arFilter, false, $arNavParams, $arSelect);
$arResult['ITEMS'] = array();
while($obElement = $rsElement->GetNextElement()) {
    $arItem = $obElement->GetFields();
    $arItem["DETAIL_PAGE_URL"] = (SITE_ID == s1 ? '/moskva' : null) . $arItem["DETAIL_PAGE_URL"];
    if(array_key_exists("PREVIEW_PICTURE", $arItem))
        $arItem["PREVIEW_PICTURE"] = CFile::GetFileArray($arItem["PREVIEW_PICTURE"]);
    if (($arItem["PREVIEW_PICTURE"]["WIDTH"]!==350) && ($arItem["PREVIEW_PICTURE"]["WIDTH"]!==270) && !empty($arItem["PREVIEW_PICTURE"]))
    {
        $arItem["PREVIEW_PICTURE"]=CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], Array("width" => '350', "height" => '270'), BX_RESIZE_IMAGE_EXACT);
        $arItem["PREVIEW_PICTURE"]['SRC']=$arItem["PREVIEW_PICTURE"]["src"];
    }
    $arResult['ITEMS'][] = $arItem;
}
if ($arResult['LANG_DIR'] == "/moskva") {
    $cityseo = "Москва";
} else {
    $cityseo = "Санкт-Петербург";
}

// Правильное присваивание значения элементу массива
$arResult['PROPERTIES']['CITY_SEO']['VALUE'] = $cityseo;

?>