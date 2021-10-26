## Docker Installation

- git clone git@github.com:sahedbs23/php-design-pattern-test.git HIRA
- `cd HIRA`
- `docker-compose up -d`
- `docker exec -it HIRA /bin/bash`
- `composer install`
- `php artisan key:generate`
- 

### Calculate commission
- Browse Application: http://localhost:8888
- Upload csv file
- Submit form
- You can see the commission for each row

## Run Unit test

- `docker exec -it HIRA /bin/bash`
- `./vendor/bin/phpunit`
  

