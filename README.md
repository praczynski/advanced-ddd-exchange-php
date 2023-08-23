
## APP module dependencies direction 
![uLAB DDD - grupa 9 1 - IWW](https://github.com/coztymit/advanced-ddd-exchange-java/assets/79380870/c5585e38-0286-4d22-8e5a-072ca3af234e)


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
- symfony/swiftmailer-bundle
- symfony/profiler-pack
- symfony/http-client


## Run project 
symfony server:start
symfony server:stop
http://127.0.0.1:8000


## Run rabbit and postgress 
run docker-compose.yml

### Swagger
http://127.0.0.1:8000/api/doc

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

### Excercise 1 
On /accounts/buyCurrency/XYZ-01-2023-123'
send:

{
"value": "10",
"currency": "PLN",
"rateCurrencyToBuy": "PLN",
"rateCurrencyToSell": "EU",
"rateValue": "4.2"
}

### Excercise 2

On /accounts/transfer/{fromAccountId}/{toAccountId}
send:

{
"amount": "10",
"currency": "PLN"
}


### Excercise 3 

1. Add currency pair

On /currency-pair/add
send: 

{
"baseCurrency": "PLN",
"targetCurrency": "EUR"
}


2. Create negotiation
On /negotiations/create
Send

{
"identityId": "123e4567-e89b-12d3-a456-426655440002",
"baseCurrency": "PLN",
"targetCurrency": "EUR",
"proposedExchangeAmount": "50",
"proposedExchangeCurrency": "EUR",
"proposedRate": "0.22"
}

3. Check database

select * from negotiations; 

select * from risk_assessments;

4. Automatice approve negotiation
On /negotiations/create
   Send

{
"identityId": "123e4567-e89b-12d3-a456-426655440002",
"baseCurrency": "PLN",
"targetCurrency": "EUR",
"proposedExchangeAmount": "100000",
"proposedExchangeCurrency": "EUR",
"proposedRate": "0.22"
}

4.1 Manual negotiation approve
send request on /negotiations/approve

check db select * from negotiations;

5. check database

select * from negotiations;
select * from risk_assessments;