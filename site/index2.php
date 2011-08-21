<?php
session_start();
require_once('session.php');
if($login)
{
	require_once('../api/db.php');
	require_once("functions.php");
	$result = mysql_query("SELECT keywords,email FROM user WHERE id=".$uid);
        if (!$result) die("error when get keywords of user");
        $row = mysql_fetch_row($result);
        $keywords = $row[0];
        $email = $row[1];
	$dom = new DOMDocument();
	if(!$dom->load("http://127.0.0.1/api/recommendation/recsys_no_reason_xml.php?uid=" . $uid))
	{
		echo 'load xml failed';
		return;
	}
	$related_authors = array();
	$papers = $dom->getElementsByTagName('paper');
	if($papers->length == 0 && strlen($keywords) > 0)
	{
		$dom->load('http://127.0.0.1/api/search/search.php?n=10&query=' . str_replace(' ','+',$keywords));
		$papers = $dom->getElementsByTagName('paper');
	}
}
?>
<html>
	<head>
		<title>PaperLens : Open Source Academic Recommender System</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="./css/main.css" />
		<script src="./js/main.js" type="text/javascript"></script>
		<?
			include('./search/sug_js.php');
			include('./search/sug_css.php');
		?>
	</head>
	
	<body>
		<div id="content">
			<div id="header">
				<?php
				if($login){
				?>
				<div id="toolbar">
					<span>Hi <?php echo $email; ?></span>&nbsp;&nbsp;
					<span><a href="index.php">Home Page</a></span>&nbsp;&nbsp;
					<span><a href="./user/logout.php">Log out</a></span>
				</div>
				<?php } ?>
				<div id="logo">PaperLens</div>
				<?
				include('./search/search_bar.php');
				?>
			</div>
			<?php
			if($login==FALSE){
			?>
			<div id="intro">
				<h3>PaperLens is an academic paper recommender system which can : </h3>
				<ul>
					<li>Recommend academic papers by analyzing your historical preference</li>
					<li>Recommend related papers of given paper</li>
				</ul>
			</div>
			</div>
			<div id="login">
				<h3>Please login : </h3>
				<form action="./user/login.php" method="post" style="width:100%;float:left;">
					<div style="float:left;width:100%;"><span>Email&nbsp;</span><input type="text" name="email" class="textinput"/></div>
					<div style="float:left;width:100%;"><span>Password&nbsp;</span><input type="password" name="password" class="textinput"/></div>
					<input type="submit" value="Login" class="button" />&nbsp;
				</form>
				<div>
					<a href="./user/doubancon.php">Login by Douban</a>&nbsp;
					<a href="./user/weibocon.php">Login by Weibo</a>&nbsp;
					<a href="#" onclick="opensignup();" style="color:#647B0F;">SignUp</a>
				</div>
			</div>
			<div id="signup" style="display:none;">
				<h3>Please Signup : </h3>
				<form action="./user/signup.php" method="post" style="width:100%;float:left;">
					<div style="float:left;width:100%;"><span>User Name&nbsp;</span><input type="text" name="username" class="textinput"/></div>
					<div style="float:left;width:100%;"><span>Email&nbsp;</span><input type="text" name="email" class="textinput"/></div>
					<div style="float:left;width:100%;"><span>Password&nbsp;</span><input type="password" name="password" class="textinput"/></div>
					<div style="float:left;width:100%;"><span>Interest Keywords&nbsp;</span><input type="text" name="keywords" class="textinput"/></div>
					<input type="submit" value="Signup" class="button" />
				</form>
				<div>
					<a href="./user/doubancon.php">Connect to Douban</a>&nbsp;&nbsp;
					<a href="./user/weibocon.php">Connect to Sina</a>
				</div>
			</div>
			<?php
			}
			else
			{
				
				if($papers->length > 0)
				{
			?>
				<div id="main">
				<h2>Paper Recommendations : </h2>
				<?php
					
					$related_authors = renderPapers($papers);
				?>
				</div>
				<div id="side">
					<h2>Related Authors</h2>
					<div class="related_author">
					<?php
					renderRelatedAuthors($related_authors);
					?>
					</div>
				</div>
			<?php }
				else
				{
				?>
				<div id="main">
				&nbsp;<br/>&nbsp;<br/>
				<span>As a new user, we need more information to make recommendations for you.</span>
				<span style="color:#647B0F;">Could you please input some tags which can bestly describe your interest:</span>
				<form style="width:100%;float:left;" action="coldstart.php" method="post">
				<input style="width:80%;float:left;height:26px;line-height:26px;" type="text" name="keywords" value=""/>
				<input style="width:15%;float:left;height:26px;line-height:26px;" type="submit" value="Submit"/>
				</form>
				<span style="color:#1F81CD;">Or you can use search engine now to find papers you like.</span>
				</div>
				<?php
				}
			}
			?>
		</div>
		<div id="feedbackcode"></div>
	</body>
</html>