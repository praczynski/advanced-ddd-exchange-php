
## What do you need to run this project
- PHP 8.2.8
- Symfony 6.3
- Composer 
- Symfony CLI

### Dependencies 
- composer require symfony/orm-pack -W
- composer require doctrine/dbal
- composer require nelmio/api-doc-bundle 
- composer require symfony/twig-bundle 
- composer require symfony/asset
- composer require symfony/console
- composer require sensio/framework-extra-bundle 
- composer require ramsey/uuid-doctrine
- composer require php-amqplib/rabbitmq-bundle
- composer require symfony/serializer
- composer require symfony/property-access
- composer require brick/math


## Run project 
symfony server:start
symfony server:stop
http://127.0.0.1:8000


## Run rabbit and postgress 
run docker-compose.yml

### Swagger
http://127.0.0.1:8000/api/doc

### Rabbit
http://localhost:15672

## rabbit consumer start
cannot add rabbitmq-supervisor-bundle because symfony 6 

### Run consumers 
run script rabbit-consumers-run.sh

### Stop consumers
run script rabbit-consumers-stop.sh


verify running consumers
ps aux | grep rabbitmq:consumer

Stop consumers
ctrl+c or 
kill -9 <PID>

### Example UUID 

UUID: 3f6f8cb0-c8a8-4a94-a668-5d495be325f9

PESEL: 73052358124

## manual run db and postgress
docker run --name exchange-db_container -e POSTGRES_PASSWORD=sa -e POSTGRES_DB=sa -p 5432:5432 -d postgres
docker run -d --name exchange-rabbit -p 5672:5672 -p 15672:15672 rabbitmq:3-management


Docker compose
Role "postgres" does not exist.
docker-compose down and docker-compose up --force-recreate