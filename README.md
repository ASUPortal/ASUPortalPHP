Портал кафедры автоматизированных систем управления
=========
Уфимский Государственный Авиационный Технический Университет

Версия на базе PHP

Если Вы хотите присоединиться к проекту - пишите по электронной почте barmin.alexander@gmail.com или
startsev@gmail.com для получения необходимых файлов для начала разработки. Для корректной работы необходим Vagrant или XAMPP


Организация стенда разработки
=========

1. Склонировать репозиторий на локальный компьютер
2. Создать две базы данных и загрузить в них дампы:
  * БД Портала: https://github.com/ASUPortal/ASUPortalPHP/blob/master/_install/asu_portal_demo.sql
  * БД Статистики: https://github.com/ASUPortal/ASUPortalPHP/blob/master/_install/asu_stats.sql
3. Установить переменные окружения для соединения с базой данных портала:
  * DB_HOST
  * DB_NAME
  * DB_USER
  * DB_PASS
4. Установить переменные окружения для соединения с базой данных статистики:
  * DB_NAME_STATS
5. В таблице settings БД Портала отредактировать записи со следующими alias:
  * web_root - должно содержать полный url-адрес к корневой директории инсталляции
  * inner_url_name - должно совпадать с web_root
6. Для входа в административный раздел использовать логин и пароль admin

Метрики
=========

[![Build Status](https://travis-ci.org/ASUPortal/ASUPortalPHP.svg?branch=master)](https://travis-ci.org/ASUPortal/ASUPortalPHP) - статус сборки