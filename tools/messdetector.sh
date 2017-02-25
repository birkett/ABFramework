#!/bin/sh

echo "Generating report..."
phpmd ../ html cleancode,codesize,controversial,design,naming,unusedcode --reportfile phpmd.html --exclude tools
