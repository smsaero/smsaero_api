# SMSAero PHP SDK

SDK для работы с SMSAero API на языке PHP.
#### Все методы принимают параметры указанные в документации https://smsaero.ru/integration/documentation/api в виде массива(исключения: методы которые требуют передачу только идентификатора, они принимают сразу ID)

### Установка
```bash
composer require smsaero/smsaero_api
```

## Работа с SMS сообщениями
```php
$smsAeroMessage = new \SmsAero\SmsAeroMessage('Ваш E-mail на сайте', 'apiKey можно посмотреть в личном кабинете в разделе настройки -> API и SMPP');
// Отправка SMS сообщений
$response = $smsAeroMessage->send(['number' => 'номер абонента', 'text' => 'текст сообщения', 'sign' => 'подпись отправителя(можно получить в личном кабинете smsaero.ru или использовать подпись: SMS Aero)']);
// Проверка статуса SMS сообщения
$response = $smsAeroMessage->getStatus($id);
// Получение списка отправленных сообщений
$response = $smsAeroMessage->getList();
```

## Работа с контактами
```php
$smsAeroContact = new \SmsAero\SmsAeroContact('Ваш E-mail на сайте', 'apiKey можно посмотреть в личном кабинете в разделе настройки -> API и SMPP');
// Добавление контакта
$response = $smsAeroContact->create(['number' => 'номер абонента']);
// Удаление контакта
$response = $smsAeroContact->delete($id);
// Список контактов
$response = $smsAeroContact->getList();
// Добавление в черный список
$response = $smsAeroContact->toBlacklist(['number' => 'номер абонента']);
// Удаление из черного списка
$response = $smsAeroContact->deleteFromBlacklist($id);
// Список контактов в черном списке
$response = $smsAeroContact->getBlacklist();
// Создание запроса на проверку HLR
$response = $smsAeroContact->check(['number' => 'номер абонента']);
// Получение статуса HLR
$response = $smsAeroContact->status($id);
// Определение оператора
$response = $smsAeroContact->getOperator(['number' => 'номер абонента']);
```

## Работа с группами
```php
$smsAeroGroup = new \SmsAero\SmsAeroGroup('Ваш E-mail на сайте', 'apiKey можно посмотреть в личном кабинете в разделе настройки -> API и SMPP');
// Добавление группы
$response = $smsAeroGroup->create(['name' => 'название новой группы']);
// Удаление группы
$response = $smsAeroGroup->delete($id);
// Получение списка групп 
$response = $smsAeroGroup->getList();
```

## Работа с профилем
```php
$response = $smsAeroUser = new \SmsAero\SmsAeroUser('Ваш E-mail на сайте', 'apiKey можно посмотреть в личном кабинете в разделе настройки -> API и SMPP');
// Запрос баланса
$response = $smsAeroUser->getBalance();
// Запрос тарифа
$response = $smsAeroUser->getTariffs();
// Карты пользователя
$response = $smsAeroUser->getCards();
// Пополнение баланса
$response = $smsAeroUser->charge(['cardId' => $cardId, 'sum' => $sum]);
```

## Работа с Whatsapp сообщениями
```php
$response = $smsAeroWhatsapp = new \SmsAero\SmsAeroWhatsapp('Ваш E-mail на сайте', 'apiKey можно посмотреть в личном кабинете в разделе настройки -> API и SMPP');
// Отправка WhatsApp-сообщений
$response = $smsAeroWhatsapp->send([
    'sign' => 'ваша одобренная подпись',
    'address' => 'номер абонента',
    'contentType' => 'text',
    'text' => 'текст сообщения',
    // Параметры ниже поддерживаются только для шаблонных сообщений
    'buttons' => [
        [
            'buttonType' => 'URL',
            'text' => 'Как начать работу?',
            'url' => 'ссылка для кнопки'
        ],
        [
            'buttonType' => 'PHONE',
            'text' => 'Позвонить',
            'phone' => '+78005557550'
        ]
    ],
    'header' => [
        'imageUrl' => 'ссылка на изображения'
    ],
    'footer' => [
        'text' => 'текст подписи для сообщения'
    ]
]);
// Проверка статуса Whatsapp сообщения
$response = $smsAeroWhatsapp->getStatus('ID сообщения, который вернул сервис при отправке');
// Получение списка диалогов
$response = $smsAeroWhatsapp->getChats(['status' => 1, 'offset' => 100, 'limit' => 100]);
// Список сообщений по диалогу
$response = $smsAeroWhatsapp->getChatMsg('Идентификатор диалога, который вернул сервис при получении списка диалогов');
// Список всех сообщений 
$response = $smsAeroWhatsapp->getAllMsg(['offset' => 100, 'limit' => 100, 'timeFrom' => 1672527600, 'timeTo' => 1701385200]);
```

## Работа с Viber сообщениями
```php
$response = $smsAeroViber = new \SmsAero\SmsAeroViber('Ваш E-mail на сайте', 'apiKey можно посмотреть в личном кабинете в разделе настройки -> API и SMPP');
// Создание Viber-рассылки
$response = $smsAeroViber->send(['number' => 'номер абонента', 'sign' => 'имя отправителя', 'channel' => 'OFFICIAL', 'text' => 'текст сообщения']);
// Статистика по Viber-рассылке
$response = $smsAeroViber->getStat($id);
// Список Viber-рассылок
$response = $smsAeroViber->getList();
//Список доступных имен для Viber-рассылок
$response = $smsAeroViber->getSigns();
```

## Обработка ответа
```php
if ($response['success']) {
    echo 'Успешный запрос';
    // Массив с данными для дальнейшего действия
    print_r($response['data');
} else {
    echo 'Ошибка авторизации или валидации';
    // Массив или сообщение содержащие детальную информацию
    print_r($response['message']);
}
```
