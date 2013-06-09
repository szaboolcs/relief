<?php
	/**
	 * Log-olast megvalosito osztaly.
	 * 
	 * @author Szabo Szabolcs
	 */
	class CLog
	{
		/**
		 * @var string Log-ok mentesenek helye.
		 */
		protected $path;
		
		/**
		 * Konstruktor. Beallitja a log mappajanak elerhetoseget.
		 * 
		 * @param string $path   A log mappajanak elerhetosege.
		 */
		public function __construct($path)
		{
			$this->path = $path;
		}
		
		/**
		 * Log bejegyzes letrehozasa
		 */
		protected function makeString($message)
		{
			$datetime = date('Y-m-d H:i:s');
			
			return $datetime . "\t" . $message . "\n";
		}
		
		/**
		 * Log bejegyzes hozzaadasa, ha nem letezik a fajl, letrehozzuk.
		 * 
		 * @param string $string   Uzenet a log-ba.
		 */
		public function log($message){
			if (!is_dir($this->path) && !mkdir($this->path)) {
				trigger_error('Log dir not exist.', E_USER_NOTICE);
			}
			
			$file    = $this->path . DIRECTORY_SEPARATOR . date('Y-m-d') . '.log';
			$message = $this->makeString($message);
			
			file_put_contents($file, $message, FILE_APPEND);
		}
	}
?>