<?php
include('binom_cloaker.php');
$binom_cloaker=new binom_cloaker();
$binom_cloaker->os_white='Windows,Android,IOS,MacOS,UNIX,Ubuntu,ChromeOS,Linux,Symbian,Robot';
$binom_cloaker->country_white='RU,AR,IQ,OM';
$binom_cloaker->ip_black='0.0.0.1,0.0.0.2';
$binom_cloaker->tokens_black='{token},{token1},{token2}';
$binom_cloaker->referer='0'; // if 1 - check referrer, 0 - dont check

if($binom_cloaker->check()==0){
?>
	<!-- YOUR LANDER's CODE HERE -->
<?php
} else {
?>
	<!-- YOUR WHITE LANDER's (SAFE PAGE) CODE HERE -->
<?php
}
?>
