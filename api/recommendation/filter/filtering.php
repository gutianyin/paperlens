<?php

function filtering($behaviors, &$recommendations, $debug = FALSE)
{
	foreach($behaviors as $item=>$weight)
	{
		if($debug) echo $item . "\n";
		if(in_array($item, $recommendations[0]))
		{
			unset($recommendations[0][$item]);
			if($debug) echo $item . "\n";
		}
		if($debug) echo "\n";
	}
}

?>