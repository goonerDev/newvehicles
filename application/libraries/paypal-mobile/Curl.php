<?php
/**
 * 
 * Baic Curl library
 *
 * Devin Smith           2006.03.05            www.devin-smith.com
 *
 */

class Curl {
	function request($url,$data = null,$method = 'post') {

		unset($datapost);
		if (is_array($data)) {
			foreach ($data as $key => $item) {
				if ($datapost)
					$datapost .= '&';
				$datapost .= $key.'='.@urlencode($item);
			}
		}

		$mtime = microtime();
		$mtime = explode(' ',$mtime);
		$mtime = $mtime[1] + $mtime[0];
		$starttime = $mtime;

		$ch = curl_init();    
		curl_setopt($ch, CURLOPT_URL,$url);  
		curl_setopt($ch, CURLOPT_POST, 1); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $datapost);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		$output=curl_exec ($ch);
		$err = curl_error($ch);
		curl_close ($ch);

		$mtime = microtime();
		$mtime = explode(' ',$mtime);
		$mtime = $mtime[1] + $mtime[0];
		$endtime = $mtime;
		$this->exectime = number_format($endtime - $starttime,$this->exec_dec,'.',',');

		return $output;
	}
}

?>