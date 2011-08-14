<?php

session_start();
//if( isset($_SESSION['last_key']) ) header("Location: weibolist.php");
include_once( 'config.php' );
include_once( 'weibooauth.php' );



$o = new WeiboOAuth( WB_AKEY , WB_SKEY  );

$keys = $o->getRequestToken();


/*
if( strpos( $_SERVER['SCRIPT_URI'] , 'index.php' ) === false )
	$callback =  $_SERVER['SCRIPT_URI'].'/callback.php';
else	
	$callback =  str_replace( 'index.php' , 'callback.php' , $_SERVER['SCRIPT_URI'] );
 */

if( strpos( $_SERVER['SCRIPT_NAME'] , 'index.php' ) === false )
    $callback =  $_SERVER['SCRIPT_NAME'].'/callback.php';
else	
    $callback =  str_replace( 'index.php' , 'callback.php' , $_SERVER['SCRIPT_NAME'] );
    $callback = "http://".$_SERVER['SERVER_NAME'].$callback;


$aurl = $o->getAuthorizeURL( $keys['oauth_token'] ,false , $callback );

$_SESSION['keys'] = $keys;

//print $_SERVER['SCRIPT_URI'];
/*
foreach($_SERVER as $k=>$v){
    print $k.":".$v."<br/>";
}
 */

?>

<a href="<?php print $aurl; ?>">Use Oauth to login</a>
