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
			},
			'request' => function () {
				return new Request;
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
		$request = $this->container->request;
		$response = $this->container->response;
		if (!is_callable($callable)) {
			$callable = explode('@', $callable);
			if (!is_object($callable[0])) {
				$callable[0] = new $callable[0]($this->container);
			}
			return call_user_func($callable, $request, $response);
		}
		return $callable($request, $response);
	}

	protected function respond($response)
	{
		if ($response instanceof View) {
			$response->compileView();
			return;
		}


		if ($response instanceof Response) {
			header(sprintf(
				'HTTP/%s %s %s',
				'1.1',
				$response->getStatusCode(),
				''
			));
			
			foreach ($response->getHeaders() as $header) {
				header("{$header[0]}: {$header[0]}");
			}

			echo $response->getBody();
			return;
		}		

		echo $response;
	}
}
