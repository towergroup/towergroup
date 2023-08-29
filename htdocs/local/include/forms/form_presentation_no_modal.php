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
<div class="detail-presentation">
    <div class="detail-presentation-form">
        <div class="h1"><?= htmlspecialcharsBack($arParams["TITLE"]); ?></div>
        <span class="detail-presentation-footnote">Укажите свои контакты и скачайте презентацию комплекса в один клик</span>
        <form class="form" id="detail-presentation">
            <input type="hidden" name="pdf" value="">
            <input type="hidden" name="site_id" value="<?= SITE_ID; ?>">
            <input type="hidden" name="tipObject"
                   value='<?= json_encode($arParams["OBJECT"], JSON_UNESCAPED_UNICODE) ?>'>
            <input type="hidden" name="form_name" value="Скачать презентацию проекта">
            <div class="form-item">
                <div class="field"><input class="field-input" type="text" name="name" value
                                          placeholder="Имя"></div>
            </div>
            <div class="form-item">
                <div class="field"><input class="field-input" type="tel" name="phone" value
                                          placeholder="Телефон"></div>
            </div>
            <div class="form-control">
                <button class="button button--light">Скачать презентацию</button>
            </div>
            <div class="form-item form-item--checkbox">
                <label class="checkbox">
                    <input type="checkbox" checked name="privacy">
                    <span>Заполняя форму на сайте, Вы соглашаетесь с нашей <a href="<?=$urlPrivacy;?>" target="_blank">политикой</a></span>
                </label>
            </div>
        </form>
    </div>
    <div class="detail-presentation-icon">
        <div><img class="lazy" data-src="<?= SITE_TEMPLATE_PATH ?>/img/catalog/presentation.png" alt></div>
        <span>PDF-презентация жилого комплекса «<?= $arParams["OBJECT"]["object"]; ?>»</span>
    </div>
</div>