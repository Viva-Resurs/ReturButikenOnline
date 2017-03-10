#!/bin/sh

# update apt
sudo apt-get update

# Add node PPA
curl -sL https://deb.nodesource.com/setup_7.x | sudo -E bash -

# Start to install packages
sudo apt-get install apache2
sudo apt-get install php
sudo apt-get install libapache2-mod-php
sudo apt-get install php-gd php-sqlite php7.0-mbstring php-xml
sudo apt-get install git
sudo apt-get install nodejs

# Get the git repo
git clone https://github.com/Viva-Resurs/ReturButikenOnline.git

# Setup apache
sudo rm -rf /var/www/html
sudo ln -s ~/ReturButikenOnline/public /var/www/html

# Install composer
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
sudo composer self-update

# Start Installing the repo
(cd ~/ReturButikenOnline && mkdir public/js)
(cd ~/ReturButikenOnline && npm install)
(cd ~/ReturButikenOnline && composer install)

# Set permissions
sudo chmod 777 -R ~/ReturButikenOnline/storage
