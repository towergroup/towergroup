</main>
<footer class="footer">
    <div class="container">
        <div class="footer-common">
            <? $APPLICATION->IncludeComponent(
                "bitrix:menu",
                "footer",
                array(
                    "ALLOW_MULTI_SELECT" => "N",
                    "CHILD_MENU_TYPE" => "sec",
                    "DELAY" => "N",
                    "MAX_LEVEL" => "1",
                    "MENU_CACHE_GET_VARS" => array(),
                    "MENU_CACHE_TIME" => "3600",
                    "MENU_CACHE_TYPE" => "N",
                    "MENU_CACHE_USE_GROUPS" => "Y",
                    "ROOT_MENU_TYPE" => "bottom",
                    "USE_EXT" => "N",
                    "COMPONENT_TEMPLATE" => ".default",
                    "MENU_THEME" => "site"
                ),
                false
            ); ?>
            <div class="footer-common-contacts"><a class="button button--light" href="#backcall" data-modal>Заказать
                    звонок</a>
                <?
                $APPLICATION->IncludeComponent(
                    "bitrix:news.detail",
                    "contacts",
                    Array(
                        "AJAX_MODE" => "N",
                        "AJAX_OPTION_ADDITIONAL" => "",
                        "AJAX_OPTION_HISTORY" => "N",
                        "AJAX_OPTION_JUMP" => "N",
                        "AJAX_OPTION_STYLE" => "Y",
                        "BROWSER_TITLE" => "-",
                        "CACHE_GROUPS" => "N",
                        "CACHE_TIME" => "36000000",
                        "CACHE_TYPE" => "A",
                        "CHECK_DATES" => "N",
                        "DETAIL_URL" => "/index.php",
                        "DISPLAY_BOTTOM_PAGER" => "Y",
                        "DISPLAY_DATE" => "Y",
                        "DISPLAY_NAME" => "Y",
                        "DISPLAY_PICTURE" => "Y",
                        "DISPLAY_PREVIEW_TEXT" => "Y",
                        "DISPLAY_TOP_PAGER" => "N",
                        "ELEMENT_CODE" => $city ? $city : "spb",
                        "FIELD_CODE" => array(""),
                        "IBLOCK_ID" => CONTACTS_IBLOCK_ID,
                        "IBLOCK_TYPE" => "content",
                        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                        "MESSAGE_404" => "",
                        "META_DESCRIPTION" => "-",
                        "META_KEYWORDS" => "-",
                        "PAGER_BASE_LINK_ENABLE" => "N",
                        "PAGER_SHOW_ALL" => "N",
                        "PAGER_TEMPLATE" => "contacts",
                        "PAGER_TITLE" => "",
                        "PROPERTY_CODE" => array("ADDRESS", ""),
                        "SET_BROWSER_TITLE" => "N",
                        "SET_CANONICAL_URL" => "N",
                        "SET_LAST_MODIFIED" => "N",
                        "SET_META_DESCRIPTION" => "Y",
                        "SET_META_KEYWORDS" => "Y",
                        "SET_STATUS_404" => "Y",
                        "SET_TITLE" => "N",
                        "SHOW_404" => "N",
                        "USE_PERMISSIONS" => "N",
                        "USE_SHARE" => "N",
                        "PAGE_TYPE_AND_POSITION" => "DEFAULT_FOOTER"
                    )
                );
                ?>
                <div class="footer-common-contacts-social">
                    <? $APPLICATION->IncludeComponent(
                        "bitrix:news.list",
                        "socials",
                        Array(
                            "IBLOCK_ID" => SOCIALS_IBLOCK_ID,
                            "DISPLAY_PROPERTIES" => array(
                                0 => "",
                                1 => "",
                            ),
                            "PROPERTY_CODE" => array(
                                0 => "LINK",
                                1 => "SVG"
                            ),
                            "SET_TITLE" => "N",
                            "NEWS_COUNT" => "20",
                            "SORT_BY1" => "SORT",
                            "SORT_ORDER1" => "ASC",
                            "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                            "IS_FOOTER" => "Y"
                        )
                    ); ?>
                </div>
            </div>
        </div>
        <div class="footer-objects">
            <div class="footer-objects-item footer-objects-item--cols4">
                <div class="footer-objects-type" data-navigation-accordion><span>Новостройки</span>
                    <svg class="icon icon--dropdown" width="10" height="6" viewbox="0 0 10 6">
                        <use xlink:href="#dropdown"/>
                    </svg>
                </div>
                <div class="footer-objects-navigation">
                    <div class="footer-objects-column">
                        <div class="footer-objects-column-item">
                            <div class="footer-objects-column-title">Тип недвижимости</div>
                            <? $APPLICATION->IncludeComponent(
                                "bitrix:menu",
                                "footer-column",
                                array(
                                    "ALLOW_MULTI_SELECT" => "N",
                                    "CHILD_MENU_TYPE" => "sec",
                                    "DELAY" => "N",
                                    "MAX_LEVEL" => "1",
                                    "MENU_CACHE_GET_VARS" => array(),
                                    "MENU_CACHE_TIME" => "3600",
                                    "MENU_CACHE_TYPE" => "N",
                                    "MENU_CACHE_USE_GROUPS" => "Y",
                                    "ROOT_MENU_TYPE" => "bottom_column_1",
                                    "USE_EXT" => "N",
                                    "COMPONENT_TEMPLATE" => ".default",
                                    "MENU_THEME" => "site"
                                ),
                                false
                            ); ?>
                        </div>
                        <div class="footer-objects-column-item">
                            <div class="footer-objects-column-title"><?= $city == "spb" ? "Районы" : "Цена" ?></div>
                            <? $APPLICATION->IncludeComponent(
                                "bitrix:menu",
                                "footer-column",
                                array(
                                    "ALLOW_MULTI_SELECT" => "N",
                                    "CHILD_MENU_TYPE" => "sec",
                                    "DELAY" => "N",
                                    "MAX_LEVEL" => "1",
                                    "MENU_CACHE_GET_VARS" => array(),
                                    "MENU_CACHE_TIME" => "3600",
                                    "MENU_CACHE_TYPE" => "N",
                                    "MENU_CACHE_USE_GROUPS" => "Y",
                                    "ROOT_MENU_TYPE" => "bottom_column_2",
                                    "USE_EXT" => "N",
                                    "COMPONENT_TEMPLATE" => ".default",
                                    "MENU_THEME" => "site"
                                ),
                                false
                            ); ?>
                        </div>
                        <div class="footer-objects-column-item">
                            <div class="footer-objects-column-title">Комнатность</div>
                            <? $APPLICATION->IncludeComponent(
                                "bitrix:menu",
                                "footer-column",
                                array(
                                    "ALLOW_MULTI_SELECT" => "N",
                                    "CHILD_MENU_TYPE" => "sec",
                                    "DELAY" => "N",
                                    "MAX_LEVEL" => "1",
                                    "MENU_CACHE_GET_VARS" => array(),
                                    "MENU_CACHE_TIME" => "3600",
                                    "MENU_CACHE_TYPE" => "N",
                                    "MENU_CACHE_USE_GROUPS" => "Y",
                                    "ROOT_MENU_TYPE" => "bottom_column_3",
                                    "USE_EXT" => "N",
                                    "COMPONENT_TEMPLATE" => ".default",
                                    "MENU_THEME" => "site"
                                ),
                                false
                            ); ?>
                        </div>
                        <div class="footer-objects-column-item">
                            <div class="footer-objects-column-title">Особенные&nbsp;квартиры</div>
                            <? $APPLICATION->IncludeComponent(
                                "bitrix:menu",
                                "footer-column",
                                array(
                                    "ALLOW_MULTI_SELECT" => "N",
                                    "CHILD_MENU_TYPE" => "sec",
                                    "DELAY" => "N",
                                    "MAX_LEVEL" => "1",
                                    "MENU_CACHE_GET_VARS" => array(),
                                    "MENU_CACHE_TIME" => "3600",
                                    "MENU_CACHE_TYPE" => "N",
                                    "MENU_CACHE_USE_GROUPS" => "Y",
                                    "ROOT_MENU_TYPE" => "bottom_column_4",
                                    "USE_EXT" => "N",
                                    "COMPONENT_TEMPLATE" => ".default",
                                    "MENU_THEME" => "site"
                                ),
                                false
                            ); ?>
                        </div>
                        <div class="footer-objects-column-item">
                            <div class="footer-objects-column-title">Готовность</div>
                            <? $APPLICATION->IncludeComponent(
                                "bitrix:menu",
                                "footer-column",
                                array(
                                    "ALLOW_MULTI_SELECT" => "N",
                                    "CHILD_MENU_TYPE" => "sec",
                                    "DELAY" => "N",
                                    "MAX_LEVEL" => "1",
                                    "MENU_CACHE_GET_VARS" => array(),
                                    "MENU_CACHE_TIME" => "3600",
                                    "MENU_CACHE_TYPE" => "N",
                                    "MENU_CACHE_USE_GROUPS" => "Y",
                                    "ROOT_MENU_TYPE" => "bottom_column_5",
                                    "USE_EXT" => "N",
                                    "COMPONENT_TEMPLATE" => ".default",
                                    "MENU_THEME" => "site"
                                ),
                                false
                            ); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-objects-item">
                <div class="footer-objects-type" data-navigation-accordion><span>Вторичная</span>
                    <svg class="icon icon--dropdown" width="10" height="6" viewbox="0 0 10 6">
                        <use xlink:href="#dropdown"/>
                    </svg>
                </div>
                <div class="footer-objects-navigation">
                    <? $APPLICATION->IncludeComponent(
                        "bitrix:menu",
                        "footer-column",
                        array(
                            "ALLOW_MULTI_SELECT" => "N",
                            "CHILD_MENU_TYPE" => "sec",
                            "DELAY" => "N",
                            "MAX_LEVEL" => "1",
                            "MENU_CACHE_GET_VARS" => array(),
                            "MENU_CACHE_TIME" => "3600",
                            "MENU_CACHE_TYPE" => "N",
                            "MENU_CACHE_USE_GROUPS" => "Y",
                            "ROOT_MENU_TYPE" => "bottom_column_6",
                            "USE_EXT" => "N",
                            "COMPONENT_TEMPLATE" => ".default",
                            "MENU_THEME" => "site"
                        ),
                        false
                    ); ?>
                </div>
            </div>
            <div class="footer-objects-item">
                <div class="footer-objects-type" data-navigation-accordion><span>Загородная</span>
                    <svg class="icon icon--dropdown" width="10" height="6" viewbox="0 0 10 6">
                        <use xlink:href="#dropdown"/>
                    </svg>
                </div>
                <div class="footer-objects-navigation">
                    <? $APPLICATION->IncludeComponent(
                        "bitrix:menu",
                        "footer-column",
                        array(
                            "ALLOW_MULTI_SELECT" => "N",
                            "CHILD_MENU_TYPE" => "sec",
                            "DELAY" => "N",
                            "MAX_LEVEL" => "1",
                            "MENU_CACHE_GET_VARS" => array(),
                            "MENU_CACHE_TIME" => "3600",
                            "MENU_CACHE_TYPE" => "N",
                            "MENU_CACHE_USE_GROUPS" => "Y",
                            "ROOT_MENU_TYPE" => "bottom_column_7",
                            "USE_EXT" => "N",
                            "COMPONENT_TEMPLATE" => ".default",
                            "MENU_THEME" => "site"
                        ),
                        false
                    ); ?>
                </div>
            </div>
            <div class="footer-objects-item footer-objects-item--cols2">
                <div class="footer-objects-type" data-navigation-accordion><span>Зарубежная</span>
                    <svg class="icon icon--dropdown" width="10" height="6" viewbox="0 0 10 6">
                        <use xlink:href="#dropdown"/>
                    </svg>
                </div>
                <div class="footer-objects-navigation">
                    <div class="footer-objects-countries">
                        <? $APPLICATION->IncludeComponent(
                            "bitrix:menu",
                            "footer-column",
                            array(
                                "ALLOW_MULTI_SELECT" => "N",
                                "CHILD_MENU_TYPE" => "sec",
                                "DELAY" => "N",
                                "MAX_LEVEL" => "1",
                                "MENU_CACHE_GET_VARS" => array(),
                                "MENU_CACHE_TIME" => "3600",
                                "MENU_CACHE_TYPE" => "N",
                                "MENU_CACHE_USE_GROUPS" => "Y",
                                "ROOT_MENU_TYPE" => "bottom_column_8",
                                "USE_EXT" => "N",
                                "COMPONENT_TEMPLATE" => ".default",
                                "MENU_THEME" => "site"
                            ),
                            false
                        ); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <?
            $APPLICATION->IncludeComponent(
                "bitrix:news.detail",
                "footer",
                Array(
                    "AJAX_MODE" => "N",
                    "AJAX_OPTION_ADDITIONAL" => "",
                    "AJAX_OPTION_HISTORY" => "N",
                    "AJAX_OPTION_JUMP" => "N",
                    "AJAX_OPTION_STYLE" => "Y",
                    "BROWSER_TITLE" => "-",
                    "CACHE_GROUPS" => "N",
                    "CACHE_TIME" => "36000000",
                    "CACHE_TYPE" => "A",
                    "CHECK_DATES" => "N",
                    "DETAIL_URL" => "/index.php",
                    "DISPLAY_BOTTOM_PAGER" => "Y",
                    "DISPLAY_DATE" => "Y",
                    "DISPLAY_NAME" => "Y",
                    "DISPLAY_PICTURE" => "Y",
                    "DISPLAY_PREVIEW_TEXT" => "Y",
                    "DISPLAY_TOP_PAGER" => "N",
                    "ELEMENT_CODE" => $city ? "footer-" . $city : "footer-spb",
                    "FIELD_CODE" => array("PREVIEW_TEXT", ""),
                    "IBLOCK_ID" => FOOTER_IBLOCK_ID,
                    "IBLOCK_TYPE" => "plug_in_areas",
                    "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                    "MESSAGE_404" => "",
                    "META_DESCRIPTION" => "-",
                    "META_KEYWORDS" => "-",
                    "PAGER_BASE_LINK_ENABLE" => "N",
                    "PAGER_SHOW_ALL" => "N",
                    "PAGER_TEMPLATE" => "contacts",
                    "PAGER_TITLE" => "",
                    "PROPERTY_CODE" => array("DEVELOP_LINK", ""),
                    "SET_BROWSER_TITLE" => "N",
                    "SET_CANONICAL_URL" => "N",
                    "SET_LAST_MODIFIED" => "N",
                    "SET_META_DESCRIPTION" => "Y",
                    "SET_META_KEYWORDS" => "Y",
                    "SET_STATUS_404" => "Y",
                    "SET_TITLE" => "N",
                    "SHOW_404" => "N",
                    "USE_PERMISSIONS" => "N",
                    "USE_SHARE" => "N"
                )
            );
            ?>
        </div>
    </div>
