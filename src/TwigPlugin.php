<?php namespace Kirby\Plugin;

use Twig_Loader_Filesystem;
use Twig_Environment;
use Twig_Extension_Debug;
use Twig_SimpleFunction;

class TwigPlugin {

	protected $twig;

	protected $templatesPath;
	
	protected $options;
	
	protected $functions = array();

	public function __construct($templatesPath, $options = array()) {
		$this->templatesPath = $templatesPath;
		$this->options = $options;
	}

	public function renderTwigTemplate($page, $variables = array())
	{
		$this->getTwig();
		$template = $this->twigTemplate($page);

		return $this->twig->render($template, array_merge(array('page' => $page), $variables));
	}

  	public function twigTemplate($page) {

	    $templateName = $page->intendedTemplate();

	    if (file_exists($this->templatesPath . '/' . $templateName . '.html.twig')) {
	    	return $templateName . '.html.twig';
	    }

	    return 'default.html.twig';
	}

	public function getTwig()
	{
		if ( ! is_object($this->twig))
		{
			$this->createTwigEnvironment();	
		}

		return $this->twig;
	}

	protected function createTwigEnvironment()
	{
		$loader = new Twig_Loader_Filesystem($this->templatesPath);

		$this->twig = new Twig_Environment($loader, $this->options);

		if (isset($this->options['debug']) && $this->options['debug']) {
			$this->twig->addExtension(new Twig_Extension_Debug());
		}

		$this->loadFunctions();
		
		return $this->twig;
	}

	protected function loadFunctions()
	{
		foreach($this->functions as $name => $function) {
			$function = new Twig_SimpleFunction($name, $function['callable'], $function['options']);
			$this->twig->addFunction($function);
		}
	}

	public function addFunction($name, $callable = null, $options = array()) {
		if ( ! $callable)
		{
			$callable = $name;
		}

		$this->functions[$name] = compact('callable', 'options');

		return $this;
	}
}