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
<form class="form" id="contact-form">
    <input type="hidden" name="site_id" value="<?=SITE_ID;?>">
    <input type="hidden" name="form_name" value="<?= htmlspecialcharsBack($arInfo["NAME"]); ?>">
    <div class="form-row">
        <div class="form-col">
            <div class="form-item">
                <div class="field"><input class="field-input" type="text" name="name" placeholder="Имя"></div>
            </div>
            <div class="form-item">
                <div class="field"><input class="field-input" type="tel" name="phone" placeholder="Телефон"></div>
            </div>
            <div class="form-item">
                <div class="field"><input class="field-input" type="email" name="email" placeholder="Email"></div>
            </div>
        </div>
        <div class="form-col form-col--textarea">
            <div class="form-item">
                <div class="field"><textarea class="field-input" name="message" placeholder="Сообщение"></textarea></div>
            </div>
        </div>
    </div>
    <div class="form-control form-control--justify">
        <div class="form-item form-item--checkbox form-item--validate form-item--success">
            <label class="checkbox"><input type="checkbox" checked="checked" name="privacy">
                <span>Заполняя форму на сайте, Вы соглашаетесь с нашей <a href="<?=$urlPrivacy;?>" target="_blank">политикой</a></span>
            </label>
        </div>
        <button class="button button--light">Отправить заявку</button>
    </div>
</form>