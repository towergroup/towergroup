<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Application;

$request  = Application::getInstance()->getContext()->getRequest();
$id       = $request->getQuery('id');
$currency = $request->getQuery('currency');

//$url = $_SERVER['HTTP_X_FORWARDED_PROTO'].'://'.$_SERVER['HTTP_HOST'].'/pdf/getpdf.php?type='.$type.(($ground) ? '&ground='.$ground : '').'&id='.$id;
//$url = 'https://tg.idemcloud.ru/pdf/getpdf.php?id='.$id.'&currency='.$currency;
$url = 'https://towergroup.ru/pdf/getpdf.php?id='.$id.'&currency='.$currency;
//if ($priceHide == 'y') $url .= '&pricehide=y';

$path = $_SERVER["DOCUMENT_ROOT"]."/upload/generate/pdf/";
$name = 'TowerGroup_flat_'.$id.'.pdf';
//$command = 'wkhtmltopdf --enable-javascript "' . $url . '" ' . $path . $name;

//$command = 'wkhtmltopdf --margin-top 0mm --margin-bottom 0mm --margin-left 0mm --margin-right 0mm "' . $url . '" ' . $path . $name;
$command = 'wkhtmltopdf --enable-javascript --margin-top 0mm --margin-bottom 0mm --margin-left 0mm --margin-right 0mm "' . $url . '" ' . $path . $name;



exec($command, $exec);

if(file_exists($path . $name)) {
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $name . '"');

    readfile($path . $name);
}