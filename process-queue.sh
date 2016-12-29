#!/bin/bash

if [[ $1 != "" ]]; then

	while (true) 
	do
		./app/console rabbitmq:multiple-consumer --messages=10 --memory-limit=2G $1_link_analysis
	done
fi
