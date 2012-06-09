<?php
header('Content-Type: text/xml');

require_once('db.php');

$author = $_GET['author'];
$topN = $_GET['n'];
$user_id = 0;
if(isset($_GET['user'])) $user_id = $_GET['user'];

//$result = mysql_query("select * from sphinx  where query='@name \"" .$author . "\";mode=any;sort=extended:year desc;limit=".$topN. ";index=idx1';");
$result = mysql_query("select query, count(*) as c from user_search_log where length(query) < 24 and length(query) > 10 group by query order by c desc limit 20;");
if (!$result) {
    die('Query failed: ' . mysql_error());
}

echo "<result>";

while ($row = mysql_fetch_row($result))
{
	$query = $row[0];
	echo "<word>$query</word>";
}

echo "</result>";

?>