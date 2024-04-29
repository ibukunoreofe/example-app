#!/bin/bash

# Load environment variables from .env file
if [ -f .env ]; then
    export $(cat .env | xargs)
else
    echo ".env file not found."
    exit 1
fi

# Optionally enable debug mode
# Run Artillery with output to a JSON file
#
# Here's what this command does:
#
# > redirects stdout to inspect.log.
# 2>&1 redirects stderr (file descriptor 2) to stdout (file descriptor 1), which has already been redirected to inspect.log.

DEBUG=http:* artillery run --output report.json Books.yml > inspect.log 2>&1
#DEBUG=artillery:* artillery run --output report.json Books.yml > inspect.log

# Generate HTML
artillery report report.json

