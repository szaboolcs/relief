<?php
	/**
	 * Beallitasokat tartalmazo osztaly, tovabba a sajat beallitasokat itt adjuk hozza amennyiben van.
	 * 
	 * @author Szabo Szabolcs
	 */
	class CConfig
	{
		/**
		 * @var array   Az autoloader altal olvasott konyvtarak tomb formajaban.
		 */
		public $autoload = array();
		
		/**
		 * @var string A log konyvtara.
		 */
		public $log = false;
		
		/**
		 * @var array   Adatbazis kapcsolatok tomb formajaban.
		 */
		public $db;
		
		/**
		 * @var string   $defaultController   Az alapertelmezett controller neve.
		 */
		public $defaultController = 'index';
		
		/**
		 * @var array   URL aliasok alias -> controller_name formaban.
		 */
		public $uriAliases = array();
		
		/**
		 * @var string   Sajat session handler hasznalata amely lehet: file|cookie|mysql
		 */
		public $sessionHandler = 'file';
		
		/**
		 * @var sting   Session fajlok eleresi helye. Alapertelmezett konyvtar eseten false.
		 */
		public $sessionSavePath = false;
		
		/**
		 * @var string   A session lejarati ideje.
		 */
		public $sessionLifeTime = 3600;
		
		/**
		 * @var sting   MySQL session kezelohoz tartozo adatbazis kapcsolat.
		 */
		public $sessionConnectioName;
		
		/**
		 * Konstruktor, elinditja az beallitasok futasat.
		 * 
		 * @param array $config   Beallitasok tomb formajaban.
		 */
		public function __construct(array $config)
		{
			$this->run($config);
		}
		
		/**
		 * Meghivja az adott beallitashoz szukseges metodust. Nem letezo beallitas eseten hibat adunk vissza.
		 * 
		 * @param array $config   Beallitasok tomb formajaban.
		 */
		protected function run(array $config)
		{
			foreach ($config as $key => $value) {
				if (!method_exists($this, 'set' . ucfirst($key))){
					trigger_error('Not exist config name.', E_USER_NOTICE);
					continue;
				}
				
				call_user_func(array($this, 'set' . ucfirst($key)), $value);
			}
		}
		
		/**
		 * Log konyvtaranak beallitasa.
		 * 
		 * @param array $param   Log konyvtar elerhetosege.
		 */
		protected function setLog($param)
		{
			$params = explode('.', trim($param, '/\\'));
			
			if ($params[0] == 'application') {
				$params[0] = Relief::getApplicationPath();
			} elseif ($params[0] == 'system') {
				$params[0] = Relief::getSystemPath();
			}
			
			$this->log = implode(DIRECTORY_SEPARATOR, $params);
		}
		
		/**
		 * Adatbazis kapcsolatok beallitasa.
		 * 
		 * @param array $param   Adatbazis kapcsolat parameterek tomb formajaban.
		 */
		protected function setDb($param)
		{
			foreach ($param as $connectionName => $params) {
				if (!isset($params['dsn'])) {
					throw new Exception('DSN params not exist', 1);
				}
				
				if (!isset($params['username'])) {
					throw new Exception('Username params not exist', 1);
				}
				
				if (!isset($params['password'])) {
					throw new Exception('Password params not exist', 1);
				}
				
				if (!isset($params['charset'])) {
					throw new Exception('Charset params not exist', 1);
				}
				
				$this->db[$connectionName]['dsn']      = $params['dsn'];
				$this->db[$connectionName]['username'] = $params['username'];
				$this->db[$connectionName]['password'] = $params['password'];
				$this->db[$connectionName]['charset']  = $params['charset'];
			}
		}
		
		/**
		 * Autoloadhoz hozzaadni kivant konyvtarak.
		 * 
		 * @param array $param   A konyvtarak tomb formajaban.
		 */
		protected function setAutoload(array $param)
		{
			foreach ($param as $value) {
				$pathArray = explode('.', trim($value, '/\\'));
				$params    = array();
				
				if ($pathArray[0] == 'application') {
					$pathArray[0] = Relief::getApplicationPath();
				} elseif ($pathArray[0] == 'system') {
					$pathArray[0] = Relief::getSystemPath();
				}
				
				$this->autoload[] = implode(DIRECTORY_SEPARATOR, $pathArray);
			}
		}
		
		/**
		 * Alapertelmezett controller beallitasa.
		 * 
		 * @param string $param   Az alapertelemezz controller neve.
		 */
		protected function setDefaultController($param)
		{
			$this->defaultController = $param;
		}
		
		/**
		 * URL aliasok beallitasa.
		 * 
		 * @param array $params   Az aliasok tomb formaban.
		 */
		protected function setUriAliases(array $params)
		{
			foreach ($params as $uri => $alias) {
				
				if (!strrpos($alias, '/')) {
					throw new Exception('Not allowed uri alias.', 1);
					continue;
				}
				
				$aliasParams= explode('/', trim($alias, '/'));
			
				$this->uriAliases[strtolower($uri)]['controller'] = strtolower($aliasParams[0]);
				$this->uriAliases[strtolower($uri)]['action']     = strtolower($aliasParams[1]);
			}
		}
		
		/**
		 * Sajat sessionkezelo hasznalatanak beallitasa.
		 * 
		 * @param string $param   Sessionkezelo tipusa.
		 */
		protected function setSessionHandler($param)
		{
			if (!in_array($param, array('file', 'cookie', 'mysql'))) {
				trigger_error('Not valid session handler.', E_USER_WARNING);
			}
			
			$this->sessionHandler = ucfirst(strtolower($param));
		}
		
		/**
		 * Session fajlok helyenek beallitasa.
		 * 
		 * @param string $param   Session fajlok konyvtara.
		 */
		protected function setSessionSavePath($param)
		{
			$params = explode('.', trim($param, '/\\'));
			
			if ($params[0] == 'application') {
				$params[0] = Relief::getApplicationPath();
			} elseif ($params[0] == 'system') {
				$params[0] = Relief::getSystemPath();
			}
			
			$this->sessionSavePath = implode(DIRECTORY_SEPARATOR, $params);
		}
		
		/**
		 *  Session lejarati idejenek beallitasa.
		 * 
		 * @param int $param   Session lejarati ideje.
		 */
		protected function setSessionLifeTime($param)
		{
			$param = (int)$param;

			$this->sessionLifeTime = $param;
		}
		
		/**
		 * MySQL session kezelohoz tartozo adatbazis kapcsolat beallitasa.
		 * 
		 * @param string $param   Adatbazis kapcsolat neve.
		 */
		protected function setSessionConnectioName($param){
			$this->sessionConnectioName = $param;
		}
	}
?>