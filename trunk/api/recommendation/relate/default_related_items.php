<?php
function GetRelatedItems($item, $table_name, $topN)
{
	$ret = array();
	$title_result = mysql_query('select title from paper where id='.$item);
	if (!$title_result) return $ret;
	$title = '';
	while ($row = mysql_fetch_row($title_result))
	{
		$title = $row[0];
	}
	
	$result = mysql_query('select id,weight from sphinx  where query=\'@title \"' . str_replace(' ', '+', $title) . '\";mode=any;sort=relevance;limit=' . (1 + $topN). ';index=idx1\';');
	if (!$result) {
	    die('Query failed: ' . mysql_error());
	}
	while ($row = mysql_fetch_row($result))
	{
		$id = $row[0];
		if($id == $item) continue;
		$weight = $row[1];
		$ret[$id] = $weight;
	}
	arsort($ret);
	echo count($ret) . " ";
	return array_slice($ret, 0, $topN, TRUE);
}

?>