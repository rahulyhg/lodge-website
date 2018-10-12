#!/bin/bash -xe
# if [ "$CIRCLE_BRANCH" = 'develop' ]
#   then
    ENVIRONMENT="staging"
    PATH="~/apps/tahosalodge-staging"
# fi
# if [ "$CIRCLE_BRANCH" = 'master' ]
  # then
    # ENVIRONMENT="production"
    # PATH="~/apps/tahosalodge"
# fi
which rsync
rsync -avP --delete ./ tahosalodge@tahosa.co:$PATH

# if [ $? -eq 0 ]; then
    # echo 'Site deployed.'
# fi