</footer>
</div>
<? $APPLICATION->IncludeComponent(
    "bitrix:news.list",
    "svg",
    Array(
        "IBLOCK_ID" => SVG_IBLOCK_ID,
        "DISPLAY_PROPERTIES" => array(
            0 => "",
            1 => "",
        ),
        "PROPERTY_CODE" => array(
            0 => "VIEWBOX",
            1 => "PATH"
        ),
        "SET_TITLE" => "N",
        "NEWS_COUNT" => "100",
        "SORT_BY1" => "SORT",
        "SORT_ORDER1" => "ASC",
        "INCLUDE_IBLOCK_INTO_CHAIN" => "N"
    )
); ?>

<? $arLogo = getLogo(SITE_ID);
//xprint($arLogo);
if(!empty($arLogo)) :?>
    <svg xmlns="http://www.w3.org/2000/svg"
         style="width: 0; height: 0; overflow: hidden; position: absolute; visibility: hidden;">
        <symbol id="logotype" viewbox="<?= $arLogo['PROPERTY_VIEWBOX_VALUE'] ?>">
            <?= $arLogo['~PROPERTY_PATH_VALUE']['TEXT'] ?>
        </symbol>
    </svg>
<?endif;?>
<input type="hidden" name="recaptcha_response" id="recaptchaResponse">
<div class="modal" id="backcall">
    <div class="modal-container">
        <button class="modal-close" data-modal-close>
            <svg class="icon icon--cross-light-large" width="32" height="32" viewbox="0 0 32 32">
                <use xlink:href="#cross-light-large"/>
            </svg>
        </button>
        <? $APPLICATION->IncludeComponent(
            "bitrix:main.include",
            "",
            Array(
                "AREA_FILE_SHOW" => "file",
                "PATH" => "/local/include/forms/form_backcall.php",
                "EDIT_TEMPLATE" => "",
                "FORM_CODE" => "backcall-form"
            ),
            false
        ); ?>
    </div>
