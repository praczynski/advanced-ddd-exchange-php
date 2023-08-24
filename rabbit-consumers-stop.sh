#!/bin/bash

# Wybieramy procesy pasujące do wzorca 'rabbitmq:consumer'
ps aux | grep 'rabbitmq:consumer' | grep -v grep | while read -r line ;
do
    # Wyciągamy ID procesu (PID)
    pid=$(echo $line | awk '{print $2}')

    # Zatrzymujemy proces
    echo "Zatrzymuję proces o PID: $pid"
    kill $pid
done

echo "Wszystkie procesy konsumentów zostały zatrzymane"