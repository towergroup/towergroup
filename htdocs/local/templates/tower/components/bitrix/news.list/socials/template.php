<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>
<ul class="list list--social">
    <? foreach ($arResult['ITEMS'] as $keyCode => $arSocial) : ?>
        <?
        if ($arParams['IS_FOOTER'] == 'Y' && $arSocial['PROPERTIES']['HIDE_IN_FOOTER']['VALUE'] == 'Y'){
            continue;
        }
        ?>
        <li class="list-item">
            <a class="list-link" href="<?=$arSocial['PROPERTIES']['LINK']['VALUE']?>" rel="noopener noreferrer" target="_blank">
                <?=$arSocial['PROPERTIES']['SVG']['~VALUE']['TEXT']?>
            </a>
        </li>
    <? endforeach; ?>
</ul>