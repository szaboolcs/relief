<?php
	/**
	 * Adott fajl validalasaert felelos osztaly.
	 * 
	 * @author Szabo Szabolcs
	 */
	class CFileValidator
	{
		/**
		 * Ha a fajl tul nagy, ezt az stringet adjuk vissza.
		 */
		const TOO_BIG = 'TOO_BIG';
		
		/**
		 * Ha a fajl tul kicsi, ezt az stringet adjuk vissza.
		 */
		const TOO_SMALL = 'TOO_SMALL';
		
		/**
		 * Ha a fajl nem megengedett formatumu, ezt az stringet adjuk vissza.
		 */
		const NOT_ALLOWED_TYPE = 'NOT_ALLOWED_TYPE';
		
		/**
		 * Ha a fajl nem letezik, ezt az stringet adjuk vissza.
		 */
		const NOT_EXISTS = 'NOT_EXISTS';
		
		/**
		 * Ha a valid, ezt az stringet adjuk vissza.
		 */
		const VALID = 'VALID';
		
		/**
		 * @var string   A validalando fajl elerhetosege.
		 */
		protected $file = null;

		/**
		 * @var array   A fajl tipusa a tombben levoek lehetnek (kiterjesztes).
		 */
		protected $types = array();

		/**
		 * @var int   A fajl minimum ekkora lehet.
		 */
		protected $minSize = 0;

		/**
		 * @var int   A fajl maximum ekkora lehet.
		 */
		protected $maxSize = 0;

		/**
		 * @var array   Kiterjesztesekhet tartozo MIME tipusok.
		 */
		protected $mimeTypes = array(
			'323'     => 'text/h323',
			'acx'     => 'application/internet-property-stream',
			'ai'      => 'application/postscript',
			'aif'     => 'audio/x-aiff',
			'aifc'    => 'audio/x-aiff',
			'aiff'    => 'audio/x-aiff',
			'asf'     => 'video/x-ms-asf',
			'asr'     => 'video/x-ms-asf',
			'asx'     => 'video/x-ms-asf',
			'au'      => 'audio/basic',
			'avi'     => 'video/x-msvideo',
			'axs'     => 'application/olescript',
			'bas'     => 'text/plain',
			'bcpio'   => 'application/x-bcpio',
			'bin'     => 'application/octet-stream',
			'bmp'     => 'image/bmp',
			'c'       => 'text/plain',
			'cat'     => 'application/vnd.ms-pkiseccat',
			'cdf'     => 'application/x-cdf',
			'cer'     => 'application/x-x509-ca-cert',
			'class'   => 'application/octet-stream',
			'clp'     => 'application/x-msclip',
			'cmx'     => 'image/x-cmx',
			'cod'     => 'image/cis-cod',
			'cpio'    => 'application/x-cpio',
			'crd'     => 'application/x-mscardfile',
			'crl'     => 'application/pkix-crl',
			'crt'     => 'application/x-x509-ca-cert',
			'csh'     => 'application/x-csh',
			'css'     => 'text/css',
			'dcr'     => 'application/x-director',
			'der'     => 'application/x-x509-ca-cert',
			'dir'     => 'application/x-director',
			'dll'     => 'application/x-msdownload',
			'dms'     => 'application/octet-stream',
			'doc'     => 'application/msword',
			'dot'     => 'application/msword',
			'dvi'     => 'application/x-dvi',
			'dxr'     => 'application/x-director',
			'eps'     => 'application/postscript',
			'etx'     => 'text/x-setext',
			'evy'     => 'application/envoy',
			'exe'     => 'application/octet-stream',
			'fif'     => 'application/fractals',
			'flr'     => 'x-world/x-vrml',
			'gif'     => 'image/gif',
			'gtar'    => 'application/x-gtar',
			'gz'      => 'application/x-gzip',
			'h'       => 'text/plain',
			'hdf'     => 'application/x-hdf',
			'hlp'     => 'application/winhlp',
			'hqx'     => 'application/mac-binhex40',
			'hta'     => 'application/hta',
			'htc'     => 'text/x-component',
			'htm'     => 'text/html',
			'html'    => 'text/html',
			'htt'     => 'text/webviewhtml',
			'ico'     => 'image/x-icon',
			'ief'     => 'image/ief',
			'iii'     => 'application/x-iphone',
			'ins'     => 'application/x-internet-signup',
			'isp'     => 'application/x-internet-signup',
			'jfif'    => 'image/pipeg',
			'jpe'     => 'image/jpeg',
			'jpeg'    => 'image/jpeg',
			'jpg'     => 'image/jpeg',
			'js'      => 'application/x-javascript',
			'latex'   => 'application/x-latex',
			'lha'     => 'application/octet-stream',
			'lsf'     => 'video/x-la-asf',
			'lsx'     => 'video/x-la-asf',
			'lzh'     => 'application/octet-stream',
			'm13'     => 'application/x-msmediaview',
			'm14'     => 'application/x-msmediaview',
			'm3u'     => 'audio/x-mpegurl',
			'man'     => 'application/x-troff-man',
			'mdb'     => 'application/x-msaccess',
			'me'      => 'application/x-troff-me',
			'mht'     => 'message/rfc822',
			'mhtml'   => 'message/rfc822',
			'mid'     => 'audio/mid',
			'mny'     => 'application/x-msmoney',
			'mov'     => 'video/quicktime',
			'movie'   => 'video/x-sgi-movie',
			'mp2'     => 'video/mpeg',
			'mp3'     => 'audio/mpeg',
			'mpa'     => 'video/mpeg',
			'mpe'     => 'video/mpeg',
			'mpeg'    => 'video/mpeg',
			'mpg'     => 'video/mpeg',
			'mpp'     => 'application/vnd.ms-project',
			'mpv2'    => 'video/mpeg',
			'ms'      => 'application/x-troff-ms',
			'mvb'     => 'application/x-msmediaview',
			'nws'     => 'message/rfc822',
			'oda'     => 'application/oda',
			'p10'     => 'application/pkcs10',
			'p12'     => 'application/x-pkcs12',
			'p7b'     => 'application/x-pkcs7-certificates',
			'p7c'     => 'application/x-pkcs7-mime',
			'p7m'     => 'application/x-pkcs7-mime',
			'p7r'     => 'application/x-pkcs7-certreqresp',
			'p7s'     => 'application/x-pkcs7-signature',
			'pbm'     => 'image/x-portable-bitmap',
			'pdf'     => 'application/pdf',
			'pfx'     => 'application/x-pkcs12',
			'pgm'     => 'image/x-portable-graymap',
			'pko'     => 'application/ynd.ms-pkipko',
			'pma'     => 'application/x-perfmon',
			'pmc'     => 'application/x-perfmon',
			'pml'     => 'application/x-perfmon',
			'pmr'     => 'application/x-perfmon',
			'pmw'     => 'application/x-perfmon',
			'pnm'     => 'image/x-portable-anymap',
			'png'     => 'image/png',
			'pot'     => 'application/vnd.ms-powerpoint',
			'ppm'     => 'image/x-portable-pixmap',
			'pps'     => 'application/vnd.ms-powerpoint',
			'ppt'     => 'application/vnd.ms-powerpoint',
			'prf'     => 'application/pics-rules',
			'ps'      => 'application/postscript',
			'pub'     => 'application/x-mspublisher',
			'qt'      => 'video/quicktime',
			'ra'      => 'audio/x-pn-realaudio',
			'ram'     => 'audio/x-pn-realaudio',
			'ras'     => 'image/x-cmu-raster',
			'rgb'     => 'image/x-rgb',
			'rmi'     => 'audio/mid',
			'roff'    => 'application/x-troff',
			'rtf'     => 'application/rtf',
			'rtx'     => 'text/richtext',
			'scd'     => 'application/x-msschedule',
			'sct'     => 'text/scriptlet',
			'setpay'  => 'application/set-payment-initiation',
			'setreg'  => 'application/set-registration-initiation',
			'sh'      => 'application/x-sh',
			'shar'    => 'application/x-shar',
			'sit'     => 'application/x-stuffit',
			'snd'     => 'audio/basic',
			'spc'     => 'application/x-pkcs7-certificates',
			'spl'     => 'application/futuresplash',
			'src'     => 'application/x-wais-source',
			'sst'     => 'application/vnd.ms-pkicertstore',
			'stl'     => 'application/vnd.ms-pkistl',
			'stm'     => 'text/html',
			'svg'     => 'image/svg+xml',
			'sv4cpio' => 'application/x-sv4cpio',
			'sv4crc'  => 'application/x-sv4crc',
			't'       => 'application/x-troff',
			'tar'     => 'application/x-tar',
			'tcl'     => 'application/x-tcl',
			'tex'     => 'application/x-tex',
			'texi'    => 'application/x-texinfo',
			'texinfo' => 'application/x-texinfo',
			'tgz'     => 'application/x-compressed',
			'tif'     => 'image/tiff',
			'tiff'    => 'image/tiff',
			'tr'      => 'application/x-troff',
			'trm'     => 'application/x-msterminal',
			'tsv'     => 'text/tab-separated-values',
			'txt'     => 'text/plain',
			'uls'     => 'text/iuls',
			'ustar'   => 'application/x-ustar',
			'vcf'     => 'text/x-vcard',
			'vrml'    => 'x-world/x-vrml',
			'wav'     => 'audio/x-wav',
			'wcm'     => 'application/vnd.ms-works',
			'wdb'     => 'application/vnd.ms-works',
			'wks'     => 'application/vnd.ms-works',
			'wmf'     => 'application/x-msmetafile',
			'wps'     => 'application/vnd.ms-works',
			'wri'     => 'application/x-mswrite',
			'wrl'     => 'x-world/x-vrml',
			'wrz'     => 'x-world/x-vrml',
			'xaf'     => 'x-world/x-vrml',
			'xbm'     => 'image/x-xbitmap',
			'xla'     => 'application/vnd.ms-excel',
			'xlc'     => 'application/vnd.ms-excel',
			'xlm'     => 'application/vnd.ms-excel',
			'xls'     => 'application/vnd.ms-excel',
			'xlt'     => 'application/vnd.ms-excel',
			'xlw'     => 'application/vnd.ms-excel',
			'xof'     => 'x-world/x-vrml',
			'xpm'     => 'image/x-xpixmap',
			'xwd'     => 'image/x-xwindowdump',
			'z'       => 'application/x-compress',
			'zip'     => 'application/zip'
		);
		
		/**
		 * Konstruktor, amely beallitja a kello parametereket.
		 * 
		 * @param string $file      A fajl elerhetosege.
		 * @param array  $types     Fajl tipusai ezek lehetnek.
		 * @param int    $minSize   A fajl minimum ekkora lehet.
		 * @param int    $maxSize   A fajl maximum ekkora lehet.
		 * 
		 * @return bool   A fajl validsagatol fuggoen true vagy false.
		 */
		public function __construct($file, array $types = array(), $minSize = 0, $maxSize = 0)
		{
			$this->file    = $file;
			$this->types   = $types;
			$this->minSize = (int)$minSize;
			$this->maxSize = (int)$maxSize;
		}
		
		/**
		 * Adott fajl validalasa.
		 * 
		 * @return string   A validsagtol fuggoen hibauzenet.
		 */
		public function validate()
		{
			if (!$this->exists()) {
				return self::NOT_EXISTS;
			}

			if (!$this->type()) {
				return self::NOT_ALLOWED_TYPE;
			}

			$size = $this->size();

			if ($size !== true) {
				return $size;
			}

			return self::VALID;
		}
		
		/**
		 * A fajl letezik e.
		 * 
		 * @return bool   Validsagtol fuggoen true vagy false.
		 */
		protected function exists()
		{
			return file_exists($this->file);
		}
		
		/**
		 * A fajl a megadott meretek kozott van e.
		 * 
		 * @return mixed   Validsagtol fuggoen true vagy hibauzenet.
		 */
		protected function size()
		{
			$fileSize = filesize($this->file);
			
			if ($this->minSize > 0 && $fileSize < $this->minSize) {
				return self::TOO_SMALL;
			}
			
			if ($this->maxSize > 0 && $fileSize > $this->maxSize) {
				return self::TOO_BIG;
			}
			
			return true;
		}
		
		/**
		 * A fajl megengedett tipusu e.
		 * 
		 * @return bool   Validsagtol fuggoen true vagy false.
		 */
		protected function type()
		{
			if (empty($this->types)) {
				return true;
			}

			// Fajl kiterjeszte.
			$extension = explode('.', $this->file);
			$extension = strtolower(end($extension));

			if (!isset($this->mimeTypes[$extension])) {
				return false;
			}

			// Fajl tipusa.
			if (function_exists('finfo_open')) {
				$finfo = finfo_open(FILEINFO_MIME_TYPE);
				$mimeType  = finfo_file($this->file, $finfo);
				
				finfo_close($finfo);
			}
			elseif (function_exists('mime_content_type')) {
				$mimeType = mime_content_type($this->file);
			}

			if ($this->mimeTypes[$extension] != $mimeType) {
				return false;
			}

			return true;
		}
	}
?>