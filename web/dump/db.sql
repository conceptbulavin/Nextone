CREATE TABLE IF NOT EXISTS `category` (
  `categoryID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(256) DEFAULT NULL,
  `parentID` int(11) unsigned NOT NULL,
  PRIMARY KEY (`categoryID`)
);

CREATE TABLE IF NOT EXISTS `product` (
  `productID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `brief_description` text NOT NULL,
  `product_code` char(64) NOT NULL,
  `warranty` int(11) NOT NULL,
  `is_archive` int(1) NOT NULL,
  `vendorID` int(11) NOT NULL,
  `articul` varchar(256) NOT NULL,
  `volume` int(1) NOT NULL,
  `categoryID` int(11) NOT NULL,
  `is_new` int(1) NOT NULL,
  `price` decimal(20,2) NOT NULL,
  `price_uah` decimal(20,2) NOT NULL,
  `small_image` text NOT NULL,
  `medium_image` text NOT NULL,
  `large_image` text NOT NULL,
  `description` text NOT NULL,
  `recommendable_price` decimal(20,2) NOT NULL,
  `options` text NOT NULL,
  `delivery_time` text NOT NULL,
  `date_added` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  PRIMARY KEY (`productID`)
);

CREATE TABLE IF NOT EXISTS `product_category` (
  `product_category_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `productID` int(11) NOT NULL,
  `categoryID` int(11) NOT NULL,
  PRIMARY KEY (`product_category_id`),
  UNIQUE KEY `productID` (`productID`,`categoryID`)
);

CREATE TABLE IF NOT EXISTS `vendor` (
  `vendorID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  PRIMARY KEY (`vendorID`)
);

CREATE TABLE IF NOT EXISTS `vendor_category` (
  `vendor_category_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `vendorID` int(11) NOT NULL,
  `categoryID` int(11) NOT NULL,
  PRIMARY KEY (`vendor_category_id`),
  UNIQUE KEY `vendorID` (`vendorID`,`categoryID`)
);

CREATE TABLE IF NOT EXISTS `target` (
  `targetID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` text,
  `type` text,
  `region` text,
  PRIMARY KEY (`targetID`)
);