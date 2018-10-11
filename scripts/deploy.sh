#!/bin/bash
if [ "$CIRCLE_BRANCH" = 'develop' ]
  then
    ENVIRONMENT="staging"
    PATH="~/apps/tahosalodge-staging"
fi
if [ "$CIRCLE_BRANCH" = 'master' ]
  then
    ENVIRONMENT="production"
    PATH="~/apps/tahosalodge"
fi

lftp sftp://tahosalodge@tahosa.co -e '
set sftp:auto-confirm yes;
mirror -v -R --parallel=4 ./ $PATH --exclude-glob node_modules/
quit
'

if [ $? -eq 0 ]; then
    echo 'Site deployed.'
fi

