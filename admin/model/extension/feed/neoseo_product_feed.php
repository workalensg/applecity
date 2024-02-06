<?php /* --== O_o ==-- */

require_once(DIR_SYSTEM . "/engine/neoseo_model.php");

class ModelExtensionFeedNeoSeoProductFeed extends NeoSeoModel
{

	public function __construct($registry)
	{
		parent::__construct($registry);
		$this->_moduleSysName = 'neoseo_product_feed';
		/* Remove _module_code */
		$this->_logFile = $this->_moduleSysName() . '.log';
		$this->debug = $this->config->get($this->_moduleSysName() . '_debug') == 1;

		$this->params = array(
			'module_key' => '',
			'status' => 1,
			'debug' => 0,
			'type' => 0,
			'check_encode' => 1,
		);

		$this->options_levels = array(
			'module_key' => 0,
			'status' => 0,
			'debug' => 0,
			'type' => 1,
			'check_encode' => 1,
		);

		/* ZzZzzz... */
	}

	// Install/Uninstall
	public function install()
	{
		if (! true /* RePlaced */) {
			return "";
		}

		// Значения параметров по умолчанию
		$this->initParams($this->params);

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "product_feed` ("
				. "`product_feed_id` int(11) NOT NULL AUTO_INCREMENT, "
				. "`language_id` int(1) NOT NULL, "
				. "`feed_name` varchar(128) NOT NULL, "
				. "`feed_shortname` varchar(128) NOT NULL, "
				. "`status` int(1) DEFAULT 0, "
				. "`id_format` int(11), "
				. "`use_main_category` int(1) DEFAULT 0, "
				. "`use_categories` int(11) DEFAULT 0, "
				. "`replace_break` int(1) DEFAULT 0, "
				. "`not_unload` text NOT NULL, "
				. "`categories` text NOT NULL, "
				. "`manufacturers` text NOT NULL, "
				. "`option_status` int(1) DEFAULT 0, "
				. "`currency` varchar(3) DEFAULT 'UAH', "
				. "`image_width` int(5) DEFAULT 600, "
				. "`image_height` int(5) DEFAULT 600, "
				. "`warranty` varchar(50) DEFAULT '12', "
				. "`ip_list` varchar(128) NOT NULL, "
				. "`data_file` varchar(128) NOT NULL, "
				. "`sql_code` text NOT NULL, "
				. "`sql_code_before` text NOT NULL, "
				. "`use_original_images` int(1) DEFAULT 0, "
				. "`product_markup` decimal(10,2) DEFAULT 0, "
				. "`product_markup_option` decimal(10,2) DEFAULT 0, "
				. "`product_markup_type` int(1) DEFAULT 0, "
				. "`image_pass` int(1) DEFAULT 0, "
				. "`warehouses` text NOT NULL, "
				. "`use_warehouse_quantity` int(1) DEFAULT 0, "
				. "`products` text NOT NULL, "
				. "`store_id` int(2) NOT NULL, "
				. "`exclude_empty_product` int(1) DEFAULT 0, "
				. "PRIMARY KEY (`product_feed_id`) )  CHARACTER SET utf8 COLLATE utf8_general_ci;");

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "product_feed_to_store` ("
				. "`product_feed_id` INT NOT NULL, "
				. "`store_id` INT NOT NULL"
				. ") DEFAULT CHARSET=utf8;");


		$this->load->model('catalog/category');
		$categories = $this->model_catalog_category->getCategories(0);

		$ids_categories = array();
		foreach ($categories as $category) {
			$ids_categories[] = $category["category_id"];
		}

		$this->load->model('catalog/manufacturer');
		$manufacturers = $this->model_catalog_manufacturer->getManufacturers(0);

		$ids_manufacturers = array();
		foreach ($manufacturers as $manufacturer) {
			$ids_manufacturers[] = $manufacturer["manufacturer_id"];
		}

		$sql[] = "INSERT INTO `" . DB_PREFIX . "product_feed` (`language_id`, `feed_name`, `feed_shortname`, `status`, `id_format`, `use_main_category`, `use_warehouse_quantity`, `warehouses`, `use_categories`, `replace_break`, `not_unload`, `categories`,`manufacturers`,  `currency`,  `image_width`,`image_height`, `warranty`, `ip_list`, `sql_code`, `sql_code_before`, `use_original_images`, `products`, `store_id`, `exclude_empty_product`) VALUES (" . (int) $this->config->get('config_language_id') . ", 'MARKET.YANDEX.RU', 'yandex_market', '1', '1','0', '0' , '', '0' , '0', '' , '" . join(",", $ids_categories) . "', '" . join(",", $ids_manufacturers) . "', '',   '600', '600', '12', '', 'p.quantity > 0', '', '0', '', '0', '0');";

		$sql[] = "INSERT INTO `" . DB_PREFIX . "product_feed` (`language_id`, `feed_name`, `feed_shortname`, `status`, `id_format`, `use_main_category`, `use_warehouse_quantity`, `warehouses`, `use_categories`, `replace_break`, `not_unload`, `categories`,`manufacturers`,  `currency`, `image_width`,	`image_height`, `warranty`, `ip_list`, `sql_code`, `sql_code_before`, `use_original_images`, `products`, `store_id`, `exclude_empty_product`) VALUES (" . (int) $this->config->get('config_language_id') . ", 'PROM.UA', 'prom_ua', '0', '3','0', '0' , '', '0' , '0', '' , '" . join(",", $ids_categories) . "', '" . join(",", $ids_manufacturers) . "',  '',    '600', '600', '12', '', 'p.quantity > 0', '', '0', '', '0', '0');";

		$sql[] = "INSERT INTO `" . DB_PREFIX . "product_feed` (`language_id`, `feed_name`, `feed_shortname`, `status`, `id_format`, `use_main_category`, `use_warehouse_quantity`, `warehouses`, `use_categories`,`replace_break`, `not_unload`, `categories`,`manufacturers`, `currency`, `image_width`, `image_height`, `warranty`, `ip_list`, `sql_code`, `sql_code_before`, `use_original_images`, `products`, `store_id`, `exclude_empty_product`) VALUES (" . (int) $this->config->get('config_language_id') . ", 'AVA.UA', 'ava_ua', '0', '1','0', '0' , '', '0' , '0', '' , '" . join(",", $ids_categories) . "', '" . join(",", $ids_manufacturers) . "',  '',   '600', '600', '12', '', 'p.quantity > 0', '', '0', '', '0', '0');";

		$sql[] = "INSERT INTO `" . DB_PREFIX . "product_feed` (`language_id`, `feed_name`, `feed_shortname`, `status`, `id_format`, `use_main_category`, `use_warehouse_quantity`, `warehouses`, `use_categories`,`replace_break`, `not_unload`, `categories`,`manufacturers`,   `currency`,  `image_width`, `image_height`, `warranty`, `ip_list`, `sql_code`, `sql_code_before`, `use_original_images`, `products`, `store_id`, `exclude_empty_product`) VALUES (" . (int) $this->config->get('config_language_id') . ", 'HOTLINE.UA', 'hotline_ua', '0', '2','0', '0' , '', '0' , '0', '' , '" . join(",", $ids_categories) . "', '" . join(",", $ids_manufacturers) . "',  '',     '600', '600', '12', '', 'p.quantity > 0', '', '0', '', '0', '0');";

		$sql[] = "INSERT INTO `" . DB_PREFIX . "product_feed` (`language_id`, `feed_name`, `feed_shortname`, `status`, `id_format`, `use_main_category`, `use_warehouse_quantity`, `warehouses`, `use_categories`,`replace_break`, `not_unload`, `categories`,`manufacturers`, `currency`,  `image_width`, `image_height`, `warranty`, `ip_list`, `sql_code`, `sql_code_before`, `use_original_images`, `products`, `store_id`, `exclude_empty_product`) VALUES (" . (int) $this->config->get('config_language_id') . ", 'HOTPRICE.UA', 'hotprice_ua', '0', '1','0', '0' , '', '0' , '0', '' ,'" . join(",", $ids_categories) . "', '" . join(",", $ids_manufacturers) . "', '',    '600', '600', '12', '', 'p.quantity > 0', '', '0', '', '0', '0');";

		$sql[] = "INSERT INTO `" . DB_PREFIX . "product_feed` (`language_id`, `feed_name`, `feed_shortname`, `status`, `id_format`, `use_main_category`, `use_warehouse_quantity`, `warehouses`, `use_categories`,`replace_break`, `not_unload`, `categories`,`manufacturers`,  `currency`,  `image_width`, `image_height`, `warranty`, `ip_list`, `sql_code`, `sql_code_before`, `use_original_images`, `products`, `store_id`, `exclude_empty_product`) VALUES (" . (int) $this->config->get('config_language_id') . ", 'NADAVI.NET', 'nadavi_net', '0', '1','0', '0' , '', '0' ,'0', '' , '" . join(",", $ids_categories) . "', '" . join(",", $ids_manufacturers) . "', '',  '600', '600', '12', '', 'p.quantity > 0', '', '0', '', '0', '0');";

		$sql[] = "INSERT INTO `" . DB_PREFIX . "product_feed` (`language_id`, `feed_name`, `feed_shortname`, `status`, `id_format`, `use_main_category`, `use_warehouse_quantity`, `warehouses`, `use_categories`,`replace_break`, `not_unload`, `categories`,`manufacturers`, `currency`,  `image_width`, `image_height`, `warranty`, `ip_list`, `sql_code`, `sql_code_before`, `use_original_images`, `products`, `store_id`, `exclude_empty_product`) VALUES (" . (int) $this->config->get('config_language_id') . ", 'PRICE.UA', 'price_ua', '0', '1','0', '0' , '', '0' , '0', '' , '" . join(",", $ids_categories) . "', '" . join(",", $ids_manufacturers) . "', '',  '600', '600', '12', '', 'p.quantity > 0', '', '0', '', '0', '0');";

		$sql[] = "INSERT INTO `" . DB_PREFIX . "product_feed` (`language_id`, `feed_name`, `feed_shortname`, `status`, `id_format`, `use_main_category`, `use_warehouse_quantity`, `warehouses`, `use_categories`,`replace_break`, `not_unload`, `categories`,`manufacturers`, `currency`,  `image_width`,`image_height`, `warranty`, `ip_list`, `sql_code`, `sql_code_before`, `use_original_images`, `products`, `store_id`, `exclude_empty_product`) VALUES (" . (int) $this->config->get('config_language_id') . ",'TORG.MAIL.RU', 'torg_mail_ru', '0', '1','0', '0' , '', '0' , '0', '' , '" . join(",", $ids_categories) . "', '" . join(",", $ids_manufacturers) . "', '',    '600', '600', '12', '', 'p.quantity > 0', '', '0', '', '0', '0');";

		$sql[] = "INSERT INTO `" . DB_PREFIX . "product_feed` (`language_id`, `feed_name`, `feed_shortname`, `status`, `id_format`, `use_main_category`, `use_warehouse_quantity`, `warehouses`, `use_categories`,`replace_break`, `not_unload`, `categories`,`manufacturers`, `currency`,  `image_width`,`image_height`, `warranty`, `ip_list`, `sql_code`, `sql_code_before`, `use_original_images`, `products`, `store_id`, `exclude_empty_product`) VALUES (" . (int) $this->config->get('config_language_id') . ",'FACEBOOK.COM', 'facebook_com', '0', '6','0' , '0' , '', '0' , '0', '' ,'" . join(",", $ids_categories) . "', '" . join(",", $ids_manufacturers) . "', '',    '600', '600', '12', '', '', '', '0', '', '0', '0');";

		$sql[] = "INSERT INTO `" . DB_PREFIX . "product_feed` (`language_id`, `feed_name`, `feed_shortname`, `status`, `id_format`, `use_main_category`, `use_warehouse_quantity`, `warehouses`, `use_categories`,`replace_break`, `not_unload`, `categories`,`manufacturers`, `currency`,  `image_width`,`image_height`, `warranty`, `ip_list`, `sql_code`, `sql_code_before`, `use_original_images`, `products`, `store_id`, `exclude_empty_product`) VALUES (" . (int) $this->config->get('config_language_id') . ",'ROZETKA.COM.UA', 'rozetka', '0', '6','0' , '0' , '', '0' , '0', '' ,'" . join(",", $ids_categories) . "', '" . join(",", $ids_manufacturers) . "', '',    '600', '600', '12', '', '', '', '0', '', '0', '0');";

		$sql[] = "INSERT INTO `" . DB_PREFIX . "product_feed` (`language_id`, `feed_name`, `feed_shortname`, `status`, `id_format`, `use_main_category`, `use_warehouse_quantity`, `warehouses`, `use_categories`, `replace_break`, `not_unload`, `categories`,`manufacturers`,  `currency`,  `image_width`,`image_height`, `warranty`, `ip_list`, `sql_code`, `sql_code_before`, `use_original_images`, `products`, `store_id`, `exclude_empty_product`) VALUES (" . (int) $this->config->get('config_language_id') . ",'GOOGLE MERCHANT', 'google_merchant', '0', '4','0', '0' , '', '0' , '0', '' , '" . join(",", $ids_categories) . "', '" . join(",", $ids_manufacturers) . "', '',   '600', '600', '12', '', 'p.quantity > 0', '', '0', '', '0', '0');";

		$sql[] = "INSERT INTO `" . DB_PREFIX . "product_feed` (`language_id`, `feed_name`, `feed_shortname`, `status`, `id_format`, `use_main_category`, `use_warehouse_quantity`, `warehouses`, `use_categories`, `replace_break`, `not_unload`, `categories`,`manufacturers`,  `currency`,  `image_width`,`image_height`, `warranty`, `ip_list`, `sql_code`, `sql_code_before`, `use_original_images`, `products`, `store_id`, `exclude_empty_product`) VALUES (" . (int) $this->config->get('config_language_id') . ",'BESPLATKA.UA', 'besplatka_ua', '0', '7','0', '0' , '', '0' , '0', '' , '" . join(",", $ids_categories) . "', '" . join(",", $ids_manufacturers) . "', '',   '600', '600', '12', '', 'p.quantity > 0', '', '0', '', '0', '0');";

		foreach ($sql as $_sql) {
			$this->db->query($_sql);
		}

		// Недостающие права
		$this->addPermission($this->user->getGroupId(), 'access', 'catalog/' . $this->_moduleSysName());
		$this->addPermission($this->user->getGroupId(), 'modify', 'catalog/' . $this->_moduleSysName());

		return TRUE;
	}

	public function upgrade()
	{
		if (! true /* RePlaced */) {
			return "";
		}

		// Добавляем недостающие новые параметры
		$this->initParams($this->params);

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "product_feed_to_store` ("
				. "`product_feed_id` INT NOT NULL, "
				. "`store_id` INT NOT NULL"
				. ") DEFAULT CHARSET=utf8;");

		$sql = "SHOW COLUMNS FROM `" . DB_PREFIX . "product_feed` LIKE 'pictures_limit';";
		$query = $this->db->query($sql);
		if (!$query->num_rows) {
			$sql = "ALTER TABLE `" . DB_PREFIX . "product_feed` ADD COLUMN pictures_limit int DEFAULT 10;";
			$this->db->query($sql);
		}

		$sql = "SHOW COLUMNS FROM `" . DB_PREFIX . "product_feed` LIKE 'cat_names_separathor';";
		$query = $this->db->query($sql);
		if (!$query->num_rows) {
			$sql = "ALTER TABLE `" . DB_PREFIX . "product_feed` ADD COLUMN cat_names_separathor varchar(7) DEFAULT ' > ';";
			$this->db->query($sql);
		}

		$sql = "SHOW COLUMNS FROM `" . DB_PREFIX . "product_feed` LIKE 'product_markup';";
		$query = $this->db->query($sql);
		if (!$query->num_rows) {
			$sql = "ALTER TABLE `" . DB_PREFIX . "product_feed` ADD COLUMN product_markup decimal(10,2) DEFAULT 0;";
			$this->db->query($sql);
		}
		$sql = "SHOW COLUMNS FROM `" . DB_PREFIX . "product_feed` LIKE 'product_markup_option';";
		$query = $this->db->query($sql);
		if (!$query->num_rows) {
			$sql = "ALTER TABLE `" . DB_PREFIX . "product_feed` ADD COLUMN product_markup_option decimal(10,2) DEFAULT 0;";
			$this->db->query($sql);
		}
		$sql = "SHOW COLUMNS FROM `" . DB_PREFIX . "product_feed` LIKE 'product_markup_type';";
		$query = $this->db->query($sql);
		if (!$query->num_rows) {
			$sql = "ALTER TABLE `" . DB_PREFIX . "product_feed` ADD COLUMN product_markup_type int(1) DEFAULT 0;";
			$this->db->query($sql);
		}

		$sql = "SHOW COLUMNS FROM `" . DB_PREFIX . "product_feed` LIKE 'warranty';";
		$query = $this->db->query($sql);
		if ($query->num_rows) {
			$sql = "ALTER TABLE `" . DB_PREFIX . "product_feed` DROP COLUMN warranty;";
			$this->db->query($sql);
		}

		$sql = "SHOW COLUMNS FROM `" . DB_PREFIX . "product_feed` LIKE 'replace_break';";
		$query = $this->db->query($sql);
		if (!$query->num_rows) {
			$sql = "ALTER TABLE `" . DB_PREFIX . "product_feed` ADD COLUMN replace_break int DEFAULT 0;";
			$this->db->query($sql);
		}

		$sql = "SHOW COLUMNS FROM `" . DB_PREFIX . "product_feed` LIKE 'not_unload';";
		$query = $this->db->query($sql);
		if (!$query->num_rows) {
			$sql = "ALTER TABLE `" . DB_PREFIX . "product_feed` ADD COLUMN not_unload text NOT NULL;";
			$this->db->query($sql);
		}

		$sql = "SHOW COLUMNS FROM `" . DB_PREFIX . "product_feed` LIKE 'product_id';";
		$query = $this->db->query($sql);
		if (!$query->num_rows) {
			$sql = "ALTER TABLE `" . DB_PREFIX . "product_feed` ADD COLUMN product_id varchar(128) NOT NULL DEFAULT '';";
			$this->db->query($sql);
		}
		$sql = "SHOW COLUMNS FROM `" . DB_PREFIX . "product_feed` LIKE 'manufacturers';";
		$query = $this->db->query($sql);
		if (!$query->num_rows) {
			$sql = "ALTER TABLE `" . DB_PREFIX . "product_feed` ADD COLUMN manufacturers text NOT NULL;";
			$this->db->query($sql);
		}
		$sql = "SHOW COLUMNS FROM `" . DB_PREFIX . "product_feed` LIKE 'sql_code';";
		$query = $this->db->query($sql);
		if (!$query->num_rows) {
			$sql = "ALTER TABLE `" . DB_PREFIX . "product_feed` ADD COLUMN sql_code text NOT NULL;";
			$this->db->query($sql);
		}
		$sql = "SHOW COLUMNS FROM `" . DB_PREFIX . "product_feed` LIKE 'use_main_category';";
		$query = $this->db->query($sql);
		if (!$query->num_rows) {
			$sql = "ALTER TABLE `" . DB_PREFIX . "product_feed` ADD COLUMN use_main_category int(1) DEFAULT 0;";
			$this->db->query($sql);
		}
		$sql = "SHOW COLUMNS FROM `" . DB_PREFIX . "product_feed` LIKE 'use_original_images';";
		$query = $this->db->query($sql);
		if (!$query->num_rows) {
			$sql = "ALTER TABLE `" . DB_PREFIX . "product_feed` ADD COLUMN use_original_images int(1) DEFAULT 0;";
			$this->db->query($sql);
		}
		$sql = "SHOW COLUMNS FROM `" . DB_PREFIX . "product_feed` LIKE 'image_pass';";
		$query = $this->db->query($sql);
		if (!$query->num_rows) {
			$sql = "ALTER TABLE `" . DB_PREFIX . "product_feed` ADD COLUMN image_pass int(1) DEFAULT 0;";
			$this->db->query($sql);
		}
		$sql = "SHOW COLUMNS FROM `" . DB_PREFIX . "product_feed` LIKE 'use_categories';";
		$query = $this->db->query($sql);
		if (!$query->num_rows) {
			$sql = "ALTER TABLE `" . DB_PREFIX . "product_feed` ADD COLUMN use_categories int(11) DEFAULT 0;";
			$this->db->query($sql);
		}
		$sql = "SHOW COLUMNS FROM `" . DB_PREFIX . "product_feed` LIKE 'sql_code_before';";
		$query = $this->db->query($sql);
		if (!$query->num_rows) {
			$sql = "ALTER TABLE `" . DB_PREFIX . "product_feed` ADD COLUMN sql_code_before text NOT NULL;";
			$this->db->query($sql);
		}
		$sql = "SHOW COLUMNS FROM `" . DB_PREFIX . "product_feed` LIKE 'products';";
		$query = $this->db->query($sql);
		if (!$query->num_rows) {
			$sql = "ALTER TABLE `" . DB_PREFIX . "product_feed` ADD COLUMN products text NOT NULL;";
			$this->db->query($sql);
		}
		/* NeoSeo Exchange 1c - begin */
		$sql = "SHOW COLUMNS FROM `" . DB_PREFIX . "product_feed` LIKE 'use_warehouse_quantity';";
		$query = $this->db->query($sql);
		if (!$query->num_rows) {
			$sql = "ALTER TABLE `" . DB_PREFIX . "product_feed` ADD COLUMN use_warehouse_quantity int(1) DEFAULT 0;";
			$this->db->query($sql);
		}
		$sql = "SHOW COLUMNS FROM `" . DB_PREFIX . "product_feed` LIKE 'warehouses';";
		$query = $this->db->query($sql);
		if (!$query->num_rows) {
			$sql = "ALTER TABLE `" . DB_PREFIX . "product_feed` ADD COLUMN warehouses text NOT NULL;";
			$this->db->query($sql);
		}
		/* NeoSeo Exchange 1c - end */
		$sql = "SHOW COLUMNS FROM `" . DB_PREFIX . "product_feed` LIKE 'store_id';";
		$query = $this->db->query($sql);
		if (!$query->num_rows) {
			$sql = "ALTER TABLE `" . DB_PREFIX . "product_feed` ADD COLUMN store_id int DEFAULT 0;";
			$this->db->query($sql);
		}
		$sql = "SHOW COLUMNS FROM `" . DB_PREFIX . "product_feed` LIKE 'exclude_empty_product';";
		$query = $this->db->query($sql);
		if (!$query->num_rows) {
			$sql = "ALTER TABLE `" . DB_PREFIX . "product_feed` ADD COLUMN exclude_empty_product int(1) DEFAULT 0;";
			$this->db->query($sql);
		}
	}

	public function uninstall()
	{
		if (! true /* RePlaced */) {
			return "";
		}

		//Уадаляем параметры из сеттингов
		$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `code` = '" . $this->_moduleSysName() . "'");

		$sql = "SELECT data_file FROM `" . DB_PREFIX . "product_feed`";

		$query = $this->db->query($sql);
		$res = $query->rows;

		foreach ($res as $data) {
			if (is_file("../" . $data["data_file"]))
				unlink("../" . $data["data_file"]);
		}

		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "product_feed`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "product_feed_to_store`");

		$this->load->model('user/user_group');
		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'access', 'catalog/' . $this->_moduleSysName());
		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'modify', 'catalog/' . $this->_moduleSysName());

		return TRUE;
	}

	private function addAccessLevels()
	{
		/* Remove set Access Levels */
	}

}

?>