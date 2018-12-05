<?php 

namespace App;

use App\Exceptions\RouteNotFoundException;

class App
{
	public $container;

	public function __construct()
	{
		$this->container = new Container([
			'router' => function () {
				return new Router;
			},
			'response' => function () {
				return new Response;
			},
			'view' => function () {
				return new View;
			}
		]);
	}

	public function getContainer()
	{
		return $this->container;
	}

	public function get($uri, $handler)
	{
		$this->container->router->addRouter($uri, $handler, ['GET']);
	}

	public function post($uri, $handler)
	{
		$this->container->router->addRouter($uri, $handler, ['POST']);
	}

	public function map($uri, $handler, array $methods = ['GET'])
	{
		$this->container->router->addRouter($uri, $handler, $methods);
	}

	public function run()
	{
		$router = $this->container->router;
		$router->setPath($_SERVER['PATH_INFO'] ?? '/');

		try {
			$response = $router->getResponse();		
		} catch (RouteNotFoundException $e) {
			if ($this->container->has('errorHandler')) {
				$response = $this->container->errorHandler;
			} else {
				return;
			}
		}

		return $this->respond($this->process($response));
	}

	protected function process($callable)
	{
		$response = $this->container->response;
		$view = $this->container->view;
		if (is_array($callable)) {
			if (!is_object($callable[0])) {
				$callable[0] = new $callable[0];
			}
			return call_user_func($callable, $response, $view);
		}
		return $callable($response, $view);
	}

	protected function respond($response)
	{
		header(sprintf(
			'HTTP/%s %s %s',
			'1.1',
			$response->getStatusCode(),
			''
		));

		if ($response instanceof View) {
			require_once 'Views/'.$response->getViewPath();
			
			return;
		}

		if (!$response instanceof Response) {
			echo $response;
			return;
		}

		

		foreach ($response->getHeaders() as $header) {
			header("{$header[0]}: {$header[0]}");
		}

		echo $response->getBody();
	}
}
