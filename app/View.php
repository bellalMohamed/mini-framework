<?php

namespace App;

class View
{
	protected $statusCode = 200;
	protected $view;
	protected $viewVariables;


	public function load($view, array $variables = [])
	{
		$this->view = $view;
		$this->viewVariables = $variables;
		return $this;
	}

	public function getViewPath()
	{
		return "Views/{$this->view}.php";
	}

	public function compileView()
	{
		$path = $this->getViewPath();
		$this->includeWithVariables($path, $this->viewVariables);
	}

	protected function includeWithVariables($filePath, $variables = array(), $print = true)
	{
		$realPath = dirname(__FILE__) .'/'. $filePath;
	    
	    $output = NULL;
	    if(file_exists($realPath)){
	        extract($variables);
	        ob_start();
	        include $realPath;
	        $output = ob_get_clean();
	        echo ($output);
	    }
	    
	    return $this;
	}

	public function withStatus($statusCode)
	{
		$this->statusCode = $statusCode;
		return $this;
	}

	public function getStatusCode()
	{
		return $this->statusCode;
	}
}