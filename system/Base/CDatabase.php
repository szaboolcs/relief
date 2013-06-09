<?php
	/**
	 * Adatbazis kapcsolat letrehozasa singleton modon.
	 * 
	 * @author Szabo Szabolcs
	 */
	class CDatabase
	{
		/**
		 * @var array Adatbazis kapcsolatok.
		 */
		protected static $connections = array();
		
		/**
		 * Konstruktor veglegesitese.
		 */
		public final function __construct()
		{
			throw new Exception('This class not instantiates, please use CDatabase::init()', 1);
		}

		/**
		 * Call veglegesitese.
		 */
		public final function __call($method, $args)
		{
			
		}

		/**
		 * Adatbazis kapcsolat inicializalasa.
		 * 
		 * @param string $connectionName   Adatbazis kapcsolat neve.
		 */
		public static function init($connectionName)
		{
			if (!isset(self::$connections[$connectionName])) {
				$dbConfig = self::getConnectionParams($connectionName);

				// Max 3x megprobalunk csatlakozni, ha nem sikerul exception-t dobunk.
				for ($i = 1; $i <= 3; $i++) {
					try{
						self::$connections[$connectionName] = new PDO($dbConfig['dsn'], $dbConfig['username'], $dbConfig['password'], array(
							PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'' . $dbConfig['charset'] . '\''
						));
						
						break;
					} catch (PDOException $pdoException) {
						if ($i < 3) {
							Relief::log('Database connection failed (' . $i . ').');
							
							// Varunk 5 masodpercet, majd megprobaljuk ujra.
							usleep(5);
						} else {
							Relief::log('Database connection failed (' . $i . ').');
							throw new Exception('Database connection failed.', 1);
						}
					}
				}
			}

			return self::$connections[$connectionName];
		}

		/**
		 * ...
		 * 
		 * @param string $connectionName   ...
		 */
		protected static function getConnectionParams($connectionName)
		{
			$config = Relief::getConfig();
			
			if (!isset($config->db[$connectionName])) {
				Relief::log('Connection not exist');
				throw new Exception('Connection not exist.', 1);
			}
			
			return $config->db[$connectionName];
		}
	}
?>