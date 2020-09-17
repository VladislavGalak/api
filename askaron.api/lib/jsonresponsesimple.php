<?php

namespace Askaron\Api;

/**
 * Class JsonResponse
 *
 * @package Askaron\Main
 */
class JsonResponseSimple extends Singleton
{
    private $response = [];
    
    /**
     * @return array
     */
    public function getResponse()
    {
        return $this->response;
    }
    
    /**
     * @param $value
     * @return $this
     */
    public function addParam($value)
    {
        $this->response = $value;
        return $this;
    }
    
    
    /**
     * Посылает запрос на сервер и умирает
     */
    public function send()
    {
        header('Content-Type: application/json');
        die(json_encode($this->response));
    }
    
    
    // Быстрые обработчики
    // ===================
    
    public function sendSuccess()
    {
        $this->send();
    }
    
    public function sendFail()
    {
        $this->send();
    }
}