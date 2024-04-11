<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Page\Asset;

if (SITE_ID == "s2") {
    define("IS_INDEX_PAGE", CSite::InDir('/index.php'));
    $city = "spb";
    setcookie("city", $city, 0, "/");
    $_COOKIE['city'] = $city;
} else {
    define("IS_INDEX_PAGE", CSite::InDir('/moskva/index.php'));
    $city = "moskva";
    setcookie("city", $city, 0, "/");
    $_COOKIE['city'] = $city;
}

if (!empty($_COOKIE['city']) && $_COOKIE['city'] !== 'deleted') {
    $city = $_COOKIE['city'];
}

define("IS_404_PAGE", defined("ERROR_404"));

if (IS_INDEX_PAGE || CSite::InDir('/moskva/index.php')) {
    $cssPage = "index.min.css";
}
//elseif (IS_404_PAGE) $cssPage404 = "404.min.css";
$cssPage404 = "404.min.css";

//Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/css/app.min.css?v=1614582130496");
Asset::getInstance()->addJs("https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js");

switch ($APPLICATION->GetDirProperty("page")) {
    case about :
        $cssPage = "about.min.css";
        break;
    case contacts :
        $cssPage = "contacts.min.css";
        break;
    case vacancies :
        $cssPage = "vacancy.min.css";
        break;
}

!empty($cssPage) ? Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/css/".$cssPage) : null;
!empty($cssPage404) ? Asset::getInstance()->addCss(SITE_TEMPLATE_PATH."/css/".$cssPage404) : null;

/**
 * GET UTM Start
 */
$keysUtm = array('utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term');
foreach ($keysUtm as $row) {
    if (!empty($_GET[$row])) {
        $value = strval($_GET[$row]);
        $value = stripslashes($value);
        $value = htmlspecialchars_decode($value, ENT_QUOTES);
        $value = strip_tags($value);
        $value = htmlspecialchars($value, ENT_QUOTES);
        $arUtm[$row] = $value;
    }
}
if (!empty($arUtm)) {
    setcookie('utm', serialize($arUtm), 0, '/');
}

/**
 * GET UTM end
 */

use \Bitrix\Main\Data\Cache;
use \Bitrix\Main\Application;

$cache = Cache::createInstance();
$taggedCache = Application::getInstance()->getTaggedCache();
$cachePath = SITE_ID.'/custom/contact';
$cacheTtl = 3600;
$cacheKey = $city;
$arContactsLD = [];
if ($cache->initCache($cacheTtl, $cacheKey, $cachePath)) {
    $arContactsLD = $cache->getVars();

} elseif ($cache->startDataCache()) {
    // Начинаем записывать теги
    $taggedCache->startTagCache($cachePath);
    $arContactsLD = CIBlockElement::GetList(
        array(),
        array(
            "IBLOCK_ID" => CONTACTS_IBLOCK_ID,
            "ACTIVE" => "Y",
            "ELEMENT_CODE" => $city,
        ),
        false,
        false,
        array(
            "ID",
            "IBLOCK_ID",
            "CODE",
            "NAME",
            "PROPERTY_ADDRESS",
            "PROPERTY_PHONE",
            "PROPERTY_PHONE_COUNTRY",
            "PROPERTY_PHONE_NEW_BUILD",
            "PROPERTY_PHONE_RESALE",
            "PROPERTY_PHONE_OVERSEAS",
        )
    )->Fetch();

    if ($arContactsLD) {
        $taggedCache->registerTag("iblock_id_".$arContactsLD["IBLOCK_ID"]);
    }

    $taggedCache->registerTag("iblock_id_new");
    $taggedCache->endTagCache();
    $cache->endDataCache($arContactsLD);
}



CModule::IncludeModule('iblock');



// Значение, которое необходимо установить для свойства CITY_SEO


?>

