<?php
	/**
	 * MySQL alapu session kezelest megvalosito osztaly.
	 * 
	 * Tabla struktura:
	 * 
	 * CREATEA TABLE `sessions` (
	 *  `session_id` VARCHAR(32) NOT NULL,
	 *  `expire` INT NOT NULL,
	 *  `data` BLOB,
	 *  PRIMATY KEY (`session_id`),
	 *  KEY `update` (`update`)
	 * ) ENGINE = MyISAM DEFAULT CHARSET=utf8
	 * 
	 * @author Szabo Szabolcs
	 */
	class CSessionMysql
	{
		/**
		 * @var int   Session lejarati ideje.
		 */
		protected $sessionLifeTime;
		
		/**
		 * Session tabla neve.
		 */
		const TABLE_NAME = 'sessions';
		
		/**
		 * Session azonosito mezo.
		 */
		const COLUMN_ID = 'session_id';
		
		/**
		 * Session lejarati ideje mezo.
		 */
		const COLUMN_EXPIRE = 'expire';
		
		/**
		 * Session adatok mezo.
		 */
		const COLUMN_DATA = 'data';
		
		/**
		 * @var object   Adatbazis kapcsolat.
		 */
		private $db;
		
		/**
		 * Konstruktor. Beallitja a session lejarati idejet.
		 */
		public function __construct()
		{
			$config                = Relief::getConfig();
			$this->sessionLifeTime = $config->sessionLifeTime;
			$connectionName        = $config->sessionConnectioName;
			$this->db              = CDatabase::init($connectionName);

			// Session lejarati idejenek beallitasa.
			session_set_cookie_params($this->sessionLifeTime, '/');
		}
		
		public function createTable()
		{
			$st = $this->db->prepare('
				CREATE TABLE IF NOT EXISTS `sessions` (
					`' . self::COLUMN_ID . '` VARCHAR(32) NOT NULL,
					`' . self::COLUMN_EXPIRE . '` INT NOT NULL,
					`' . self::COLUMN_DATA . '` BLOB,
					PRIMARY KEY (`' . self::COLUMN_ID . '`),
					KEY `' . self::COLUMN_EXPIRE . '` (`' . self::COLUMN_EXPIRE . '`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8
			');
			
			return $st->execute();
		}
		
		/**
		 * Session munkamenet megnyitasa.
		 * 
		 * @param string $savePath      Sessionok konyvtara.
		 * @param string $sessionName   Session neve.
		 * 
		 * @return bool
		 */
		public function openSession($savePath, $sessionName)
		{
			$this->createTable();
			return true;
		}

		/**
		 * Session zarasa.
		 * 
		 * @return bool
		 */
		public function closeSession()
		{
			return true;
		}
		
		/**
		 * Session adatok kiolvasasa.
		 * 
		 * @param string $sessionId   Session azonosito.
		 * 
		 * @return array
		 */
		public function readSession($sessionId)
		{
			$st = $this->db->prepare('
				SELECT
					' . self::COLUMN_DATA . '
				FROM
					' . self::TABLE_NAME . '
				WHERE
					' . self::COLUMN_ID . ' = :_' . self::COLUMN_ID . '
					AND
					' . self::COLUMN_EXPIRE . ' > :_' . self::COLUMN_EXPIRE . '
			');
			
			$time = time();
			
			$st->bindParam(':_' . self::COLUMN_ID, $sessionId, PDO::PARAM_STR);
			$st->bindParam(':_' . self::COLUMN_EXPIRE, $time, PDO::PARAM_STR);
			
			if (!$st->execute()) {
				
				return false;
			}
			
			$data = $st->fetchColumn();
			
			return  (empty($data) ? false : $data);
		}
		
		/**
		 * Session adatok mentese.
		 * 
		 * @param string $sessionId   Session azonosito.
		 * @param string $data        Menteni kivant adatok.
		 * 
		 * @return bool
		 */
		public function writeSession($sessionId, $data)
		{
			$exists_st = $this->db->prepare('SELECT count(*) FROM ' . self::TABLE_NAME . ' WHERE ' . self::COLUMN_ID . ' = :_' . self::COLUMN_ID);
			$exists_st->bindParam(':_' . self::COLUMN_ID, $sessionId, PDO::PARAM_STR);
			$exists_st->execute();
			$exists_result = $exists_st->fetchColumn();
				
			
			if ($exists_result != 0) {
				$st = $this->db->prepare('
					UPDATE
						' . self::TABLE_NAME . '
					SET
						' . self::COLUMN_DATA . ' = :_' . self::COLUMN_DATA . ',
						' . self::COLUMN_EXPIRE . ' = :_' . self::COLUMN_EXPIRE . '
					WHERE
						' . self::COLUMN_ID . ' = :_' . self::COLUMN_ID . '
				');
			}
			else {
				$st = $this->db->prepare('
					INSERT INTO
						' . self::TABLE_NAME . ' 
					(
						' . self::COLUMN_ID . ', 
						' . self::COLUMN_EXPIRE . ',
						' . self::COLUMN_DATA . '
					) VALUES (
						:_' . self::COLUMN_ID . ',
						:_' . self::COLUMN_EXPIRE . ',
						:_' . self::COLUMN_DATA . '
					)
				');
			}
			
			$expireTime = time() + $this->sessionLifeTime;
			
			$st->bindParam(':_' . self::COLUMN_DATA, $data, PDO::PARAM_STR);
			$st->bindParam(':_' . self::COLUMN_ID, $sessionId, PDO::PARAM_STR);
			$st->bindParam(':_' . self::COLUMN_EXPIRE, $expireTime, PDO::PARAM_INT);
			
			return $st->execute();
		}
		
		/**
		 * Session torlese.
		 * 
		 * @parma string $sessionID   Session azonosito.
		 * 
		 * @return bool
		 */
		public function destroySession($sessionId)
		{
			$st = $this->db->prepare('
				DELETE FROM 
					' . self::TABLE_NAME . '
				WHERE
					' . self::COLUMN_ID . ' = :_' . self::COLUMN_ID . '
			');
			
			$st->bindParam(':_' . self::COLUMN_ID, $sessionId, PDO::PARAM_STR);
			
			return $st->execute();
		}
		
		/**
		 * Lejart sessionok torlese, amire cookiek eseteben nincs szukseg, ezert automatikusan true-val terunk vissza.
		 * 
		 * @param string $lifeTime   Session lejarati ideje.
		 * 
		 * @return bool
		 */
		public function collectGarbage($lifeTime)
		{
			$st = $this->db->prepare('
				DELETE FROM 
					' . self::TABLE_NAME . '
				WHERE
					' . self::COLUMN_EXPIRE . ' < :_' . self::COLUMN_EXPIRE . '
			');
			
			$st->bindParam(':_' . self::COLUMN_EXPIRE, time(), PDO::PARAM_STR);
			
			return $st->execute();
		}
	}
?>