New Narratives
==============

An open access, searchable database that will house and deliver a
comprehensive bibliography of works by early modern women.

This application was developed by the Digital Humanities Innovation Lab at 
Simon Fraser University with considerable assistance and support from the SFU
Library.

Installation
----

The WPHP application is based on Symfony 3.2. Installation follows the normal
process for installing a Symfony application.

1. Get the code from GitHub. 
  
  ```bash
  git clone https://github.com/sfu-dhil/newn.git
  ```

1. Get the submodules from Git. There is quite a bit of reusable code in the
application, and it's organized with git submodules.

  ```bash
  git submodule init
  git submodule update --recursive --remote
  ```

1. Create a database and database user.
  
  ```sql
  create database newn;
  grant all on newn.* to newn@localhost;
  set password for newn@localhost = password('hotpockets');
  ```

1. [Install composer](https://getcomposer.org/download/), if it isn't already 
   installed somewhere.
  
1. Install the composer dependencies. Composer will ask for some 
   configuration variables during installation.
  
  ```bash
  ./vendor/bin/composer install --no-dev -o
  ```
  
  Sometimes composer runs out of memory. If that happens, try this alternate.
  
  ```bash
  php -d memory_limit=-1 ./vendor/bin/composer install --no-dev -o
  ```

1. Update file permissions. The user running the web server must 
  be able to write to `var/cache/*` and `var/logs/*` and `var/sessions/*`. The 
  symfony docs provide [recommended commands](http://symfony.com/doc/current/setup/file_permissions.html),
  depending on your OS.
  
1. Load the schema into the database. This is done with the 
  symfony console.
  
  ```bash
  ./bin/console doctrine:schema:update --force
  ```
  
1. Create an application user with full admin privileges. This is also done 
  with the symfony console.
  
  ```bash
  ./bin/console fos:user:create --super-admin  
  ```
  
1. Install bower, npm, and nodejs if you haven't already. Then use bower to 
  download and install the javascript and css dependencies.
  
  ```bash
  bower install
  ```

1. Configure the web server. The application's `web/` directory must
  be accessible to the world. Symfony 
  provides [example configurations](http://symfony.com/doc/current/setup/web_server_configuration.html)
  for most server setups.
  
At this point, the web interface should be up and running, and you should
be able to login by following the Login link in the top right menu bar.

Updates
----

Applying updates from git shouldn't be difficult.

1. Get the updates from a git remote

  ```bash
  git pull
  ```

1. Update the git submodules.

  ```bash
  git submodule update --recursive --remote
  ```

1. Install any updated composer dependencies.

  ```bash
  php -d memory_limit=-1 ./vendor/bin/composer install -o
  ```

1. Apply any database schema updates

  ```bash
  ./bin/console doctrine:schema:update --force
  ```
  
1. Update the web assets.
  
  ```bash
  bower install
  ```

1. Clear the cache 

  ```
  ./bin/console cache:clear --env=prod
  ```

That should be it.
