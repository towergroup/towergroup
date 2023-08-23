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
<p><?= htmlspecialcharsBack($arInfo["FOOTNOTE"]); ?></p>
<form class="form new-form-object" id="object-new-form">
    <input type="hidden" name="form_name" value="<?= htmlspecialcharsBack($arInfo["NAME"]); ?>">
    <input type="hidden" name="site_id" value="<?=SITE_ID;?>">
    <input type="hidden" name="telegram_button" value="false">
    <input type="hidden" name="whatsapp_button" value="false">
    <div class="form-row-border">
        <div class="form-item">
            <div class="field"><input class="field-input" type="text" placeholder="Ваше имя" name="name"></div>
        </div>
        <div class="form-item">
            <div class="field"><input class="field-input" type="tel" placeholder="Телефон" name="phone"></div>
        </div>
        <div class="form-control">
            <button class="button button--light button-object-social button-object-tg" type="submit" data-value="telegram">
                <span>Получить в Telegram</span>
                <svg class="icon icon--social" width="21" height="17" viewbox="0 0 21 17">
                    <use xlink:href="#telegram-button" />
                </svg>
            </button>
			<button class="button button--light button-object-social button-object-wa" type="submit" data-value="whatsapp">
                <span>Получить в WhatsApp</span>
                <svg class="icon icon--social" width="20" height="20" viewbox="0 0 20 20">
                    <use xlink:href="#whatsapp-button" />
                </svg>
			</button>
        </div>
    </div>
    <div class="form-item form-item--checkbox form-item--validate form-item--success"><label class="checkbox"><input type="checkbox" checked="checked" name="privacy"><span>Заполняя форму на сайте, Вы соглашаетесь с нашей <a href="<?=$urlPrivacy;?>" target="_blank">политикой</a></span></label></div>
</form>