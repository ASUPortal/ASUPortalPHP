#!/usr/bin/env bash

DBHOST=localhost
DBNAME=asu_portal
DBNAMESTATS=asu_stats
DBUSER=asu_user
DBPASSWD=asu_password

apt-get update

echo -e "\n--- Install MySQL specific packages and settings ---\n"
echo "mysql-server mysql-server/root_password password $DBPASSWD" | debconf-set-selections
echo "mysql-server mysql-server/root_password_again password $DBPASSWD" | debconf-set-selections
echo "phpmyadmin phpmyadmin/dbconfig-install boolean true" | debconf-set-selections
echo "phpmyadmin phpmyadmin/app-password-confirm password $DBPASSWD" | debconf-set-selections
echo "phpmyadmin phpmyadmin/mysql/admin-pass password $DBPASSWD" | debconf-set-selections
echo "phpmyadmin phpmyadmin/mysql/app-pass password $DBPASSWD" | debconf-set-selections
echo "phpmyadmin phpmyadmin/reconfigure-webserver multiselect none" | debconf-set-selections
apt-get -y install mysql-server-5.5 phpmyadmin > /dev/null 2>&1

echo -e "\n--- Setting up our MySQL user and db ---\n"
mysql -uroot -p$DBPASSWD -e "CREATE DATABASE $DBNAME"
mysql -uroot -p$DBPASSWD -e "grant all privileges on $DBNAME.* to '$DBUSER'@'localhost' identified by '$DBPASSWD'"

mysql -uroot -p$DBPASSWD -e "CREATE DATABASE $DBNAMESTATS"
mysql -uroot -p$DBPASSWD -e "grant all privileges on $DBNAMESTATS.* to '$DBUSER'@'localhost' identified by '$DBPASSWD'"

echo -e "\n--- Installing PHP-specific packages ---\n"
apt-get -y install php5 apache2 libapache2-mod-php5 php5-curl php5-gd php5-mcrypt php5-mysql php-apc > /dev/null 2>&1

echo -e "\n--- Enabling mod-rewrite ---\n"
a2enmod rewrite > /dev/null 2>&1

echo -e "\n--- Allowing Apache override to all ---\n"
sed -i "s/AllowOverride None/AllowOverride All/g" /etc/apache2/apache2.conf

echo -e "\n--- We definitly need to see the PHP errors, turning them on ---\n"
sed -i "s/error_reporting = .*/error_reporting = E_ALL/" /etc/php5/apache2/php.ini
sed -i "s/display_errors = .*/display_errors = On/" /etc/php5/apache2/php.ini

echo -e "\n--- Configure Apache to use phpmyadmin ---\n"
echo "Include /etc/phpmyadmin/apache.conf" >> /etc/apache2/apache2.conf

a2enconf phpmyadmin > /dev/null 2>&1

echo -e "\n--- Add environment variables to Apache ---\n"
cat > /etc/apache2/sites-enabled/000-default.conf <<EOF
<VirtualHost *:80>
    DocumentRoot /var/www
    ErrorLog \${APACHE_LOG_DIR}/error.log
    CustomLog \${APACHE_LOG_DIR}/access.log combined
    SetEnv APP_ENV $APPENV
    SetEnv DB_HOST $DBHOST
    SetEnv DB_NAME $DBNAME
    SetEnv DB_NAME_STATS $DBNAMESTATS
    SetEnv DB_USER $DBUSER
    SetEnv DB_PASS $DBPASSWD
</VirtualHost>
EOF

echo "export DB_HOST=$DBHOST" >> /etc/apache2/envvars
echo "export DB_NAME=$DBNAME" >> /etc/apache2/envvars
echo "export DB_NAME_STATS=$DBNAMESTATS" >> /etc/apache2/envvars
echo "export DB_USER=$DBUSER" >> /etc/apache2/envvars
echo "export DB_PASS=$DBPASSWD" >> /etc/apache2/envvars

echo -e "\n--- Restarting Apache ---\n"
service apache2 restart > /dev/null 2>&1

echo -e "\n--- Creating a symlink for future phpunit use ---\n"
ln -fs /vagrant/vendor/bin/phpunit /usr/local/bin/phpunit

echo -e "\n--- Add environment variables locally for artisan ---\n"
cat >> /home/vagrant/.bashrc <<EOF

apt-get install -y mc
apt-get install -y htop

rm -rf /var/www
ln -fs /vagrant /var/www