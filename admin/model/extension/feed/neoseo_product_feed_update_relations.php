<?php /* --== O_o ==-- */

require_once(DIR_SYSTEM . "/engine/neoseo_model.php");

class ModelExtensionFeedNeoSeoProductFeedUpdateRelations extends NeoSeoModel
{

	public function __construct($registry)
	{
		parent::__construct($registry);
		$this->_moduleSysName = 'neoseo_product_feed_update_relations';
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
        CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "product_to_feed_category` (
	    `id` int(11) NOT NULL AUTO_INCREMENT,
            `product_id` int(11) NOT NULL,
            `product_feed_id` int(11) NOT NULL,
            `category_id` int(11) NOT NULL,
            PRIMARY KEY (`id`)
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
        CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "product_to_feed_category` (
	    `id` int(11) NOT NULL AUTO_INCREMENT,
            `product_id` int(11) NOT NULL,
            `product_feed_id` int(11) NOT NULL,
            `category_id` int(11) NOT NULL,
            PRIMARY KEY (`id`)
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

		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "product_to_feed_category`");

		return TRUE;
	}

	private function addAccessLevels()
	{
		/* Remove set Access Levels */
	}

}

?>