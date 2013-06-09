<?php
	/**
	 * Autoloadert megvalosito osztaly.
	 * 
	 * @author Szabo Szabolcs
	 */
	class CAutoloader
	{
		/**
		 * @var array $autoLoad   A config fajlban atadott, alkalmazas altal olvasando osztalyok tombje.
		 */
		protected $autoload = array();
		
		/**
		 * @var array $coreMap    A rendszer altal olvasando osztalyok tombje.
		 */
		protected $coreMap  = array(
			'CController'     => 'Base/CController.php',
			'CLog'            => 'Base/CLog.php',
			'CModel'          => 'Base/CModel.php',
			'CCache'          => 'Base/CCache.php',
			'CView'           => 'Base/CView.php',
			'CMap'            => 'Base/CMap.php',
			'CDatabase'       => 'Base/CDatabase.php',
			'CSecurity'       => 'Base/CSecurity.php',
			'CSessionCookie'  => 'Base/SessionHandlers/CSessionCookie.php',
			'CSessionFile'    => 'Base/SessionHandlers/CSessionFile.php',
			'CSessionMysql'   => 'Base/SessionHandlers/CSessionMysql.php',
			'CValidators'     => 'Classes/CValidators.php',
			'CIBANValidator'  => 'Classes/Validators/CIBANValidator.php',
			'CEmailValidator' => 'Classes/Validators/CEmailValidator.php',
			'CFileValidator'  => 'Classes/Validators/CFileValidator.php',
			'CEmail'          => 'Classes/CEmail.php',
			'IController'     => 'Interfaces/IController.php',
			'IModel'          => 'Interfaces/IModel.php'
		);
		
		/**
		 * Konstruktor. Beallitja az autoload valtozo erteket.
		 */
		public function __construct(array $autoload)
		{
			$this->autoload = $autoload;
		}
		
		/**
		 * Autoload megvalositasa.
		 * 
		 * @param string $className   Beolvasni kivant osztaly neve.
		 */
		public function Init($className)
		{
			if (isset($this->coreMap[$className])) {
				require_once Relief::getSystemPath() . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $this->coreMap[$className]);
				return;
			}
			
			foreach ($this->autoload as $dir) {
				$file = $dir . DIRECTORY_SEPARATOR . $className . '.php';
				
				if (file_exists($file)) {
					require_once $file;
				}
			}
		}
	}
?>