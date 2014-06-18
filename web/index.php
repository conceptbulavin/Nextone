<?php

ini_set('memory_limit', -1);
ini_set('max_execution_time', 0);

header('Content-Type: text/html; charset=utf-8');

include_once dirname(__FILE__).'/conf.php';
include_once dirname(__FILE__).'/funcs.php';

$_TIME_START = microtime_float();

$db = db_connect();
$SID = api_auth();
echo "\n";

clearDB();

$targets = get_targets($SID);
if($targets) {
	foreach($targets as $key_target => $std_target) {
		$target = (array)$std_target;
		// target
		update_target($target);
		echo ($key_target + 1)." - id: ".$target['targetID']." | name: ".$target['name']."\n";
	}
	echo "\n";
}

$vendors = get_vendors($SID);
if($vendors) {
	foreach($vendors as $key_vendor => $std_vendor) {
		$vendor = (array)$std_vendor;
		// vendor
		update_vendor($vendor);
		// vendor_category
		update_vendor_category($vendor['vendorID'], $vendor['categoryID']);
		echo ($key_vendor + 1)." - id: ".$vendor['vendorID']." | name: ".$vendor['name']." | category id: ".$vendor['categoryID']."\n";
	}
	echo "\n";
}

$sum = 0;
$existing = array();
$stocks = array();
$categories = get_categories($SID);
if($categories) {
	foreach($categories as $key_category => $std_category) {
		$category = (array)$std_category;
		// category
		update_category($category);

		$tmp = (array)get_products($SID, $category['categoryID']);
		if(isset($tmp) && $tmp && isset($tmp['count'])) {
			echo ($key_category + 1)." - id: ".$category['categoryID']." | name: ".$category['name']." | product count: ".$tmp['count']."\n";

			if($tmp['count'] > 0) {
				$sum = $sum + $tmp['count'];
				$offset = 0;
				do {
					$std_products = get_products($SID, $category['categoryID'], $offset, $conf['api_param']['limit']);
					$products = (array)$std_products;

					if(isset($products) && $products && count($products['list']) > 0) {
						echo '    '.($offset + 1).' - '.($offset + count($products['list']))." ";
						foreach($products['list'] as $key_product => $std_product) {
							if(!($key_product % 100)) {
								echo ".";
							}
							$product = (array)$std_product;
							if(!isset($existing[$product['productID']])) {
								// product
								update_product($product);
								$existing[$product['productID']] = 1;
								$stocks[$product['productID']] = $product['stocks'];
							}
							// product_category
							update_product_category($product['productID'], $category['categoryID']);
						}
						echo "\n";
						$offset = $offset + $conf['api_param']['limit'];
					}

				} while(isset($products) && $products && count($products['list']) > 0);
			}

		} else {
			echo ($key_category + 1)." - id: ".$category['categoryID']." ERROR\n";
		}
	}
	echo "sum: ".$sum;
	echo "\n\n";
	echo render_time().' s.';
	echo "\n\n";

	// details
	if($existing) {
		$k = 0;
		echo "products count: ".count($existing)."\n";
		foreach($existing as $key => $val) {
			$k++;
			$std_product = get_product($SID, $key);
			$product = (array)$std_product;
			echo $k." - id: ".$key." | name: ".$product['name']."\n";
			// details_product
			update_details_product($product);
		}
	}

	// delivery_time
	if($existing) {
		$k = 0;
		echo "products count: ".count($existing)."\n";
		foreach($existing as $key => $val) {
			$k++;
			if(isset($stocks[$key]) && count($stocks[$key]) > 0) {
				$std_product_delivery_time = get_product_delivery_time($SID, $key, $stocks[$key]);
				$product_delivery_time = (array)$std_product_delivery_time;
				echo $k." - id: ".$key." | count: ".count($product_delivery_time)."\n";
				// delivery_time
				update_delivery_time_product($key, $product_delivery_time);
			} else {
				echo $k." - id: ".$key." | - \n";
			}
		}
	}
}

echo "\n";
api_logout($SID);
echo render_time().' s.';

$db = null;