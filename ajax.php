<?php
require_once('/app/Mage.php'); // ABSOLUTH PATH TO MAGE
umask(0);
Mage::app ();

Mage::getSingleton('core/session', array('name'=>'frontend'));   // GET THE SESSION
$simbol= Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->getSymbol();  // GET THE CURRENCY SIMBOL
$store=Mage::app()->getStore()->getCode();
$cart = Mage::getSingleton('checkout/cart'); //->getItemsCount();





$ajtem=$_POST['item'];    // THIS IS THE ITEM ID
$items = $cart->getItems();
foreach ($items as $item) {   // LOOP
if($item->getId()==$ajtem){  // IS THIS THE ITEM WE ARE CHANGING? IF IT IS:
$item->setQty($_POST['qty']); // UPDATE ONLY THE QTY, NOTHING ELSE!
$cart->save();  // SAVE
Mage::getSingleton('checkout/session')->setCartWasUpdated(true);
echo '<span class="price">';
       echo Mage::helper('core')->formatPrice(number_format($item->getPriceInclTax() * $_POST['qty'],2), true);
       echo '</span>';
break;
}

}




// THE REST IS updatTotalG FUNCTION WHICH IS CALLED AFTER AJAX IS COMPLETED
// (UPDATE THE TOTALS)
echo '<script type="text/javascript">';
    echo 'function updateTotalG(){';
    echo 'jQuery("#shopping-cart-totals-table .price").html(\'';
    echo '<strong><span class="price">';
    echo Mage::helper('core')->formatPrice(number_format(Mage::getSingleton('checkout/session')->getQuote()->getGrandTotal(), 2), true);
    echo " </span></strong>');";
    echo '}   </script>';

?>