</div>

<div class="modal" id="backcall-secondary">
    <div class="modal-container">
        <button class="modal-close" data-modal-close>
            <svg class="icon icon--cross-light-large" width="32" height="32" viewbox="0 0 32 32">
                <use xlink:href="#cross-light-large"/>
            </svg>
        </button>
        <? $APPLICATION->IncludeComponent(
            "bitrix:main.include",
            "",
            Array(
                "AREA_FILE_SHOW" => "file",
                "PATH" => "/local/include/forms/form_backcall_secondary.php",
                "EDIT_TEMPLATE" => "",
                "FORM_CODE" => "backcall-form"
            ),
            false
        ); ?>
    </div>
</div>
<? $APPLICATION->IncludeComponent(
    "bitrix:main.include",
    "",
    Array(
        "AREA_FILE_SHOW" => "file",
        "PATH" => "/local/include/forms/form_success.php",
        "EDIT_TEMPLATE" => "",
        "FORM_CODE" => "success-form"
    ),
    false
); ?>
<div class="modal" id="feedback">
    <div class="modal-container">
        <button class="modal-close" data-modal-close="data-modal-close">
            <svg class="icon icon--cross-light-large" width="32" height="32" viewbox="0 0 32 32">
                <use xlink:href="#cross-light-large"/>
            </svg>
        </button>
        <? $APPLICATION->IncludeComponent(
            "bitrix:main.include",
            "",
            Array(
                "AREA_FILE_SHOW" => "file",
                "PATH" => "/local/include/forms/form_feedback.php",
                "EDIT_TEMPLATE" => "",
                "FORM_CODE" => "feedback-form"
            ),
            false
        ); ?>
    </div>
