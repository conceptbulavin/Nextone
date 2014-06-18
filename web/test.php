<?php

header('Content-Type: text/html; charset=utf-8');

include_once dirname(__FILE__).'/conf.php';
include_once dirname(__FILE__).'/funcs.php';

$db = db_connect();

$tbl_category = "category";
$tbl_product_category = "product_category";
$tbl_product = "product";

$sql = "SELECT `categoryID` AS `id`, `name` AS `title`, `parentID` AS `parent_id` FROM `".$tbl_category."`";
$stmt = $db->prepare($sql);
$stmt->execute();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$data[$row['id']] = $row;
}

function view_cat ($dataset) {
	foreach ($dataset as $menu) {
		if(isset($menu["id"])) {
			echo '<li style="list-style-type: none;"><a href="?id='.$menu["id"].'">'.$menu["title"].'</a>';
			if(isset($menu['childs']) && $menu['childs']) {
				echo '<ul class="submenu">';
				view_cat($menu['childs']);
				echo '</ul>';
			}
			echo '</li>';
		}
	}
}

function mapTree($dataset) {
	$tree = array();
	foreach ($dataset as $id=>&$node) {
		if(isset($node['parent_id'])) {
			if ($node['parent_id'] < 2) {
				$tree[$id] = &$node;
			} else {
				$dataset[$node['parent_id']]['childs'][$id] = &$node;
			}
		}
	}
	return $tree;
}

$data = mapTree($data);
?>

<table width="100%" style="font-family: tahoma;">
	<tr>
		<td width="25%" valign="top" style="padding:5px;">
			<?php view_cat($data);?>
		</td>
		<td width="75%" valign="top" style="padding:5px;">
			<?php
			$id = $_GET['id'];
			if($id > 0) {

				$sql = "SELECT `name` FROM `".$tbl_category."` WHERE `categoryID` = :id";
				$stmt = $db->prepare($sql);
				$stmt->bindParam(':id', $id);
				$stmt->execute();
				$category = $stmt->fetch(PDO::FETCH_ASSOC);

				$sql = "SELECT COUNT(*) FROM `".$tbl_product_category."` AS `pc` ".
					   "LEFT JOIN `".$tbl_product."` AS `p` ON(`p`.`productID` = `pc`.`productID`) ".
					   "WHERE `pc`.`categoryID` = :id";
				$stmt = $db->prepare($sql);
				$stmt->bindParam(':id', $id);
				$stmt->execute();
				$count = $stmt->fetch(PDO::FETCH_ASSOC);

				echo '<h1>'.$category['name'].' ('.$count['COUNT(*)'].')</h1>';

				$sql = "SELECT `p`.* FROM `".$tbl_product_category."` AS `pc` ".
					   "LEFT JOIN `".$tbl_product."` AS `p` ON(`p`.`productID` = `pc`.`productID`) ".
					   "WHERE `pc`.`categoryID` = :id LIMIT 10";
				$stmt = $db->prepare($sql);
				$stmt->bindParam(':id', $id);
				$stmt->execute();

				$k = 0;
				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					$k++;
					echo '<b>Num:</b> '.$k;

					if($row['medium_image']) {
						echo "<div><image src=".$row['medium_image']." /></div>";
					}

					echo '<b>productID:</b> '.$row['productID'].'<br>';

					echo '<b>name:</b> '.$row['name'].'<br />';
					echo '<b>brief_description:</b> '.$row['brief_description'].'<br />';
					echo '<b>product_code:</b> '.$row['product_code'].'<br />';
					echo '<b>warranty:</b> '.$row['warranty'].'<br />';
					echo '<b>is_archive:</b> '.$row['is_archive'].'<br />';
					echo '<b>vendorID:</b> '.$row['vendorID'].'<br />';
					echo '<b>articul:</b> '.$row['articul'].'<br />';
					echo '<b>volume:</b> '.$row['volume'].'<br />';
					echo '<b>categoryID:</b> '.$row['categoryID'].'<br />';
					echo '<b>is_new:</b> '.$row['is_new'].'<br />';
					echo '<b>price:</b> '.$row['price'].'<br />';
					echo '<b>price_uah:</b> '.$row['price_uah'].'<br />';
					echo '<b>small_image:</b> '.$row['small_image'].'<br />';
					echo '<b>medium_image:</b> '.$row['medium_image'].'<br />';
					echo '<b>large_image:</b> '.$row['large_image'].'<br />';

					echo '<b>description:</b> '.$row['description'].'<br />';
					echo '<b>recommendable_price:</b> '.$row['recommendable_price'].'<br />';
					echo '<b>options:</b> '.$row['options'].'<br />';
					echo '<b>date_added:</b> '.$row['date_added'].'<br />';
					echo '<b>date_modified:</b> '.$row['date_modified'].'<br />';

					echo '<b>date_modified:</b> '.$row['date_modified'].'<hr />';
				}
			}
			?>
		</td>
	</tr>
</table>