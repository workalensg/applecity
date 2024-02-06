<?php
/**********************************************************/
/*	@copyright	OCTemplates 2015-2019.					  */
/*	@support	https://octemplates.net/					  */
/*	@license	LICENSE.txt									  */
/**********************************************************/

class ControllerOCTemplatesStickersOCTStickersSettings extends Controller {
	private $error = [];

	public function index() {
		$this->load->language('octemplates/stickers/oct_stickers_settings');

		$this->document->addStyle('view/javascript/octemplates/spectrum/spectrum.css');
		$this->document->addScript('view/javascript/octemplates/spectrum/spectrum.js');

		$this->document->addScript('view/javascript/octemplates/bootstrap-notify/bootstrap-notify.min.js');
		$this->document->addScript('view/javascript/octemplates/oct_main.js');
		$this->document->addStyle('view/stylesheet/oct_ultrastore.css');

		$this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');
        $this->load->model('localisation/language');
		$this->load->model('octemplates/widgets/oct_seogeneration');
		
		$data['language_id'] = $this->config->get('config_language_id');
		
		$oct_stickers_info = $this->model_setting_setting->getSetting('oct_stickers');

		if (!$oct_stickers_info) {
            $this->response->redirect($this->url->link('octemplates/stickers/oct_stickers_settings/install', 'user_token=' . $this->session->data['user_token'], true));
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
	        if (!isset($this->request->post['oct_stickers_status'])) {
				$this->request->post['oct_stickers_status'] = 0;
	        }
			
			if (isset($this->request->post['oct_stickers_data']['customer']) && !empty($this->request->post['oct_stickers_data']['customer'])) {
				$customers = $this->request->post['oct_stickers_data']['customer'];
				unset($this->request->post['oct_stickers_data']['customer']);
				
				foreach ($customers as $customer) {
					$index = $this->model_octemplates_widgets_oct_seogeneration->octTranslit($customer['title'][(int)$data['language_id']]);
					$this->request->post['oct_stickers_data']['customer'][$index] = $customer;
				}
			}
			
            $this->model_setting_setting->editSetting('oct_stickers', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('octemplates/stickers/oct_stickers_settings', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
        }

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('octemplates/stickers/oct_stickers_settings', 'user_token=' . $this->session->data['user_token'], true)
        ];

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

		if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        $data['action'] = $this->url->link('octemplates/stickers/oct_stickers_settings', 'user_token=' . $this->session->data['user_token'], true);
        $data['cancel'] = $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

        $data['user_token'] = $this->session->data['user_token'];
		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['oct_stickers_status'])) {
			$data['oct_stickers_status'] = $this->request->post['oct_stickers_status'];
		} else {
			$data['oct_stickers_status'] = $this->config->get('oct_stickers_status');
		}

        if (isset($this->request->post['oct_stickers_data'])) {
			$data['oct_stickers_data'] = $this->request->post['oct_stickers_data'];
		} else {
			$data['oct_stickers_data'] = $this->config->get('oct_stickers_data');
		}
		
        $data['header']      = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer']      = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('octemplates/stickers/oct_stickers_settings', $data));
	}

	public function install() {
		$this->load->language('octemplates/stickers/oct_stickers_settings');

        $this->load->model('setting/setting');
		$this->load->model('user/user_group');

        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'octemplates/module/oct_stickers');
        $this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'octemplates/module/oct_stickers');

		if ($this->checkIfColumnExist(DB_PREFIX . "product", "oct_stickers")) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD `oct_stickers` text NOT NULL;");
		}

		$this->load->model('localisation/language');

		$results = $this->model_localisation_language->getLanguages();

		$stickers_title = [];

        foreach ($results as $result) {
            $stickers_title[$result['language_id']] = '';
        }

        $this->model_setting_setting->editSetting('oct_stickers', [
	        'oct_stickers_status' => '0',
            'oct_stickers_data' => [
	            'stickers' => [
		            'new' => [
						'status' => 0,
						'text_color' => 'rgb(255, 255, 255)',
						'fon_color' => 'rgb(14, 144, 23)',
						'title' => $stickers_title,
						'sort' => 1,
						'count' => 10,
						'auto' => 0,
					],
					'bestseller' => [
						'status' => 0,
						'text_color' => 'rgb(255, 255, 255)',
						'fon_color' => 'rgb(235, 80, 20)',
						'title' => $stickers_title,
						'sort' => 2,
						'count' => 10,
						'auto' => 0,
					],
					'popular' => [
						'status' => 0,
						'text_color' => 'rgb(255, 255, 255)',
						'fon_color' => 'rgb(238, 202, 53)',
						'title' => $stickers_title,
						'sort' => 3,
						'count' => 10,
						'auto' => 0,
					],
					'special' => [
						'status' => 0,
						'text_color' => 'rgb(255, 255, 255)',
						'fon_color' => 'rgb(0, 120, 255)',
						'title' => $stickers_title,
						'sort' => 4,
						'auto' => 0,
						'persent' => 1,
						'view_title' => 1
					],
					'sold' => [
						'status' => 0,
						'text_color' => 'rgb(255, 255, 255)',
						'fon_color' => 'rgb(0, 0, 0)',
						'title' => $stickers_title,
						'sort' => 5,
						'count' => 0,
						'auto' => 0,
					],
					'ends' => [
						'status' => 0,
						'text_color' => 'rgb(255, 255, 255)',
						'fon_color' => 'rgb(230, 12, 12)',
						'title' => $stickers_title,
						'sort' => 6,
						'count' => 5,
						'auto' => 0,
					],
					'featured' => [
						'status' => 0,
						'text_color' => 'rgb(255, 255, 255)',
						'fon_color' => 'rgb(191, 7, 232)',
						'title' => $stickers_title,
						'sort' => 7,
					]
	            ],
				'customer' => []
            ]
        ]);

        $this->session->data['success'] = $this->language->get('text_success_install');

        $this->response->redirect($this->url->link('octemplates/stickers/oct_stickers_settings', 'user_token=' . $this->session->data['user_token'], true));
	}
		
	private function checkIfColumnExist($table_name, $table_column) {
		$query = $this->db->query("
        		SELECT
				COUNT(*) as total
			FROM information_schema.COLUMNS
			WHERE
				TABLE_SCHEMA = '".DB_DATABASE."'
			AND
				TABLE_NAME = '".$table_name."'
			AND
				COLUMN_NAME  = '".$table_column."'
		");

		return ($query->row['total'] == 0) ? true : false;
    }

	private function validate() {
		if (!$this->user->hasPermission('modify', 'octemplates/stickers/oct_stickers_settings')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'octemplates/stickers/oct_stickers_settings')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}