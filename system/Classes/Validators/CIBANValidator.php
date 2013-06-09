<?php
	/**
	 * IBAN kod validalasat megvalosito osztaly.
	 * 
	 * @author Szabo Szabolcs
	 */
	class CIBANValidator
	{
		/**
		 * @var string   Az ellenorizendo IBAN kod.
		 */
		protected $IBAN = null;
		
		/**
		 * @var array   IBAN orszagonkenti formai.
		 */
		protected $IBANFormats = array(
			'FO' => array(
				'name'    => 'Faroe Islands',
				'length'  => 18,
				'pattern' => '/^FO\d{16}$/'
			),
			'FR' => array(
				'name'    => 'France',
				'length'  => 27,
				'pattern' => '/^FR\d{12}[0-9A-Z]{11}\d{2}$/'
			),
			'GL' => array(
				'name'    => 'Greenland',
				'length'  => 18,
				'pattern' => '/^FO\d{16}$/'
			),
			'KZ' => array(
				'name'    => 'Kazakhstan',
				'length'  => 20,
				'pattern' => '/^[A-Z]{2}\d{5}[0-9A-Z]{13}$/'
			),
			'MK' => array(
				'name'    => 'Macedonia',
				'length'  => 19,
				'pattern' => '/^MK\d{5}[0-9A-Z]{10}\d{2}$/'
			),
			'MD' => array(
				'name'    => 'Moldova',
				'length'  => 24,
				'pattern' => '/^MD\d{2}[A-Z]{2}\d{18}$/'
			),
			'ME' => array(
				'name'    => 'Montenegro',
				'length'  => 22,
				'pattern' => '/^ME\d{20}$/'
			),
			'NL' => array(
				'name'    => 'Netherlands',
				'length'  => 18,
				'pattern' => '/^NL\d{2}[A-Z]{4}\d{10}$/'
			),
			'RS' => array(
				'name'    => 'Serbia',
				'length'  => 22,
				'pattern' => '/^RS\d{20}$/'
			),
			'GB' => array(
				'name'    => 'United Kingdom',
				'length'  => 22,
				'pattern' => '/^GB\d{2}[A-Z]{4}\d{14}$/'
			),
			'AL' => array(
				'name'    => 'Albania',
				'length'  => 28,
				'pattern' => '/^AL\d{10}[0-9A-Z]{16}$/'
			),
			'AD' => array(
				'name'    => 'Andorra',
				'length'  => 24,
				'pattern' => '/^AD\d{10}[0-9A-Z]{12}$/'
			),
			'AT' => array(
				'name'    => 'Austria',
				'length'  => 20,
				'pattern' => '/^AT\d{18}$/'
			),
			'AZ' => array(
				'name'    => 'Azerbaijan',
				'length'  => 28,
				'pattern' => '/^AZ\d{2}[A-Z]{4}\d{20}$/'
			),
			'BH' => array(
				'name'    => 'Bahrain',
				'length'  => 22,
				'pattern' => '/^BH\d{2}[A-Z]{4}[0-9A-Z]{14}$/'
			),
			'BE' => array(
				'name'    => 'Belgium',
				'length'  => 16,
				'pattern' => '/^BE\d{14}$/'
			),
			'BA' => array(
				'name'    => 'Bosnia and Herzegovina',
				'length'  => 20,
				'pattern' => '/^BA\d{18}$/'
			),
			'BG' => array(
				'name'    => 'Bulgaria',
				'length'  => 22,
				'pattern' => '/^BG\d{2}[A-Z]{4}\d{6}[0-9A-Z]{8}$/'
			),
			'CR' => array(
				'name'    => 'Costa Rica',
				'length'  => 21,
				'pattern' => '/^CR\d{19}$/'
			),
			'HR' => array(
				'name'    => 'Croatia',
				'length'  => 21,
				'pattern' => '/^HR\d{19}$/'
			),
			'CY' => array(
				'name'    => 'Cyprus',
				'length'  => 28,
				'pattern' => '/^CY\d{10}[0-9A-Z]{16}$/'
			),
			'CZ' => array(
				'name'    => 'Czech Republic',
				'length'  => 24,
				'pattern' => '/^CZ\d{22}$/'
			),
			'DK' => array(
				'name'    => 'Denmark',
				'length'  => 18,
				'pattern' => '/^DK\d{16}$|^FO\d{16}$|^GL\d{16}$/'
			),
			'DO' => array(
				'name'    => 'Dominican Republic',
				'length'  => 28,
				'pattern' => '/^DO\d{2}[0-9A-Z]{4}\d{20}$/'
			),
			'EE' => array(
				'name'    => 'Estonia',
				'length'  => 20,
				'pattern' => '/^EE\d{18}$/'
			),
			'FI' => array(
				'name'    => 'Finland',
				'length'  => 18,
				'pattern' => '/^FI\d{16}$/'
			),
			'GE' => array(
				'name'    => 'Georgia',
				'length'  => 22,
				'pattern' => '/^GE\d{2}[A-Z]{2}\d{16}$/'
			),
			'DE' => array(
				'name'    => 'Germany',
				'length'  => 22,
				'pattern' => '/^DE\d{20}$/'
			),
			'GI' => array(
				'name'    => 'Gibraltar',
				'length'  => 23,
				'pattern' => '/^GI\d{2}[A-Z]{4}[0-9A-Z]{15}$/'
			),
			'GR' => array(
				'name'    => 'Greece',
				'length'  => 27,
				'pattern' => '/^GR\d{9}[0-9A-Z]{16}$/'
			),
			'GT' => array(
				'name'    => 'Guetamala',
				'length'  => 28,
				'pattern' => '/^GT\d{2}[A-Z]{4}\d{20}$/'
			),
			'HU' => array(
				'name'    => 'Hungary',
				'length'  => 28,
				'pattern' => '/^HU\d{26}$/'
			),
			'IS' => array(
				'name'    => 'Iceland',
				'length'  => 26,
				'pattern' => '/^IS\d{24}$/'
			),
			'IE' => array(
				'name'    => 'Ireland',
				'length'  => 22,
				'pattern' => '/^IE\d{2}[A-Z]{4}\d{14}$/'
			),
			'IL' => array(
				'name'    => 'Israel',
				'length'  => 23,
				'pattern' => '/^IL\d{21}$/'
			),
			'IT' => array(
				'name'    => 'Italy',
				'length'  => 27,
				'pattern' => '/^IT\d{2}[A-Z]\d{10}[0-9A-Z]{12}$/'
			),
			'KW' => array(
				'name'    => 'Kuwait',
				'length'  => 30,
				'pattern' => '/^KW\d{2}[A-Z]{4}\d{22}$/'
			),
			'LV' => array(
				'name'    => 'Latvia',
				'length'  => 21,
				'pattern' => '/^LV\d{2}[A-Z]{4}[0-9A-Z]{13}$/'
			),
			'LB' => array(
				'name'    => 'Lebanon',
				'length'  => 28,
				'pattern' => '/^LB\d{6}[0-9A-Z]{20}$/'
			),
			'LI' => array(
				'name'    => 'Liechtenstein',
				'length'  => 21,
				'pattern' => '/^LI\d{7}[0-9A-Z]{12}$/'
			),
			'LT' => array(
				'name'    => 'Lithuania',
				'length'  => 20,
				'pattern' => '/^LT\d{18}$/'
			),
			'LU' => array(
				'name'    => 'Luxembourg',
				'length'  => 20,
				'pattern' => '/^LU\d{5}[0-9A-Z]{13}$/'
			),
			'MT' => array(
				'name'    => 'Malta',
				'length'  => 31,
				'pattern' => '/^MT\d{2}[A-Z]{4}\d{5}[0-9A-Z]{18}$/'
			),
			'MR' => array(
				'name'    => 'Mauritania',
				'length'  => 27,
				'pattern' => '/^MR13\d{23}$/'
			),
			'MU' => array(
				'name'    => 'Mauritius',
				'length'  => 30,
				'pattern' => '/^MU\d{2}[A-Z]{4}\d{19}[A-Z]{3}$/'
			),
			'MC' => array(
				'name'    => 'Monaco',
				'length'  => 27,
				'pattern' => '/^MC\d{12}[0-9A-Z]{11}\d{2}$/'
			),
			'NO' => array(
				'name'    => 'Norway',
				'length'  => 15,
				'pattern' => '/^NO\d{13}$/'
			),
			'PK' => array(
				'name'    => 'Pakistan',
				'length'  => 24,
				'pattern' => '/^PK\d{2}[A-Z]{4}\d{16}$/'
			),
			'PS' => array(
				'name'    => 'Palestinian Territory, Occupied',
				'length'  => 29,
				'pattern' => '/^PS\d{2}[A-Z]{4}\d{21}$/'
			),
			'PL' => array(
				'name'    => 'Poland',
				'length'  => 28,
				'pattern' => '/^PL\d{10}[0-9A-Z]{16}$/'
			),
			'PT' => array(
				'name'    => 'Portugal',
				'length'  => 25,
				'pattern' => '/^PT\d{23}$/'
			),
			'RO' => array(
				'name'    => 'Romania',
				'length'  => 24,
				'pattern' => '/^RO\d{2}[A-Z]{4}[0-9A-Z]{16}$/'
			),
			'SM' => array(
				'name'    => 'San Marino',
				'length'  => 27,
				'pattern' => '/^SM\d{2}[A-Z]\d{10}[0-9A-Z]{12}$/'
			),
			'SA' => array(
				'name'    => 'Saud Arabia',
				'length'  => 24,
				'pattern' => '/^SA\d{4}[0-9A-Z]{18}$/'
			),
			'SK' => array(
				'name'    => 'Slovakia',
				'length'  => 24,
				'pattern' => '/^SK\d{22}$/'
			),
			'SI' => array(
				'name'    => 'Slovenia',
				'length'  => 19,
				'pattern' => '/^SI\d{17}$/'
			),
			'ES' => array(
				'name'    => 'Spain',
				'length'  => 24,
				'pattern' => '/^ES\d{22}$/'
			),
			'SE' => array(
				'name'    => 'Sweden',
				'length'  => 24,
				'pattern' => '/^SE\d{22}$/'
			),
			'CH' => array(
				'name'    => 'Switzerland',
				'length'  => 21,
				'pattern' => '/^CH\d{7}[0-9A-Z]{12}$/'
			),
			'TN' => array(
				'name'    => 'Tunisia',
				'length'  => 24,
				'pattern' => '/^TN59\d{20}$/'
			),
			'TR' => array(
				'name'    => 'Turkey',
				'length'  => 26,
				'pattern' => '/^TR\d{7}[0-9A-Z]{17}$/'
			),
			'AE' => array(
				'name'    => 'United Arab Emirates',
				'length'  => 23,
				'pattern' => '/^AE\d{21}$/'
			),
			'VG' => array(
				'name'    => 'British Virgin Islands',
				'length'  => 24,
				'pattern' => '/^VG\d{2}[A-Z]{4}\d{16}$/'
			),
		);

		/**
		 * @var array   IBAN betukhoz tartozo szamok.
		 */
		protected $charMap = array(
			'A' => 10,
			'B' => 11,
			'C' => 12,
			'D' => 13,
			'E' => 14,
			'F' => 15,
			'G' => 16,
			'H' => 17,
			'I' => 18,
			'J' => 19,
			'K' => 20,
			'L' => 21,
			'M' => 22,
			'N' => 23,
			'O' => 24,
			'P' => 25,
			'Q' => 26,
			'R' => 27,
			'S' => 28,
			'T' => 29,
			'U' => 30,
			'V' => 31,
			'W' => 32,
			'X' => 33,
			'Y' => 34,
			'Z' => 35
		);
		
		/**
		 * Konstruktor, amely beallitja az IBAN kodot.
		 * 
		 * @param string $IBAN   IBAN kod.
		 */
		public function __construct($IBAN)
		{
			$this->IBAN = $IBAN;
		}

		/**
		 * IBAN kod formazasa mod 97 ellenorzeshez.
		 * 
		 * @param string $IBAN   IBAN kod.
		 * 
		 * @return string   Formazott IBAN kod.
		 */
		protected function format($IBAN)
		{
			$IBAN = substr($IBAN, 4) . substr($IBAN, 0, 4);
			$IBAN = str_replace(array_keys($this->charMap), array_values($this->charMap), $IBAN);

			return $IBAN;
		}

		/**
		 * Egy adott IBAN kod ellenorzese.
		 * 
		 * @return bool   A kod validsagatol fuggoen true vagy false.
		 */
		public function validate()
		{
			$IBAN        = str_replace(' ', '', $this->IBAN);
			$countryCode = substr($IBAN, 0, 2);

			if (!isset($this->IBANFormats[$countryCode])) {
				return false;
			}

			// Orszag szerinti formai ellenorzes.
			if (!preg_match($this->IBANFormats[$countryCode]['pattern'], $IBAN)) {
				return false;
			}

			// Kod formazasa mod 97-hez.
			$IBAN = $this->format($IBAN);

			// mod 97 ellenorzes.
			if (bcmod($IBAN, '97') != 1) {
				return false;
			}

			return true;
		}
	}
?>