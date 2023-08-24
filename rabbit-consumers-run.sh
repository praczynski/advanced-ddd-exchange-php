#!/bin/bash

consumers=("identity_consumer_account" "identity_consumer_promotion" "negotiation_created_consumer_promotion" "account_activated_consumer_promotion")

for consumer in ${consumers[@]}
do
    echo "Uruchamiam konsumenta: $consumer"
    nohup php bin/console rabbitmq:consumer $consumer > /dev/null 2>&1 &
done

echo "Wszystkie procesy konsumentów zostały uruchomione"