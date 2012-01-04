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
