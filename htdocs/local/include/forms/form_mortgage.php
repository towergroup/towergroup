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
<form class="form" id="credit-form">
    <div class="form-row-border">
        <input type="hidden" name="site_id" value="<?= SITE_ID; ?>">
        <input type='hidden' name='tipObject' value='<?= $arParams["OBJECT"]; ?>' placeholder=''>
        <input type="hidden" name="form_name" value="Заявка на ипотеку">
        <div class="form-item">
            <div class="field"><input class="field-input" type="text" placeholder="Ваше имя" name="name"></div>
        </div>
        <div class="form-item">
            <div class="field"><input class="field-input" type="tel" placeholder="Телефон" name="phone"></div>
        </div>
        <div class="form-control"><button class="button button--light" type="submit">Отправить</button></div>
    </div>
    <div class="form-item form-item--checkbox"><label class="checkbox"><input type="checkbox" checked name="privacy"><span>Заполняя форму на сайте, Вы соглашаетесь с нашей <a href="<?=$urlPrivacy;?>" target="_blank">политикой</a></span></label></div>
</form>