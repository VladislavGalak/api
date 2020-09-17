<?php

namespace Askaron\Api;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

use \Bitrix\Main\Loader;

Loader::includeModule('askaron.api');

class ApiRouterExtended extends ApiRouter
{
	public $sefVariables = [];
	public $arUrlTemplates = [];
}
