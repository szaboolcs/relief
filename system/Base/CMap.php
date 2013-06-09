<?php
	/**
	 * URL cim felterkepezeset vegzo osztaly.
	 * 
	 * @author Szabo Szabolcs
	 */
	class CMap
	{
		/**
		 * @var string Az eppen aktualis controller neve.
		 */
		public $controller;
		
		/**
		 * @var string Az eppen aktualis action neve.
		 */
		public $action;
		
		/**
		 * Konstruktor, amely kiolvassa es bellitja az aktualis controller es action neveit,
		 * es meghivja a hasznalando metodusokat.
		 */
		public function __construct()
		{
			$config = Relief::getConfig();
			
			$requestUri = $_SERVER['REQUEST_URI'];
			$requestUri = $this->cleanUrl($requestUri);
			
			$scriptName = $_SERVER['SCRIPT_NAME'];
			$scriptName = $this->cleanUrl($scriptName);
			$scriptName = explode('/', $scriptName);
			
			array_pop($scriptName);
			
			$scriptName = implode('/', $scriptName);
			
			$path = str_replace($scriptName, '', $requestUri);
			$path = trim($path, '/');
			$path = explode('/', $path);
			
			// Ha nincs megadva url, akkor az alapertelmezett controllert hasznaljuk, actionnek meg false-t adunk.
			$this->controller = (!empty($path[0]) ? trim($path[0], '/\\') : $config->defaultController);
			$this->action     = (!empty($path[1]) ? trim($path[1], '/\\') : false);
			
			// Ha van megadva aliases akkor azt hasznaljuk.
			if (isset($config->uriAliases[$this->controller])) {
				$this->action     = $config->uriAliases[$this->controller]['action'];
				$this->controller = $config->uriAliases[$this->controller]['controller'];
				
				unset($path[0]);
			} else {
				unset($path[0], $path[1]);
			}
			
			$params = implode('/', $path);
			
			$this->setGet($params);
		}
		
		/**
		 * Elerhetoseg tisztitasa, es alakitasa.
		 * 
		 * @param string $url   Elerhetoseg string formajaban.
		 * 
		 * @return string   A formazott string.
		 */
		public function cleanUrl($url)
		{
			$url = str_replace('\\', '/', $url);
			$url = strtolower($url);
			return trim($url, '/');
		}
		
		/**
		 * Parameterek atadasa a globalis $_GET-nek;
		 * 
		 * @param string $params   Url parameterek string formajaban.
		 */
		public function setGet($params)
		{
			$params = explode('/', $params);
			
			foreach ($params as $key => $param) {
				if ($key % 2 == 0) {
					$_GET[$param] = (isset($params[$key + 1]) ? $params[$key + 1] : '');
				}
			}
		}
	}
?>