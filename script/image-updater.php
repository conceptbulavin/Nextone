<?php
require dirname(__FILE__) . '/../app/Mage.php';

$currentStore = Mage::app()->getStore()->getId();
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$start = time();

echo '<b>Started at: '.date('h:i:s, D d M, Y').'</b><br/>';

$imgUpdater = new Temashop_Imageupdater_Model_Updater();

try {
    if (!isset($_GET['sku'])) {
        $productIds = $imgUpdater->getProductCollection($_GET['limit'], $_GET['offset']);
        $productCount = count($productIds);
        if ($productIds){
            echo 'Products found in the store : <b>' . $productCount . '</b>.<br />';
            $result = $imgUpdater->processByProductIds($productIds);
            print_r(new Varien_Object($result));
        } else {
            echo 'No products found for "'.$manufacturer.'" manufacturer.<br/>';
        }
    } else {
        $sku = trim($_GET['sku']);
        $product = $imgUpdater->getProductBySku($sku);
        $imgUpdater->processProductModel($product);
    }
} catch (Exception $e) {
    echo "Exception:<br> ".$e->getMessage().'<br>';
}
Mage::app()->setCurrentStore($currentStore);
$end = time();
echo '<b>Ended at: '.date('h:i:s, D d M, Y').'. Duration: '.$imgUpdater->secondsToTime($end - $start).'</b><br/>';


