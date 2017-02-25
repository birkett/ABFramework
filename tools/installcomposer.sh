#!/bin/sh

php -r "copy('https://composer.github.io/installer.sig', 'installer.sig');"
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"

EXPECTED_SIGNATURE=$(php -r "echo file_get_contents('installer.sig');")
ACTUAL_SIGNATURE=$(php -r "echo hash_file('SHA384', 'composer-setup.php');")

if [ "$EXPECTED_SIGNATURE" != "$ACTUAL_SIGNATURE" ]
then
    >&2 echo 'ERROR: Invalid installer signature'
    rm composer-setup.php installer.sig
    exit 1
fi

php composer-setup.php
RESULT=$?
rm composer-setup.php installer.sig

mkdir -p vendor/bin
mv composer.phar vendor/bin/composer

exit $RESULT
