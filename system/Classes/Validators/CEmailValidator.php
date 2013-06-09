<?php
	/**
	 * Email cim validalasert felelos osztaly.
	 * 
	 * !A domain nev ellenorzes csak linux rendszereken mukodik!
	 * 
	 * @author Szabo Szabolcs
	 */
	class CEmailValidator
	{
		/**
		 * @var bool   MX rekord ellenorizve e legyen.
		 */
		protected $checkMX = false;

		/**
		 * @var string   Email formai ellenorzesere hasznalt pattern.
		 */
		protected $pattern = '/^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/';
		
		/**
		 * @var string   Az ellenorizendo email cim.
		 */
		protected $emailAddress = null;
		
		/**
		 * Konstruktor, beallitja az email cimet, es hogy vizsgaljuk e az mx rekordokat.
		 * 
		 * @param string $email     A validalni kivant email cim.
		 * @param bool   $checkMX   MX rekordok vizsgalva e legyenek.
		 */
		public function __construct($emailAddress, $checkMX = false)
		{
			$this->emailAddress = $emailAddress;
			$this->checkMX      = $checkMX;
		}
		
		/**
		 * Email cim validalasa.
		 * 
		 * @return bool   Az email validsagatol fuggoen true vagy false.
		 */
		public function validate()
		{
			if (!$this->checkFormat()) {
				return false;
			}

			if ($this->checkMX && !$this->checkMX()) {
				return false;
			}

			return true;
		}

		/**
		 * Email cim formai ellenorzese.
		 */
		protected function checkFormat()
		{
			return preg_match($this->pattern, $this->emailAddress);
		}

		/**
		 * MX rekordok vizsgalata windows alatt.
		 * 
		 * @param string $domain   Domain nev.
		 * 
		 * @return bool   True vagy false a validsagtol fuggoen.
		 */
		protected function checkMXWin($domain)
		{
			exec('nslookup -type=MX ' . $domain, $result);
 			
			foreach ($result as $line) {
				if (strstr($line, $domain)) {
					return true;
				}
			}
			
			return false;
		}
		
		/**
		 * MX rekordok vizsgalata egyeb rendszeren.
		 * 
		 * @param string $domain   Domain nev.
		 * 
		 * @return bool   True vagy false a validsagtol fuggoen.
		 */
		protected function checkMXOther($domain)
		{
			return checkdnsrr($domain . '.', 'MX');
		}
		
		/**
		 * MX rekordok vizsgalata.
		 * 
		 * @return bool   True vagy false a validsagtol fuggoen.
		 */
		protected function checkMX()
		{
			$domain = explode('@', $this->emailAddress);
			$domain = $domain[1];

			if (!function_exists('checkdnsrr')) {
				$result = $this->checkMXWin($domain);
			} else {
				$result = $this->checkMXOther($domain);
			}

			return $result;
		}
	}
?>