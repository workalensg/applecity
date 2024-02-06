<?php
ini_set("display_errors","1");
ini_set("display_startup_errors","1");
ini_set('error_reporting', E_ALL);

set_time_limit(0);

require_once('config.php');   


require_once(DIR_SYSTEM . 'library/db.php');

if (file_exists(DIR_SYSTEM . 'database/mysql.php')) {
    require_once(DIR_SYSTEM . 'database/mysql.php');
}
elseif (file_exists(DIR_SYSTEM . 'library/db/mysqli.php')) {
    require_once(DIR_SYSTEM . 'library/db/mysqli.php');
}


$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);


function getColumnNamesWithMeta($tbl_name) {
    $mysqli = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }

    $names = array();

    $sql = "SELECT * FROM " . DB_PREFIX . $tbl_name. " WHERE 1 = 0";
    //echo $sql;
    if ($result = $mysqli->query($sql)) {
        while ($field = $result->fetch_field()) {
            //print_r($field);
            $names[] = $field->name; 
        }

        $result->close();
    }

    return ($names);
}


/*$arr = getColumnNamesWithMeta('relatedoptions');
print_r($arr);
die;*/


$arr = getColumnNamesWithMeta('product');

if (!in_array('base_price', $arr)) {
    $db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD COLUMN `base_price` decimal(15,4) NOT NULL;");
}


if (!in_array('base_cost', $arr)) {
    $db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD COLUMN `base_cost` decimal(15,4) NOT NULL;");
}


if (!in_array('base_currency_code', $arr)) {
    $sql = "SELECT * FROM " . DB_PREFIX . "setting WHERE `key` = 'config_currency' limit 1";
    $query = $db->query($sql);

    if ($query->num_rows) {
        $fields = $query->row;
        //print_r($fields);
        $config_currency = $fields['value'];
    }
    else {
        $config_currency = 'USD';
    }
    //echo $config_currency;

    $db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD COLUMN `base_currency_code` varchar(3) NOT NULL DEFAULT '" . $config_currency . "' AFTER `base_price`;");
}


$query = $db->query("SHOW INDEX FROM " . DB_PREFIX . "product WHERE Key_name='base_currency_code' and Column_name='base_currency_code'");
if (count($query->rows) == 0) {
    $db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD INDEX(`base_currency_code`); ");
}

$query = $db->query("SHOW INDEX FROM " . DB_PREFIX . "product WHERE Key_name='base_price' and Column_name='base_price'");
if (count($query->rows) == 0) {
    $db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD INDEX(`base_price`); ");
}

$query = $db->query("SHOW INDEX FROM " . DB_PREFIX . "product WHERE Key_name='price' and Column_name='price'");
if (count($query->rows) == 0) {
    $db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD INDEX(`price`); ");
}



if (!in_array('cost', $arr)) {
    $db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD `cost` decimal(15,4) NOT NULL DEFAULT '0.0000' AFTER `price`;");
}

if (!in_array('extra_charge', $arr)) {
    $db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD COLUMN `extra_charge` decimal(15,4) NOT NULL DEFAULT 0 AFTER `price`;");
}
else {
    $db->query("ALTER TABLE `" . DB_PREFIX . "product` MODIFY COLUMN `extra_charge` decimal(15,4) NOT NULL DEFAULT 0 AFTER `price`;");
}



$arr = getColumnNamesWithMeta('currency');

if (!in_array('value_official', $arr)) {
    $db->query("ALTER TABLE `" . DB_PREFIX . "currency` ADD COLUMN `value_official` float(15,8) NULL;");
}

if (!in_array('nominal', $arr)) {
    $db->query("ALTER TABLE `" . DB_PREFIX . "currency` ADD COLUMN `nominal` smallint NULL;");
}

if (!in_array('round', $arr)) {
    $db->query("ALTER TABLE `" . DB_PREFIX . "currency` ADD COLUMN `round` varchar(30) NOT NULL DEFAULT '';");
}

if (!in_array('round_type', $arr)) {
    $db->query("ALTER TABLE `" . DB_PREFIX . "currency` ADD COLUMN `round_type` varchar(30) NOT NULL DEFAULT '';");
}



