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
<h3 class="h1"><?= htmlspecialcharsBack($arInfo["TITLE"]); ?></h3>
<p><?= htmlspecialcharsBack($arInfo["FOOTNOTE"]); ?></p>
<form class="form" id="reserve-form">
    <input hidden='' type='text' name='tipObject' value='<?= $arParams["OBJECT"]; ?>' placeholder=''>
    <input type="hidden" name="site_id" value="<?=SITE_ID;?>">
    <input type="hidden" name="form_name" value="<?= htmlspecialcharsBack($arInfo["NAME"]); ?>">
    <input type="hidden" name="url" value="<?=$APPLICATION->GetCurDir();?>">
    <div class="form-row-border">
        <div class="form-item">
            <div class="field"><input class="field-input" type="text" placeholder="Ваше имя" name="name"></div>
        </div>
        <div class="form-item">
            <div class="field"><input class="field-input" type="tel" placeholder="Телефон" name="phone"></div>
        </div>
        <div class="form-control"><button class="button button--light" type="submit">Отправить заявку</button></div>
    </div>
    <div class="form-item form-item--checkbox form-item--validate form-item--success"><label class="checkbox"><input type="checkbox" checked="checked" name="privacy"><span>Заполняя форму на сайте, Вы соглашаетесь с нашей <a href="<?=$urlPrivacy;?>" target="_blank">политикой</a></span></label></div>
</form>