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
<div class="div-title div-title-h2 with-bottom-margin"><?= htmlspecialcharsBack($arInfo["TITLE"]); ?><span></span></div>
<form class="form" id="vacancy-form"><input type="hidden" id="vacancy-name" name="vacancy-name" value>
    <input type="hidden" name="site_id" value="<?=SITE_ID;?>">
    <input type="hidden" name="form_name" value="<?= htmlspecialcharsBack($arInfo["NAME"]); ?>">
    <div class="form-item">
        <div class="field"><input class="field-input" type="text" value name="name" placeholder="Имя"></div>
    </div>
    <div class="form-item">
        <div class="field"><input class="field-input" type="tel" value name="phone" placeholder="Телефон"></div>
    </div>
    <div class="form-control"><button class="button button--light">Отправить</button></div>
    <div class="form-item form-item--checkbox"><label class="checkbox"><input type="checkbox" checked name="privacy"><span>Заполняя форму на сайте, Вы соглашаетесь с нашей <a href="<?=$urlPrivacy;?>" target="_blank">политикой</a></span></label></div>
</form>