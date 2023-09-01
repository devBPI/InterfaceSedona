#!/bin/bash

make install-wkhtmltopdf

echo "\033[30;48;5;82m > Wait to Postgres ready -------------------------------------------------------- \033[0m"; \
wait-for-it $POSTGRES_HOST:$POSTGRES_PORT -s -t 500 -- echo "        >  Postgres is ready"

echo "\033[30;48;5;82m > Install project -------------------------------------------------------- \033[0m"; \
if ! make install; then
    echo "\033[30;48;5;196m make returned an error                                    \033[0m"; \
fi
echo "\033[30;48;5;82m > Install project done -------------------------------------------------------- \033[0m"; \
