<?php
session_start();
require_once('session.php');
require_once('config.php');
if($login)
{
	require_once('../api/db.php');
	require_once("functions.php");
	$result = mysql_query("SELECT keywords,email FROM user WHERE id=".$_SESSION['uid']);
	if (!$result) die("error when get keywords of user");
	$row = mysql_fetch_row($result);
	$keywords = $row[0];
        $email = $row[1];
	$dom = new DOMDocument();
	$home_type = 'recommendation';
	if(isset($_GET['type'])) $home_type = $_GET['type'];
	if($home_type == 'recommendation')
		$dom->load("http://127.0.0.1/api/recommendation/recsys_reason_xml.php?uid=" .$_SESSION['uid']);
	else
		$dom->load("http://127.0.0.1/api/user/" . $home_type . ".php?uid=" .$_SESSION['uid']);
	$related_authors = array();
	$related_users = array();
	$papers = $dom->getElementsByTagName('paper');
	if($home_type == 'recommendation')
	{
		if($papers->length == 0 && strlen($keywords) > 0)
		{
			$keywords = trim($keywords, " ,.;");
			$keywords = str_replace(',', '|', $keywords);
			$dom->load('http://127.0.0.1/api/search/search.php?n=10&query=' . str_replace(' ','+',$keywords));
			$papers = $dom->getElementsByTagName('paper');
		}
	}
}
?>
<html>
	<head>
		<title><?php echo $SITE_NAME; ?> : Open Source Academic Recommender System</title>
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
				<div style="width:100%;float:left;">
				<div id="toolbar">
					<span>Hi <?php echo $email; ?></span>&nbsp;&nbsp;
					<span><a href="./index.php">Home Page</a></span>&nbsp;&nbsp;
					<span><a href="./logout.php">Log out</a></span>&nbsp;&nbsp;
				</div>
				<div id="share">
					<span>
						<script type="text/javascript" charset="utf-8">
						(function(){
						  var _w = 106 , _h = 24;
						  var param = {url:location.href,type:'6',count:'', appkey:'',title:'',pic:'',ralateUid:'2363867140',rnd:new Date().valueOf()}
						  var temp = [];
						  for( var p in param ){
						    temp.push(p + '=' + encodeURIComponent( param[p] || '' ) )
						  }
						  document.write('<iframe allowTransparency="true" frameborder="0" scrolling="no" src="http://hits.sinajs.cn/A1/weiboshare.html?' + temp.join('&') + '" width="'+ _w+'" height="'+_h+'"></iframe>')
						})()
						</script>
					</span>
					<span>
						<a href="javascript:void(function(){var d=document,e=encodeURIComponent,s1=window.getSelection,s2=d.getSelection,s3=d.selection,s=s1?s1():s2?s2():s3?s3.createRange().text:'',r='http://www.douban.com/recommend/?url='+e(d.location.href)+'&title='+e(d.title)+'&sel='+e(s)+'&v=1',x=function(){if(!window.open(r,'douban','toolbar=0,resizable=1,scrollbars=yes,status=1,width=450,height=330'))location.href=r+'&r=1'};if(/Firefox/.test(navigator.userAgent)){setTimeout(x,0)}else{x()}})()"><img src="http://img2.douban.com/pics/fw2douban1.png" alt="�Ƽ�������" /></a>
					</span>
					<!--<span><a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></span>-->
				</div>
				</div>
				<?php } else echo "&nbsp;<br>"; ?>
				<div id="logo"><?php echo $SITE_NAME; ?></div>
				<?
				include('./search/search_bar.php');
				?>
			</div>
			<?php
			if($login==FALSE){
			?>
			<div style="width:100%;float:left;clear:both;margin-top:30px;">
				<div id="intro">
					<h3><?php echo $SITE_NAME; ?> is an academic paper recommender system which can : </h3>
					<ul>
						<li>Recommend academic papers by analyzing your historical preference</li>
						<li>Recommend related papers of given paper</li>
					</ul>
				</div>
				<div id="login">
					<?php require_once('./tools/login_section.php'); ?>
				</div>
			</div>
			<div style="width:95%;float:left;clear:both;margin-top:30px;text-align:left;">
				<?php include_once("hotwords.php"); ?>
				<div style="width:100%;text-align:center; clear:both; margin-top:20px;">
				<script type="text/javascript"><!--
				google_ad_client = "ca-pub-8346721777356276";
				/* reculike home */
				google_ad_slot = "6228489593";
				google_ad_width = 728;
				google_ad_height = 90;
				//-->
				</script>
				<script type="text/javascript"
				src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
				</script>
				</div>
			</div>
			<?php
			}
			else
			{
			?>
				<div id="main">
				<span id="home_type">
					<a href="index.php?type=bookmarked" style="<?php if($home_type=='bookmarked') echo 'background:#E4EEF0'; ?>">Your Bookmarks</a>
					<a href="index.php?type=recommended" style="<?php if($home_type=='recommended') echo 'background:#E4EEF0'; ?>">Recommended by You</a>
					<a href="index.php?type=recommendation" style="<?php if($home_type=='recommendation') echo 'background:#E4EEF0'; ?>">Recommendation for You</a>
				</span>
				<?php
				if($papers->length > 0)
				{
					if($home_type == 'recommendation') renderRecommendationPapers($papers, $related_authors, $related_users);
					else renderPapers($papers, $related_authors, $related_users);
				?>
				
			<?php }
				else
				{
					if($home_type == 'recommendation'){
				?>
					<span>As a new user, we need more information to make recommendations for you.</span>
					<span style="color:#647B0F;">Could you please input some tags <font color=red>(seprated by comma)</font> which can bestly describe your interest:</span>
					<form style="width:100%;float:left;" action="coldstart.php" method="post">
						<input style="width:80%;float:left;height:26px;line-height:26px;" type="text" name="keywords" value=""/>
						<input style="width:15%;float:left;height:26px;line-height:26px;" type="submit" value="Submit"/>
					</form>
					<span style="color:#1F81CD;width:100%;float:left;">Or you can use search engine now to find papers you like.</span>
				<?php
					}
					else
					{
				?>
					<span>Sorry! You have not done this behavior before, so there is no results!</span>
				<?php
					}
				}
				?>
				</div>
				<div id="side">
					<h2>Admin</h2>
					<div class="related_author">
						<span><a href="./user/edit.php">Edit My Info</a></span>
					</div>
					<?php renderRelatedUsers($related_users); ?>
					<h2>Related Authors</h2>
					<div class="related_author">
					<?php
					renderRelatedAuthors($related_authors);
					?>
					</div>
				</div>
				<?php
			}
			?>
		</div>
		<div id="foot">&copy; <?php echo $SITE_NAME; ?> 2011</div>
		<div id="feedbackcode"></div>
		<?php require_once('ga.php'); ?>
	</body>
</html>