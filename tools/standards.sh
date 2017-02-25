#!/bin/sh

if [ -z $1 ]
then
    phpcs -i
    exit
else
    standard=${1}
fi

if [ -z $2 ]
then
    phpversion=""
else
    phpversion='--runtime-set testVersion '${2}
fi

echo "Testing for standard ${standard}"
phpcs --standard=${standard} ${phpversion} ../admin ../classes ../controllers ../models
