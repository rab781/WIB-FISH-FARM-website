#!/bin/bash

# Parse MYSQL_URL if it exists
if [ -n "$MYSQL_URL" ]; then
    # Extract components from mysql://user:password@host:port/database
    DB_URL_PARSED=$(echo $MYSQL_URL | sed -e 's|mysql://||')
    
    export DB_USERNAME=$(echo $DB_URL_PARSED | cut -d: -f1)
    export DB_PASSWORD=$(echo $DB_URL_PARSED | cut -d: -f2 | cut -d@ -f1)
    export DB_HOST=$(echo $DB_URL_PARSED | cut -d@ -f2 | cut -d: -f1)
    export DB_PORT=$(echo $DB_URL_PARSED | cut -d: -f2 | cut -d/ -f1)
    export DB_DATABASE=$(echo $DB_URL_PARSED | cut -d/ -f2)
    export DB_CONNECTION=mysql
fi

# Clear config cache and run migrations
php artisan config:clear
php artisan migrate --force

# Start the server
php artisan serve --host=0.0.0.0 --port=$PORT
