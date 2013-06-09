<?php
	/**
	 * SAjat kivetelkezeles megvalositasa.
	 * 
	 * @author Szabo Szabolcs
	 */
	class CException
	{
		/**
		 * ...
		 * 
		 * @param object $exception   ...
		 */
		public function Init(Exception $exception)
		{
			$this->log($exception->getMessage() . "\t" .$exception->getFile() . "\t" . $exception->getLine() . "\t" . $exception->getCode());
			exit($exception->getMessage() . "\t" .$exception->getFile() . "\t" . $exception->getLine() . "\t" . $exception->getCode());
		}
		
		/**
		 * ...
		 * 
		 * @param string $string   ...
		 */
		protected function log($string)
		{
			Relief::log($string);
		}
	}
?>