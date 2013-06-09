<?php
	/**
	 * Sajat hibakezelo osztaly.
	 * 
	 * @author Szabo Szabolcs
	 */
	class CError
	{
		/**
		 * ...
		 * 
		 * @param int    $code      ...
		 * @param string $message   ...
		 * @param string $file      ...
		 * @param int    $line      ...
		 */
		public function Init($code, $message, $file, $line)
		{
			$this->log($message . "\t" . $file . "\t" . $line . "\t" . $code);
			exit($message . "\t" . $file . "\t" . $line . "\t" . $code);
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
		
		/**
		 * ...
		 * 
		 * @param int $httpCode   ...
		 * 
		 * @return string   ...
		 */
		protected function getHttpHeader($httpCode)
		{
			$httpCodes = array(
				100 => 'Continue',
				101 => 'Switching Protocols',
				102 => 'Processing',
				118 => 'Connection timed out',
				200 => 'OK',
				201 => 'Created',
				202 => 'Accepted',
				203 => 'Non-Authoritative',
				204 => 'No Content',
				205 => 'Reset Content',
				206 => 'Partial Content',
				207 => 'Multi-Status',
				210 => 'Content Different',
				300 => 'Multiple Choices',
				301 => 'Moved Permanently',
				302 => 'Found',
				303 => 'See Other',
				304 => 'Not Modified',
				305 => 'Use Proxy',
				307 => 'Temporary Redirect',
				310 => 'Too many Redirect',
				400 => 'Bad Request',
				401 => 'Unauthorized',
				402 => 'Payment Required',
				403 => 'Forbidden',
				404 => 'Not Found',
				405 => 'Method Not Allowed',
				406 => 'Not Acceptable',
				407 => 'Proxy Authentication Required',
				408 => 'Request Time-out',
				409 => 'Conflict',
				410 => 'Gone',
				411 => 'Length Required',
				412 => 'Precondition Failed',
				413 => 'Request Entity Too Large',
				414 => 'Request-URI Too Long',
				415 => 'Unsupported Media Type',
				416 => 'Requested range unsatisfiable',
				417 => 'Expectation failed',
				418 => 'I’m a teapot',
				422 => 'Unprocessable entity',
				423 => 'Locked',
				424 => 'Method failure',
				425 => 'Unordered Collection',
				426 => 'Upgrade Required',
				449 => 'Retry With',
				450 => 'Blocked by Windows Parental Controls',
				500 => 'Internal Server Error',
				501 => 'Not Implemented',
				502 => 'Bad Gateway ou Proxy Error',
				503 => 'Service Unavailable',
				504 => 'Gateway Time-out',
				505 => 'HTTP Version not supported',
				507 => 'Insufficient storage',
				509 => 'Bandwidth Limit Exceeded',
			);
			
			if (isset($httpCodes[$httpCode])) {
				return $httpCodes[$httpCode];
			}
			
			return false;
		}
	}
?>