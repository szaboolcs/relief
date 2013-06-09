<?php
	/**
	 * ...
	 * 
	 * @author Szabo Szabolcs
	 */
	class CSessionCookie
	{
		/**
		 * @var int   Session lejarati ideje.
		 */
		protected $sessionLifeTime;
		
		/**
		 * Konstruktor. Beallitja a session lejarati idejet.
		 */
		public function __construct()
		{
			$config                = Relief::getConfig();
			$this->sessionLifeTime = $config->sessionLifeTime;

			// Session lejarati idejenek beallitasa.
			session_set_cookie_params($this->sessionLifeTime, '/');
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
			$sessionId = md5($sessionId);
			$cookie    = (!isset($_COOKIE[$sessionId]) ? '' : $_COOKIE[$sessionId]);
			$data      = base64_decode(json_decode($cookie, true));
			
			return $data;
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
			$sessionId = md5($sessionId);
			$data      = json_encode(base64_encode($data));
			
			setcookie($sessionId, $data, time() + $this->sessionLifeTime);
			
			return true;
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
			$sessionId = md5($sessionId);
			
			setcookie($sessionId, '');
			
			return true;
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
			return true;
		}
	}
?>