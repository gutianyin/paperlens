<?php
require_once('db.php');
srand(time());

function GetArticle($article_id)
{
	$itemxml = file_get_contents("http://www.reculike.com/site/reader/articles/" . (string)($article_id % 10) . "/" . (string)($article_id));
	$p1 = strpos($itemxml, "<description>");
	$p2 = strpos($itemxml, "</description>");
	return htmlspecialchars_decode(substr($itemxml, $p1, $p2 - $p1));
}

$id = $_GET['id'];

?>

<html>
	<head>
		<title>个性化RSS</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<script src="reader.js" type="text/javascript"></script>
		<style type="text/css">
			body {font-family:Verdana; font-size:13px;line-height:26px;}
			#main{width:900px; margin:0 auto; margin-top:20px;padding-left:5px;}
			#head{width:900px; margin:0 auto; font-size:40px;margin-top:30px; font-weight:bold;}
			.item {width:100%;text-align:left;clear:both;}
			.feed {width:29%; float:left; }
			.article {width:56%; float:left; }
			.like{display:block;width:50px;float:left;text-decoration:none;background:#000;height:18px;line-height:18px;cursor:pointer;margin-top:3px;margin-left:3px;font-size:12px;text-align:center;color:#FFF;}
			.share{display:block;width:40px;float:left;height:18px;line-height:18px;cursor:pointer;margin-top:3px;font-size:12px;text-align:center}
			.subscribe {width:12%; float:left;vertical-align:bottom; }
			a {font-size:13px; color: #1D5261;}
			a:hover {color: #5697A3;}
			.butn {display:block; float:left; margin-left:5px;width:120px;height:28px;background:#DDD;text-decoration:none;text-align:center;color:#333;font-size:14px;cursor:pointer;}
			/*.feedtitle {height:18px;line-height:18px; display:block;float:left;width:95%;}*/
			img {border:none;}
		</style>
	<head>
	<body>
		<div id="main">
			<?php echo GetArticle($id); ?>
		</div>
		<script type="text/javascript">

		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', 'UA-1103913-21']);
		  _gaq.push(['_trackPageview']);
		
		  (function() {
		    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();

		</script>
&nbsp;<br>&nbsp;<br>
	</body>
</html>
