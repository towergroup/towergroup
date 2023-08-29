<?php
$arUrlRewrite=array (
  0 => 
  array (
    'CONDITION' => '#^\\/?\\/mobileapp/jn\\/(.*)\\/.*#',
    'RULE' => 'componentName=$1',
    'ID' => NULL,
    'PATH' => '/bitrix/services/mobileapp/jn.php',
    'SORT' => 100,
  ),
  5 => 
  array (
    'CONDITION' => '#^/(.*?)/novostroyki/(.*?)/#',
    'RULE' => 'CITY_CODE=$1&OBJECT_CODE=$2&',
    'ID' => '',
    'PATH' => '/novostroyki/index.php',
    'SORT' => 100,
  ),
  3 => 
  array (
    'CONDITION' => '#^/about/services/(.*?)/#',
    'RULE' => 'ELEMENT_CODE=$1&',
    'ID' => '',
    'PATH' => '/about/services/index.php',
    'SORT' => 100,
  ),
  7 => 
  array (
    'CONDITION' => '#^/(.*?)/novostroyki/#',
    'RULE' => 'CITY_CODE=$1&',
    'ID' => '',
    'PATH' => '/novostroyki/index.php',
    'SORT' => 100,
  ),
  2 => 
  array (
    'CONDITION' => '#^/pressa/(.*?)/#',
    'RULE' => 'ELEMENT_CODE=$1&',
    'ID' => '',
    'PATH' => '/pressa/index.php',
    'SORT' => 100,
  ),
  1 => 
  array (
    'CONDITION' => '#^/rest/#',
    'RULE' => '',
    'ID' => NULL,
    'PATH' => '/bitrix/services/rest/index.php',
    'SORT' => 100,
  ),
);
