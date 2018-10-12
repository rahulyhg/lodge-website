#!/bin/bash -xe
if [ "$CIRCLE_BRANCH" = 'develop' ]
  then
    ENVIRONMENT="staging"
    DESTINATION="~/apps/tahosalodge-staging"
fi
if [ "$CIRCLE_BRANCH" = 'master' ]
  then
    ENVIRONMENT="production"
    DESTINATION="~/apps/tahosalodge"
fi
which rsync
rsync -avP --delete ./ tahosalodge@tahosa.co:$DESTINATION

if [ $? -eq 0 ]; then
    echo 'Site deployed.'
fi

