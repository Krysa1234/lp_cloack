<?php 
class binom_cloaker{
	var $os_white;
	var $country_white;
	var $tokens_black;
	var $ip_black;
	var $referer;
	function __construct(){
		$this->detect();
	}
	function check(){
		$result=0;

		$os_white_checker = stristr($this->os_white, $this->detect['os']);
		$country_white_checker = stristr($this->country_white, $this->detect['country']);
		$ip_black_checker=  stristr($this->ip_black, $this->detect['ip']);

		if(!empty($this->os_white) && empty($os_white_checker) ){$result=1;$this->result[]='os';}
		if(!empty($this->country_white) && empty($country_white_checker) ){$result=1;$this->result[]='country';}
		if(!empty($this->ip_black) && !empty($ip_black_checker) ){$result=1;$this->result[]='ip';}
		if(!empty($this->referer) && (int)$this->referer==1 && (int)$this->detect['referer']==0){$result=1;$this->result[]='referer';}
		if(!empty($this->tokens_black)){
			$this->tokens_black=explode(',',$this->tokens_black);
			foreach($_GET AS $token){
				if(empty($token)){$token='Unknown';}
				if (in_array($token, $this->tokens_black)) {
					$result=1;
					$this->result[]='token';
				}
			}
		}
		return $result;
	}
	function detect(){
		include("geoip.inc");
		$g = geoip_open("GeoIP.dat", GEOIP_STANDARD);
		$a['referer']=0;
		$a['os']='Unknown';
		$a['country']='Unknown';
		if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])){$a['referer']=1;}
		$o = array ('Windows' => '(Windows)','Android' => '(Android)','IOS' => '(iPod)|(iPhone)|(iPad)','MacOS' => '(Mac OS)|(Mac_PowerPC)|(PowerPC)|(Macintosh)','UNIX' => '(UNIX)','Ubuntu' => '(Ubuntu)','ChromeOS' => '(ChromeOS)|(ChromiumOS)','Linux' => '(Linux)|(X11)','Symbian' => '(SymbianOS)','Robot' => '(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Slurp)|(msnbot)|(Ask Jeeves\/Teoma)|(ia_archiver)');
		if(isset($_SERVER['HTTP_USER_AGENT'])){
			foreach($o as $s=>$p){if(preg_match("/".$p."/i", $_SERVER['HTTP_USER_AGENT']) && $a['os']=='Unknown') { $a['os']=$s;}}
		}
		
		if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
			$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
			$ip=explode(", ", $ip);
			if(count($ip)<=1){$ip=explode(",", $ip[0]);}
			if(!empty($ip[0])){
				$a['ip']=$ip[0];
			}
		}
		if(!isset($a['ip'])){
			if(isset($_SERVER['REMOTE_ADDR'])){
				$a['ip']=$_SERVER['REMOTE_ADDR'];
			}else{
				$a['ip']='Unknown';
			}
		}
		$a['country'] = geoip_country_code_by_addr($g, $a['ip']);
		geoip_close($g);
		$this->detect=$a;
	}
}
?>