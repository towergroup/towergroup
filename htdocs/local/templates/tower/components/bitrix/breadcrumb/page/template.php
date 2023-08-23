<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

//delayed function must return a string
//xprint($arResult);
$keyBreadcrumb = 1;
$serverUrl = 'https://'.$_SERVER['HTTP_HOST'];

if (empty($arResult)) {
    return "";
}
$strReturn = '<ul class="list list--crumbs">';

$num_items = count($arResult);

for ($index = 0, $itemSize = $num_items; $index < $itemSize; $index++) {
    $title = htmlspecialcharsex($arResult[$index]["TITLE"]);

    if ($arResult[$index]["LINK"] <> "" && $index != $itemSize - 1) {
        $arResult[$index]["LINK"] = $arResult[$index]["LINK"] === '/' ? '/moskva/' : $arResult[$index]["LINK"];
        $arResult[$index]["LINK"] = $arResult[$index]["LINK"] === '/spb/' ? '/' : $arResult[$index]["LINK"];
        $strReturn .= '<li class="list-item"><svg class="icon icon--circle" width="3" height="3" viewbox="0 0 3 3"><use xlink:href="#circle" /></svg><a class="list-link" href="'.$arResult[$index]["LINK"].'" title="'.$title.'">'.$title.'</a></li>';
    } else {
        $strReturn .= '<li class="list-item"><span class="list-link">'.$title.'</span></li>';
    }

}

$strReturn .= '</ul>';

$strReturn .= '<script type="application/ld+json">
    {
        "@context": "http://schema.org",
        "@type": "BreadcrumbList",
        "itemListElement": [';
foreach ($arResult as $key => $item):
    if ($key == array_key_last($arResult)):
        $urlLastElement = explode('?', $_SERVER["REQUEST_URI"]);
        $urlLastElement = $urlLastElement[0];
        $breadElement = !empty($item["LINK"]) ? $item["LINK"] : $urlLastElement;
        $strReturn .= ' { "@type": "ListItem", "position": '.$keyBreadcrumb.', "name": "'.$item["TITLE"].'", "item": "'.$serverUrl.$breadElement.'" }';
    else:
        $item["LINK"] = ($item["LINK"] === '/' || $item["LINK"] === '/spb/') ? '/' : $item["LINK"];
        $breadElement = $breadElement == 1 ? $serverUrl : $serverUrl.''.$item["LINK"];
        $strReturn .= ' { "@type": "ListItem", "position": '.$keyBreadcrumb.', "name": "'.$item["TITLE"].'", "item": "'.$breadElement.'" },';
    endif;
    $keyBreadcrumb++;
endforeach;
$strReturn .= ']}</script>';

return $strReturn;
?>