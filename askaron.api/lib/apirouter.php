<?php

namespace Askaron\Api;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

use \Bitrix\Main\Loader;

/**
 * Class ApiRouter
 * @package Askaron\Api
 */
abstract class ApiRouter extends \CBitrixComponent
{
	/**
	 * @var array
	 */

	public $sefVariables = [];

	/**
	 * Массив маршрутов в формате:
	 * '\\ПутьКласса\\ИмяКласса' => 'адрес/маршрута/'
	 * где адрес/маршрута без приставки /api/
	 *
	 * Пример:
	 * '\MySite\Lk\Response\Resubmit' => 'application/resubmit/',
	 * @var array
	 */
	public $arUrlTemplates = [];

	/**
	 * @var array
	 */
	public $arComponentVariables = [];

	/**
	 * Список модулей необходимых к загрузке
	 * @var array
	 */
	public $arLoadModules = [];

	/**
	 * Execution component
	 */
	public function executeComponent() {
		Loader::includeModule('askaron.api');
		foreach ($this->arLoadModules as $loadModule) {
			Loader::includeModule($loadModule);
		}
		$this->router();
	}

	/**
	 * Маршрутизация
	 */
	private function router() {
		$engine    = new \CComponentEngine($this);
		$className = $engine->ParseComponentPath(
			$this->arParams['SEF_FOLDER'],
			$this->arUrlTemplates,
			$this->sefVariables
		);

		if (!$className || strlen($className) <= 0) {
			$className = '\\Askaron\\Api\\Error';
		}

		if (!class_exists($className)) {
			$response = new \Askaron\Api\NotFoundRoute($this->sefVariables);
		} else {
			$response = new $className($this->sefVariables);
		}

		$response->validateMethod();
		if ($response->validate()) {
			$response->handler();
		} else {
			$response->response->sendFail('Невалидный запрос');
		}

		$response->response->send();
	}
}