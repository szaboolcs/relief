<?php
	/**
	 * A megjelenitesert felelos osztaly.
	 * 
	 * @author Szabo Szabolcs
	 */
	class CView
	{
		/**
		 * @var string View neve.
		 */
		protected $viewName;
		
		/**
		 * @var array Atadni kivant parameterek.
		 */
		protected $params;
		
		/**
		 * @var string Az aktualis controller neve.
		 */
		protected $controllerName;
		
		/**
		 * @var string A viewer fajl elerhetosege.
		 */
		protected $file;
		
		/**
		 * @var string A layout fajl elerhetosege.
		 */
		protected $layout;
		
		/**
		 * ...
		 * 
		 * @param string $viewName   A meghivando view neve.
		 * @param array  $params     A view fajlnak atadni kivant parameterek.
		 * @param string $layout     A hasznalando layout neve.
		 */
		public function __construct($viewName, array $params = array(), $layout)
		{
			$CMap                 = new CMap();
			$this->controllerName = strtolower($CMap->controller);
			$this->viewName       = strtolower($viewName);
			$this->params         = $params;
			$viewDir              = Relief::getApplicationPath() . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $this->controllerName;
			$this->file           = $viewDir . DIRECTORY_SEPARATOR . $this->viewName . '.php';
			$this->layout         = Relief::getApplicationPath() . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . $layout . '.php';

			if (!is_dir($viewDir)) {
				Relief::log('View dir not exist.');
				throw new Exception('View dir not exist.', 1);
			}
			
			if (!file_exists($this->file)) {
				Relief::log('View file not exist.');
				throw new Exception('View file not exist.', 1);
			}
			
			if (!file_exists($this->layout)) {
				Relief::log('Layout file not exist.');
				throw new Exception('Layout file not exist.', 1);
			}
			
			$content = $this->getView();
			$this->getLayout($content);
		}
		
		/**
		 * View fajl beuzasa, es eredmenyenek valtozoba rakasa
		 * 
		 * @return string   A view fajl eredmenye.
		 */
		protected function getView()
		{
			ob_start();
			extract($this->params);
			include $this->file;
			$content = ob_get_contents();
			ob_end_clean();
			
			return $content;
		}
		
		/**
		 * Layout fajl behuzasa.
		 * 
		 * @param string $content   A view fajl tartalma.
		 */
		protected function getLayout($content)
		{
			require_once $this->layout;
		}
	}
?>