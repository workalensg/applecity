<?php /* --== O_o ==-- */

require_once(DIR_SYSTEM . "/engine/neoseo_model.php");

class ModelExtensionFeedNeoSeoProductFeedCategories extends NeoSeoModel
{

	public function __construct($registry)
	{
		parent::__construct($registry);
		$this->_moduleSysName = 'neoseo_product_feed_categories';
		/* Remove _module_code */
		$this->_logFile = $this->_moduleSysName() . '.log';
		$this->debug = $this->config->get($this->_moduleSysName() . '_debug') == 1;

		/* ZzZzzz... */
	}

	// Install/Uninstall
	public function install()
	{
		if (! true /* RePlaced */) {
			return "";
		}

		$sql = "
        CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "product_feed_categories` (
            `category_id` int(11) NOT NULL AUTO_INCREMENT,
            `parent_id` int(11) NOT NULL,
            `name` varchar(255) NOT NULL,
	    `system_category` int(11) NOT NULL,
	    `level` int(11) DEFAULT 0,
            PRIMARY KEY (`category_id`)
        ) DEFAULT CHARSET=utf8;
		";
		$this->db->query($sql);

		$sql = "
        CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "product_feed_categories_path` (
	     `category_id` int(11) NOT NULL,
	     `path_id` int(11) NOT NULL,
	     `level` int(11) NOT NULL,
	     PRIMARY KEY (`category_id`,`path_id`)
        ) DEFAULT CHARSET=utf8;
		";
		$this->db->query($sql);

		return TRUE;
	}

	public function upgrade()
	{
		if (! true /* RePlaced */) {
			return "";
		}

		$sql = "
        CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "product_feed_categories` (
            `category_id` int(11) NOT NULL AUTO_INCREMENT,
            `parent_id` int(11) NOT NULL,
            `name` varchar(255) NOT NULL,
	    `system_category` int(11) NOT NULL,
	    `level` int(11) DEFAULT 0,
            PRIMARY KEY (`category_id`)
        ) DEFAULT CHARSET=utf8;
		";
		$this->db->query($sql);

		$sql = "
        CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "product_feed_categories_path` (
	     `category_id` int(11) NOT NULL,
	     `path_id` int(11) NOT NULL,
	     `level` int(11) NOT NULL,
	     PRIMARY KEY (`category_id`,`path_id`)
        ) DEFAULT CHARSET=utf8;
		";
		$this->db->query($sql);

		return TRUE;
	}

	public function uninstall()
	{
		if (! true /* RePlaced */) {
			return "";
		}

		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "product_feed_categories`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "product_feed_categories_path`");

		return TRUE;
	}

	private function addAccessLevels()
	{
		/* Remove set Access Levels */
	}

}

?>