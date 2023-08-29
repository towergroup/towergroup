<?

$rsSection = CIBlockSection::GetList(
    array("SORT" => "ASC"),
    array(
        "IBLOCK_ID" => NEWS_IBLOCK_ID,
        "ACTIVE" => "Y",
    ),
    true,
    array(
        "ID",
        "IBLOCK_ID",
        "CODE",
        "NAME"
    ),
    false
);

while ($arSection = $rsSection->GetNext()) {
    if ($arSection["CODE"] == $arResult["SECTION"]["PATH"][0]["CODE"])
        $arSection["IS_CURRENT"] = "Y";
    $arSection["SECTION_PAGE_URL"] = str_replace("#SECTION_CODE#",$arSection["CODE"], $arParams["SECTION_URL"]);
    $arResult["SECTIONS"][$arSection["ID"]] = $arSection;
}

foreach ($arResult["ITEMS"] as $key => $arItem) {
    if (($arItem["PREVIEW_PICTURE"]["WIDTH"]!==350) && ($arItem["PREVIEW_PICTURE"]["WIDTH"]!==270) && !empty($arItem["PREVIEW_PICTURE"]))
    {
        $arItem["PREVIEW_PICTURE"]=CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], Array("width" => '350', "height" => '270'), BX_RESIZE_IMAGE_EXACT);
        $arResult["ITEMS"][$key]["PREVIEW_PICTURE"]['SRC']=$arItem["PREVIEW_PICTURE"]["src"];
    }
}
?>