<?php
	/**
	 * Kulonbozo validalasokat osszefogo osztaly.
	 * 
	 * @author Szabo Szabolcs
	 */
	class CValidators
	{
		/**
		 * Email cim validalasa.
		 * 
		 * @param string $emailAddress   Validalando email cim.
		 * @param bool   $checkMX        MX rekordok is vizsgalva e legyenek.
		 * 
		 * @return bool   Az email validsagatol fuggoen true vagy false.
		 */
		public function Email($emailAddress, $checkMX = false)
		{
			$validator = new CEmailValidator($emailAddress, $checkMX);
			return $validator->validate();
		}

		/**
		 * IBAN szam validalasa.
		 * 
		 * @param string $IBAN   Ellenorizendo IBAN kod.
		 * 
		 * @return bool   A kod validsagatol fuggoen true vagy false.
		 */
		public function IBAN($IBAN)
		{
			$IBANValidator = new CIBANValidator($IBAN);
			return $IBANValidator->validate();
		}
		
		/**
		 * Fajl validalasa.
		 * 
		 * @param string $file      A fajl elerhetosege.
		 * @param array  $types     Fajl tipusai ezek lehetnek (kiterjesztesek).
		 * @param int    $minSize   A fajl minimum ekkora lehet.
		 * @param int    $maxSize   A fajl maximum ekkora lehet.
		 * 
		 * @return string   A fajl validsagatol fuggoen, osztalykonstansban meghatarozott string (CFileValidator::VALID, ...).
		 */
		public function File($file, array $types = array(), $minSize = 0, $maxSize = 0)
		{
			$CFileValidator = new CFileValidator($file, $types, $minSize, $maxSize);
			return $CFileValidator->validate();
		}
	}
?>