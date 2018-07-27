<?php

//require 'vendor/autoload.php';

use Sami\Sami;
use Sami\RemoteRepository\GitHubRemoteRepository;
use Symfony\Component\Finder\Finder;

$dir = __DIR__;

$iterator = Finder::create()
          ->files()
          ->name('*.php')
          ->exclude('Resources')
          ->exclude('Tests')
          ->in($dir . '/src/AppBundle');

$options = array(
    // 'theme' => 'symfony',
    'title' => 'NEWN Internals',
    'build_dir' => $dir . '/web/docs/sami',
    'cache_dir' => $dir . '/var/cache/sami',
    'default_opened_level' => 2,
    'include_parent_data' => true,
    'insert_todos' => true,
    'sort_class_properties' => true,
    'sort_class_methods' => true,
    'sort_class_traits' => true,
    'sort_class_interfaces' => true,
);

return new Sami($iterator, $options);
