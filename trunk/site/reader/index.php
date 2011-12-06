<?php
require_once('db.php');
?>

<html>
	<head>
		<title>RSS源推荐</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<style type="text/css">
			body {font-family:Verdana; font-size:13px;line-height:26px;}
			#main{width:900px; margin:0 auto; margin-top:20px;}
			#head{width:900px; margin:0 auto; font-size:40px;margin-top:10px;}
			.item {width:100%;text-align:left;clear:both;}
			.feed {width:30%; float:left; }
			.article {width:50%; float:left; }
			.subscribe {width:20%; float:left; }
			a {font-size:13px; color: #1D5261; }
			a:hover {font-size:13px; color: #5697A3; }
			img {border:none;}
		</style>
	<head>
	<body>
		<div id="head">RSS源推荐</div>
		<div id="main">
			<div class="item">
				<span class="feed" style="font-size:16px;line-height:36px;font-weight:bold;">RSS源</span>
				<span class="article" style="font-size:16px;line-height:36px;font-weight:bold;">最新文章</span>
				<span class="subscribe" style="font-size:16px;line-height:36px;font-weight:bold;">订阅</span>
			</div>
			<?php

			$result = mysql_query("select name, link, latest_article_title, latest_article_link from feeds order by popularity desc limit 32");
			while($row=mysql_fetch_array($result))
			{
				$name = $row[0];
				$link = $row[1];
				$article = $row[2];
				$article_link = $row[3];
				if(strlen($article) < 10 || strlen($article_link) > 180 || strlen($article) > 80) continue;
				echo "<div class=\"item\"><span class=\"feed\"><a href=\"$link\" target=_blank>$name</a></span>"
					."<span class=\"article\"><a href=\"$article_link\">$article</a></span>"
					."<span class=\"subscribe\"><a href=\"http://fusion.google.com/add?feedurl=$link\"><img src=\"http://buttons.googlesyndication.com/fusion/add.gif\" /></a></span>"
					. "</div>";
			}
			?>
		</div>
	</body>
</html>