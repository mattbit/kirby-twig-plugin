# Twig plugin for Kirby

This plugin brings the [Twig templating](http://twig.sensiolabs.org/) to [Kirby CMS](http://getkirby.com/).

It is based on Matthew Spencer's [kirby twig plugin](https://github.com/matthewspencer/kirby-twig-plugin), whom I thank for his great work.

## Installation

You must use [composer](https://getcomposer.org/) to install this plugin. 

Create a file names `composer.json` in the kirby root directory, with the following content:

```json
{
	"repositories": [
		{
			"type": "vcs",
			"url": "https://github.com/mattbit/kirby-twig-plugin"
		}
	],
	"require": {
		"mattbit/kirby-twig-plugin": "dev-master",
		"composer/installers": "~1.0"
	},
	"extra": {
		"installer-paths": {
			"site/plugins/{$name}/": ["type:kirby-plugin"]
		}
	},
	"minimum-stability": "dev"
}
```

Then run `php composer.phar install`.

### Configuration 

You must set some config variables to use the plugin.

Add the following lines to `/site/config/config.php`.

```php
// Kirby root path (used to require the composer autoloader)
c::set('kirby.path', __DIR__ . '/../..');

// The twig templates directory
c::set('twig.templates.path', __DIR__ . '/../twig_templates');

// Twig cache directory
c::set('twig.cache.path', __DIR__ . '/../cache/twig');
```

## Usage

### Render templates

You can render the twig template from the original kirby template:

```php
<?php
// /site/templates/mypage.php

// Get the twig plugin
$twigPlugin = c::get('twig_plugin');

// Print the twig template
echo $twigPlugin->renderTwigTemplate($page);
```

If you want to use twig for every page put the code above in `/site/templates/default.php`.

The twig template name is resolved in the same way kirby does, so if you have a page `/content/home/home.txt` the plugin will look for `/site/twig_templates/home.html.twig`, and if it does not exists will render the `/site/twig_templates/default.html.twig`.

By default the `page` variable is available in the twig templates, but you may want to use other variables: you can pass them as a second argument for the `renderTwigTemplate($page, $extraVariables)`.

```php
<?php

$twigPlugin = c::get('twig_plugin');

$variables = array(
	'site' => $site,
	'pages' => $pages,
	'foo' => 'bar'
);


// site, pages and foo will be available in the twig template
echo $twigPlugin->renderTwigTemplate($page, $variables);
```

```twig
{% extends "layout.html.twig" %}
{% block content %}
	<h1>{{ site.title }}</h1>
	<p>{{ foo }}</p>
{% endblock %}
```

### Kirby functions

Some basic kirby functions are available in twig by default:

- `css`
-  `js`
- `url`
- `thumb`
- `kirbytext`
- `e`
- `l`

You can use them as simple functions inside the template:
```twig
{% extends "layout.html.twig" %}
{% block content %}
	{{ l("This string will be translated by kirby") }}

	{{ kirbytext(page.content) }}
{% endblock %}
```

You can easily add other functions before rendering the template using the method `addFunction($name, $callable, $options)`, where `$name` is how the function will be called inside the templates, `$callable` is the function, `$options` are the twig options.

So, for example:

```php
<?php

$twigPlugin = c::get('twig_plugin');

$twigPlugin->addFunction('k', 'kirbytext', array('is_safe' => array('html')))

echo $twigPlugin->renderTwigTemplate($page);
```