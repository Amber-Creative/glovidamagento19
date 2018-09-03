<?php
include 'app/Mage.php';
Mage::app();
$_productID = $_GET['pid'];

Mage::getSingleton('core/session', array('name' => 'frontend'));
try {
    $product_id = $_productID; // Replace id with your product id
    $qty = '1'; // Replace qty with your qty
    $product = Mage::getModel('catalog/product')->load($product_id);
    $cart = Mage::getSingleton('checkout/cart');
    $cart->init();
    $cart->addProduct($product, array('qty' => $qty));
    $cart->save();
    Mage::getSingleton('checkout/session')->setCartWasUpdated(true);
    Mage::getSingleton('core/session')->addSuccess('Product added successfully');
	header('Location: ' . '/checkout/cart/');
} catch (Exception $e) {
    echo $e->getMessage();
}
?>

 