<!DOCTYPE html>
<html lang="<?= LANGUAGE_ID ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="format-detection" content="telephone=no">
    <link rel="canonical"
          href="<?= ($APPLICATION->IsHTTPS() ? 'https://' : 'http://').$_SERVER['SERVER_NAME'].str_replace('index.php',
              '', $APPLICATION->GetCurPage(true)); ?>"/>
    <link rel="preload" href="<?= SITE_TEMPLATE_PATH ?>/fonts/Code-Pro.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="<?= SITE_TEMPLATE_PATH ?>/fonts/Navigo-Bold.woff2" as="font" type="font/woff2"
          crossorigin>
    <link rel="preload" href="<?= SITE_TEMPLATE_PATH ?>/fonts/Navigo-Medium.woff2" as="font" type="font/woff2"
          crossorigin>
    <link rel="preload" href="<?= SITE_TEMPLATE_PATH ?>/fonts/Navigo-Regular.woff2" as="font" type="font/woff2"
          crossorigin>
    <link rel="stylesheet" href="<?= SITE_TEMPLATE_PATH ?>/css/app.min.css?v=1618407476537">
    <meta http-equiv="Content-Type" content="text/html; charset=<?= LANG_CHARSET; ?>"/>
    <? $APPLICATION->ShowMeta("description"); ?>
    <? $APPLICATION->ShowCSS(); ?>
    <? $APPLICATION->ShowHeadStrings(); ?>
    <? $APPLICATION->ShowHeadScripts(); ?>
    <title><? $APPLICATION->ShowTitle() ?></title>

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#087466">
    <meta name="msapplication-TileColor" content="#087466">
    <meta name="theme-color" content="#ffffff">

	<div itemscope itemtype="http://schema.org/WPHeader">
		<meta itemprop="name" content="<?php echo $APPLICATION->ShowTitle(false); ?>"> 
		<meta itemprop="description" content="<?php $APPLICATION->ShowProperty('description'); ?>">
	</div>

    <!-- See contacts in $arContactsLD variable -->

    <script type="application/ld+json">
        {
        "@context": "http://schema.org",
        "@type": "Organization",
        "url": "https://towergroup.ru",
        "name": "Агентство недвижимости Tower Group",
        "email": "info@towergroup.ru",
        "logo": "https://<?= $_SERVER['HTTP_HOST']; ?>/apple-touch-icon.png",
        "description": "Официальный партнер ведущих застройщиков Санкт-Петербурга и Москвы. Новостройки, вторичная и коммерческая недвижимость.",
        "address":
    	[{
    	"@type": "PostalAddress",
    	"addressLocality": "Москва, Россия",
        "streetAddress": ["Пресненская наб., д.12, башня Федерация (Восток), 5 этаж "]
    	},{
        "@type": "PostalAddress",
        "addressLocality": "Санкт-Петербург, Россия",
        "streetAddress": ["ул. Беринга, д.1"]
        }],
        "contactPoint":
        [{
        "@type": "ContactPoint",
        "telephone": "+7 (495) 990-48-14",
        "contactType": "customer service"
        },{
        "@type": "ContactPoint",
        "telephone": "+7 (812) 770-42-01",
        "contactType": "customer service"
        }],
        "sameAs" : ["https://vk.com/towergroup","https://www.instagram.com/towergroup.realestate/","https://www.facebook.com/towergroup.agency","https://www.youtube.com/channel/UCGkJ_EDrKFm9PwBO_Co1wAA","https://t.me/towergroupagency"]
        }
    </script>

    <? if (!Helper::isPageSpeed()): ?>
        <?
        /*Определение кодов метрик в зависимости от номера сайта*/
        $metricsID = getIDMetrics(SITE_ID);
        ?>
        <!-- Yandex.Metrika counter -->
        <script type="text/javascript" data-skip-moving="true">
            (function (m, e, t, r, i, k, a) {
                m[i] = m[i] || function () {
                    (m[i].a = m[i].a || []).push(arguments)
                };
                m[i].l = 1 * new Date();
                k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k, a)
            })
            (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

            ym(<?=$metricsID['PROPERTY_YM_NUMBER_VALUE'];?>, "init", {
                clickmap: true,
                trackLinks: true,
                accurateTrackBounce: true,
                webvisor: true
            });
        </script>
        <noscript>
            <div><img src="https://mc.yandex.ru/watch/<?= $metricsID['PROPERTY_YM_NUMBER_VALUE']; ?>"
                      style="position:absolute; left:-9999px;" alt=""/></div>
        </noscript>
        <!-- /Yandex.Metrika counter -->
        <!-- Top.Mail.Ru counter -->
        <script type="text/javascript">
            var _tmr = window._tmr || (window._tmr = []);
            _tmr.push({id: "3303678", type: "pageView", start: (new Date()).getTime()});
            (function (d, w, id) {
                if (d.getElementById(id)) return;
                var ts = d.createElement("script");
                ts.type = "text/javascript";
                ts.async = true;
                ts.id = id;
                ts.src = "https://top-fwz1.mail.ru/js/code.js";
                var f = function () {
                    var s = d.getElementsByTagName("script")[0];
                    s.parentNode.insertBefore(ts, s);
                };
                if (w.opera == "[object Opera]") {
                    d.addEventListener("DOMContentLoaded", f, false);
                } else {
                    f();
                }
            })(document, window, "tmr-code");
        </script>
        <noscript>
            <div><img src="https://top-fwz1.mail.ru/counter?id=3303678;js=na" style="position:absolute;left:-9999px;"
                      alt="Top.Mail.Ru"/></div>
        </noscript>
        <!-- /Top.Mail.Ru counter -->
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script data-skip-moving="true" async
                src="https://www.googletagmanager.com/gtag/js?id=<?= $metricsID['PROPERTY_GA_NUMBER_VALUE']; ?>"></script>
        <script data-skip-moving="true">
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }

            gtag('js', new Date());

            gtag('config', '<?=$metricsID['PROPERTY_GA_NUMBER_VALUE'];?>');
        </script>
        <script src="//code-ya.jivosite.com/widget/biLpsJHcx2" async></script>

    <? if ($metricsID['PROPERTY_GTM_NUMBER_VALUE']): ?>
        <? /*GTM START**/ ?>
        <!-- Google Tag Manager -->
        <script data-skip-moving="true">
            (function (w, d, s, l, i) {
                w[l] = w[l] || [];
                w[l].push({
                    'gtm.start':
                        new Date().getTime(), event: 'gtm.js'
                });
                var f = d.getElementsByTagName(s)[0],
                    j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
                j.async = true;
                j.src =
                    'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
                f.parentNode.insertBefore(j, f);
            })(window, document, 'script', 'dataLayer', '<?=$metricsID['PROPERTY_GTM_NUMBER_VALUE'];?>');
        </script>
        <!-- End Google Tag Manager -->
        <? /*GTM END**/ ?>
    <? endif; ?>
    <? endif; ?>




    <?
    if (SITE_ID == "s1" || SITE_ID == "s2") {
        if (CSite::InDir('/'.$arContactsLD['CODE'].'/novostroyki/') && !empty($arContactsLD['PROPERTY_PHONE_NEW_BUILD_VALUE'])) {
            $phoneValue = $arContactsLD['PROPERTY_PHONE_NEW_BUILD_VALUE'];
        } elseif (CSite::InDir('/'.$arContactsLD['CODE'].'/kupit-kvartiru/') && !empty($arContactsLD['PROPERTY_PHONE_RESALE_VALUE'])) {
            $phoneValue = $arResult['PROPERTY_PHONE_RESALE_VALUE'];
        } elseif (CSite::InDir('/'.$arContactsLD['CODE'].'/kupit-dom/') && !empty($arContactsLD['PROPERTY_PHONE_COUNTRY_VALUE'])) {
            $phoneValue = $arResult['PROPERTY_PHONE_COUNTRY_VALUE'];
        } elseif (CSite::InDir('/'.$arContactsLD['CODE'].'/nedvizhimost-za-rubezhom/') && !empty($arContactsLD['PROPERTY_PHONE_OVERSEAS_VALUE'])) {
            $phoneValue = $arContactsLD['PROPERTY_PHONE_OVERSEAS_VALUE'];
        } else {
            $phoneValue = $arContactsLD['PROPERTY_PHONE_VALUE'];
        }
    } else {
        if (!empty($metricsID["PROPERTY_PHONE_LANDING_VALUE"])) {
            $phoneValue = $metricsID["PROPERTY_PHONE_LANDING_VALUE"];
        } else {
            $phoneValue = $arContactsLD['PROPERTY_PHONE_VALUE'];
        }
    }
    if (!empty($metricsID["PROPERTY_PHONE_REPLACE_VALUE"])) {
        $phoneReplaceClass = $metricsID["PROPERTY_PHONE_REPLACE_VALUE"];
    } else {
        $phoneReplaceClass = PHONE_REPLACE_CLASS;
    }
    $APPLICATION->SetPageProperty("PHONE_REPLACE_VALUE", $phoneValue);
    $APPLICATION->SetPageProperty("PHONE_REPLACE_CLASS", $phoneReplaceClass);
    ?>

    <? if (!Helper::isPageSpeed()): ?>
        <!-- <script src="https://app.getreview.io/tags/bKoRB0hKX59hWdsq/sdk.js" async></script> -->
        <script src="https://www.google.com/recaptcha/api.js?render=6LfiazYfAAAAAD1JMSBhuwQ20jCGWdXkAyb7URBR"></script>
        <script>
            grecaptcha.ready(function () {
                grecaptcha.execute('6LfiazYfAAAAAD1JMSBhuwQ20jCGWdXkAyb7URBR', {action: 'contact'}).then(function (token) {
                    var recaptchaResponse = document.getElementById('recaptchaResponse');
                    recaptchaResponse.value = token;
                });
            });
        </script>
        <style>.grecaptcha-badge {
                visibility: hidden;
            }</style>
        <? if (!empty($metricsID['PREVIEW_TEXT'])): ?>
            <?= $metricsID['~PREVIEW_TEXT']; ?>
        <? endif; ?>
    <? endif; ?>
