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
<div class="detail-excursion">
    <div class="detail-excursion-title">
        <div><?= htmlspecialcharsBack(str_replace("#OBJECT_NAME#",$arParams["OBJECT"]['object'],$arInfo["TITLE"])); ?></div>
    </div>
    <div class="detail-excursion-form">
        <span class="detail-excursion-form-title">
            <?= htmlspecialcharsBack($arInfo["FOOTNOTE"]); ?>
        </span>
        <form class="form" id="detail-excursion">
            <input type="hidden" name="site_id" value="<?= SITE_ID; ?>">
            <input type="hidden" name="tipObject"
                   value='<?= json_encode($arParams["OBJECT"], JSON_UNESCAPED_UNICODE) ?>'>
            <input type="hidden" name="form_name" value="Записаться на экскурсию">
            <div class="form-item">
                <div class="field"><input class="field-input" type="text" name="name" value placeholder="Имя"></div>
            </div>
            <div class="form-item">
                <div class="field"><input class="field-input" type="tel" name="phone" value placeholder="Телефон"></div>
            </div>
            <div class="form-control"><button class="button button--light">Оставить заявку</button></div>
        </form>
    </div>
</div>