<?php
	$dom = new DOMDocument();
	$dom->load("http://127.0.0.1/api/hotwords.php");
	$words = $dom->getElementsByTagName('word');
	foreach($words as $word)
	{
		echo $word->item(0)->nodeValue;
	}
?>