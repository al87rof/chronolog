docker-compose down
docker-compose up -d --build


docker system prune -a
docker volume prune
prune -a → удаляет все остановленные контейнеры и неиспользуемые образы
volume prune → удаляет все неиспользуемые volume

docker exec -it symfony_app bash  - вызвать консоль контейнера

docker exec -it symfony_app chown -R www-data:www-data /var/www/var
docker exec -it symfony_app chmod -R 775 /var/www/var
docker exec -it symfony_app chmod -R 777 /var/www/var/cache

wsl --shutdown

wsl -d Ubuntu


make:migration — генерирует новый файл миграции на основе изменений в сущностях.
doctrine:migrations:migrate — применяет все новые миграции к базе данных.
doctrine:migrations:status — показывает текущее состояние (какие миграции уже выполнены).
doctrine:migrations:execute --down [версия] — позволяет откатить конкретную миграцию, если что-то пошло не так.
