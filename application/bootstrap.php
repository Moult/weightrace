<?php defined('SYSPATH') OR die('No direct script access.');

// -- Environment setup --------------------------------------------------------

// Load the core Kohana class
require SYSPATH.'classes/Kohana/Core'.EXT;

if (is_file(APPPATH.'classes/Kohana'.EXT))
{
    // Application extends the core
    require APPPATH.'classes/Kohana'.EXT;
}
else
{
    // Load empty core extension
    require SYSPATH.'classes/Kohana'.EXT;
}

/**
 * Set the default time zone.
 *
 * @link http://kohanaframework.org/guide/using.configuration
 * @link http://www.php.net/manual/timezones
 */
date_default_timezone_set('America/Chicago');

/**
 * Set the default locale.
 *
 * @link http://kohanaframework.org/guide/using.configuration
 * @link http://www.php.net/manual/function.setlocale
 */
setlocale(LC_ALL, 'en_US.utf-8');

/**
 * Enable the Kohana auto-loader.
 *
 * @link http://kohanaframework.org/guide/using.autoloading
 * @link http://www.php.net/manual/function.spl-autoload-register
 */
spl_autoload_register(array('Kohana', 'auto_load'));
spl_autoload_register(function($class) { Kohana::auto_load($class, 'vendor/Welgam/Core/src'); });

/**
 * Load vendor applications.
 */
// spl_autoload_register(function($class) { Kohana::auto_load($class, 'vendor/App/Core/src'); });

/**
 * Optionally, you can enable a compatibility auto-loader for use with
 * older modules that have not been updated for PSR-0.
 *
 * It is recommended to not enable this unless absolutely necessary.
 */
// spl_autoload_register(array('Kohana', 'auto_load_lowercase'));

/**
 * Enable the Kohana auto-loader for unserialization.
 *
 * @link http://www.php.net/manual/function.spl-autoload-call
 * @link http://www.php.net/manual/var.configuration#unserialize-callback-func
 */
ini_set('unserialize_callback_func', 'spl_autoload_call');

// -- Configuration and initialization -----------------------------------------

/**
 * Set the default language
 */
I18n::lang('en-us');

/**
 * Set Kohana::$environment if a 'KOHANA_ENV' environment variable has been supplied.
 *
 * Note: If you supply an invalid environment name, a PHP warning will be thrown
 * saying "Couldn't find constant Kohana::<INVALID_ENV_NAME>"
 */
if (isset($_SERVER['KOHANA_ENV']))
{
    Kohana::$environment = constant('Kohana::'.strtoupper($_SERVER['KOHANA_ENV']));
}

/**
 * Initialize Kohana, setting the default options.
 *
 * The following options are available:
 *
 * - string   base_url    path, and optionally domain, of your application   NULL
 * - string   index_file  name of your index file, usually "index.php"       index.php
 * - string   charset     internal character set used for input and output   utf-8
 * - string   cache_dir   set the internal cache directory                   APPPATH/cache
 * - integer  cache_life  lifetime, in seconds, of items cached              60
 * - boolean  errors      enable or disable error handling                   TRUE
 * - boolean  profile     enable or disable internal profiling               TRUE
 * - boolean  caching     enable or disable internal caching                 FALSE
 * - boolean  expose      set the X-Powered-By header                        FALSE
 */
Kohana::init(array(
    'base_url'   => '/weightrace/',
    'index_file' => ''
));

/**
 * Attach the file write to logging. Multiple writers are supported.
 */
Kohana::$log->attach(new Log_File(APPPATH.'logs'));

/**
 * Attach a file reader to config. Multiple readers are supported.
 */
Kohana::$config->attach(new Config_File);

/**
 * Enable modules. Modules are referenced by a relative or absolute path.
 */
Kohana::modules(array(
    // 'auth'       => MODPATH.'auth',       // Basic authentication
    // 'cache'      => MODPATH.'cache',      // Caching with multiple backends
    // 'codebench'  => MODPATH.'codebench',  // Benchmarking tool
       'database'   => MODPATH.'database',   // Database access
    // 'image'      => MODPATH.'image',      // Image manipulation
       'minion'     => MODPATH.'minion',     // CLI Tasks
    // 'orm'        => MODPATH.'orm',        // Object Relationship Mapping
    // 'unittest'   => MODPATH.'unittest',   // Unit testing
    // 'userguide'  => MODPATH.'userguide',  // User guide and API documentation
       'kostache'   => MODPATH.'kostache',   // Mustache templating
       'errorpage'  => MODPATH.'errorpage',  // Display custom 404 error pages
       'driver'     => MODPATH.'driver',     // Base set of drivers
    // 'cms'        => MODPATH.'cms',        // CMS editor for template files
    ));

/**
 * Set the routes. Each route must have a minimum of a name, a URI and a set of
 * defaults for the URI.
 */
Route::set('homepage', '')
    ->defaults(array(
        'controller' => 'create',
        'action'     => 'display',
    ));

Route::set('view', 'view/<competition_id>(/<racer_id>/<racer_password>)', array('competition_id' => '[0-9]*', 'racer_id' => '[0-9]*', 'racer_password' => '.*'))
    ->defaults(array(
        'controller' => 'view',
        'action'     => 'display',
    ));

Route::set('process racers', 'racers/process/<competition_id>(/<registrant_id>/<registrant_password>)')
    ->defaults(array(
        'controller' => 'racers',
        'action'     => 'process',
        'registrant_id' => NULL,
        'registrant_password' => NULL
    ));

Route::set('switch unit', 'switch')
    ->defaults(array(
        'controller' => 'unit',
        'action'     => 'switch'
    ));

/**
 * CMS module routes
 */
/*
Route::set('cms editor', 'cms/edit(/<template_path>)', array('template_path' => '.*'))
    ->defaults(array(
        'controller' => 'cms',
        'action'     => 'edit',
    ));
 */

/**
 * Catchall route
 */
Route::set('default', '(<template_path>)', array('template_path' => '.*'))
    ->defaults(array(
        'controller' => 'static',
        'action'     => 'loader',
    ));

/**
 * Salt used for storing cookies for sessions
 */
Cookie::$salt = 'pleasechangethistoanyrandomstring';

/**
 * Load composer dependencies
 */
if (file_exists(DOCROOT.'vendor/autoload.php'))
{
    require_once DOCROOT.'vendor/autoload.php';
}
