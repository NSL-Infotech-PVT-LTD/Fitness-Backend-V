cd /var/www/dev/tourney 
git pull origin development-0.0.1
composer install
sudo chmod -R 0777 /var/www/dev/tourney/storage /var/www/dev/tourney/bootstrap 
/usr/bin/php /var/www/dev/tourney/artisan migrate
exit

