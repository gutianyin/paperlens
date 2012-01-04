<?php
require_once('db.php');
srand(time());

function IsChinese($buf)
{
	for($i = 0; $i < strlen($buf); ++$i)
	{
		if(ord($buf[$i]) > 127) return true;
	}
	return false;
}
function GetArticle($article_id)
{
	$itemxml = file_get_contents("http://www.reculike.com/site/reader/articles/" . (string)($article_id % 10) . "/" . (string)($article_id));
	$p1 = strpos($itemxml, "<description>");
	$p2 = strpos($itemxml, "</description>");
	return htmlspecialchars_decode(substr($itemxml, $p1, $p2 - $p1));
}

$get_rss = 0;
if(isset($_GET["rss"])) $get_rss = 1;

$history = array();
if(array_key_exists("his", $_COOKIE)) $history = explode("_", $_COOKIE["his"]);
$load_history = array();
if(array_key_exists("loadhis", $_COOKIE)) $load_history = explode("_", $_COOKIE["loadhis"]);
$uid = -1;
if($get_rss == 1)
{
	if(count($history) > 0)
	{
		$result = mysql_query("select max(user_id) from myfeed;");
		while($row=mysql_fetch_array($result))
		{
			$uid = $row[0];
		}
		$uid += 1;
		foreach($history as $id)
		{
			if(strlen($id) == 0) continue;
			mysql_query("insert into myfeed (user_id, feed_id) values ($uid, $id)");
		}
	}
}
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
	<script type="text/javascript">
  window.___gcfg = {lang: 'zh-CN'};

  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>
		<div id="head">
			<p style="font-size:14px;color:#888;">选择自己喜欢的feed点击订阅，点击刷新看到更多的候选feed，选定后点击生成RSS可以生成一个RSS链接</p>
			<p style="font-size:14px;color:#888;">分享: <a href="javascript:void(function(){var d=document,e=encodeURIComponent,s1=window.getSelection,s2=d.getSelection,s3=d.selection,s=s1?s1():s2?s2():s3?s3.createRange().text:'',r='http://www.douban.com/recommend/?url='+e(d.location.href)+'&title='+e(d.title)+'&sel='+e(s)+'&v=1',x=function(){if(!window.open(r,'douban','toolbar=0,resizable=1,scrollbars=yes,status=1,width=450,height=330'))location.href=r+'&r=1'};if(/Firefox/.test(navigator.userAgent)){setTimeout(x,0)}else{x()}})()"><img src="http://img2.douban.com/pics/fw2douban1.png" alt="推荐到豆瓣" /></a><g:plusone size="medium" annotation="inline"></g:plusone></p>
			<a href="http://www.reculike.com/site/reader/" class="butn">刷新</a>
			<a onclick="deleteHistory();" href="http://www.reculike.com/site/reader/" class="butn">
				重置
			</a>
			<?php
				if($uid < 0)
				{
					echo "<a href=\"http://www.reculike.com/site/reader/?rss=1\" class=\"butn\">生成RSS</a>";
				}
				else
				{
					$rss_link = "http://www.reculike.com/site/reader/myfeed.php?uid=$uid";
					$rss_encode_link = urlencode($rss_link);
					echo "<a target=\"_blank\" href=\"$rss_link\" class=\"butn\">我的RSS</a>";
					echo "<a class=\"butn\" style=\"background:#FFF;\" target=\"_blank\" href=\"http://fusion.google.com/add?source=atgs&feedurl=$rss_encode_link\"><img src=\"http://buttons.googlesyndication.com/fusion/add.gif\" border=\"0\" alt=\"Add to Google\"></a>";
					echo "<a target=\"_blank\" href=\"http://xianguo.com/subscribe?url=$rss_encode_link\"><img src=\"http://xgres.com/static/images/sub/sub_XianGuo_08.gif\" border=\"0\" /></a>";
				}
			?>
		</div>
		<div id="main">
			<div class="item">
				<span class="feed" style="font-size:16px;line-height:36px;font-weight:bold;">RSS源</span>
				<span class="article" style="font-size:16px;line-height:36px;font-weight:bold;">最新文章</span>
				<span class="subscribe" style="font-size:16px;line-height:36px;font-weight:bold;">订阅</span>
			</div>
			<?php
			
			$n = 0;
			$k = 0;
			$rank = array();
			$names = array();
			$articles = array();
			foreach($history as $src_id)
			{
				if(strlen($src_id) == 0) continue;
				$max_weight = 0;
				$j = 0;
				$result = mysql_query("select dst_id,weight from feedsim where src_id=$src_id order by weight desc");
				while($row=mysql_fetch_array($result))
				{
					$dst_id = $row[0];
					$weight = $row[1];
					if($j == 0) $max_weight = $weight;
					++$j;
					if(in_array($dst_id, $history)) continue;
					if(in_array($dst_id, $load_history)) continue;
					if(!array_key_exists($dst_id, $rank)) $rank[$dst_id] = $weight / $max_weight;
					else $rank[$dst_id] += $weight / $max_weight;
				}
			}
			$minvalue = 0.01;
			$result = mysql_query("select id from feeds a order by popularity desc");
			while($row=mysql_fetch_array($result))
			{
				$id = $row[0];
				if(array_key_exists($id, $rank)) continue;
				if(in_array($id, $history)) continue;
				if(in_array($id, $load_history)) continue;
				$rank[$id] = $minvalue * 0.95;
				$minvalue *= 0.95;
				if(count($rank) > 300) break;
			}
			$ids = '';
			$n = 0;
			foreach($rank as $id => $w)
			{
				if($w < 0) continue;
				$ids .= $id . ',';
				
				if(++$n > 100) break;
			}
			$ids .= '0';
			$n = 0;
			foreach($history as $id)
			{
				if(strlen($id) == 0) continue;
				$result = mysql_query("select a.name, a.link, c.title, c.link  from feeds a, feed_articles b, articles c where a.id=$id and a.id=b.feed_id and b.article_id=c.id order by c.pub_at desc");
				while($row=mysql_fetch_array($result))
				{
					$name = $row[0];
					if(in_array($name, $names)) continue;
					array_push($names, $name);
					$link = $row[1];
					$encode_link = urlencode($link);
					$article = $row[2];
					if(in_array($article, $articles)) continue;
					array_push($articles, $article);
					$article_link = $row[3];
					if(++$n > 24) break;
					$onclick_str = "onclick=\"addHistory($id);\"";
					$like_str = "<a id=\"feed_$id\" class=\"like\" $onclick_str>订阅</a>";
					if(in_array($id, $history)) $like_str = "<a id=\"feed_$id\" class=\"like\" $onclick_str style=\"background:#AAA;\">谢谢</a>";

					echo "<div class=\"item\"><span class=\"feed\"><a href=\"$link\" target=_blank>$name</a></span>"
					     . "<span class=\"article\"><a href=\"$article_link\" target=_blank>$article</a></span>"
					     . "<span class=\"subscribe\">$like_str</span>"
					     . "</div>";
				}
			}
			foreach($rank as $id => $w)
			{
				$result = mysql_query("select a.name, a.link, c.title, c.link,c.id  from feeds a, feed_articles b, articles c where a.id=$id and a.id=b.feed_id and b.article_id=c.id order by c.pub_at desc");
				while($row=mysql_fetch_array($result))
				{
					$name = $row[0];
					if(in_array($name, $names)) continue;
					array_push($names, $name);
					$link = $row[1];
					$encode_link = urlencode($link);
					$article = $row[2];
					if(in_array($article, $articles)) continue;
					array_push($articles, $article);
					$article_link = $row[3];
					$article_id = $row[4];
					$encode_article_link = urlencode($article_link);
					$title = urlencode($name . ": " . $article . " / 分享自 http://www.reculike.com/site/reader/");
					if(strlen($name) > 40 || strlen($article) < 10 || strlen($article_link) > 180 || strlen($article) > 80) continue;
					if(!IsChinese($article)) continue;
					if(++$n > 24) break;
					$onclick_str = "onclick=\"addHistory($id);\"";
					$like_str = "<a id=\"feed_$id\" class=\"like\" $onclick_str>订阅</a>";
					if(in_array($id, $history)) $like_str = "<a id=\"feed_$id\" class=\"like\" $onclick_str style=\"background:#AAA;\">谢谢</a>";

					echo "<div class=\"item\"><span class=\"feed\"><a href=\"$link\" target=_blank>$name</a></span>"
					     . "<span class=\"article\"><a href=\"/site/reader/article.php?id=$article_id\" target=_blank>$article</a></span>"
					     . "<span class=\"subscribe\">$like_str</span>"
					     . "</div>";
					echo "<script type=\"text/javascript\">addLoadHistory($id)</script>";
				}
			}
			?>
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
