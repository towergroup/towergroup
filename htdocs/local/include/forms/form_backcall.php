<?
$arInfo = getFormInfo($arParams["FORM_CODE"]);
if (SITE_ID == "s1") {
    $urlPrivacy = "/moskva/privacy/";
} elseif(SITE_ID == "s2") {
    $urlPrivacy = "/spb/privacy/";
} else {
    $urlPrivacy = "/privacy/";
}
?>
<div class="div-title div-title-h2 with-bottom-margin"><?= htmlspecialcharsBack($arInfo["TITLE"]); ?></div>
<form class="form" id="backcall-form">
    <input type="hidden" name="site_id" value="<?=SITE_ID;?>">
    <input type="hidden" name="form_name" value="<?= htmlspecialcharsBack($arInfo["NAME"]); ?>">
    <?if ($GLOBALS['default_broker']['TYPE_OF_PROPERTY']):?>
        <input type="hidden" name="type-of-property" value="<?=$GLOBALS['default_broker']['TYPE_OF_PROPERTY'];?>">
    <?endif;?>
    <div class="form-item">
        <div class="field"><input class="field-input" type="text" name="name" value placeholder="Имя"></div>
    </div>
    <div class="form-item">
        <div class="field"><input class="field-input" type="tel" name="phone" value placeholder="Телефон"></div>
    </div>
    <div class="form-control"><button class="button button--light">Отправить</button></div>
    <div class="form-item form-item--checkbox"><label class="checkbox"><input type="checkbox" checked="checked" name="privacy"><span>Заполняя форму на сайте, Вы соглашаетесь с нашей <a href="<?=$urlPrivacy;?>" target="_blank">политикой</a></span></label></div>
</form>