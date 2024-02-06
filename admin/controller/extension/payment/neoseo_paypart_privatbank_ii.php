<?php /* --== O_o ==-- */

require_once( DIR_SYSTEM . "/engine/neoseo_controller.php");
require_once( DIR_SYSTEM . '/engine/neoseo_view.php' );

class ControllerExtensionPaymentNeoseoPaypartPrivatbankIi extends NeoSeoController
{

	private $error = array();

	public function __construct($registry)
	{
		parent::__construct($registry);
		$this->_moduleSysName = "neoseo_paypart_privatbank";
		/* Remove _module_code */
		$this->_modulePostfix = "_ii"; // Постфикс для разных типов модуля, поэтому переходим на испольлзование $this->_moduleSysName()
		$this->_logFile = $this->_moduleSysName() . ".log";
		$this->debug = $this->config->get($this->_moduleSysName() . "_debug") == 1;
	}

	public function index()
	{
		$this->upgrade();

		$data = $this->load->language('extension/' . $this->_route . '/' . $this->_moduleSysName());
		//Во всех частях загружаем языковой файл с текстами лицензии, поддержки и прочего.
		//Для всех файл один и тот же
		$data = array_merge($data, $this->load->language('extension/' . $this->_route . '/' . $this->_moduleSysName));

		$this->document->setTitle($this->language->get('heading_title_raw'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {

			$this->model_setting_setting->editSetting($this->_moduleSysName(), $this->request->post);

			$globalModuleData[$this->_moduleSysName . '_module_key'] = $this->request->post[$this->_moduleSysName() . '_module_key'];
			$this->model_setting_setting->editSetting($this->_moduleSysName, $globalModuleData);

			//Это нужно чтобы при нажатии кнопки "сохранить и закрыть" был правильный статус
			$this->model_extension_payment_neoseo_paypart_privatbank_ii->setModuleStatus("payment_" . $this->request->post[$this->_moduleSysName() . $this->_modulePostfix . "_status"]);

			$this->session->data['success'] = $this->language->get('text_success');

			if ($this->request->post['action'] == "save") {
				$this->response->redirect($this->url->link('extension/' . $this->_route . '/' . $this->_moduleSysName(), 'user_token=' . $this->session->data['user_token'], 'SSL'));
			} else {
				$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL'));
			}
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else if (isset($this->session->data['error_warning'])) {
			$data['error_warning'] = $this->session->data['error_warning'];
			unset($this->session->data['error_warning']);
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		}

		$data = $this->initBreadcrumbs(array(
			array('extension/extension', "text_module"),
			array('extension/'.$this->_route .  '/' . $this->_moduleSysName(), "heading_title_raw")
		    ), $data);

		$data = $this->initButtons($data);

		$this->load->model('extension/'.$this->_route . '/' . $this->_moduleSysName());
		$data = $this->initParamsListEx($this->{"model_extension_" . $this->_route . "_" . $this->_moduleSysName()}->getParams(), $data);

		$this->load->model('localisation/order_status');

		$order_statuses = $this->model_localisation_order_status->getOrderStatuses();
		$data['order_statuses'] = array();
		foreach ($order_statuses as $status) {
			$data['order_statuses'][$status['order_status_id']] = $status['name'];
		}

		$data[$this->_moduleSysName() . '_paymentparts_url'] = $this->language->get('text_paymentparts_url');

		$data["user_token"] = $this->session->data['user_token'];
		$data['config_language_id'] = $this->config->get('config_language_id');
		$data['params'] = $data;

		$data['logs'] = $this->getLogs();

$data['old_module'] = true; 		$widgets = new NeoSeoWidgets($this->_moduleSysName() . '_', $data);
		$widgets->text_select_all = $this->language->get('text_select_all');
		$widgets->text_unselect_all = $this->language->get('text_unselect_all');
		$data['widgets'] = $widgets;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/'.$this->_route . '/' . $this->_moduleSysName(), $data));
	}

	private function validate()
	{

		if (!$this->user->hasPermission('modify', 'extension/'.$this->_route . '/' . $this->_moduleSysName())) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post[$this->_moduleSysName() . '_shop_id']) {
			$this->error['warning'] = $this->language->get('error_shop_id');
		}

		if (!$this->request->post[$this->_moduleSysName() . '_shop_password']) {
			$this->error['warning'] = $this->language->get('error_shop_password');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

}

?>
