<?php

// DB
function db_connect() {
	global $conf;

	try {
		$db	= new PDO('mysql:host='.$conf['db']['host'].';dbname='.$conf['db']['dbname'], $conf['db']['user'], $conf['db']['pass']);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db->exec("set names utf8");
		echo "DB connect: ok\n";
		return $db;
	} catch(PDOException $e) {
		die($e->getMessage());
	}
}

function update_category($category) {
	global $db;

	$sql = "INSERT INTO `category` ".
		   "(`categoryID`, `name`, `parentID`) VALUES (:categoryID, :name1, :parentID1) ".
		   "ON DUPLICATE KEY UPDATE `name` = :name2, `parentID` = :parentID2";

	$stmt = $db->prepare($sql);

	$stmt->bindParam(':categoryID', $category['categoryID']);

	$stmt->bindParam(':name1', $category['name']);
	$stmt->bindParam(':name2', $category['name']);

	$stmt->bindParam(':parentID1', $category['parentID']);
	$stmt->bindParam(':parentID2', $category['parentID']);

	$stmt->execute();
}

function update_product($product) {
	global $db;

	$sql = "INSERT INTO `product` ".
		   "(`productID`, `name`, `brief_description`, `product_code`, `warranty`, `is_archive`, `vendorID`, `articul`, `volume`, `categoryID`, `is_new`, `price`, `price_uah`, `small_image`, `medium_image`, `large_image`) VALUES (:productID, :name1, :brief_description1, :product_code1, :warranty1, :is_archive1, :vendorID1, :articul1, :volume1, :categoryID1, :is_new1, :price1, :price_uah1, :small_image1, :medium_image1, :large_image1) ".
		   "ON DUPLICATE KEY UPDATE `name` = :name2, `brief_description` = :brief_description2, `product_code` = :product_code2, `warranty` = :warranty2, `is_archive` = :is_archive2, `vendorID` = :vendorID2, `articul` = :articul2, `volume` = :volume2, `categoryID` = :categoryID2, `is_new` = :is_new2, `price` = :price2, `price_uah` = :price_uah2, `small_image` = :small_image2, `medium_image` = :medium_image2, `large_image` = :large_image2";

	$stmt = $db->prepare($sql);

	$stmt->bindParam(':productID', $product['productID']);

	$stmt->bindParam(':name1', $product['name']);
	$stmt->bindParam(':brief_description1', $product['brief_description']);
	$stmt->bindParam(':product_code1', $product['product_code']);
	$stmt->bindParam(':warranty1', $product['warranty']);
	$stmt->bindParam(':is_archive1', $product['is_archive']);
	$stmt->bindParam(':vendorID1', $product['vendorID']);
	$stmt->bindParam(':articul1', $product['articul']);
	$stmt->bindParam(':volume1', $product['volume']);
	$stmt->bindParam(':categoryID1', $product['categoryID']);
	$stmt->bindParam(':is_new1', $product['is_new']);
	$stmt->bindParam(':price1', $product['price']);
	$stmt->bindParam(':price_uah1', $product['price_uah']);
	$stmt->bindParam(':small_image1', $product['small_image']);
	$stmt->bindParam(':medium_image1', $product['medium_image']);
	$stmt->bindParam(':large_image1', $product['large_image']);

	$stmt->bindParam(':name2', $product['name']);
	$stmt->bindParam(':brief_description2', $product['brief_description']);
	$stmt->bindParam(':product_code2', $product['product_code']);
	$stmt->bindParam(':warranty2', $product['warranty']);
	$stmt->bindParam(':is_archive2', $product['is_archive']);
	$stmt->bindParam(':vendorID2', $product['vendorID']);
	$stmt->bindParam(':articul2', $product['articul']);
	$stmt->bindParam(':volume2', $product['volume']);
	$stmt->bindParam(':categoryID2', $product['categoryID']);
	$stmt->bindParam(':is_new2', $product['is_new']);
	$stmt->bindParam(':price2', $product['price']);
	$stmt->bindParam(':price_uah2', $product['price_uah']);
	$stmt->bindParam(':small_image2', $product['small_image']);
	$stmt->bindParam(':medium_image2', $product['medium_image']);
	$stmt->bindParam(':large_image2', $product['large_image']);

	$stmt->execute();
}

