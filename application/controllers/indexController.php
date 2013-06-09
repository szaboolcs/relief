<?php
	/**
	 * ...
	 * 
	 * @author Szabo Szabolcs
	 */
	class indexController extends CController
	{
		/**
		 * @var string Az alapertelmezett action neve.
		 */
		public $defaultAction = 'default';
		
		/**
		 * ...
		 */
		public function actionIndex()
		{
			$this->loadView('index', array(
				'valtozo' => 'ertek'
			));
			
			print_r($_SESSION);
		}
		
		/**
		 * ...
		 */
		public function actionDefault()
		{
			$CValidators = new CValidators();
			
			
			//$db = CDatabase::init('connectionName');
			//print_r($_GET);
		}
	}
?>