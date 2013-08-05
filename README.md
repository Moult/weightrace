# weightrace

WeightRace is a website for competitive weight loss.

# Installation

 1. `git submodule update --init --recursive`
 2. Verify `RewriteBase /` in `.htaccess`
 3. Verify `application/logs/` and `application/cache/` are writeable
 4. Configure everything in `application/config/*`
 5. Configure `application/bootstrap.php`
 6. Install `DATABASE.sql`

## Development

 1. `curl -s https://getcomposer.org/installer | php`
 2. `php composer.phar install --dev`
 3. `vim behat.yml`
 4. `vim build.xml`

`bin/behat` and `bin/phpspec` is now available to you. PHPSpec2 is set to load
classes in `application/classes/`.

`phing -projecthelp` lists all tools.

# Licenses

This software is open-source and free software. See `licenses/` for full text.

# Todo

WeightRace is currently alpha software and so is incomplete. It is usable for
testing purposes but the following features are missing:

 * Homepage latest losers not yet working
 * Newsfeed commentary not yet working on competition view page
 * Emails look ugly and don't show enough information
 * No edit functions exposed for editing racers and editing competitions
 * No delete functions exposed for deleting racers and deleting competitions
   and deleting updates
 * Add social media integration
