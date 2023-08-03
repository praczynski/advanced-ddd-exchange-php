#!/bin/bash

consumers=("identity_consumer" "identity_consumer" "identity_consumer")

for consumer in ${consumers[@]}
do
    echo "Uruchamiam konsumenta: $consumer"
    nohup php bin/console rabbitmq:consumer $consumer > /dev/null 2>&1 &
done

echo "Wszystkie procesy konsumentów zostały uruchomione"