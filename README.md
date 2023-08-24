
## APP module dependencies direction 
![uLAB DDD - grupa 9 1 - IWW](https://github.com/coztymit/advanced-ddd-exchange-java/assets/79380870/c5585e38-0286-4d22-8e5a-072ca3af234e)


## What do you need to run this project
- PHP 8.2.8
- Symfony 6.3
- Composer 
- Docker 

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
- symfony/swiftmailer-bundle
- symfony/profiler-pack
- symfony/http-client


## Run project 
docker compose up -d 
docker compose exec php composer install
http://localhost


## Run rabbit and postgress 
run docker-compose.yml

### Swagger
http://localhost/api/doc

### MailCatcher
http://localhost:64185/

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

PESEL: 
73052358124

66082265528

79071495941

52032013341

01220623785

63121959617

## manual run db and postgress
docker run --name exchange-db_container -e POSTGRES_PASSWORD=sa -e POSTGRES_DB=sa -p 5432:5432 -d postgres
docker run -d --name exchange-rabbit -p 5672:5672 -p 15672:15672 rabbitmq:3-management


Docker compose
Role "postgres" does not exist.
docker-compose down and docker-compose up --force-recreate

Example dealer number: ABC-01-2023-123

Reported Doctrine issue with DQL (where with embedded Value object)
https://github.com/doctrine/orm/issues/10898

Verify - local and remote message routing 
https://symfony.com/doc/current/messenger.html