function update_details_product($product) {
	global $db;

	$sql = "UPDATE `product` SET `description` = :description, `recommendable_price` = :recommendable_price, `options` = :options, `date_added` = :date_added, `date_modified` = :date_modified WHERE `productID` = :productID";

	$stmt = $db->prepare($sql);

	$stmt->bindParam(':productID', $product['productID']);

	$stmt->bindParam(':description', $product['description']);
	$stmt->bindParam(':recommendable_price', $product['recommendable_price']);
	$stmt->bindParam(':date_added', $product['date_added']);
	$stmt->bindParam(':date_modified', $product['date_modified']);

	$options = serialize($product['options']);
	$stmt->bindParam(':options', $options);

	$stmt->execute();
}

function update_delivery_time_product($productID, $product_delivery_time) {
	global $db;

	$sql = "UPDATE `product` SET `delivery_time` = :delivery_time WHERE `productID` = :productID";

	$stmt = $db->prepare($sql);

	$stmt->bindParam(':productID', $productID);

	$delivery_time = serialize($product_delivery_time);
	$stmt->bindParam(':delivery_time', $delivery_time);

	$stmt->execute();
}

function update_product_category($productID, $categoryID) {
	global $db;

	$sql = "INSERT IGNORE INTO `product_category` ".
		   "(`productID`, `categoryID`) VALUES (:productID, :categoryID)";

	$stmt = $db->prepare($sql);

	$stmt->bindParam(':productID', $productID);
	$stmt->bindParam(':categoryID', $categoryID);

	$stmt->execute();
}

function update_vendor_category($vendorID, $categoryID) {
	global $db;

	$sql = "INSERT IGNORE INTO `vendor_category` ".
		   "(`vendorID`, `categoryID`) VALUES (:vendorID, :categoryID)";

	$stmt = $db->prepare($sql);

	$stmt->bindParam(':vendorID', $vendorID);
	$stmt->bindParam(':categoryID', $categoryID);

	$stmt->execute();
}

function update_vendor($vendor) {
	global $db;

	$sql = "INSERT INTO `vendor` ".
		   "(`vendorID`, `name`) VALUES (:vendorID, :name1) ".
		   "ON DUPLICATE KEY UPDATE `name` = :name2";

	$stmt = $db->prepare($sql);

	$stmt->bindParam(':vendorID', $vendor['vendorID']);

	$stmt->bindParam(':name1', $vendor['name']);
	$stmt->bindParam(':name2', $vendor['name']);

	$stmt->execute();
}

function update_target($target) {
	global $db;

	$sql = "INSERT INTO `target` ".
		   "(`targetID`, `name`, `type`, `region`) VALUES (:targetID, :name1, :type1, :region1) ".
		   "ON DUPLICATE KEY UPDATE `name` = :name2, `type` = :type2, `region` = :region2";

	$stmt = $db->prepare($sql);

	$stmt->bindParam(':targetID', $target['targetID']);

	$stmt->bindParam(':name1', $target['name']);
	$stmt->bindParam(':type1', $target['type']);
	$stmt->bindParam(':region1', $target['region']);

	$stmt->bindParam(':name2', $target['name']);
	$stmt->bindParam(':type2', $target['type']);
	$stmt->bindParam(':region2', $target['region']);

	$stmt->execute();
}

function clearDB() {
	global $db;

	$sql = "TRUNCATE TABLE `category`";
	$stmt = $db->prepare($sql);
	$stmt->execute();

	$sql = "TRUNCATE TABLE `product`";
	$stmt = $db->prepare($sql);
	$stmt->execute();

	$sql = "TRUNCATE TABLE `product_category`";
	$stmt = $db->prepare($sql);
	$stmt->execute();

	$sql = "TRUNCATE TABLE `vendor`";
	$stmt = $db->prepare($sql);
	$stmt->execute();

	$sql = "TRUNCATE TABLE `vendor_category`";
	$stmt = $db->prepare($sql);
	$stmt->execute();
}

