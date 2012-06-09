<?php
	$dom = new DOMDocument();
	$dom->load("http://127.0.0.1/api/hotwords.php");
	$words = $dom->getElementsByTagName('word');
	echo "<h2>Hot Keywords</h2>";
	echo "<div style=\"text-align:justify;\">";
	foreach($words as $word)
	{
		echo "<a href=\"http://reculike.com/site/search.php?query=$word->nodeValue\">$word->nodeValue</a>&nbsp;";
	}
	echo "</div>"
?>