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
