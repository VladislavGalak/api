# askaron/api

Простой интерфейс для написания api в CMS Bitrix.

Выносит все обработчики в пространство /api/, заставляя разработчиков писать унифицированные обработчики.

Решение для тех кому надоело писать компоненты-обработчики, плодить кучу файлов в doc_root аля в папке /ajax и кто не внедрил себе роутер из laravel/symphony.

## Установка в CMS Bitrix
1. ```composer require askaron/api```
2. Установка модуля в админке

### Процесс установки
В процессе установки модуль
- Добавляет в корень DocRoot `api-router.php`
- Устанавливает маршрут `/api/` в `urlrewrite.php`
- Устанавливает компонент в `local/components/askaron/api/`

## Написание обработчиков
1. Регистрируем маршруты `$arUrlTemplates` в компоненте askaron:api в local.
```php
class ApiRouterExtended extends \Askaron\Api\ApiRouter
{
	public $sefVariables   = [];
	public $arUrlTemplates = [
		'\MySite\Lk\Response\Resubmit' => 'application/resubmit/',
	];
	public $arLoadModules  = [
		'askaron.main'
	];
}
```
> В модули передаем те которые нужны для autoload (@todo fixme)

2. Добавляем обработчик \MySite\Lk\Response\Resubmit наследуя его от \Askaron\Api\Response

Method | Description
------------ | -------------
public function handler(){} | главная логика
public function validate() | логика проверок. При
public $method | GET/POST - ожидаемый тип запроса
public $request | `\Bitrix\Main\HttpRequest` https://dev.1c-bitrix.ru/api_d7/bitrix/main/request/index.php
public $response | `\Askaron\Api\JsonResponse`

## Json example
```json
{
  "status": true,
  "errors": []
}
```

## Интерфейс JsonResponse
Method | Description
------------ | -------------
addError($strError) | добавление ошибки
addParam($name, $value) | добавление параметра в json
setSuccess/setFail | установка status true/false
send | json_encode + die()
sendSuccess | setSuccess + send()
sendFail($strError) | addError($strError) + send()
haveErrors() | true|false
getErrors() | array
getResponse() | array

### Example
```php
class FormRegister extends \Askaron\Api\Response
{
	public function handler() {
		if (!$this->register()) {
		    $this->response->sendFail('Ошибка регистрации')
		}
		$this->response
		    ->addParam('password', $this->password)
		    ->setSuccess()
		    ->send();
	}

	public function validate() {
		if (!$this->request->getPost('email')) {
		    $this->response->addError('Не введен email');
		}
		if (!$this->request->getPost('login')) {
		    $this->response->addError('Не введен логин');
		}

		return $this->response->haveErrors();
	}
}
```
> пример абстрактный, просто чтобы показать возможности