$arr = getColumnNamesWithMeta('product_option_value');
if (!in_array('base_price', $arr)) {
    $db->query("ALTER TABLE `" . DB_PREFIX . "product_option_value` ADD COLUMN `base_price` decimal(15,4) NOT NULL AFTER `price`;");
}

if (!in_array('cost', $arr)) {
    $db->query("ALTER TABLE `" . DB_PREFIX . "product_option_value` ADD `cost` decimal(15,4) NOT NULL DEFAULT '0.0000' AFTER `price`;");
}

if (!in_array('extra_charge', $arr)) {
    $db->query("ALTER TABLE `" . DB_PREFIX . "product_option_value` ADD COLUMN `extra_charge` decimal(15,4) NOT NULL DEFAULT 0 AFTER `price`;");
}



$query = $db->query("SHOW INDEX FROM " . DB_PREFIX . "product_option_value WHERE Key_name='price' and Column_name='price'");
if (count($query->rows) == 0) {
    $db->query("ALTER TABLE `" . DB_PREFIX . "product_option_value` ADD INDEX(`price`); ");
}

$query = $db->query("SHOW INDEX FROM " . DB_PREFIX . "product_option_value WHERE Key_name='base_price' and Column_name='base_price'");
if (count($query->rows) == 0) {
    $db->query("ALTER TABLE `" . DB_PREFIX . "product_option_value` ADD INDEX(`base_price`); ");
}

$query = $db->query("SHOW INDEX FROM " . DB_PREFIX . "product_option_value WHERE Key_name='product_id' and Column_name='product_id'");
if (count($query->rows) == 0) {
    $db->query("ALTER TABLE `" . DB_PREFIX . "product_option_value` ADD INDEX(`product_id`); ");
}

$query = $db->query("SHOW INDEX FROM " . DB_PREFIX . "product_option_value WHERE Key_name='product_option_id' and Column_name='product_option_id'");
if (count($query->rows) == 0) {
    $db->query("ALTER TABLE `" . DB_PREFIX . "product_option_value` ADD INDEX(`product_option_id`); ");
}

$query = $db->query("SHOW INDEX FROM " . DB_PREFIX . "product_option_value WHERE Key_name='option_id' and Column_name='option_id'");
if (count($query->rows) == 0) {
    $db->query("ALTER TABLE `" . DB_PREFIX . "product_option_value` ADD INDEX(`option_id`); ");
}

$query = $db->query("SHOW INDEX FROM " . DB_PREFIX . "product_option_value WHERE Key_name='option_value_id' and Column_name='option_value_id'");
if (count($query->rows) == 0) {
    $db->query("ALTER TABLE `" . DB_PREFIX . "product_option_value` ADD INDEX(`option_value_id`); ");
}



$arr = getColumnNamesWithMeta('product_discount');

if (!in_array('base_price', $arr)) {
    $db->query("ALTER TABLE `" . DB_PREFIX . "product_discount` ADD COLUMN `base_price` decimal(15,4) NOT NULL AFTER `price`;");
}

if (!in_array('extra_charge', $arr)) {
    $db->query("ALTER TABLE `" . DB_PREFIX . "product_discount` ADD COLUMN `extra_charge` decimal(15,4) NOT NULL DEFAULT 0 AFTER `price`;");
}


$query = $db->query("SHOW INDEX FROM " . DB_PREFIX . "product_discount WHERE Key_name='price' and Column_name='price'");
if (count($query->rows) == 0) {
    $db->query("ALTER TABLE `" . DB_PREFIX . "product_discount` ADD INDEX(`price`); ");
}

$query = $db->query("SHOW INDEX FROM " . DB_PREFIX . "product_discount WHERE Key_name='base_price' and Column_name='base_price'");
if (count($query->rows) == 0) {
    $db->query("ALTER TABLE `" . DB_PREFIX . "product_discount` ADD INDEX(`base_price`); ");
}

$query = $db->query("SHOW INDEX FROM " . DB_PREFIX . "product_discount WHERE Key_name='customer_group_id' and Column_name='customer_group_id'");
if (count($query->rows) == 0) {
    $db->query("ALTER TABLE `" . DB_PREFIX . "product_discount` ADD INDEX(`customer_group_id`); ");
}


$arr = getColumnNamesWithMeta('product_special');