</head>
<body id="body" <? $APPLICATION->ShowProperty("body-page") ?>>
<? if ($metricsID['PROPERTY_GTM_NUMBER_VALUE']): ?>
    <!-- Google Tag Manager (noscript) -->
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id=<?= $metricsID['PROPERTY_GTM_NUMBER_VALUE']; ?>"
                height="0" width="0" style="display:none;visibility:hidden"></iframe>
    </noscript>
    <!-- End Google Tag Manager (noscript) -->
<? endif; ?>
<? if ($USER->IsAuthorized()): ?>
    <div id="admin_panel"><? $APPLICATION->ShowPanel(); ?></div>
<? endif; ?>
<div class="wrapper">
    <header class="header">
        <div class="container container--wide">
            <? if (!IS_INDEX_PAGE): ?>
                <? if (SITE_ID == "s2"): ?>
                    <a class="header-logotype" href="/">
                        <svg class="icon icon--logotype">
                            <use xlink:href="#logotype"/>
                        </svg>
                    </a>
                <? elseif (SITE_ID == "s1"): ?>
                    <a class="header-logotype" href="/moskva/">
                        <svg class="icon icon--logotype">
                            <use xlink:href="#logotype"/>
                        </svg>
                    </a>
                <? else: ?>
                    <span class="header-logotype">
						<svg class="icon icon--logotype">
							<use xlink:href="#logotype"/>
						</svg>
					</span>
                <? endif; ?>
            <? else: ?>
                <span class="header-logotype">
					<svg class="icon icon--logotype">
						<use xlink:href="#logotype"/>
					</svg>
				</span>
            <? endif; ?>
            <? $APPLICATION->ShowProperty("logo-object") ?>
            <div class="header-menu">
                <? if (SITE_ID == 's1' || SITE_ID == 's2'): ?>
                    <div class="header-contacts mobile">
                        <?
                        $APPLICATION->IncludeComponent(
                            "bitrix:news.list",
                            "contacts_header_mobile",
                            array(
                                "AJAX_MODE" => "N",
                                "AJAX_OPTION_ADDITIONAL" => "",
                                "AJAX_OPTION_HISTORY" => "N",
                                "AJAX_OPTION_JUMP" => "N",
                                "AJAX_OPTION_STYLE" => "Y",
                                "BROWSER_TITLE" => "-",
                                "CACHE_GROUPS" => "N",
                                "CACHE_TIME" => "36000000",
                                "CACHE_TYPE" => "N",
                                "CHECK_DATES" => "N",
                                "CITY_CODE" => $city,
                                "DETAIL_URL" => "/index.php",
                                "DISPLAY_BOTTOM_PAGER" => "Y",
                                "DISPLAY_DATE" => "Y",
                                "DISPLAY_NAME" => "Y",
                                "DISPLAY_PICTURE" => "Y",
                                "DISPLAY_PREVIEW_TEXT" => "Y",
                                "DISPLAY_TOP_PAGER" => "N",
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
                                "SET_CANONICAL_URL" => "Y",
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

                    <nav class="navigation">
                        <? $APPLICATION->IncludeComponent(
                            "bitrix:menu",
                            ".default",
                            array(
                                "ALLOW_MULTI_SELECT" => "N",
                                "CHILD_MENU_TYPE" => "sec",
                                "DELAY" => "N",
                                "MAX_LEVEL" => "2",
                                "MENU_CACHE_GET_VARS" => array(),
                                "MENU_CACHE_TIME" => "3600",
                                "MENU_CACHE_TYPE" => "N",
                                "MENU_CACHE_USE_GROUPS" => "Y",
                                "ROOT_MENU_TYPE" => "top",
                                "USE_EXT" => "N",
                                "COMPONENT_TEMPLATE" => ".default",
                                "MENU_THEME" => "site",
                            ),
                            false
                        ); ?>
                    </nav>
                <? else: ?>
                    <nav class="navigation">
                        <div class="detail-heading">
                            <? $APPLICATION->ShowProperty("landing-menu") ?>
                        </div>
                    </nav>
                <? endif; ?>
                <div class="work-times" style="padding-left: 20px; ">
                    <? $APPLICATION->AddBufferContent('ShowCondWorkTime') ?>
                </div>
                <div class="header-contacts">
                    <?
                    $APPLICATION->IncludeComponent(
                        "bitrix:news.list",
                        "contacts_header",
                        array(
                            "AJAX_MODE" => "N",
                            "AJAX_OPTION_ADDITIONAL" => "",
                            "AJAX_OPTION_HISTORY" => "N",
                            "AJAX_OPTION_JUMP" => "N",
                            "AJAX_OPTION_STYLE" => "Y",
                            "BROWSER_TITLE" => "-",
                            "CACHE_GROUPS" => "N",
                            "CACHE_TIME" => "36000000",
                            "CACHE_TYPE" => "N",
                            "CHECK_DATES" => "N",
                            "CITY_CODE" => $city,
                            "DETAIL_URL" => "/index.php",
                            "DISPLAY_BOTTOM_PAGER" => "Y",
                            "DISPLAY_DATE" => "Y",
                            "DISPLAY_NAME" => "Y",
                            "DISPLAY_PICTURE" => "Y",
                            "DISPLAY_PREVIEW_TEXT" => "Y",
                            "DISPLAY_TOP_PAGER" => "N",
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
                            "SET_CANONICAL_URL" => "Y",
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
                    <? if (SITE_ID == 's1' || SITE_ID == 's2') : ?>
                        <div class="header-contacts-request"><a class="text-control text-control--phone"
                                                                href="#request-quiz" data-modal><span>Оставить заявку на подбор</span>
                                <svg class="icon icon--phone" width="17" height="17" viewbox="0 0 17 17">
                                    <use xlink:href="#phone"/>
                                </svg>
                            </a></div>
                    <? endif; ?>
                </div>
            </div>
            <? // if (SITE_ID != 's1' || SITE_ID != 's2') : ?>
            <? //$APPLICATION->ShowViewContent('header_controls');?>
            <div class="header-controls">
                <? $APPLICATION->ShowViewContent('header_controls_phone') ?>
                <a class="header-controls-telegram" href="<?= TELEGRAM ?>" target="_blank" rel="noopener noreferrer">
                    <svg class="icon icon--telegram" width="19" height="15" viewBox="0 0 19 15">
                        <use xlink:href="#telegram"></use>
                    </svg>
                </a>
            </div>
            <? //endif;?>
            <? // if (SITE_ID == 's1' || SITE_ID == 's2') : ?>
            <button class="header-toggler" data-menu-toggle><span>Меню</span></button>
            <? // endif; ?>
        </div>
    </header>
    <main class="main">


        <?

function ShowCondWorkTime()
{
    global $APPLICATION;
    return (strpos($APPLICATION->GetProperty("landing-menu"), 'Записаться на')) ? '' : 'ПН-ВС | 10:00 - 20:00';
}

?>