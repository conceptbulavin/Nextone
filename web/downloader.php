<?php

ini_set('memory_limit', -1);
ini_set('max_execution_time', 0);

header('Content-Type: text/html; charset=utf-8');

include_once dirname(__FILE__).'/conf.php';
include_once dirname(__FILE__).'/funcs.php';

$_TIME_START = microtime_float();

$db = db_connect();

echo "\n";

$tbl_product = "product";
$sql = "SELECT `p`.`productID`, `p`.`large_image` FROM `".$tbl_product."` AS `p`";
$stmt = $db->prepare($sql);
$stmt->execute();
$n = 0;
$m = 0;
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$n++;
	echo $n." - ";
	echo ($row['productID']);
	if($row['large_image']) {

		echo " | ".$row['large_image']." ...";
		$image_name = get_image_name($row['large_image']);
		// download
		download_image($row['large_image'], 'images/'.$image_name);
		// update db
		$data = array();
		$data['productID'] = $row['productID'];
		$data['real_image'] = $image_name;
		update_image_product($data);
		// counts
		$m++;

		echo " ok";
	} else {
		echo " - none";
	}
	echo "\n";
}

echo "\n";
echo "Downloaded ".$m." images";
echo "\n";
echo render_time().' s.';

$db = null;