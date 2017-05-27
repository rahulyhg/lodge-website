<?php
/** Staging */
ini_set('display_errors', 0);
define('WP_DEBUG_DISPLAY', false);
define('SCRIPT_DEBUG', false);
/** Disable all file modifications including updates and update notifications */
define('DISALLOW_FILE_MODS', true);

define( 'S3_UPLOADS_BUCKET', 'tahosawp' );
define( 'S3_UPLOADS_KEY', 'AKIAIJD5HM4AU6RNDV4Q' );
define( 'S3_UPLOADS_SECRET', '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' );
define( 'S3_UPLOADS_REGION', 'us-west-2' );
