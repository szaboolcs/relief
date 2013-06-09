<?php
	/**
	 * Email kuldest megvalosito osztaly.
	 * 
	 * @author Szabo Szabolcs
	 */
	class CEmail
	{
		/**
		 * @var string   Uzenet karakterkodolasa.
		 */
		public $CharSet = 'utf8';
		
		/**
		 * @var string   Uzenet tipusa.
		 */
		public $ContentType = 'text/plain';
		
		/**
		 * @var string   Uzenet prioritasa.
		 */
		public $Priority = 3;
		
		/**
		 * @var string   Uzenet kodolasa.
		 */
		public $Encoding = '8bit';
		
		/**
		 * @var string   Uzenet targya.
		 */
		public $Subject = '';
		
		/**
		 * @var string   Uzenet szovege.
		 */
		public $Body = '';
		
		/**
		 * @var string   Kerunk e visszajelzest.
		 */
		public $ConfirmReadingTo  = '';
		
		/**
		 * @var string   Uzenet fejlece.
		 */
		public $headers = '';
		
		/**
		 * @var string   Felado email cime es neve.
		 */
		public $From = array();
		
		/**
		 * @var array   Cimzett(ek) email cime(i) es neve(i).
		 */
		public $To = array();
		
		/**
		 * @var array   Masolatot kapo(k) email cime(i) es neve(i).
		 */
		public $Cc = array();
		
		/**
		 * @var array   Titkos masolatot kapo(k) email cime(i) es neve(i).
		 */
		public $Bcc = array();
		
		/**
		 * @var array   ...
		 */
		public $ReplyTo = array();
		
		/**
		 * @var array   Csatolmanyok listaja.
		 */
		public $attachment = array();
		
		/**
		 * Cimzett hozzaadasa a listahoz.
		 * 
		 * @param string $emailAddress   Cimzett email cime.
		 * @param string $name           Cimzett neve.
		 */
		public function addAddress($emailAddress, $name = '')
		{
			$this->To[] = array(trim($emailAddress), $name);
		}
		
		/**
		 * Masolatot kapo cimzett hozzaadasa a listahoz.
		 * 
		 * @param string $emailAddress   Masolatot kapo cimzett email cime.
		 * @param string $name           Masolatot kapo cimzett neve.
		 */
		public function addCc($emailAddress, $name = '')
		{
			$this->Cc[] = array(trim($emailAddress), $name);
		}
		
		/**
		 * Titkos masolatot kapo cimzett hozzaadasa a listahoz.
		 * 
		 * @param string $emailAddress   Titkos masolatot kapo cimzett email cime.
		 * @param string $name           Titkos masolatot kapo cimzett neve.
		 */
		public function addBcc($emailAddress, $name = '')
		{
			$this->Bcc[] = array(trim($emailAddress), $name);
		}
		
		/**
		 * Valaszt kapok beallitasa.
		 * 
		 * @param string $emailAddress   Valaszt kapo email cime.
		 * @param string $name           Valaszt kapo neve.
		 */
		public function addReplyTo($emailAddress, $name = '')
		{
			$this->ReplyTo[] = array(trim($emailAddress), $name);
		}
		
		/**
		 * Csatolmany hozzaadasa.
		 * 
		 * @param string $attachment   Csatolmany.
		 */
		public function addAttachment($attachment)
		{
			$this->attachment[] = $attachment;
		}
		
		/**
		 * Uzenet beallitasa html formatumura.
		 */
		public function setHtml()
		{
			$this->ContentType = 'text/html';
		}
		
		/**
		 * Felado beallitasa.
		 * 
		 * @param string $emailAddress   A felado email cime.
		 * @param string $name           A felado neve.
		 */
		public function setFrom($emailAddress, $name = ''){
			$this->From = array(trim($emailAddress), $name);
		}
		
		/**
		 * Karakterkodolas beallitasa.
		 * 
		 * @param string $CharSet   Karakterkodolas tipusa.
		 */
		public function setCharset($CharSet)
		{
			$this->CharSet = $CharSet;
		}
		
		/**
		 * Uzenet prioritasanak beallitasa.
		 * 
		 * @param int $priority   Uzenet prioritasa.
		 */
		public function setPriority($priority)
		{
			$this->Priority = $priority;
		}
		
		/**
		 * Kodolas beallitasa.
		 * 
		 * @param string $Encoding   Kodolas tipusa.
		 */
		public function setEncoding($Encoding)
		{
			$this->Encoding = $Encoding;
		}
		
		/**
		 * Uzenet targyanak beallitasa.
		 * 
		 * @param string $Subject   Uzenet targya.
		 */
		public function setSubject($Subject)
		{
			$this->Subject = $subject;
		}
		
		/**
		 * Uzenet szovegenek beallitasa.
		 * 
		 * @param string $Body   Uzenet szovege.
		 */
		public function setBody($Body)
		{
			$this->Body = $Body;
		}
		
		/**
		 * Visszajelzes az elolvasasrol beallitasa.
		 * 
		 * @param int $ConfirmReadingTo   Visszajelzes beallitasa 0|1
		 */
		public function setConfirmReadingTo($ConfirmReadingTo)
		{
			if ($ConfirmReadingTo != 0 && $ConfirmReadingTo != 1) {
				throw new Exception('Not valid!', 1);
			}
			
			$this->ConfirmReadingTo = $ConfirmReadingTo;
		}
		
		
		
		/**
		 * Email fejlecenek osszeallitasa.
		 */
		public function setHeader()
		{
			
			$to = array();
			
			foreach ($this->To as $key => $addresses) {
				$to[] = (!empty($addresses[1]) ? $addresses[1] . '<' . $addresses[0] . '>' : $addresses[0]);
			}
			
			$this->headers .= 'To: ' . implode(',', $to);
			$this->headers .= 'From: ' . (!empty($this->from[1]) ? $this->from[1] . '<' . $this->from[0] . '>' : $this->from[0]);
			
			$this->headers .= 'Cc: ';
			$this->headers .= 'Bcc: ';
			$this->headers .= 'Reply-To: ';
			
			$this->headers .= 'X-Priority: ' . $this->Priority . "\r\n";
			$this->headers .= 'X-Mailer: Relief CMailer' . "\r\n";
			$this->headers .= 'MIE-Version: 1.0' . "\r\n";
			$this->headers .= 'Content-Transfer-Encoding: ' . $this->Encoding . "\r\n";
			$this->headers .= 'Content-Type: ' . $this->ContentType . '; charset=' . $this->CharSet . "\r\n";
		}
		
		/**
		 * Email uzenet kuldese.
		 * 
		 * @return bool   Az uzenet elkuldesenek sikeressegetol fuggoen true vagy false.
		 */
		public function send()
		{
			mail('', $this->Subject, $this->Body, $this->headers);
		}
	}
?>