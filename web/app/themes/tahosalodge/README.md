# Kevin's WordPress Child Theme
A WordPress child theme, featuring Gulp, SASS, an object-oriented functions.php file, and support for [Soil](http://roots.io). ESLint and Style Lint configuration files are included for Atom's linting plugins.

## Setup
You'll need to have node and npm installed.

1. Clone this repository.

2. In the cloned directory, run the following: `rm -rf .git && npm install`

3. Rename class in `functions.php` at the top and bottom of the file.

4. Make any changes required in `assets/scss/style.scss` for your theme (name, parent, etc).


### Gulp Tasks
Configuration is centrally located in `gulpConfig.json`

Gulp tasks are organized in a modular structure, using 'gulp-load-plugins' and 'gulp-task-loader'. You'll find all tasks other than default in 'gulp-tasks/' following this structure:
```
├── images
│   ├── raster.js
│   └── vector.js
├── scripts
│   ├── app.js
│   ├── custom.js
│   └── vendor.js
├── browser-sync.js
└── scss.js
```
Each file in this folder is converted to a task. See existing tasks for proper formatting.

#### Images
These tasks optimize all images in `assets/img/raw/` using either imagemin or svgmin. Subdirectories are respected, `assets/img/upload/` is ignored by default so you can optimize images that you'll upload in to WordPress, without filling up your repository with them.

#### Scripts
These tasks concatenate all scripts in `assets/js/custom/` and `assets/js/vendor/` down to `custom.min.js` and `vendor.min.js`, before finally compiling down to `app.min.js` which includes both of them. Non-minified versions are also available for debugging. All of the custom scripts are wrapped in a jQuery `document.ready()`

#### SASS
This task compiles all non-partial files in `assets/scss/` to the root directory, `style.scss` by default. Sourcemaps are created, and vendor prefixes are automatically included by `gulp-autoprefixer`.

#### Browser Sync
Browser Sync is setup to proxy a development url, as configured in `gulpConfig.json`.

## Functions

`mime_types()` - adds SVG as an accepted upload type.

`add_slug_to_body_class()` - adds a class to the body, based on post type & slug, eg. `.page-about`

`custom_headers()` - sets `Access-Control-Allow-Origin: '*'`

`roots_support()` - adds support for Soil. See https://roots.io/plugins/soil/ for more information, comment/uncomment as desired.

`typekit()` - easily add a Typekit Font. Replace `$kit_id` with your own.
