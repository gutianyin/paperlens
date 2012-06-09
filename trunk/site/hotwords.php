<?php
	$dom = new DOMDocument();
	$dom->load("http://127.0.0.1/api/hotwords.php");
	$words = $dom->getElementsByTagName('word');
	echo "<div>";
	foreach($words as $word)
	{
		echo "<a href=\"http://reculike.com/site/search.php?query=$word->nodeValue\">$word->nodeValue</a>&nbsp;";
	}
	echo "</div>"
?>