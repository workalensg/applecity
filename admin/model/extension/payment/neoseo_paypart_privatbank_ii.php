<?php /* --== O_o ==-- */

require_once( DIR_SYSTEM . "/engine/neoseo_model.php");

class ModelExtensionPaymentNeoseoPaypartPrivatbankIi extends NeoSeoModel
{

	public function __construct($registry)
	{
		parent::__construct($registry);
		$this->_moduleSysName = "neoseo_paypart_privatbank";
		/* Remove _module_code */
		$this->_modulePostfix = "_ii"; // Постфикс для разных типов модуля, поэтому переходим на испольлзование $this->_moduleSysName()
		$this->_logFile = $this->_moduleSysName() . ".log";
		$this->debug = $this->config->get($this->_moduleSysName() . "_debug") == 1;

		/* ZzZzzz... */

		$this->params = array(
			'status' => 1,
			'debug' => 0,
			'module_key' => '',
			'shop_id' => '',
			'shop_password' => '',
			'merchantType' => 3,
			'completed_status_id' => $this->config->get('config_order_status_id'),
			'canceled_status_id' => $this->config->get('config_order_status_id'),
			'clientwait_status_id' => $this->config->get('config_order_status_id'),
			'created_status_id' => $this->config->get('config_order_status_id'),
			'failed_status_id' => $this->config->get('config_order_status_id'),
			'rejected_status_id' => $this->config->get('config_order_status_id'),
		);
		$this->options_levels = array(
			'module_key' => 0,
			'status' => 0,
			'debug' => 0,
			'shop_id' => 1,
			'shop_password' => 1,
			'merchantType' => 1,
			'completed_status_id' => 1,
			'canceled_status_id' => 1,
			'clientwait_status_id' => 1,
			'created_status_id' => 1,
			'failed_status_id' => 1,
			'rejected_status_id' => 1,
		);

	}

	public function install()
	{
		if (! true /* RePlaced */) {
			return "";
		}

		// Значения параметров по умолчанию
		$this->initParams($this->params);
		$this->installTables();
		return TRUE;
	}

	public function upgrade()
	{
		if (! true /* RePlaced */) {
			return "";
		}

		return TRUE;
	}

	public function installTables()
	{
		if (! true /* RePlaced */) {
			return "";
		}

		return TRUE;
	}

	public function uninstall()
	{
		if (! true /* RePlaced */) {
			return "";
		}

		return TRUE;
	}

	private function addAccessLevels()
	{
		/* Remove set Access Levels */
	}


}

?>