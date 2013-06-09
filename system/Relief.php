<?php
	/**
	 * Keretrendszer belepesi pontjat biztosito osztaly. Elvegzi az alapbeallitasokat,
	 * inicializalja a hibakezeloket, es beallitja az autoloadert.
	 * 
	 * @author Szabo Szabolcs 
	 */
	class Relief
	{
		/**
		 * @var string A rendszer eleresi utja.
		 */
		protected static $systemPath;
		
		/**
		 * @var string Az alkalmazas eleresi utja.
		 */
		protected static $applicationPath;
		
		/**
		 * @var object Konfiguracios osztaly objektuma.
		 */
		protected static $config;
		
		/**
		 * @var object Log osztaly objektuma.
		 */
		protected static $log;
		
		/**
		 * @var bool A rendszer inicializalva e van.
		 */
		protected static $initalize = false;
		
		/**
		 * Konstruktor, elinditja az alkalmazas futasat, amennyiben nem lett inicailizalva, hibat dobunk.
		 */
		public function __construct(array $config = array())
		{
			if (!self::$initalize) {
				trigger_error('System not initalized.', E_USER_ERROR);
			}

			$this->run($config);
		}
		
		/**
		 * Rendszer futtatasa, alapertelmezett beallitasok, es kezelo osztalyok beallitasa.
		 * 
		 * @param array $config   Beallitasok tombje.
		 */
		protected function run(array $config = array())
		{
			self::$systemPath = dirname(__FILE__);
			
			if (isset($config['applicationPath'])) {
				self::$applicationPath = $config['applicationPath'];
				unset($config['applicationPath']);
			}
			
			// Fajlok behuzasa.
			require_once self::$systemPath . DIRECTORY_SEPARATOR . 'Base' . DIRECTORY_SEPARATOR . 'CConfig.php';
			require_once self::$systemPath . DIRECTORY_SEPARATOR . 'Base' . DIRECTORY_SEPARATOR . 'CError.php';
			require_once self::$systemPath . DIRECTORY_SEPARATOR . 'Base' . DIRECTORY_SEPARATOR . 'CException.php';
			require_once self::$systemPath . DIRECTORY_SEPARATOR . 'Base' . DIRECTORY_SEPARATOR . 'CLog.php';
			require_once self::$systemPath . DIRECTORY_SEPARATOR . 'Base' . DIRECTORY_SEPARATOR . 'CAutoloader.php';
			
			// Osztalyok peldanyositasa.
			self::$config = new CConfig($config);
			self::$log    = new CLog(self::$config->log);
			$CAutoloader  = new CAutoloader(self::$config->autoload);
			$CError       = new CError();
			$CException   = new CException();
			
			// Autoloadert megvalosito osztaly.
			spl_autoload_register(array($CAutoloader, 'Init'));
			
			// Hibakezelot megvalosito osztaly.
			set_error_handler(array($CError, 'Init'));
			
			// Hibakezelot megvalosito osztaly.
			set_exception_handler(array($CException, 'Init'));
			
			
			// Session kezelo beallitasa.
			$sessionHandlerType = self::$config->sessionHandler;
			$sessionHandler     = 'CSession' . ucfirst(strtolower($sessionHandlerType));
			$sessionHandler     = new $sessionHandler;
			
			session_set_save_handler(
				array($sessionHandler, 'openSession'),
				array($sessionHandler, 'closeSession'),
				array($sessionHandler, 'readSession'),
				array($sessionHandler, 'writeSession'),
				array($sessionHandler, 'destroySession'),
				array($sessionHandler, 'collectGarbage')
			);

			register_shutdown_function('session_write_close');
			session_start();
			
			$this->loadController();
		}

		/**
		 * Kontroller fajl beolvasasa.
		 * Ellenorizzuk a kontroller fajl letezeset, az osztaly letezeset, es hogy orokli e a CController ososztalyt,
		 * es ellenorizzuk hogy az action metodus letezik e.
		 */
		protected function loadController()
		{
			// Parameterek osszegyujtese
			$CMap           = new CMap();
			$controllerName = (!$CMap->controller ? self::$config->defaultController : $CMap->controller);
			$controllerName = strtolower($controllerName) . 'Controller';
			$controllerFile = self::$applicationPath . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $controllerName . '.php';
			$action         = $CMap->action;

			// Ha a controller fajl nem letezik, hibat dobunk.
			if (!file_exists($controllerFile)) {
				Relief::log('Controller not exist.');
				throw new Exception('Controller not exist.', 1);
			}
			require_once $controllerFile;
			
			// Ha az osztaly nem letezik, hibat dobunk.
			if (!class_exists($controllerName)) {
				Relief::log('Class not exist.');
				throw new Exception('Class not exist.', 1);
			}
			
			$controller = new $controllerName;
			
			// Ha nem orokli a CController ososztalyt akkor hibat dobunk.
			if (!is_subclass_of($controller, 'CController')) {
				Relief::log('This is not subclass of CController.');
				throw new Exception('This is not subclass of CController');
			}
			
			// action Metodus parametere.
			$actionMethod = (!$action ? $controller->defaultAction : $action);
			$actionMethod = 'action' . ucfirst(strtolower($actionMethod));
			
			// Ha nem letezik az action metodus akkor hibat dobunk.
			if (!method_exists($controller, $actionMethod)) {
				Relief::log('Method not exist.');
				throw new Exception('Method not exist.', 1);
			}
			
			// Meghivjuk a metodust es atadjuk a $_GET parametereket neki.
			call_user_func_array(array($controller, $actionMethod), $_GET);
		}

		/**
		 * Log osztalyt hivo statikus metodus.
		 * 
		 * @param string $string   ...
		 */
		public static function log($string){
			self::$log->log($string);
		}
		
		/**
		 * Visszaadja a rendszer konyvtaranak elerhetoseget.
		 * 
		 * @return string   A rendszer elerhetosege.
		 */
		public static function getSystemPath()
		{
			return self::$systemPath;
		}
		
		/**
		 * Visszaadja az alkalmazas konyvtaranak elerhetoseget.
		 * 
		 * @return string   Az alkalmazas elerhetosege.
		 */
		public static function getApplicationPath()
		{
			return self::$applicationPath;
		}
		
		/**
		 * Visszaadja a beallitasok objektumat.
		 * 
		 * @return object   A CConfig osztaly objektuma.
		 */
		public static function getConfig()
		{
			return self::$config;
		}
		
		/**
		 * Rendszer inicializalasa.
		 * 
		 * @param array $config   Konfiguracio.
		 * 
		 * @return obj   A rendszer objektuma.
		 */
		public static function init(array $config = array())
		{
			self::$initalize = true;
			return new Relief($config);
		}
		
		/**
		 * ...
		 * 
		 * @param string $name        ...
		 * @param array  $arguments   ...
		 */
		public function __call($name, $arguments)
		{
			
		}
	}
?>