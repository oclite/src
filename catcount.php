<?php
echo "start<br/>\n";

include("config.php");

$mysqli = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

if ($res = $mysqli->query('SELECT DISTINCT `category_id` FROM `'.DB_PREFIX.'product_to_category`')) {
	$cids = array();
    while ($row = $res->fetch_row()) $cids[] = intval($row[0]);
    $res->close();
	$i = 0;
	$f = fopen("count.sql","w");
	foreach ($cids as $cid){
		if ($res = $mysqli->query('SELECT COUNT(*) FROM `'.DB_PREFIX.'product_to_category` WHERE `category_id`='.$cid)){
			$count = $res->fetch_row()[0];
			$res->close();
			$q = 'UPDATE `'.DB_PREFIX.'category` SET `icount`='.$count.' WHERE `category_id`='.$cid;
			fwrite($f,$q."\n");
			//$mysqli->query($q);
		}
		$i++;
	}
	fclose($f);
	echo $i."<br />\n";
}

echo 'end';
$mysqli->close();
//sub.moscow - free
//hits.moscow - free
