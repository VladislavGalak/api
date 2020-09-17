<?php
global $APPLICATION;
define("NO_KEEP_STATISTIC", true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$APPLICATION->IncludeComponent(
	'askaron:api',
	null,
	[ 'SEF_FOLDER' => '/api/']
);