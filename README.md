### Initialization

- rename env.dist to .env
- docker-compose pull
- docker-compose build
- docker-compose up -d
- docker-compose exec web php bin/console d:s:u

### Create an administrator
- docker-compose exec web composer install
- docker-compose exec web  php bin/console app:create-admin admin@admin.com admin