</div>
<? if ($APPLICATION->GetDirProperty("page") === "reviews") : ?>
    <div class="modal modal--review" id="review-item">
        <div class="modal-container">
            <button class="modal-close" data-modal-close>
                <svg class="icon icon--cross-light-large" width="32" height="32" viewbox="0 0 32 32">
                    <use xlink:href="#cross-light-large"/>
                </svg>
            </button>
            <div class="modal-review" data-modal-review></div>
        </div>
    </div>
<? endif; ?>
<? if ($APPLICATION->GetDirProperty("page") === "reviews") : ?>
    <div class="modal" id="review">
        <div class="modal-container">
            <button class="modal-close" data-modal-close="data-modal-close">
                <svg class="icon icon--cross-light-large" width="32" height="32" viewbox="0 0 32 32">
                    <use xlink:href="#cross-light-large"/>
                </svg>
            </button>
            <? $APPLICATION->IncludeComponent(
                "bitrix:main.include",
                "",
                Array(
                    "AREA_FILE_SHOW" => "file",
                    "PATH" => "/local/include/forms/form_review.php",
                    "EDIT_TEMPLATE" => "",
                    "FORM_CODE" => "review-form"
                ),
                false
            ); ?>
        </div>
    </div>
<? endif; ?>
<? if (
    $APPLICATION->GetDirProperty("page") === "about"
    ||
    $APPLICATION->GetPageProperty("page") === "catalog_new_build"
    ||
    $APPLICATION->GetPageProperty("page") === "catalog_resale"
    ||
    $APPLICATION->GetPageProperty("page") === "catalog_country"
    ||
    $APPLICATION->GetPageProperty("page") === "catalog_overseas"
) : ?>
    <div class="modal modal--broker" id="broker">
        <div class="modal-container">
            <button class="modal-close" data-modal-close>
                <svg class="icon icon--cross-light-large" width="32" height="32" viewbox="0 0 32 32">
                    <use xlink:href="#cross-light-large"/>
                </svg>
            </button>
            <div class="modal-broker">
                <? $APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "",
                    Array(
                        "AREA_FILE_SHOW" => "file",
                        "PATH" => "/local/include/forms/form_broker.php",
                        "EDIT_TEMPLATE" => "",
                        "FORM_CODE" => "broker-form"
                    ),
                    false
                ); ?>
            </div>
        </div>
    </div>