// API
function get_targets($SID) {
	global $conf;

	$data = get($conf['api_url']['targets'].$SID);
	if($data) {
		$arr = (array)$data;
		echo "GET targets: ok (count: ".count($arr).")\n";
		return $arr;
	} else {
		die("GET targets: no data\n");
	}
}

function get_vendors($SID) {
	global $conf;

	$data = get($conf['api_url']['vendors'].$SID);
	if($data) {
		$arr = (array)$data;
		echo "GET vendors: ok (count: ".count($arr).")\n";
		return $arr;
	} else {
		die("GET vendors: no data\n");
	}
}

function get_categories($SID) {
	global $conf;

	$data = get($conf['api_url']['categories'].$SID);
	if($data) {
		$arr = (array)$data;
		echo "GET categories: ok (count: ".count($arr).")\n";
		return $arr;
	} else {
		die("GET categories: no data\n");
	}
}

function get_products($SID, $category_id, $offset = 0, $limit = 1) {
	global $conf;

	$params = array();
	$get_params = "";
	if($offset) {
		$params['offset'] = "offset=".$offset;
	}
	if($limit) {
		$params['limit'] = "limit=".$limit;
	}
	if($params) {
		$get_params = implode($params, "&");
		$get_params = "?".$get_params;
	}

	$data = get($conf['api_url']['products'].$category_id.'/'.$SID.$get_params);
	if($data) {
		$arr = (array)$data;
		return $arr;
	} else {
		return false;
	}
}

function get_product($SID, $product_id) {
	global $conf;

	$data = get($conf['api_url']['product'].$product_id.'/'.$SID);
	if($data) {
		$arr = (array)$data;
		return $arr;
	} else {
		return false;
	}
}

function get_product_delivery_time($SID, $product_id, $stocks) {
	global $conf;

	$data = get($conf['api_url']['delivery_time'].$product_id.'/'.implode(',', $stocks).'/'.$SID);
	if($data) {
		$arr = (array)$data;
		return $arr;
	} else {
		return false;
	}
}

function get($url, $params = array(), $method = 'GET') {
	global $conf;
	global $SID;

	$k = 0;
	while($k <= 4) {
		$k++;
		$stdData = json_decode(
			@file_get_contents(
				$url, false, stream_context_create(
					array(
						'http' => array(
							'method'  => $method,
							'header'  => 'Content-type: application/x-www-form-urlencoded',
							'content' => http_build_query($params)
						)
					)
				)
			)
		);

		if($stdData) {
			if($stdData->status && $stdData->result) {
				return $stdData->result;
			} else {
				echo("GET ERROR: ".$stdData->error_message."\n");
				if(!$params) {
					$DIE_SID = $SID;
					$SID = api_auth();
					$url = str_replace($DIE_SID, $SID, $url);
				}
			}
		} else {
			echo("TRY...\n");
			if(!$params) {
				$DIE_SID = $SID;
				$SID = api_auth();
				$url = str_replace($DIE_SID, $SID, $url);
			}
		}
	}
	return false;
}

function api_auth() {
	global $conf;

	$data = get($conf['api_url']['auth'], $conf['api'], 'POST');
	if($data) {
		echo "API auth: ok (SID: ".$data.")\n";
		return $data;
	} else {
		die("API auth: fail\n");
	}
}

function api_logout($SID) {
	global $conf;

	$data = get($conf['api_url']['logout'].$SID, array(), 'POST');
	if($data) {
		echo "API logout: ok\n";
		return $data;
	} else {
		die("API logout: fail\n");
	}
}

// UTILS
function microtime_float() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

function render_time() {
    global $_TIME_START;
    $_TIME_END = microtime_float();
    return $_TIME_END-$_TIME_START;
}