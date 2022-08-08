## Разворачивание проекта

- Склонировать репозиторий
- Создать .env файл в корне на основе .env.example
- В корне проекта запустить команду `docker-compose up -d`
- Внутри контейнера php(`docker exec -it twittest-php bash`) выполнить команды:
    - `composer install`
    - `php artisan key:generate`
    - `php artisan optimize`
    - `php artisan storage:link`
    - `php artisan migrate --seed`
- Далее потребуется единоразово перезапустить контейнеры:
    - `docker-compose down`
    - `docker-compose up -d`
    
Сайт должен быть доступен по адресу http://localhost:11770, если вы не меняли порт в .env