<? elseif (
    $APPLICATION->GetPageProperty("page") === "detail_new_build"
    ||
    $APPLICATION->GetPageProperty("page") === "landing"
    ||
    $APPLICATION->GetPageProperty("page") === "detail_resale"
    ||
    $APPLICATION->GetPageProperty("page") === "detail_country"
    ||
    $APPLICATION->GetPageProperty("page") === "detail_overseas"
) : ?>
    <div class="modal modal--broker" id="broker">
        <div class="modal-container">
            <button class="modal-close" data-modal-close>
                <svg class="icon icon--cross-light-large" width="32" height="32" viewbox="0 0 32 32">
                    <use xlink:href="#cross-light-large"/>
                </svg>
            </button>
            <div class="modal-broker">
                <? $APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "",
                    Array(
                        "AREA_FILE_SHOW" => "file",
                        "PATH" => "/local/include/forms/form_viewing.php",
                        "EDIT_TEMPLATE" => "",
                        "FORM_CODE" => "viewing-form"
                    ),
                    false
                ); ?>
            </div>
        </div>
    </div>
    <div class="modal modal--broker" id="excursion">
        <div class="modal-container">
            <button class="modal-close" data-modal-close>
                <svg class="icon icon--cross-light-large" width="32" height="32" viewbox="0 0 32 32">
                    <use xlink:href="#cross-light-large"/>
                </svg>
            </button>
            <div class="modal-broker">
                <? $APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "",
                    Array(
                        "AREA_FILE_SHOW" => "file",
                        "PATH" => "/local/include/forms/form_excursion.php",
                        "EDIT_TEMPLATE" => "",
                        "FORM_CODE" => "excursion-form"
                    ),
                    false
                ); ?>
            </div>
        </div>
    </div>
<? endif; ?>
<? if ($APPLICATION->GetPageProperty("page") === "landing") : ?>
    <? $APPLICATION->IncludeComponent(
        "bitrix:main.include",
        "",
        Array(
            "AREA_FILE_SHOW" => "file",
            "PATH" => "/local/include/forms/form_landing_backcall.php",
            "EDIT_TEMPLATE" => "",
            "FORM_CODE" => "landing-backcall"
        ),
        false
    ); ?>
<?endif;?>
<div class="modal" id="contact-with-us">
    <div class="modal-container">
        <button class="modal-close" data-modal-close>
            <svg class="icon icon--cross-light-large" width="32" height="32" viewbox="0 0 32 32">
                <use xlink:href="#cross-light-large"/>
            </svg>
        </button>
        <? $APPLICATION->IncludeComponent(
            "bitrix:main.include",
            "",
            Array(
                "AREA_FILE_SHOW" => "file",
                "PATH" => "/local/include/forms/form_contact_with_us.php",
                "FORM_CODE" => "contact-with-us-form",
                "EDIT_TEMPLATE" => ""
            ),
            false
        ); ?>
    </div>
