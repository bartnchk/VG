## 1. Развернуть базу данных, дамп которой лежит в папке "db"
## 2. Указать следующие параметры в файле configuration.php

####конфигурация базы данных:
- public $host = '';
- public $user = '';
- public $password = '';
- public $db = '';

####папки:
- public $log_path = '/var/www/.../administrator/logs';
- public $tmp_path = '/var/www/.../tmp';

## 3. Локализация приложения
- #####en
/languages/en-GB/en-GB.com_plants.ini
- #####de 
/language/de-DE/de-DE.com_plants.ini

## 4. Необходимые права для пользователя базы данных 
- SELECT – Позволяет пользователям делать выборку данных
- INSERT – Позволяет пользователям добавлять новые записи в таблицы
- UPDATE – Позволяет пользователям изменять существующие записи в таблицах
- DELETE – Позволяет пользователям удалять записи из таблиц