if (!in_array('base_price', $arr)) {
    $db->query("ALTER TABLE `" . DB_PREFIX . "product_special` ADD COLUMN `base_price` decimal(15,4) NOT NULL AFTER `price`;");
}

if (!in_array('extra_charge', $arr)) {
    $db->query("ALTER TABLE `" . DB_PREFIX . "product_special` ADD COLUMN `extra_charge` decimal(15,4) NOT NULL DEFAULT 0 AFTER `price`;");
}

$query = $db->query("SHOW INDEX FROM " . DB_PREFIX . "product_special WHERE Key_name='price' and Column_name='price'");
if (count($query->rows) == 0) {
    $db->query("ALTER TABLE `" . DB_PREFIX . "product_special` ADD INDEX(`price`); ");
}

$query = $db->query("SHOW INDEX FROM " . DB_PREFIX . "product_special WHERE Key_name='base_price' and Column_name='base_price'");
if (count($query->rows) == 0) {
    $db->query("ALTER TABLE `" . DB_PREFIX . "product_special` ADD INDEX(`base_price`); ");
}

$query = $db->query("SHOW INDEX FROM " . DB_PREFIX . "product_special WHERE Key_name='customer_group_id' and Column_name='customer_group_id'");
if (count($query->rows) == 0) {
    $db->query("ALTER TABLE `" . DB_PREFIX . "product_special` ADD INDEX(`customer_group_id`); ");
}



$arr = getColumnNamesWithMeta('order_product');

if (!in_array('cost', $arr)) {
    $db->query("ALTER TABLE `" . DB_PREFIX . "order_product` ADD `cost` decimal(15,4) NOT NULL DEFAULT '0.0000' AFTER `price`;");
}



$arr = getColumnNamesWithMeta('order_option');

if (!in_array('cost', $arr)) {
    $db->query("ALTER TABLE `" . DB_PREFIX . "order_option` ADD `cost` decimal(15,4) NOT NULL DEFAULT '0.0000';");
}



$arr = getColumnNamesWithMeta('currency');

if (!in_array('correction_prefix', $arr)) {
    $db->query("ALTER TABLE `" . DB_PREFIX . "currency` ADD `correction_prefix` varchar(1) NOT NULL DEFAULT '+';");
}

if (!in_array('correction', $arr)) {
    $db->query("ALTER TABLE `" . DB_PREFIX . "currency` ADD `correction` float(15,8) NOT NULL DEFAULT '0.0000';");
}

if (!in_array('realcode', $arr)) {
    $db->query("ALTER TABLE `" . DB_PREFIX . "currency` ADD `realcode` varchar(3) NOT NULL DEFAULT 'USD';");
}

if (!in_array('auto_update_course', $arr)) {
    $db->query("ALTER TABLE `" . DB_PREFIX . "currency` ADD `auto_update_course` int(1) NOT NULL DEFAULT 1;");
}

if (!in_array('round', $arr)) {
    $db->query("ALTER TABLE `" . DB_PREFIX . "currency` ADD `round` varchar(20) NULL;");
}

if (!in_array('round_type', $arr)) {
    $db->query("ALTER TABLE `" . DB_PREFIX . "currency` ADD `round_type` varchar(20) NULL;");
}

$query = $db->query("SHOW INDEX FROM " . DB_PREFIX . "currency WHERE Key_name='code' and Column_name='code'");
if (count($query->rows) == 0) {
    $db->query("ALTER TABLE `" . DB_PREFIX . "currency` ADD UNIQUE INDEX `code` (`code`) ;");
}


if (file_exists(str_replace("system", "vqmod/xml", DIR_SYSTEM) . 'related_options.xml') or file_exists(DIR_SYSTEM . 'related_options.vqmod.xml') 
) {
    $arr = getColumnNamesWithMeta('relatedoptions');

    if (!in_array('base_price', $arr)) {
        $db->query("ALTER TABLE `" . DB_PREFIX . "relatedoptions` ADD COLUMN `base_price` decimal(15,4) NOT NULL;");
    }

    $arr = getColumnNamesWithMeta('relatedoptions_discount');

    if (!in_array('base_price', $arr)) {
        $db->query("ALTER TABLE `" . DB_PREFIX . "relatedoptions_discount` ADD COLUMN `base_price` decimal(15,4) NOT NULL;");
    }
}

?>