</div>
<div class="modal modal--quiz" id="request-quiz">
    <div class="modal-container">
        <button class="modal-close" data-modal-close>
            <svg class="icon icon--cross-light-large" width="32" height="32" viewbox="0 0 32 32">
                <use xlink:href="#cross-light-large"/>
            </svg>
        </button>
        <div class="quiz">
            <? $APPLICATION->IncludeComponent(
                "bitrix:main.include",
                "",
                Array(
                    "AREA_FILE_SHOW" => "file",
                    "PATH" => "/local/include/forms/form_quiz_request.php",
                    "EDIT_TEMPLATE" => "",
                    "FORM_CODE" => "quiz-request-form"
                ),
                false
            ); ?>
        </div>
    </div>
</div>
<!--<div class="loader">
    <div class="loader-icon"></div>
</div>-->
<?
if (IS_INDEX_PAGE && (SITE_ID == 's1' || SITE_ID == 's2')) :
    if ($_GET['city'] == 'no') {
        setcookie("CITY_POPUP", 'Y', 0, "/");
        $_COOKIE['CITY_POPUP'] = 'Y';
    } else {
        setcookie("CITY_POPUP", 'N', 0, "/");
    }
    if (empty($_COOKIE['CITY_POPUP'])):
        $dir = $APPLICATION->GetCurDir();
        $dir = explode("/", $dir);
        if (count($dir) > 3) {
            if ($dir[2] == 'about' && count($dir) > 4) {
                $url = $dir[2] . '/' . $dir[3];
            } else {
                $url = $dir[2];
            }
        } ?>
        <div class="location-load" data-location-load>
            <div class="container container--wide">
                <div class="location-load-container">
                    <div class="location-load-label">Ваш город <?= SITE_ID == "s1" ? 'Москва' : 'Санкт-Петербург' ?>?
                    </div>
                    <div class="location-load-controls"><a class="button button--inverse"
                                                           href="<?= SITE_ID == "s2" ? '/moskva' : '/spb' ?>/<?= $url ? $url . '/?city=no' : '?city=no'; ?>">Нет, <?= SITE_ID == "s2" ? 'Москва' : 'Санкт-Петербург' ?></a><a
                            class="button button--light" data-location-load-close href="">Да</a></div>
                </div>
            </div>
        </div>
    <? endif; ?>
<? endif; ?>
<? $APPLICATION->IncludeComponent(
    "bitrix:news.list",
    "employees_main",
    array(
        "IBLOCK_ID" => EMPLOYEES_IBLOCK_ID,
        "LANG" => LANGUAGE_ID,
        "DISPLAY_PICTURE" => "Y",
        "DISPLAY_PROPERTIES" => array(
            0 => "PREVIEW_PICTURE",
            1 => "ICON",
            2 => ""
        ),
        "PROPERTY_CODE" => array(
            0 => "DEPARTMENT",
            1 => "ICON",
            2 => "",
        ),
        "NEWS_COUNT" => "1",
        "SET_TITLE" => "N",
        "SORT_BY1" => "RAND",
        "SORT_ORDER1" => "ASC",
        "SORT_BY2" => "SORT",
        "SORT_ORDER2" => "ASC",
        "FILTER_NAME" => "filter_employees",
        "FIELD_CODE" => array(
            0 => "PREVIEW_PICTURE",
            1 => "ICON",
            2 => "",
        ),
        "CHECK_DATES" => "Y",
        "DETAIL_URL" => "",
        "AJAX_MODE" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_ADDITIONAL" => "",
        "CACHE_TYPE" => "N",
        "CACHE_TIME" => "36000000",
        "CACHE_FILTER" => "N",
        "CACHE_GROUPS" => "Y",
        "PREVIEW_TRUNCATE_LEN" => "",
        "ACTIVE_DATE_FORMAT" => "d.m.Y",
        "SET_BROWSER_TITLE" => "N",
        "SET_META_KEYWORDS" => "N",
        "SET_META_DESCRIPTION" => "N",
        "SET_LAST_MODIFIED" => "N",
        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
        "ADD_SECTIONS_CHAIN" => "N",
        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
        "PARENT_SECTION" => "",
        "PARENT_SECTION_CODE" => "",
        "INCLUDE_SUBSECTIONS" => "Y",
        "STRICT_SECTION_CHECK" => "N",
        "PAGER_TEMPLATE" => ".default",
        "DISPLAY_TOP_PAGER" => "N",
        "DISPLAY_BOTTOM_PAGER" => "Y",
        "PAGER_TITLE" => "Новости",
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_DESC_NUMBERING" => "N",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
        "PAGER_SHOW_ALL" => "N",
        "PAGER_BASE_LINK_ENABLE" => "N",
        "SET_STATUS_404" => "N",
        "SHOW_404" => "N",
        "MESSAGE_404" => "",
        "PHONE_VALUE" => $phoneValue,
    ),
    false
); ?>
<?
if (IS_INDEX_PAGE) {
    $jsPage = "index.min.js";
}

