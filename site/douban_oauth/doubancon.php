<?php
//error_reporting(0);
//error_reporting(E_ALL);


require_once('DoubanOauth.php');
//print "hola";

$myclient = new DoubanOAuthClient($my_consumer_key,$my_consumer_secret);
$rt_arr = $myclient->get_request_token();
foreach($rt_arr as $k=>$v){
    //echo $k.":".$v."<br>";
}
//print $rt_arr['oauth_token'];

$a_url = $myclient->get_authorization_url($rt_arr['oauth_token'],$rt_arr['oauth_token_secret'],"http://127.0.0.1/site/douban_oauth/doubansave.php");

Header("Location: $a_url");

//print $a_url;

?>
