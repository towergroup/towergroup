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
<div class="quiz-content">
    <div class="quiz-headnote">
        <div><?= htmlspecialcharsBack($arInfo["FOOTNOTE"]); ?></div>
        <div><span data-quiz-current="data-quiz-current"></span> из <span data-quiz-total="data-quiz-total"></span> вопросов</div>
    </div>
    <div class="quiz-list" data-quiz-list="data-quiz-list">
        <div class="div-title div-title-h4" data-quiz-question="data-quiz-question"></div><span class="quiz-footnote">Выберите вариант ответа</span>
        <div class="quiz-answers" data-quiz-answers="data-quiz-answers"></div>
        <div class="quiz-control">
            <button class="button button--inverse" data-quiz-prev="data-quiz-prev" disabled="disabled">Назад</button>
            <button class="button button--light" disabled="disabled" data-quiz-next="data-quiz-next">Дальше</button>
        </div>
    </div>
    <div class="quiz-form" data-quiz-form="data-quiz-form">
        <div class="div-title div-title-h4">Остался последний шаг.</div><span class="quiz-footnote">Укажите Ваши контактные данные</span>
        <form class="form" id="request-quiz-form">
            <input type="hidden" name="site_id" value="<?=SITE_ID;?>">
            <input type="hidden" name="form_name" value="<?= htmlspecialcharsBack($arInfo["NAME"]); ?>">
            <div class="form-item">
                <div class="field"><input class="field-input" type="text" name="name" value placeholder="Имя"></div>
            </div>
            <div class="form-item">
                <div class="field"><input class="field-input" type="tel" name="phone" value placeholder="Телефон"></div>
            </div>
            <div class="form-control"><button class="button button--light">Отправить</button></div>
            <div class="form-item form-item--checkbox form-unlock form-item--validate form-item--success"><label class="checkbox"><input type="checkbox" checked="checked" name="privacy"><span>Заполняя форму на сайте, Вы соглашаетесь с нашей <a href="<?=$urlPrivacy;?>" target="_blank">политикой</a></span></label></div>
        </form>
    </div>
</div>
<div class="quiz-icon">
    <div><img class="lazy" data-src="<?= SITE_TEMPLATE_PATH ?>/img/catalog/catalog.png" alt></div>
</div>