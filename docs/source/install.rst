.. _install:

Installation
============

.. note::

    New Narratives doesn't use labeled or numbered releases. The code in the
    master branch of the repository should be runnable.

Make sure the requirements are satisfied.

The New Narratives application is based on Symfony 3.2. Installation follows the 
normal process for installing a Symfony application.

1. Get the code from GitHub. 

.. code-block:: bash

  git clone https://github.com/sfu-dhil/btd.git

2. Get the submodules from Git. There is quite a bit of reusable code in the
   application, and it's organized with git submodules.

.. code-block:: bash

  git submodule init
  git submodule update --recursive --remote

3. Create a database and database user.
  
.. code-block:: sql

  create database btd;
  grant all on btd.* to btd@localhost;
  set password for btd@localhost = password('hotpockets');

4. `Install composer`_ if it isn't already installed somewhere.
  
5. Install the composer dependencies. Composer will ask for some 
   configuration variables during installation.
  
.. code-block:: bash

  ./vendor/bin/composer install --no-dev -o
   
Sometimes composer runs out of memory. If that happens, try this alternate.
  
.. code-block:: bash

  php -d memory_limit=-1 ./vendor/bin/composer install --no-dev -o

6. Update file permissions. The user running the web server must be
   able to write to `var/cache/*` and `var/logs/*` and
   `var/sessions/*`. The symfony docs provide `recommended commands`_
   depending on your OS.
  
7. Load the schema into the database. This is done with the 
   symfony console.
  
.. code-block:: bash

  ./bin/console doctrine:schema:update --force
  
8. Create an application user with full admin privileges. This is also done 
   with the symfony console.
  
.. code-block:: bash

  ./bin/console fos:user:create --super-admin  
  
9. Install bower, npm, and nodejs if you haven't already. Then use bower to 
   download and install the javascript and css dependencies.
  
.. code-block:: bash

  bower install

10. Download and install CkEditor. It is a nice GUI editor for web content. It isn't redistributable itself, so must be downloaded separately.

.. code-block:: bash

  ./bin/console ckeditor:install
  ./bin/console assets:install web --symlink

11. Configure the web server. The application's `web/` directory must be accessible to the world. Symfony provides `example configurations`_ for most server setups.

12. The documentation module should be built seperately. You need the Sphinx to be already installed. Check the `DHIL Documentation Guide`_ for more information. 

Navigate to the 'newn/docs' directory in the command line and type: 

.. code-block:: bash

  make html

13. Start the web server you are using. A quick way to run the application is by using the built-in php server.

.. note:: I've you've configured Apache to run the site, this step isn't necessary. Visit http://localhost/path/to/app_dev.php.

.. code-block:: bash

  php bin/console server:start

Go to the localhost:8000 in your browser.

At this point, the web interface should be up and running, and you should be able to login by following the Login link in the top right menu bar.

That should be it.

.. _`XAMPP`: https://www.apachefriends.org/download.html

.. _`WAMP`: http://www.wampserver.com/en/

.. _`Install Composer`: https://getcomposer.org/download/

.. _`recommended commands`: http://symfony.com/doc/current/setup/file_permissions.html

.. _`example configurations`: http://symfony.com/doc/current/setup/web_server_configuration.html

.. _`DHIL Documentation Guide`: https://github.com/sfu-dhil/dhil-docs-guide

.. _`Install composer`: https://getcomposer.org/download/

.. _`recommended commands`:
   http://symfony.com/doc/current/setup/file_permissions.html

.. _`example configurations`:
   http://symfony.com/doc/current/setup/web_server_configuration.html
