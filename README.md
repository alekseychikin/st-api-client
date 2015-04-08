# СаранскТудэй API Client
## PHP-реализация
### Документация <http://saransktoday.ru/api/1.0/>


## Список категорий

Получить экземпляр объекта:

```
$categories = STAPIClient::categorieslist()
```

Выставить опции запроса:
```
$categories = $categories
  ->order('asc')
  ->offset(3)
  ->limit(12);
```

Все методы, кроме `exec()`, возвращают метку `$this`, поэтому их можно компоновать в цепочки.

Сделать запрос:
```
$categories = $categories->exec($error);
```

Если что-то пойдёт не так, в переменной `$error` будет текст ошибки, иначе её значение будет `false`.

В переменной `$categories` будет массив из категорий.

## Список новостей

Получить экземпляр объекта:
```
$events = STAPIClient::eventslist()
```

Поддерживаемые методы для опций запроса:

```
$events = $events
  ->offset(1)
  ->limit(8)
  ->order('desc')
  ->category(23)
  ->date(date('Y-m-d'))
  ->periodStart('2015-01-01')
  ->periodEnd('2015-02-02')
  ->exec($error);
```

## Новость подробно

Получить экземпляр объекта:
```
$event = STAPIClient::event()
```

Единственная обязательная опция:
```
$event = STAPIClient::event()->id(1268)->exec();
```
