<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
if (SITE_ID == "s1") {
    $urlPrivacy = "/moskva/privacy/";
} elseif(SITE_ID == "s2") {
    $urlPrivacy = "/spb/privacy/";
} else {
    $urlPrivacy = "/privacy/";
}
?>


<div>
    <?= $arResult['PROPERTIES']['COPYRIGHT']['VALUE']; ?>
</div>
<div><a href="<?= $urlPrivacy;?>">Политика конфиденциальности</a></div>
<div class="footer-bottom-developer">
    Разработано в <a href="<?= $arResult['PROPERTIES']['DEVELOP_LINK']['VALUE']; ?>" rel="noopener noreferrer nofollow" target="_blank"><?= $arResult['PROPERTIES']['DEVELOP_LINK']['DESCRIPTION']; ?></a>
</div>
<div class="footer-bottom-information">
    <p><?= $arResult['~PREVIEW_TEXT']; ?></p>
</div>
