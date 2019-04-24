# CCMA Website Mock Environment
=============================

A simple docker environment to mock up the museum WordPress site for development, testing, and migrations.

Requirements:
- Docker (tested on 2.0.0.3)
- Docker compose (tested with 1.23.2)

## Usage
========
- `docker-compose up -d`

## Known Issues
===============
Lots of them:
- postflight.sh should be run in a custom entrypoint or just make our own dockerfile that uses wordpress:4.7.1
- With WP_DEBUG enabled and colbymuseum enabled as a theme we get wp_enqueue_style errors: `Notice: wp_style_is was called incorrectly. Scripts and styles should not be registered or enqueued until the wp_enqueue_scripts, admin_enqueue_scripts, or login_enqueue_scripts hooks. Please see Debugging in WordPress for more information. (This message was added in version 3.3.0.) in /var/www/html/wp-includes/functions.php on line 4136`. Might be needed-but-not-installed plugins?
- the WordPress entrypoint doesn't have an environment var for debug logging. Easy with our own entrypoint / image, copying the docker entrypoint sed magic for the wp-config parts (see https://github.com/docker-library/wordpress/blob/5fb8808fa31a48b20636b67f026cb18b8a75db94/docker-entrypoint.sh#L194)
- Should mock a colby.edu hostname for colbyTicket bits