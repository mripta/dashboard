#!/bin/bash

# Check if a version parameter is provided
if [ -z "$1" ]; then
  # If not provided, get the latest Git tag
  APP_VERSION=$(git describe --tags --abbrev=0)
else
  # Use the provided version parameter
  APP_VERSION="$1"
fi

# Update the .env file with the version
sed -i "s/'version' => '.*',/'version' => '${APP_VERSION}',/" config/app-version.php
