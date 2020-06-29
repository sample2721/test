# Тестовое задание

## Файлы

resources/

    log1 - формат: дата|время|ip|url откуда пришёл|url куда пришёл
    log2 - формат: ip|браузер|ос
    create_tables.sql - sql создания таблиц log1 и log2

www/

    js/app.js - js код фронтенда
    index.html - скрипт запуска фротенда
    data.php - скрипт бекэнда для обработки запросов с фронтенда
    
/

    db.php  - конфиг для бд
    parse_log.php - парсер логов и записи в бд
 
tools/
    
    logs_generator.php - генератор тестовых логов
