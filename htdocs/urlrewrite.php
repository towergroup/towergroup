<?php
$arUrlRewrite=array (
  10 => 
  array (
    'CONDITION' => '#^/([moskva|spb]+)/nedvizhimost-za-rubezhom/([0-9a-zA-Z_-]+)/([\\?\\#].*)?$#',
    'RULE' => 'OBJECT_CODE=$2&',
    'ID' => '',
    'PATH' => '/$1/nedvizhimost-za-rubezhom/index.php',
    'SORT' => 100,
  ),
  6 => 
  array (
    'CONDITION' => '#^/([moskva|spb]+)/kupit-kvartiru/([0-9a-zA-Z_-]+)/([\\?\\#].*)?$#',
    'RULE' => 'CITY_CODE=$1&OBJECT_CODE=$2&',
    'ID' => '',
    'PATH' => '/$1/kupit-kvartiru/index.php',
    'SORT' => 100,
  ),
  4 => 
  array (
    'CONDITION' => '#^/([moskva|spb]+)/novostroyki/([0-9a-zA-Z_-]+)/([\\?\\#].*)?$#',
    'RULE' => 'CITY_CODE=$1&OBJECT_CODE=$2&',
    'ID' => '',
    'PATH' => '/$1/novostroyki/index.php',
    'SORT' => 100,
  ),
  8 => 
  array (
    'CONDITION' => '#^/([moskva|spb]+)/kupit-dom/([0-9a-zA-Z_-]+)/([\\?\\#].*)?$#',
    'RULE' => 'CITY_CODE=$1&OBJECT_CODE=$2&',
    'ID' => '',
    'PATH' => '/$1/kupit-dom/index.php',
    'SORT' => 100,
  ),
  11 => 
  array (
    'CONDITION' => '#^/([moskva|spb]+)/nedvizhimost-za-rubezhom/([\\?\\#].*)?$#',
    'RULE' => 'CITY_CODE=$1&',
    'ID' => '',
    'PATH' => '/$1/nedvizhimost-za-rubezhom/index.php',
    'SORT' => 100,
  ),
  7 => 
  array (
    'CONDITION' => '#^/([moskva|spb]+)/kupit-kvartiru/([\\?\\#].*)?$#',
    'RULE' => 'CITY_CODE=$1&',
    'ID' => '',
    'PATH' => '/$1/kupit-kvartiru/index.php',
    'SORT' => 100,
  ),
  5 => 
  array (
    'CONDITION' => '#^/([moskva|spb]+)/novostroyki/([\\?\\#].*)?$#',
    'RULE' => 'CITY_CODE=$1&',
    'ID' => '',
    'PATH' => '/$1/novostroyki/index.php',
    'SORT' => 100,
  ),
  9 => 
  array (
    'CONDITION' => '#^/([moskva|spb]+)/kupit-dom/([\\?\\#].*)?$#',
    'RULE' => 'CITY_CODE=$1&',
    'ID' => '',
    'PATH' => '/$1/kupit-dom/index.php',
    'SORT' => 100,
  ),
  0 => 
  array (
    'CONDITION' => '#^\\/?\\/mobileapp/jn\\/(.*)\\/.*#',
    'RULE' => 'componentName=$1',
    'ID' => NULL,
    'PATH' => '/bitrix/services/mobileapp/jn.php',
    'SORT' => 100,
  ),
  19 => 
  array (
    'CONDITION' => '#^/moskva/about/services/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/moskva/about/services/index.php',
    'SORT' => 100,
  ),
  21 => 
  array (
    'CONDITION' => '#^/spb/about/services/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/spb/about/services/index.php',
    'SORT' => 100,
  ),
  24 => 
  array (
    'CONDITION' => '#^/moskva/pressa/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/moskva/pressa/index.php',
    'SORT' => 100,
  ),
  22 => 
  array (
    'CONDITION' => '#^/spb/pressa/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/spb/pressa/index.php',
    'SORT' => 100,
  ),
  23 => 
  array (
    'CONDITION' => '#^/pressa/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/pressa/index.php',
    'SORT' => 100,
  ),
  1 => 
  array (
    'CONDITION' => '#^/.*#',
    'RULE' => '',
    'ID' => 'profistudio.seo',
    'PATH' => '/profistudio_seo_file_rewrite.php',
    'SORT' => 10000,
  ),
);
