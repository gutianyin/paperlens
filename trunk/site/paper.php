<?php
session_start();
require_once('session.php');
if(!$login) Header("Location: index.php");
require_once("functions.php");
$paper = $_GET["id"];

$paper_dom = new DOMDocument();
if(!$paper_dom->load('http://50.18.105.189/api/paper.php?id=' . $paper))
{
	echo 'load xml failed';
	return;
}

$dom = new DOMDocument();
if(!$dom->load('http://50.18.105.189/api/recommendation/relate/default_related_items_xml.php?id=' . $paper))
{
	echo 'load xml failed';
	return;
}
$related_authors = array();
?>
<html>
	<head>
		<title>PaperLens : Open Source Academic Recommender System</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="./css/main.css" />
		<script src="./js/main.js" type="text/javascript"></script>
		<?
			include('./inc/sug_js.php');
			include('./inc/sug_css.php');
		?>
	</head>
	
	<body>
		<div id="content">
			<div id="header">
				<div id="toolbar">
					<span>Hi <?php echo $_SESSION["email"]; ?></span>&nbsp;&nbsp;
					<span><a href="/site/index.php">Home Page</a></span>&nbsp;&nbsp;
					<span><a href="/site/logout.php">Log out</a></span>
				</div>
				<div id="logo">PaperLens</div>
				<?
				include('./inc/search_bar.php');
				renderFirstPaper($paper_dom);
				?>
			</div>
			
			<div id="main">
				<div id="searchret">
					<h2>Related Articles</h2>
					<?php
					$papers = $dom->getElementsByTagName('paper');
					$related_authors = renderPapers($papers);
					?>
				</div>
			</div>
			<div id="side">
				<h2>Related Authors</h2>
				<div class="related_author">
				<?php
				renderRelatedAuthors($related_authors);
				?>
				</div>
			</div>
		</div>
		<div id="foot">&copy; PaperLens 2011</div>
	</body>
</html>