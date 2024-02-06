<?php /* --== O_o ==-- */

require_once(DIR_SYSTEM . "/engine/neoseo_controller.php");
require_once(DIR_SYSTEM . '/engine/neoseo_view.php' );

class ControllerExtensionFeedNeoSeoProductFeed extends NeoSeoController
{

	private $error = array();

	public function __construct($registry)
	{
		parent::__construct($registry);
		$this->_moduleSysName = "neoseo_product_feed";
		/* Remove _module_code */
		$this->_logFile = $this->_moduleSysName() . ".log";
		$this->debug = $this->config->get($this->_moduleSysName() . "_debug") == 1;
	}

	public function index()
	{

		$data = $this->load->language('extension/feed/' . $this->_moduleSysName());

		$this->document->setTitle($this->language->get('heading_title_raw'));

		$this->load->model('setting/setting');
		$this->load->model('extension/feed/' . $this->_moduleSysName());
		$this->load->model('extension/feed/' . $this->_moduleSysName() . '_formats');
		$this->load->model('extension/feed/' . $this->_moduleSysName() . '_categories');
		$this->load->model('extension/feed/' . $this->_moduleSysName() . '_update_relations');
		$this->load->model('tool/' . $this->_moduleSysName());

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting($this->_moduleSysName(), $this->request->post);

			$this->model_extension_feed_neoseo_product_feed->setModuleStatus($this->request->post[$this->_moduleSysName() . "_status"]);

			$this->session->data['success'] = $this->language->get('text_success_options'); //add data of saved feed

			if ($this->request->post['action'] == "save") {
				$this->response->redirect($this->url->link('extension/feed/' . $this->_moduleSysName(), 'user_token=' . $this->session->data['user_token'], 'SSL'));
			} else {
				$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], 'SSL'));
			}
		}

		$this->model_extension_feed_neoseo_product_feed->upgrade();
		$this->model_extension_feed_neoseo_product_feed_formats->upgrade();
		$this->model_extension_feed_neoseo_product_feed_categories->upgrade();
		$this->model_extension_feed_neoseo_product_feed_update_relations->upgrade();



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
			array('marketplace/extension', "text_feed"),
			array('extension/feed/' . $this->_moduleSysName(), "heading_title_raw")
				), $data);

		$data = $this->initButtons($data);

		$data = $this->initParamsListEx($this->{"model_extension_" . $this->_route . "_" . $this->_moduleSysName()}->getParams(), $data);

		$data['user_token'] = $this->session->data['user_token'];
		$data["cron"] = "php " . realpath(DIR_SYSTEM . "../cron/" . $this->_moduleSysName() . ".php");
		$data[$this->_moduleSysName() . "_cron"] = "php " . realpath(DIR_SYSTEM . "../cron/" . $this->_moduleSysName() . ".php");
		// Start formation list exports
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = '';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		$sort_data = array('sort' => $sort, 'order' => $order, 'start' => ($page - 1) * $this->config->get('config_limit_admin'), 'limit' => $this->config->get('config_limit_admin'));
		$feeds_total = $this->model_tool_neoseo_product_feed->getTotalFeeds();

		$results = $this->model_tool_neoseo_product_feed->getListFeeds($sort_data);

		if (is_array($results)) {
			foreach ($results as $id => $feed) {
				if (isset($feed['store_id']) && $feed['store_id'] > 0) {
					$store_url = $this->model_setting_setting->getSettingValue('config_url', $feed['store_id']);
				} else
					$store_url = HTTP_CATALOG;
				$item = array(
					'product_feed_id' => $feed['product_feed_id'],
					'feed_name' => $feed['feed_name'],
					'id_format' => $feed['id_format'],
					'status' => $feed['status'],
					'image_width' => $feed['image_width'],
					'use_categories' => $feed['use_categories'],
					'image_height' => $feed['image_height'],
					'feed_demand' => rtrim($store_url, "/") . "/index.php?route=extension/feed/" . $this->_moduleSysName() . "&name=" . $feed["feed_shortname"],
					'feed_cron' => rtrim($store_url, "/") . "/index.php?route=extension/feed/" . $this->_moduleSysName() . "/download&feed=" . $feed["feed_shortname"] . ".xml",
					'feed_cron_link' => rtrim($store_url, "/") . "/system/storage/download/" . $feed["feed_shortname"] . ".xml",
					'feed_cron_file' => rtrim(DIR_DOWNLOAD, "/") . "/" . $feed["feed_shortname"] . ".xml",
					'edit' => $this->url->link('extension/feed/' . $this->_moduleSysName() . '/editFeed', 'user_token=' . $this->session->data['user_token'] . '&product_feed_id=' . $feed['product_feed_id'] . $url, 'SSL'),
					'delete' => $this->url->link('extension/feed/' . $this->_moduleSysName() . '/deleteFeed', 'user_token=' . $this->session->data['user_token'] . '&product_feed_id=' . $feed['product_feed_id'] . $url, 'SSL')
				);

				if (file_exists($item['feed_cron_file'])) {
					$item['feed_cron_date'] = date("Y-m-d H:i:s", filemtime($item['feed_cron_file']));
				}

				$data['feeds'][] = $item;
			}
		} else {
			$data['feeds'] = array();
		}
		$getListFormats = $this->model_tool_neoseo_product_feed->getListFormats();

		if (is_array($getListFormats)) {
			foreach ($getListFormats as $key => $format) {
				$formats[$format['product_feed_format_id']] = $format;
			}

			foreach ($getListFormats as $key => $format) {
				$array_formats[$format['product_feed_format_id']] = $format['feed_format_name'];
			}

			$data["formats"] = $formats;
			$data["array_formats"] = $array_formats;
		} else {
			$data["formats"] = array();
			$data["array_formats"] = array();
		}

		$url = '';

		if ($order == 'DESC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['add'] = $this->url->link('extension/feed/' . $this->_moduleSysName() . '/addFeed', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['generate_url'] = $this->url->link('extension/feed/' . $this->_moduleSysName() . '/generate', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['sort_feed_name'] = $this->url->link('extension/feed/' . $this->_moduleSysName(), 'user_token=' . $this->session->data['user_token'] . '&sort=feed_name' . $url, 'SSL');
		$data['sort_feed_format_name'] = $this->url->link('extension/feed/' . $this->_moduleSysName(), 'user_token=' . $this->session->data['user_token'] . '&sort=id_format' . $url, 'SSL');
		$data['sort_feed_status'] = $this->url->link('extension/feed/' . $this->_moduleSysName(), 'user_token=' . $this->session->data['user_token'] . '&sort=status' . $url, 'SSL');

		$url = '';
		if ($order == 'DESC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		$pagination = new Pagination();
		$pagination->total = $feeds_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('extension/feed/' . $this->_moduleSysName(), 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}' . '#tab-feeds', 'SSL');
		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($feeds_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($feeds_total - $this->config->get('config_limit_admin'))) ? $feeds_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $feeds_total, ceil($feeds_total / $this->config->get('config_limit_admin')));
		// End formation list exports
		// Start formation list formats
		if (isset($this->request->get['sort_formats'])) {
			$sort_formats = $this->request->get['sort_formats'];
		} else {
			$sort_formats = '';
		}

		if (isset($this->request->get['order_formats'])) {
			$order_formats = $this->request->get['order_formats'];
		} else {
			$order_formats = 'ASC';
		}

		if (isset($this->request->get['page_formats'])) {
			if ($this->request->get['page_formats'] == '{page}') {
				$page_formats = 1;
			} else {
				$page_formats = $this->request->get['page_formats'];
			}
		} else {
			$page_formats = 1;
		}

		$url_formats = '';
		if (isset($this->request->get['sort_formats'])) {
			$url_formats .= '&sort_formats=' . $this->request->get['sort_formats'];
		}
		if (isset($this->request->get['order_formats'])) {
			$url_formats .= '&order_formats=' . $this->request->get['order_formats'];
		}

		if (isset($this->request->get['page_formats'])) {
			$url_formats .= '&page_formats=' . $this->request->get['page_formats'];
		}
		$sort_data_formats = array(
			'sort' => $sort_formats,
			'order' => $order_formats,
			'start' => ($page_formats - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$formats_total = $this->model_tool_neoseo_product_feed->getTotalFormats();

		$results_formats = $this->model_tool_neoseo_product_feed->getFormats($sort_data_formats);

		if (is_array($results_formats)) {
			foreach ($results_formats as $id => $format) {
				$data['list_formats'][] = array('product_feed_format_id' => $format['product_feed_format_id'],
					'feed_format_name' => $format['feed_format_name'],
					'format_xml' => $format['format_xml'],
					'edit' => $this->url->link('extension/feed/' . $this->_moduleSysName() . '/editFormat', 'user_token=' . $this->session->data['user_token'] . '&product_feed_format_id=' . $format['product_feed_format_id'] . $url_formats, 'SSL'),
					'delete' => $this->url->link('extension/feed/' . $this->_moduleSysName() . '/deleteFormat', 'user_token=' . $this->session->data['user_token'] . '&product_feed_format_id=' . $format['product_feed_format_id'] . $url_formats, 'SSL')
				);
			}
		} else {
			$data['list_formats'] = array();
		}

		$url_formats = '';
		if ($order_formats == 'DESC') {
			$url_formats .= '&order_formats=DESC';
		} else {
			$url_formats .= '&order_formats=ASC';
		}

		if (isset($this->request->get['page_formats'])) {
			$url_formats .= '&page_formats=' . $this->request->get['page_formats'];
		}

		$data['add_formats'] = $this->url->link('extension/feed/' . $this->_moduleSysName() . '/addFormat', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['default_formats'] = $this->url->link('extension/feed/' . $this->_moduleSysName() . '/defaultFormat', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['sort_feed_format_name'] = $this->url->link('extension/feed/' . $this->_moduleSysName(), 'user_token=' . $this->session->data['user_token'] . '&sort_formats=feed_format_name' . $url_formats, 'SSL');

		$url_formats = '';
		if ($order_formats == 'DESC') {
			$url_formats .= '&order_formats=DESC';
		} else {
			$url_formats .= '&order_formats=ASC';
		}

		$pagination_formats = new Pagination();
		$pagination_formats->total = $formats_total;
		$pagination_formats->page = $page_formats;
		$pagination_formats->limit = $this->config->get('config_limit_admin');
		$pagination_formats->url = $this->url->link('extension/feed/' . $this->_moduleSysName(), 'user_token=' . $this->session->data['user_token'] . $url_formats . '&page_formats={page}' . '#tab-formats', 'SSL');
		$data['pagination_formats'] = $pagination_formats->render();
		$data['results_formats'] = sprintf($this->language->get('text_pagination'), ($feeds_total) ? (($page_formats - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page_formats - 1) * $this->config->get('config_limit_admin')) > ($feeds_total - $this->config->get('config_limit_admin'))) ? $feeds_total : ((($page_formats - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $feeds_total, ceil($feeds_total / $this->config->get('config_limit_admin')));
		// End formation list formats

		$this->document->addStyle('view/stylesheet/' . $this->_moduleSysName() . '.css');

		$data["logs"] = $this->getLogs();

		$ids = array();
		$query = $this->db->query("SELECT product_id FROM `" . DB_PREFIX . "product`");
		foreach ($query->rows as $row) {
			$ids[] = $row['product_id'];
		}

		$data['fields'] = $this->getFields();
		$data['ids'] = $ids;
		$data['sort'] = $sort;
		$data['order'] = $order;
		$data['sort_formats'] = $sort_formats;
		$data['order_formats'] = $order_formats;
		$data['sort_formats'] = $sort_formats;
		$data['order_formats'] = $order_formats;
		$data['params'] = $data;

$data['old_module'] = true; 		$widgets = new NeoSeoWidgets($this->_moduleSysName() . '_', $data);
		$widgets->text_select_all = $this->language->get('text_select_all');
		$widgets->text_unselect_all = $this->language->get('text_unselect_all');
		$data['widgets'] = $widgets;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/feed/' . $this->_moduleSysName(), $data));
	}

	public function install()
	{
		$this->load->model('extension/feed/' . $this->_moduleSysName());
		$this->model_extension_feed_neoseo_product_feed->install(($this->config->get('config_store_id') ? $this->config->get('config_store_id') : 0));

		$this->load->model('extension/feed/' . $this->_moduleSysName() . "_formats");
		$this->model_extension_feed_neoseo_product_feed_formats->install();

		$this->load->model('extension/feed/' . $this->_moduleSysName() . "_categories");
		$this->model_extension_feed_neoseo_product_feed_categories->install();

		$this->load->model('extension/feed/' . $this->_moduleSysName() . "_update_relations");
		$this->model_extension_feed_neoseo_product_feed_update_relations->install();
	}

	public function uninstall()
	{
		$this->load->model('extension/feed/' . $this->_moduleSysName());
		$this->model_extension_feed_neoseo_product_feed->uninstall();

		$this->load->model('extension/feed/' . $this->_moduleSysName() . "_formats");
		$this->model_extension_feed_neoseo_product_feed_formats->uninstall();

		$this->load->model('extension/feed/' . $this->_moduleSysName() . "_categories");
		$this->model_extension_feed_neoseo_product_feed_categories->uninstall();

		$this->load->model('extension/feed/' . $this->_moduleSysName() . "_update_relations");
		$this->model_extension_feed_neoseo_product_feed_update_relations->uninstall();
	}

	private function validate()
	{
		if (!$this->user->hasPermission('modify', 'extension/feed/' . $this->_moduleSysName())) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	protected function getForm()
	{
		$data = $this->load->language('extension/feed/' . $this->_moduleSysName());
		$this->load->model('extension/feed/' . $this->_moduleSysName());
		$this->load->model('extension/feed/' . $this->_moduleSysName() . '_formats');
		$data['params'] = $data;

		$data['text_form'] = !isset($this->request->get['product_feed_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = array();
		}

		if (isset($this->error['error_url_group'])) {
			$data['error_url_group'] = $this->error['error_url_group'];
		} else {
			$data['error_url_group'] = '';
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data = $this->initBreadcrumbs(array(
			array('extension/feed', "text_feed"),
			array('extension/feed/' . $this->_moduleSysName(), 'heading_title_raw'),
			array('extension/feed/' . $this->_moduleSysName(), 'text_list', '#tab-feeds'),
				), $data);

		if (!isset($this->request->get['product_feed_id'])) {
			$data['action'] = $this->url->link('extension/feed/' . $this->_moduleSysName() . '/addFeed', 'user_token=' . $this->session->data['user_token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('extension/feed/' . $this->_moduleSysName() . '/editFeed', 'user_token=' . $this->session->data['user_token'] . '&product_feed_id=' . $this->request->get['product_feed_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('extension/feed/' . $this->_moduleSysName(), 'user_token=' . $this->session->data['user_token'] . $url, 'SSL');

		$getListFormats = $this->model_tool_neoseo_product_feed->getListFormats();
		foreach ($getListFormats as $key => $format) {
			$formats[$format['product_feed_format_id']] = $format;
		}

		$this->load->model('catalog/' . $this->_moduleSysName() . '_categories');
		$data['feedMainCategories'] = $this->model_catalog_neoseo_product_feed_categories->getParentCategories();

		$feedCategories = array();
		foreach ($data['feedMainCategories'] as $mainCategories) {
			$feedCategories[$mainCategories['category_id']] = $this->model_catalog_neoseo_product_feed_categories->getCategoriesByParentId($mainCategories['category_id']);
		}
		foreach ($this->model_catalog_neoseo_product_feed_categories->getCategories(array('where' => 'c2.parent_id!=0')) as $category) {
			foreach ($feedCategories as $parent_id => $categories) {
				foreach ($categories as $item) {
					if ($item['category_id'] != $category['category_id'])
						continue;
					$category['parent_id'] = $parent_id;
					$data['feedCategories'][] = $category;
				}
			}
		}

		$data['token'] = $this->session->data['user_token'];
		$data['product_not_unload'] = array();
		$this->load->model('catalog/product');

		$data['formats'] = $formats;
		$this->load->model('localisation/language');

		$languages = array();
		foreach ($this->model_localisation_language->getLanguages() as $language) {
			$languages[$language['language_id']] = $language['name'];
		}
		$data['languages'] = $languages;

		$this->load->model('catalog/category');
		$this->load->model('catalog/manufacturer');

		if (isset($this->request->get['product_feed_id'])) {
			$getFeed = $this->model_tool_neoseo_product_feed->getFeed($this->request->get['product_feed_id']);
			$categories_store = $this->model_catalog_category->getCategoriesIDStore($getFeed['store_id']);
			$manufacturers_store = $this->model_catalog_manufacturer->getManufacturersIDStore($getFeed['store_id']);
		}
		$categories = array();
		if (isset($categories_store)) {
			foreach ($this->model_catalog_category->getCategories(0) as $category) {
				foreach ($categories_store as $category_store) {
					if ($category_store['category_id'] == $category['category_id']) {
						$categories[$category['category_id']] = $category['name'];
					}
				}
			}
			$data['categories'] = $categories;
		} else {
			foreach ($this->model_catalog_category->getCategories(0) as $category) {
				$categories[$category['category_id']] = $category['name'];
			}
			$data['categories'] = $categories;
		}

		$manufacturers = array();
		if (isset($manufacturers_store)) {
			foreach ($this->model_catalog_manufacturer->getManufacturers(0) as $manufacturer) {
				foreach ($manufacturers_store as $manufacturer_store) {
					if ($manufacturer_store['manufacturer_id'] == $manufacturer['manufacturer_id']) {
						$manufacturers[$manufacturer['manufacturer_id']] = $manufacturer['name'];
					}
				}
			}
			$data['manufacturers'] = $manufacturers;
		} else {
			foreach ($this->model_catalog_manufacturer->getManufacturers(0) as $manufacturer) {
				$manufacturers[$manufacturer['manufacturer_id']] = $manufacturer['name'];
			}
			$data['manufacturers'] = $manufacturers;
		}

		$this->load->model('localisation/currency');
		$currencies = array();
		foreach ($this->model_localisation_currency->getCurrencies() as $currency) {
			$currencies[$currency['code']] = $currency['title'];
		}
		$data['currencies'] = $currencies;

		$this->load->model('setting/store');

		$stores = array();
		foreach ($this->{'model_extension_' . $this->_route . '_' . $this->_moduleSysName()}->getStores() as $store) {
			$stores[$store['store_id']] = $store['name'];
		}
		$data['stores'] = $stores;

		$data['product_feed_store'] = array(0);

		if (isset($this->request->post['product_feed_store'])) {
			$data['product_feed_store'] = $this->request->post['product_feed_store'];
		}

		/* NeoSeo Exchange1c - begin */
		$data['warehouses'] = array();
		$data['use_warehouses'] = false;
		if (($this->config->get('neoseo_exchange1c_status') == 1 && $this->config->get('neoseo_exchange1c_use_warehouse') == 1) ||
				($this->config->get('soforp_exchange1c_status') == 1 && $this->config->get('soforp_exchange1c_use_warehouse') == 1)) {
			$warehouses = $this->model_tool_neoseo_product_feed->getWarehouses();
		}
		if (isset($warehouses)) {
			foreach ($warehouses as $warehouse) {
				$data['warehouses'][$warehouse['warehouse_id']] = $warehouse['name'];
			}
			$data['use_warehouses'] = true;
		}
		/* NeoSeo Exchange1c - end */
		$this->load->model('setting/setting');
		if (isset($this->request->get['product_feed_id'])) {

			$data['product_feed_id'] = $this->request->get['product_feed_id'];
			$getFeeds = $this->model_tool_neoseo_product_feed->getFeed($this->request->get['product_feed_id']);
			if (isset($getFeeds['store_id']) && $getFeeds['store_id'] > 0) {
				$store_url = $this->model_setting_setting->getSettingValue('config_url', $getFeeds['store_id']);
			} else
				$store_url = HTTP_CATALOG;
			$getFeeds['feed_demand'] = rtrim($store_url, "/") . "/index.php?route=" . $this->_route . "/" . $this->_moduleSysName() . "&name=" . $getFeeds["feed_shortname"];
			$getFeeds["feed_cron"] = "php " . realpath(DIR_SYSTEM . "/../cron/" . $this->_moduleSysName() . ".php");

			$this->load->model('catalog/product');
			$getFeeds['product_not_unload'] = array();
			$product_not_unload = explode(',', $getFeeds['not_unload']);
			if ($product_not_unload) {
				foreach ($product_not_unload as $product_id) {
					$product_info = $this->model_catalog_product->getProduct($product_id);
					if ($product_info) {
						$getFeeds['product_not_unload'][] = array(
							'product_id' => $product_info['product_id'],
							'name' => $product_info['name']
						);
					}
				}
			}

			$getFeeds['product_list'] = array();
			$products = explode(',', $getFeeds['products']);
			if ($products) {
				foreach ($products as $product_id) {
					$product_info = $this->model_catalog_product->getProduct($product_id);
					if ($product_info) {
						$getFeeds['product_list'][] = array(
							'product_id' => $product_info['product_id'],
							'name' => $product_info['name']
						);
					}
				}
			}

			$data['feed'] = $getFeeds;

			$data['product_feed_store'] = $this->model_tool_neoseo_product_feed->getProductFeedStore($this->request->get['product_feed_id']);

			$data['breadcrumbs'][] = array(
				'text' => $getFeeds['feed_name'],
				'href' => $this->url->link('extension/feed/' . $this->_moduleSysName() . '/editFeed', 'user_token=' . $this->session->data['user_token'] . '&product_feed_id=' . $this->request->get['product_feed_id'], 'SSL')
			);
		} else {
			$data['feed'] = array(
				'status' => 0,
				'id_format' => 1,
				'feed_name' => '',
				'feed_shortname' => '',
				'feed_demand' => rtrim(HTTP_CATALOG, "/") . "/index.php?route=" . $this->_route . "/" . $this->_moduleSysName() . "&name=",
				'ip_list' => '',
				'feed_cron' => "php " . realpath(DIR_SYSTEM . "/../cron/" . $this->_moduleSysName() . ".php"),
				'language_id' => 1,
				'use_main_category' => 0,
				'categories' => '',
				'manufacturers' => '',
				'currency' => 1,
				'use_original_images' => 0,
				'image_pass' => 0,
				'image_width' => '',
				'image_height' => '',
				'pictures_limit' => '',
				'cat_names_separathor' => ' > ',
				'product_markup' => 0,
				'product_markup_option' => 0,
				'product_markup_type' => 0,
				'sql_code' => 'p.quantity > 0',
				'sql_code_before' => '',
				'use_warehouse_quantity' => 0,
				'warehouses' => '',
				'product_not_unload' => array(),
				'product_list' => array(),
				'store_id' => 0,
			);
		}
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/feed/' . $this->_moduleSysName() . '_form', $data));
	}

	protected function getFormFormat()
	{
		$data = $this->load->language('extension/feed/' . $this->_moduleSysName());
		$this->load->model('tool/' . $this->_moduleSysName());
		$data['params'] = $data;

		$data['text_form'] = !isset($this->request->get['product_feed_format_id']) ? $this->language->get('text_add_format') : $this->language->get('text_edit_format');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = array();
		}

		if (isset($this->error['error_url_group'])) {
			$data['error_url_group'] = $this->error['error_url_group'];
		} else {
			$data['error_url_group'] = '';
		}

		$url_formats = '';

		if (isset($this->request->get['sort_formats'])) {
			$url_formats .= '&sort_formats = ' . $this->request->get['sort_formats'];
		}

		if (isset($this->request->get['order_formats'])) {
			$url_formats .= '&order_format = ' . $this->request->get['order_formats'];
		}

		if (isset($this->request->get['page_formats'])) {
			$url_formats .= '&page = ' . $this->request->get['page_formats'];
		}

		$data = $this->initBreadcrumbs(array(
			array('extension/feed', "text_feed"),
			array('extension/feed/' . $this->_moduleSysName(), 'heading_title_raw'),
			array('extension/feed/' . $this->_moduleSysName(), 'text_list_format', '#tab-formats'),
				), $data);

		if (!isset($this->request->get['product_feed_format_id'])) {
			$data['action'] = $this->url->link('extension/feed/' . $this->_moduleSysName() . '/addFormat', 'user_token=' . $this->session->data['user_token'] . $url_formats, 'SSL');
		} else {
			$data['action'] = $this->url->link('extension/feed/' . $this->_moduleSysName() . '/editFormat', 'user_token=' . $this->session->data['user_token'] . '&product_feed_format_id = ' . $this->request->get['product_feed_format_id'] . $url_formats, 'SSL');
		}

		$data['cancel'] = $this->url->link('extension/feed/' . $this->_moduleSysName(), 'user_token=' . $this->session->data['user_token'] . $url_formats, 'SSL');


		if (isset($this->request->get['product_feed_format_id'])) {

			$data['product_feed_format_id'] = $this->request->get['product_feed_format_id'];
			$data['format'] = $this->model_tool_neoseo_product_feed->getFormat($this->request->get['product_feed_format_id']);

			$data['breadcrumbs'][] = array(
				'text' => $data['format']['feed_format_name'],
				'href' => $this->url->link('extension/feed/' . $this->_moduleSysName() . '/editFormat', 'user_token=' . $this->session->data['user_token'] . '&product_feed_format_id=' . $this->request->get['product_feed_format_id'], 'SSL')
			);
		} else {
			$data['format'] = array('feed_format_name' => '', 'format_xml' => '',);
		}
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/feed/' . $this->_moduleSysName() . '_format_form', $data));
	}

	public function addFeed()
	{
		$this->load->language('extension/feed/' . $this->_moduleSysName());

		$this->document->setTitle($this->language->get('heading_title_raw'));

		$this->load->model('tool/' . $this->_moduleSysName());
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_tool_neoseo_product_feed->saveFeed($this->request->post['feed']);
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort = ' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order = ' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page = ' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('extension/feed/' . $this->_moduleSysName(), 'user_token=' . $this->session->data['user_token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function editFeed()
	{
		$this->load->language('extension/feed/' . $this->_moduleSysName());

		$this->document->setTitle($this->language->get('heading_title_raw'));

		$this->load->model('tool/' . $this->_moduleSysName());
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_tool_neoseo_product_feed->saveFeed($this->request->post['feed']);
			$this->session->data['success'] = $this->language->get('text_success');
			$url = '';

			$url .= '#tab-feeds';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort = ' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order = ' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page = ' . $this->request->get['page'];
			}

			if (isset($this->request->get['product_feed_id']) && $this->request->get['product_feed_id'] > 0) {
				$this->response->redirect($this->url->link('extension/' . $this->_route . '/' . $this->_moduleSysName() . '/editFeed&product_feed_id=' . $this->request->get['product_feed_id'], 'user_token=' . $this->session->data['user_token'], 'SSL'));
			} else {
				$this->response->redirect($this->url->link('extension/' . $this->_route . '/' . $this->_moduleSysName(), 'user_token=' . $this->session->data['user_token'] . $url, 'SSL'));
			}
		}

		$this->getForm();
	}

	public function deleteFeed()
	{

		$data = $this->load->language('extension/feed/' . $this->_moduleSysName());
		$data['params'] = $data;
		$this->load->model('tool/' . $this->_moduleSysName());

		if (!$this->user->hasPermission('modify', 'extension/feed/' . $this->_moduleSysName())) {
			$data['error_warning'] = $this->language->get('error_permission');

			$this->template = 'extension/feed/' . $this->_moduleSysName();
			$this->children = array('common/header', 'common/footer');

			$data['post'] = $this->request->post;

			$this->response->setOutput($this->render(), $this->config->get('config_compression'));
			return;
		} else {

			if (isset($this->request->get["product_feed_id"])) {
				$this->model_tool_neoseo_product_feed->deleteFeed($this->request->get);

				$this->session->data['success'] = $this->language->get('text_success_delete');
			}

			header("location:" . str_replace('&amp;', '&', $this->url->link('extension/feed/' . $this->_moduleSysName(), 'user_token=' . $this->session->data['user_token'], 'SSL')));
		}
	}

	public function addFormat()
	{
		$this->load->language('extension/feed/' . $this->_moduleSysName());

		$this->document->setTitle($this->language->get('heading_title_raw'));

		$this->load->model('tool/' . $this->_moduleSysName());
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

			$this->model_tool_neoseo_product_feed->saveFormats($this->request->post['format']);
			$this->session->data['success'] = $this->language->get('text_success');

			$url_formats = '';

			if (isset($this->request->get['sort_formats'])) {
				$url_formats .= '&sort_formats = ' . $this->request->get['sort_formats'];
			}

			if (isset($this->request->get['order_format'])) {
				$url_formats .= '&order_format = ' . $this->request->get['order_formats'];
			}

			if (isset($this->request->get['page_format'])) {
				$url_formats .= '&page_format = ' . $this->request->get['page_formats'];
			}

			$this->response->redirect($this->url->link('extension/feed/' . $this->_moduleSysName(), 'user_token=' . $this->session->data['user_token'] . $url_formats, 'SSL'));
		}

		$this->getFormFormat();
	}

	public function editFormat()
	{
		$this->load->language('extension/feed/' . $this->_moduleSysName());

		$this->document->setTitle($this->language->get('heading_title_raw'));

		$this->load->model('tool/' . $this->_moduleSysName());
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_tool_neoseo_product_feed->saveFormats($this->request->post['format']);
			$this->session->data['success'] = $this->language->get('text_success');

			$url_formats = '';
			$url_formats .= '#tab-formats';

			if (isset($this->request->get['sort_formats'])) {
				$url_formats .= '&sort_formats = ' . $this->request->get['sort_formats'];
			}

			if (isset($this->request->get['order_formats'])) {
				$url_formats .= '&order_formats = ' . $this->request->get['order_formats'];
			}

			if (isset($this->request->get['page_format'])) {
				$url_formats .= '&page_formats = ' . $this->request->get['page_formats'];
			}
			$this->response->redirect($this->url->link('extension/feed/' . $this->_moduleSysName(), 'user_token=' . $this->session->data['user_token'] . $url_formats, 'SSL'));
		}

		$this->getFormFormat();
	}

	public function deleteFormat()
	{

		$data = $this->load->language('extension/feed/' . $this->_moduleSysName());
		$data['params'] = $data;
		$this->load->model('tool/' . $this->_moduleSysName());

		if (!$this->user->hasPermission('modify', 'extension/feed/' . $this->_moduleSysName())) {
			$data['error_warning'] = $this->language->get('error_permission');

			$this->template = 'extension/feed/' . $this->_moduleSysName();
			$this->children = array('common/header', 'common/footer');

			$data['post'] = $this->request->post;

			$this->response->setOutput($this->render(), $this->config->get('config_compression'));
			return;
		} else {

			if (isset($this->request->get["product_feed_format_id"])) {
				$this->model_tool_neoseo_product_feed->deleteFormat($this->request->get);

				$this->session->data['success'] = $this->language->get('text_success_delete');
			}

			header("location:" . str_replace('&amp;', '&', $this->url->link('extension/feed/' . $this->_moduleSysName(), 'user_token=' . $this->session->data['user_token'], 'SSL')));
		}
	}

	public function defaultFormat()
	{

		$data = $this->load->language('extension/feed/' . $this->_moduleSysName());
		$this->load->model('extension/feed/' . $this->_moduleSysName() . '_formats');

		if (!$this->validate()) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$this->{'model_extension_feed_' . $this->_moduleSysName() . '_formats'}->defaultFormat();
			$this->session->data['success'] = $this->language->get('text_success_formats');
		}
		$this->response->redirect($this->url->link('extension/feed/' . $this->_moduleSysName(), 'user_token=' . $this->session->data['user_token'], 'SSL'));
	}

	public function generate()
	{
		$product_id = $this->request->get["id"];
		$width = $this->request->get["width"];
		$height = $this->request->get["height"];

		$count = 0;
		$this->load->model("catalog/product");
		$this->load->model("tool/image");
		$images = $this->model_catalog_product->getProductImages($product_id);
		foreach ($images as $image) {
			$this->model_tool_image->resize($image['image'], $width, $height);
			$count++;
		}

		echo json_encode(array('status' => 'OK', 'count' => $count));
	}

	protected function getFields()
	{
		$result = array();

		$result['date'] = $this->language->get("field_desc_date");
		$result['url'] = $this->language->get("field_desc_url");
		$result['currency'] = $this->language->get("field_desc_currency");
		$result['categories'] = $this->language->get("field_desc_categories");
		$result['category'] = $this->language->get("field_desc_category");
		$result['category.id'] = $this->language->get("field_desc_category.id");
		$result['category.parentId'] = $this->language->get("field_desc_category.parentId");
		$result['category.name'] = $this->language->get("field_desc_category.name");
		$result['category.url'] = $this->language->get("field_desc_category.url");
		$result['offers'] = $this->language->get("field_desc_offers");
		$result['offer'] = $this->language->get("field_desc_offer");
		$result['offer.id'] = $this->language->get("field_desc_offer.id");
		$result['offer.path'] = $this->language->get("field_desc_path");
		$result['offer.url'] = $this->language->get("field_desc_offer.url");
		$result['offer.tag'] = $this->language->get("field_desc_offer.tag");
		$result['offer.meta_title'] = $this->language->get("field_desc_offer.meta_title");
		$result['offer.meta_h1'] = $this->language->get("field_desc_offer.meta_h1");
		$result['offer.meta_description'] = $this->language->get("field_desc_offer.meta_description");
		$result['offer.meta_keyword'] = $this->language->get("field_desc_offer.meta_keyword");
		$result['offer.price'] = $this->language->get("field_desc_offer.price");
		$result['offer.currencyId'] = $this->language->get("offer.currencyId");
		$result['offer.categoryId'] = $this->language->get("offer.categoryId");
		$result['offer.name'] = $this->language->get("offer.name");
		$result['offer.description'] = $this->language->get("offer.description");
		$result['offer.description_no_html'] = $this->language->get("offer.description_no_html");
		$result['offer.model'] = $this->language->get("offer.model");
		$result['offer.vendor'] = $this->language->get("offer.vendor");
		$result['offer.vendorCode'] = $this->language->get("offer.vendorCode");
		$result['offer.image'] = $this->language->get("offer.image");
		$result['offer.discount'] = $this->language->get("offer.discount");
		$result['offer.special'] = $this->language->get("offer.special");
		$result['image'] = $this->language->get("image");
		$result['offer.options'] = $this->language->get("offer.options");
		$result['option'] = $this->language->get("option");
		$result['option.name'] = $this->language->get("option.name");
		$result['option.value'] = $this->language->get("option.value");
		$result['option.available'] = $this->language->get("option.available");
		$result['offer.attributes'] = $this->language->get("offer.attributes");
		$result['attribute'] = $this->language->get("attribute");
		$result['attributes.name'] = $this->language->get("attributes.name");
		$result['attributes.value'] = $this->language->get("attributes.value");
		$result['filter_attributes'] = $this->language->get("filter_attributes");
		$result['offer.*'] = $this->language->get("offer.*");
		$result['offer.stock_status_name'] = $this->language->get("offer.stock_status_name");

		return $result;
	}

}

?>