switch ($APPLICATION->GetDirProperty("page")) {
    case about :
        $jsPage = "about.min.js";
        break;
    case vacancies :
        $jsPage = "vacancy.min.js";
        break;
    case news :
        $jsPage = "news.min.js";
        break;
    case catalog_new_build :
        $jsPage = "catalog_new_build.min.js";
        break;
    case catalog_resale :
        $jsPage = "catalog_resale.min.js";
        break;
    case catalog_country :
        $jsPage = "catalog_country.min.js";
        break;
    case catalog_overseas :
        $jsPage = "catalog_overseas.min.js";
        break;
    case detail_new_build :
        $jsPage = "detail_new_build.min.js";
        break;
    case detail_resale :
        $jsPage = "detail_resale.min.js";
        break;
    case detail_country :
        $jsPage = "detail_country.min.js";
        break;
    case detail_overseas :
        $jsPage = "detail_overseas.min.js";
        break;
}
switch ($APPLICATION->GetPageProperty("page")) {
    case catalog_new_build :
        $jsPage = "catalog_new_build.min.js";
        break;
    case catalog_resale :
        $jsPage = "catalog_resale.min.js";
        break;
    case catalog_country :
        $jsPage = "catalog_country.min.js";
        break;
    case catalog_overseas :
        $jsPage = "catalog_overseas.min.js";
        break;
    case detail_new_build :
        $jsPage = "detail_new_build.min.js";
        break;
    case detail_resale :
        $jsPage = "detail_resale.min.js";
        break;
    case detail_country :
        $jsPage = "detail_country.min.js";
        break;
    case detail_overseas :
        $jsPage = "detail_overseas.min.js";
        break;
    case landing :
        $jsPage = "landing.min.js";
        break;
    case reviews :
        $jsPage = "reviews.min.js";
        break;
    case reviews_video :
        $jsPage = "reviews_video.min.js";
        break;
}
?>
<script defer src="<?= SITE_TEMPLATE_PATH . '/js/app.min.js' ?>"></script>
<script defer src="<?= SITE_TEMPLATE_PATH . '/js/common.min.js' ?>"></script>
<?= ($APPLICATION->GetPageProperty("page") === "catalog_new_build" || $APPLICATION->GetPageProperty("page") === "catalog_resale" || $APPLICATION->GetPageProperty("page") === "catalog_country" || $APPLICATION->GetPageProperty("page") === "catalog_overseas")
    ?
    '<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU"></script>'
    :
    null;
?>
<? if (!empty($jsPage)): ?>
    <? $jsFile = SITE_TEMPLATE_PATH . '/js/' . $jsPage; ?>
    <script defer src="<?= $jsFile; ?>?v=2132"></script>
<? endif; ?>
<script src="<?= SITE_TEMPLATE_PATH; ?>/js/form.js?v=<?= filemtime($_SERVER["DOCUMENT_ROOT"].'/js/form.js')?>"></script>
<script src="<?= SITE_TEMPLATE_PATH; ?>/js/validation-form.js"></script>
<script>
    <?
    $dir = 'header';
    $resQuiz = [];
    $arFilter = ['ACTIVE' => 'Y', 'IBLOCK_ID' => QUIZ_IBLOCK_ID, 'DEPTH_LEVEL' => 1, 'UF_LINK' => $dir];
    $arSelect = ['ID', 'NAME', 'DEPTH_LEVEL'];
    $rsSect = CIBlockSection::GetList(false, $arFilter, false, $arSelect)->Fetch();
    if (!empty($rsSect['ID'])) {
        $arFilter = ['ACTIVE' => 'Y', 'IBLOCK_ID' => QUIZ_IBLOCK_ID, 'DEPTH_LEVEL' => 2, 'SECTION_ID' => $rsSect['ID']];
        $arSelect = ['ID', 'NAME'];
        $rsQuestions = CIBlockSection::GetList(['SORT' => 'ASC'], $arFilter, false, $arSelect);
        while ($arQuestion = $rsQuestions->GetNext()) {
            $arQuiz = [];
            $arQuiz['question'] = $arQuestion['NAME'];

            $rsAnswers = CIBlockElement::GetList(
                ['SORT' => 'ASC'],
                [
                    'ACTIVE' => 'Y',
                    'IBLOCK_ID' => QUIZ_IBLOCK_ID,
                    'SECTION_ID' => $arQuestion['ID'],
                ],
                false,
                false,
                [
                    'ID',
                    'IBLOCK_SECTION_ID',
                    'NAME',
                    'CODE',
                ]
            );
            while ($arAnswer = $rsAnswers->Fetch()) {
                $arQuiz['answers'][] = $arAnswer;
            }
            $resQuiz[] = $arQuiz;
        }
    }
    ?>


    window.requestQuiz = [
        <?foreach ($resQuiz as $quiz):?>
        {
            question: '<?= $quiz['question']; ?>',
            answers: [
                <?foreach ($quiz['answers'] as $answer):?>
                {
                    id: '<?= $answer['ID'];?>',
                    title: '<?= $answer['NAME'];?>'
                },
                <?endforeach;?>
            ]
        },
        <?endforeach;?>
    ]
