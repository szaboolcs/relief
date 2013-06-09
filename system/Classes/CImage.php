<?php
	/**
	 * Kepkezelest megvalosito osztaly.
	 * 
	 * @author Szabo Szabolcs
	 */
	class CImage
	{
		/**
		 * Ha nem megfelelo formatumu kepet toltunk fel.
		 */
		const ERROR_INVALID_TYPE = 'INVALID_TYPE';
		
		/**
		 * Nem megfelelo forgatasi szog.
		 */
		const ERROR_INVALID_ANGLE_NUMBER = 'INVALID_ANGLE_NUMBER';
		
		/**
		 * A konyvtar nem letezik.
		 */
		const ERROR_DIR_NOT_EXISTS = 'DIR_NOT_EXISTS';
		
		/**
		 * A fajl nem letezik.
		 */
		const ERROR_FILE_NOT_EXISTS = 'FILE_NOT_EXISTS';
		
		/**
		 * Nem sikerult menteni a kepet.
		 */
		const ERROR_ERROR_WHEN_SAVING = 'ERROR_WHEN_SAVING';
		
		/**
		 * Nem megfelelo kroppolasi parameterek.
		 */
		const ERROR_INVALID_CROP_PARAMETERS = 'INVALID_CROP_PARAMETERS';
		
		/**
		 * Nincs megadva atmeretezeshez meret.
		 */
		const ERROR_INVALID_RESIZE_PARAMETERS = 'INVALID_RESIZE_PARAMETERS';
		
		/**
		 * @var sting   A letrehozott kep.
		 */
		public $image;
		
		/**
		 * @var array   Az eredeti kep adatai.
		 */
		public $imageDatas = array();
		
		/**
		 * @var array   Megengedett keptipusok mime tipusai.
		 */
		public $mimeTypes = array(
			'jpg'  => 'image/jpeg',
			'jpeg' => 'image/jpeg',
			'bmp'  => 'image/bmp',
			'gif'  => 'image/gif',
			'png'  => 'image/png'
		);
		
		/**
		 * Az atalakitando kep betoltese, kiterjesztesenek es mime tipusanak ellenorzese.
		 * 
		 * @param string $image   Az atalakitani kivant kep.
		 * 
		 * @return bool   A muvelet sikeressegetol fuggoen true vagy false.
		 */
		public function load($image)
		{
			if (!is_file($image)) {
				return self::ERROR_FILE_NOT_EXISTS;
			}
			
			$imageInfo                     = getimagesize($image);
			$this->imageDatas['width']     = $imageInfo[0];
			$this->imageDatas['height']    = $imageInfo[1];
			$this->imageDatas['mime']      = $imageInfo['mime'];
			$this->imageDatas['extension'] = explode('.', $image);
			$this->imageDatas['extension'] = end($this->imageDatas['extension']);
			
			if (!isset($this->mimeTypes[$this->imageDatas['extension']])) {
				return self::ERROR_INVALID_TYPE;
			}
			
			if ($this->mimeTypes[$this->imageDatas['extension']] != $this->imageDatas['mime']) {
				return self::ERROR_INVALID_TYPE;
			}
			
			$this->createImage($image);
		}
		
		/**
		 * Kep letrehozasa.
		 * 
		 * @param string $image   A kep.
		 */
		public function createImage($image)
		{
			switch ($this->imageDatas['extension']) {
				case 'jpg':
				case 'jpeg':
					$this->image = imagecreatefromjpeg($filename);
					break;
				case 'png':
					$this->image = imagecreatefrompng($filename);
					break;
				case 'gif':
					$this->image = imagecreatefromgif($filename);
					break;
				case 'bmp':
					$this->image = imagecreatefromwbmp($filename);
					break;
			}
		}

		/**
		 * Kep atmeretezese.
		 * 
		 * @param int $width    A kep szelessege.
		 * @param int $height   A kep magassaga.
		 * 
		 * @return bool   A muvelet sikeressegetol fuggoen true vagy false.
		 */
		public function resize($width = 0, $height = 0)
		{
			$width  = (int)$width;
			$height = (int)$height;

			if ($width == 0 && $height == 0) {
				return self::ERROR_INVALID_RESIZE_PARAMETERS;
			}
			elseif ($width > 0 && $height == 0) {
				$height = $width / $this->imageDatas['width'] * $this->imageDatas['height'];
			}
			elseif ($width == 0 && $height > 0) {
				$width = $height / $this->imageDatas['height'] * $this->imageDatas['width'];
			}
			else {
				// Fixen meretezunk nem is kell az else ag, kiveve, ha egy hatteret adunk neki, es arra aranyositjuk a kepet.
			}

			$image = imagecreatetruecolor($width, $height);
			imagecopyresampled($image, $this->image, 0, 0, 0, 0, $width, $height, $this->imageDatas['width'], $this->imageDatas['height']);
			$this->image = $image;
			
			return true;
		}
		
		/**
		 * Kep elforgatasa adott iranyba.
		 * 
		 * @param int $angle   Forgasi szog megadasa -180 - 180
		 */
		public function rotate($angle)
		{
			if ($angle > 180 || $angle < -180) {
				return self::ERROR_INVALID_ANGLE_NUMBER;
			}
		}
		
		/**
		 * Az elkeszult kep mentese.
		 * 
		 * @param string $format   A menteni kivant formatum megnevezese.
		 * 
		 * @return bool   A muvelet sikeressegetol fuggoen true vagy false.
		 */
		public function save($format, $path, $imageName)
		{
			if (!is_dir($path)) {
				return self::ERROR_DIR_NOT_EXISTS;
			}
			
			$path = trim($path, '/\\');
			
			switch ($format) {
				case 'jpg':
				case 'jpeg':
					$save = imagejpeg($this->image, $path . DIRECTORY_SEPARATOR . $imageName, $quality);
					break;
				case 'png':
					$save = imagepng($this->image, $path . DIRECTORY_SEPARATOR . $imageName, $quality);
					break;
				case 'gif':
					$save = imagegif($this->image, $path . DIRECTORY_SEPARATOR . $imageName);
					break;
				case 'bmp':
					$save = imagewbmp($this->image, $path . DIRECTORY_SEPARATOR . $imageName);
					break;
			}
			
			if (!$save) {
				return self::ERROR_ERROR_WHEN_SAVING;
			}
			
			return true;
		}
		
		/**
		 * Egy adott kepbol egy resz kivagasa.
		 * 
		 * @param int $fromX    A croppolas kindulopontja balrol.
		 * @param int $fromY    A croppolas kiindulopontja fentrol.
		 * @param int $toX      A croppolas befejezopontja balrol.
		 * @param int $toY      A croppolas befejezopontja fentrol.
		 * 
		 * @return bool   A muvelet sikeressegetol fuggoen true vagy false.
		 */
		public function crop($fromX, $fromY, $toX, $toY)
		{
			if ($toX <= $fromX || $toY <= $fromY) {
				return self::ERROR_INVALID_CROP_PARAMETERS;
			}
		}
		
		/**
		 * Egy adott kepre vizjel rahelyezese.
		 * 
		 * @param string $image   A kepre rarakni kivant vizjel.
		 * 
		 * @return bool   A muvelet sikeressegetol fuggoen true vagy false.
		 */
		public function watermark($image)
		{
			
		}
	}
?>