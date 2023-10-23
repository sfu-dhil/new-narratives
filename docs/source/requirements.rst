.. _requirements:

Requirements
============

PHP 5.5.9 or later is required.

The application takes uploads of media files (image, video, audio, and
PDF) and generates thumbnails of those files. ImageMagick provides the
thumbnailing, along with some external libraries for video and PDF.

* `MySQL`_ or `MariaDB`_.

PHP dependencies, other than imagick above, are managed with
`Composer`_. The Symfony framework has additional requirements for
PHP. Composer should be installed globally.

Javascript and CSS dependencies are managed with `Bower`_, which requires
`NPM`_ and `NodeJS`_.

.. _MySQL: https://www.mysql.com/downloads/
.. _MariaDB: https://mariadb.org/
.. _Composer: https://getcomposer.org/
.. _Bower: https://bower.io/
.. _NPM: https://www.npmjs.com/
.. _NodeJS: https://nodejs.org/en/
