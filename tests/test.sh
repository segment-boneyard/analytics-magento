#!/bin/sh

echo "Running unit tests: \n"
cd Unit/
phpunit --stderr
echo "\nRunning functional tests: \n"
cd ../Functional/
casperjs test --includes=config.js --url="$1" Tests/