#!/bin/bash
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

IGNORE="public .env .git web/app/cache web/app/db.php web/app/advanced-cache.php web/user.ini web/.htaccess web/app/plugins/wp-rocket/cache.json web/app/uploads"

ssh-keyscan -H tahosa.co >> ~/.ssh/known_hosts
rsync -avP --delete --exclude $IGNORE ./ tahosalodge@tahosa.co:$DESTINATION

if [ $? -eq 0 ]; then
    echo 'Site deployed.'
fi