</script>
<?
$dir = $APPLICATION->GetCurDir();
$resQuiz = [];
$arFilter = ['ACTIVE' => 'Y', 'IBLOCK_ID' => QUIZ_IBLOCK_ID, 'DEPTH_LEVEL' => 1, 'UF_LINK' => $dir];
$arSelect = ['ID', 'NAME', 'DEPTH_LEVEL'];
$rsSect = CIBlockSection::GetList(false, $arFilter, false, $arSelect)->Fetch();
if (!empty($rsSect['ID'])) {
    $arFilter = ['ACTIVE' => 'Y', 'IBLOCK_ID' => QUIZ_IBLOCK_ID, 'DEPTH_LEVEL' => 2, 'SECTION_ID' => $rsSect['ID']];
    $arSelect = ['ID', 'NAME'];
    $rsQuestions = CIBlockSection::GetList(['SORT' => 'ASC'], $arFilter, false, $arSelect);
    while ($arQuestion = $rsQuestions->GetNext()) {
        $arQuiz = [];
        $arQuiz['question'] = $arQuestion['NAME'];

        $rsAnswers = CIBlockElement::GetList(
            ['SORT' => 'ASC'],
            [
                'ACTIVE' => 'Y',
                'IBLOCK_ID' => QUIZ_IBLOCK_ID,
                'SECTION_ID' => $arQuestion['ID'],
            ],
            false,
            false,
            [
                'ID',
                'IBLOCK_SECTION_ID',
                'NAME',
                'CODE',
            ]
        );
        while ($arAnswer = $rsAnswers->Fetch()) {
            $arQuiz['answers'][] = $arAnswer;
        }
        $resQuiz[] = $arQuiz;
    }
}
?>
<script>
    window.quiz = [
        <?foreach ($resQuiz as $quiz):?>
        {
            question: '<?= $quiz['question']; ?>',
            answers: [
                <?foreach ($quiz['answers'] as $answer):?>
                {
                    id: '<?= $answer['ID'];?>',
                    title: '<?= $answer['NAME'];?>'
                },
                <?endforeach;?>
            ]
        },
        <?endforeach;?>
    ]
</script>
<script>
    $('.dropdown-values .list-item').on('click', function () {
        var link = $(this).data('link');
        $('.main-screen-link').attr('href', link);
    });
</script>

<? if(!Helper::isPageSpeed()): ?>
<!-- BEGIN JIVOSITE INTEGRATION WITH ROISTAT -->
<script>
    (function(w, d, s, h) {
        w.roistatWithJivoSiteIntegrationWebHook = 'https://cloud.roistat.com/integration/webhook?key=58099200f5c752ec68464e03ed86e072';
        var p = d.location.protocol == "https:" ? "https://" : "http://";
        var u = "/static/marketplace/JivoSite/script.js";
        var js = d.createElement(s); js.async = 1; js.src = p+h+u; var js2 = d.getElementsByTagName(s)[0]; js2.parentNode.insertBefore(js, js2);
    })(window, document, 'script', 'cloud.roistat.com');
</script>
<!-- END JIVOSITE INTEGRATION WITH ROISTAT -->
<script>
    (function (w, d, s, h, id) {
        w.roistatProjectId = id;
        w.roistatHost = h;
        var p = d.location.protocol == "https:" ? "https://" : "http://";
        var u = /^.*roistat_visit=[^;]+(.*)?$/.test(d.cookie) ? "/dist/module.js" : "/api/site/1.0/" + id + "/init?referrer=" + encodeURIComponent(d.location.href);
        var js = d.createElement(s);
        js.charset = "UTF-8";
        js.async = 1;
        js.src = p + h + u;
        var js2 = d.getElementsByTagName(s)[0];
        js2.parentNode.insertBefore(js, js2);
    })(window, document, 'script', 'cloud.roistat.com', 'bbfbea29fe2e824b49470720545b1387');
</script>
<!-- /Pixel -->
<? endif; ?>

<?php $curPage = CHTTP::URN2URI($APPLICATION->GetCurPage(false)); ?>

</body>
</html>