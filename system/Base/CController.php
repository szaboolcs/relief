<?php
	/**
	 * Controller osztalyok ososztalya, amely tartalmazza a controller osztaly alapertelmezeseit.
	 * 
	 * @author Szabo Szabolcs
	 */
	abstract class CController
	{
		/**
		 * @var string A contoller altal hasznalt layout neve.
		 */
		public $layout = 'main';
		
		/**
		 * @var string Az alapertelmezett action neve.
		 */
		public $defaultAction = 'index';
		
		/**
		 * View fajl meghivasa.
		 * 
		 * @param string $viewName   View neve.
		 * @param array  $params     View fajlnak atadni kivant parameterek.
		 */
		protected function loadView($viewName, array $params = array())
		{
			$CView = new CView($viewName, $params, $this->layout);
		}
	}
?>