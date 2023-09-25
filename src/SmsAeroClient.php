<?php

namespace SmsAero;

class SmsAeroClient
{
    private $userLogin;
    private $apiKey;
    private $baseUrls = [
        'https://gate.smsaero.ru/v2/',
        'https://gate.smsaero.org/v2/',
        'https://gate.smsaero.net/v2/',
        'https://gate.smsaero.uz/v2/'
    ];

    /**
     * Конструктор класса, который устанавливает значения для userLogin и apiKey.
     *
     * @param string $userLogin Логин пользователя в SMS Aero.
     * @param string $apiKey API-ключ, полученный от SMS Aero.
     */
    public function __construct($userLogin, $apiKey)
    {
        $this->userLogin = $userLogin;
        $this->apiKey = $apiKey;
    }

    /**
     * Выполняет запрос к API-интерфейсу SMS Aero.
     *
     * @param string $method HTTP метод, который должен быть использован при запросе.
     * @param string $url Путь запроса относительно базового URL API.
     * @param array $params Массив параметров запроса.
     *
     * @return array Ответ API.
     * @throws \Exception
     */
    public function makeRequest($method, $url, $params = [])
    {
        if (!extension_loaded('curl')) {
            throw new \Exception('Расширение curl не установлено');
        }

        $apiUrl = null;
        foreach ($this->baseUrls as $baseUrl) {
            if ($this->isApiAvailable($baseUrl)) {
                $apiUrl = $baseUrl;
                break;
            }
        }

        $curl = curl_init();

        // Устанавливаем параметры для запроса.
        $url = $apiUrl . $url;

        if ($method == 'GET' && !empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Authorization: Basic ' . base64_encode($this->userLogin . ':' . $this->apiKey)
        ]);

        if ($method == 'POST' && !empty($params)) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
        }

        // Выполняем запрос.
        $result = curl_exec($curl);

        // Проверяем наличие ошибок.
        if ($error = curl_error($curl)) {
            throw new \Exception('Ошибка выполнения запроса: ' . $error);
        }

        // Закрываем соединение.
        curl_close($curl);

        return json_decode($result, true);
    }

    private function isApiAvailable($url) {
        $curlInit = curl_init($url);

        curl_setopt($curlInit,CURLOPT_CONNECTTIMEOUT,5);
        curl_setopt($curlInit,CURLOPT_HEADER,true);
        curl_setopt($curlInit,CURLOPT_NOBODY,true);
        curl_setopt($curlInit,CURLOPT_RETURNTRANSFER,true);

        $response = curl_exec($curlInit);

        curl_close($curlInit);

        return (bool)$response;
    }
}
