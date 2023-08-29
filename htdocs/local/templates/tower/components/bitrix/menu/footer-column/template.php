<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<? if (!empty($arResult)): ?>
    <ul class="list">
        <?foreach ($arResult as $arItem):?>
            <? if ($arItem["SELECTED"]): ?>
                <li class="list-item list-item--active">
                    <span class="list-link"><?= $arItem["TEXT"] ?></span>
                </li>
            <? else: ?>
                <li class="list-item">
                    <a <?= (SITE_ID != 's1' && SITE_ID != 's1') ? 'target="_blank"' : '';?> href='<?= (SITE_ID != 's1' && SITE_ID != 's1') ? 'https://towergroup.ru' : '';?><?= $arItem["LINK"]; ?>' <?=$arItem["PARAMS"]["target"];?> class="list-link">
                        <span><?= $arItem["TEXT"] ?></span>
                    </a>
                </li>
            <? endif; ?>
        <?endforeach;?>
    </ul>
<? endif ?>