<?php
/** Staging */
ini_set('display_errors', 0);
define('WP_DEBUG_DISPLAY', false);
define('SCRIPT_DEBUG', false);
/** Disable all file modifications including updates and update notifications */
define('DISALLOW_FILE_MODS', true);

define( 'S3_UPLOADS_BUCKET', 'tahosawp' );
define( 'S3_UPLOADS_KEY', 'AKIAIIMADUSJOGB44BCA' );
define( 'S3_UPLOADS_SECRET', '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' );
define( 'S3_UPLOADS_REGION', 'us-west-2' );
