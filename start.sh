#!/bin/bash
php  php -S localhost:8000 -t public > slim.log 2>&1 &
echo "Slim et WebSocket sont démarrés."
