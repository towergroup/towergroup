<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>

<ul class="list list--types">
    <?
    $index = 0;
    foreach ($arResult["ITEMS"] as $arItem):?>
    <?
    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
    if ($arItem['PREVIEW_PICTURE']['WIDTH'] > 882)
        $arItem["PREVIEW_PICTURE_RESIZE"] = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"]["ID"], array('width' => 882, 'height' => 390),
            BX_RESIZE_IMAGE_PROPORTIONAL, false)['src']
    ?>
    <li class="list-item <?= $index < 2 ? 'list-item-large' : '';?>" id="<?= $this->GetEditAreaId($arItem['ID']); ?>" data-scroll-fx>
        <a class="list-link lazy" href="<?= $arItem['PROPERTIES']['LINK']['VALUE']; ?>" data-bg="<?= $arItem["PREVIEW_PICTURE_RESIZE"] ? $arItem["PREVIEW_PICTURE_RESIZE"] : $arItem['PREVIEW_PICTURE']['SRC'] ?>">
            <div class="h3 h4-title"><?= $arItem['NAME'] ?></div>
            <div class="list-item-hide">
                <? foreach ($arItem['PROPERTIES']['LINKS']['VALUE'] as $key => $value): ?>
                    <object>
                        <a href="<?= $value; ?>"><?= $arItem['PROPERTIES']['LINKS']['DESCRIPTION'][$key]; ?></a>
                    </object>
                <? endforeach; ?>
            </div>
        </a>
    </li>
    <? 
    $index++;
    endforeach; ?>
</ul>
