<?
foreach ($arResult["ITEMS"] as $key => $arItem) {
    if (($arItem["PREVIEW_PICTURE"]["WIDTH"]!==350) && ($arItem["PREVIEW_PICTURE"]["WIDTH"]!==270) && !empty($arItem["PREVIEW_PICTURE"]))
    {
        $arItem["PREVIEW_PICTURE"]=CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], Array("width" => '350', "height" => '270'), BX_RESIZE_IMAGE_EXACT);
        $arResult["ITEMS"][$key]["PREVIEW_PICTURE"]['SRC']=$arItem["PREVIEW_PICTURE"]["src"];
    }
}
?>