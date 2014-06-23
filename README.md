douglas
=======
Requirements:
*Laravel
*Composer
*YAML

User PEAR/PECL to configure PHP dependencies:
http://jason.pureconcepts.net/2012/10/install-pear-pecl-mac-os-x/

#Install YAML
pecl install yaml

Setup Database
=======
##Prevent Class Errors

composer dump-autoload

##Run Database Migrations

php artisan migrate

##Seed the Database

php artisan db:seed



ToDo:
=======

#Install Horde Text Diff
http://www.horde.org/libraries/Horde_Text_Diff/download
More Work to be done here.
