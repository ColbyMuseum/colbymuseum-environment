#!/bin/bash

# postflight.sh

# - Installs colbycollege and colbymuseum themes from the repos
git clone https://github.com/ColbyCommunications/colbycollege wp-content/themes/
git clone https://github.com/ColbyCommunications/colbymuseum wp-content/themes/

# - Installs composer (FIXME: run this as docker exec, but first name the containers)
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === '48e3236262b34d30969dca3c37281b3b4bbe3221bda826ac6a9a62d6444cdb0dcd0615698a5cbe587c3f0fe57a54d8f5') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"

mv composer.phar /usr/local/bin/composer

# - Install the PHP plugins
cd wp-content/themes/colbycollege
composer install
cd -
cd wp-content/themes/colbymuseum
composer install

# - FIXME: Run npm in colbycollege and colbymuseum
# - FIXME: Run webpack in the colbycollege dir (to knock out wp_enque_style errs?)
# - FIXME: Run gulp in the colbymuseum dir
# - FIXME: Populates mock content for Museum theme