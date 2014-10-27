<?php

if ( ! file_exists(c::get('kirby.path') . '/vendor/autoload.php')) {
	die('Cannot find composer autoload.');
}

require_once(c::get('kirby.path') . '/vendor/autoload.php');

$options = array(
    'debug' => c::get('debug'),
    'cache' => c::get('twig.cache.path')
);

$twigPlugin = new Kirby\Plugin\TwigPlugin(c::get('twig.templates.path'), $options);

$twigPlugin->addFunction('css', 'css', array('is_safe' => array('html')))
           ->addFunction('js', 'js', array('is_safe' => array('html')))
           ->addFunction('url', 'url')
           ->addFunction('thumb', 'thumb')
           ->addFunction('kirbytext', 'kirbytext', array('is_safe' => array('html')))
           ->addFunction('e', 'e')
           ->addFunction('l', 'l');

c::set('twig_plugin', $twigPlugin);