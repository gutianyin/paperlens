<?php


if($_SERVER['REQUEST_METHOD'] == "GET"){
    //show the login page
?>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="Author" contect="wangxing">
    <meta http-equiv="Content-Language" contect="zh-CN">
    <script type="text/javascript" src=""></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.js" type="text/javascript"></script>

<script type="text/javascript">
    <!--
    //handle the blur of checking username and email
    $emailok = false;

    function checkreg(){
        //check empty and the format things
        if($emailok == false){
            alert("email invalid");
            return false;
        }
        return true;
    }

function checkEmail(){
    $curemail = $("#id_email").attr('value');
    if(!checkEmailFormat($curemail)){
        //alert($curemail+checkEmailFormat($curemail));
        $emailok = false;
        $("#id_email_hidden").html("email format error");
    }
    else{
        $.getJSON("/site/user/checkemailused?email="+$("#id_email").attr("value"),checkEmailResult);
    }
}

function isValidEmailAddress(emailAddress) {
var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
alert( pattern.test(emailAddress) );
};


function checkEmailFormat(email_addr){
    var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
    return pattern.test(email_addr);
}

function checkEmailResult(data){
    //$("#id_email_hidden").attr("hidden","false");
    if(data.status == "good"){
        $emailok = false;
        $("#id_email_hidden").html("email invalid");
    }
    else if(data.status == "bad"){
        $emailok = true;
        $("#id_email_hidden").html("email ok");
    }
    else{
        alert("status not right:"+data.status);
    }
}
-->


function initevent(){
    $("#id_email").keyup(checkEmail);
    checkEmail();
}

$(initevent);

</script>
</head>

<body>

<div id="content-main">
<form action="/site/login.php" method="post"> 
    <table>
        <tr>
            <td><label for="id_email">Email:</label> </td>
            <td><input type="text" name="email" id="id_email"></td>
            <td><label id="id_email_hidden"></label></td>
        </tr>

        <tr>
            <td><label for="id_password">Password:</label> </td>
            <td><input type="password" name="password" id="id_password"></td>
        </tr>
        <tr>
            <td><input type="submit" value="Login" onclick="return checkreg()"></td>
        </tr>
    </table>
    <table>
        <tr>
            <td>使用其他方式登陆：</td>
            <td><a href="/site/doubancon.php">连接豆瓣</a></td>
            <td><a href="/site/weibocon.php">连接新浪</a></td>
        </tr>
    </table>
</form>
</body>

<?php
}
else{
require_once('db.php');
$password = md5($_POST["password"]);
$email = $_POST["email"];

function IsEmailExist($mail)
{
	$result = mysql_query("select count(*) from user where email='$mail'");
	if($result)
	{
		$row = mysql_fetch_row($result);
		if($row[0] == 1) return TRUE;
	}
	return FALSE;
}

if(IsEmailExist($email))
{
	echo "exist email";
	$result = mysql_query("SELECT id FROM user WHERE email='".$email."' and passwd = '" . $password . "'");
	if ($result && mysql_num_rows($result) > 0)
	{
		$row = mysql_fetch_row($result);
		$uid = $row[0];
		echo $uid;
		session_start();
		$_SESSION["admin"] = true;
		$_SESSION["uid"] = $uid;
		$_SESSION["email"] = $email;
		Header("Location: index.php");
	} else {
		echo "User name and password error";
	}
}
else
{
	if(strpos($email, "@") === false)
	{
		echo "<h2>Email address is invalid!</h2>";
		return;
	}
	if(strlen($_POST["password"]) < 6)
	{
		echo "<h2>Password must exceed 6 characters</h2>";
		return;
	}
	echo "insert into user (email,passwd) values ('" . $email . "', '".$password."');";
	mysql_query("insert into user (email,passwd) values ('" . $email . "', '".$password."');");

	$result = mysql_query("SELECT id FROM user WHERE email='".$email."' and passwd = '" . $password . "'");
	if ($result && mysql_num_rows($result) > 0) 
	{
		$row = mysql_fetch_row($result);
		$uid = $row[0];
		session_start();
		$_SESSION["admin"] = true;
		$_SESSION["uid"] = $uid;
		$_SESSION["email"] = $email;
		Header("Location: index.php");
	}
}
}
?>
