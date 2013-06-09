<?php
	/**
	 * Fajl alapu session kezeles megvalositasa.
	 * 
	 * @author Szabo Szabolcs
	 */
	class CSessionFile
	{
		/**
		 * @var string   A session fajlok eleresi helye.
		 */
		protected $sessionSavePath;
		
		/**
		 * @var string   Session fajlokhoz hasznalando prefix.
		 */
		protected $prefix = 'sess_';
		
		/**
		 * Konstruktor. Beallitja a session lejarati idejet es a session fajlok mentesi helyet.
		 */
		public function __construct()
		{
			$config                = Relief::getConfig();
			$this->sessionSavePath = (!$config->sessionSavePath ? session_save_path() : $config->sessionSavePath);
			$sessionLifeTime       = $config->sessionLifeTime;

			// Session lejarati idejenek beallitasa.
			session_set_cookie_params($sessionLifeTime);

			if (!is_dir($this->sessionSavePath)) {
				if (!mkdir($this->sessionSavePath, 0777)) {
					throw new Exception('Session dir not exists', 1);
				}
			}
			
			// Session fajlok mentesi helye.
			session_save_path($this->sessionSavePath);
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
			if (!is_file($this->sessionSavePath . DIRECTORY_SEPARATOR . $this->prefix . $sessionId)) {
				return true;
			}

			$data = file_get_contents($this->sessionSavePath . DIRECTORY_SEPARATOR . $this->prefix . $sessionId);
			$data = base64_decode(json_decode($data, true));

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
			$data = json_encode(base64_encode($data));
			
			return file_put_contents($this->sessionSavePath . DIRECTORY_SEPARATOR . $this->prefix . $sessionId, $data) === false ? false : true;
		}
		
		/**
		 * Session torlese.
		 * 
		 * @param string $sessionId   Session azonosito.
		 * 
		 * @return bool
		 */
		public function destroySession($sessionId)
		{
			$file = $this->sessionSavePath . DIRECTORY_SEPARATOR . $this->prefix . $sessionId;
			
			if (file_exists($file)) {
				unlink($file);
			}
			
			return true;
		}
		
		/**
		 * Lejart ideju sessionok torlese.
		 * 
		 * @param string $lifeTime   Session lejarati ideje.
		 * 
		 * @return bool
		 */
		public function collectGarbage($lifeTime)
		{
			foreach (glob($this->sessionSavePath . DIRECTORY_SEPARATOR . $this->prefix . '*') as $file) {
				if (filemtime($file) + $lifeTime < time() && file_exists($file)) {
					unlink($file);
				}
			}
			
			return true;
		}
	}
?>