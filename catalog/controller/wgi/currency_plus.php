<?php
class ControllerWgiCurrencyPlus extends Controller {

    public function index() {
        $this->load->model('wgi/currency_plus');

        if (isset($this->request->get['type']) and ($this->request->get['type'] == 'all'
                or $this->request->get['type'] == 'product' or $this->request->get['type'] == 'currency')) {
            
            $type = $this->request->get['type'];
        }
        else {
            $type = 'currency';
        }
		
		
		if (isset($this->request->get['data'])) {
            $data = $this->request->get['data'];
        }
        else {
            $data = array();
        }

        if (isset($this->request->get['pid'])) {
            $data['product_id'] = $this->request->get['pid'];
        }

        $this->model_wgi_currency_plus->updateCurrencies(true, $type, $data);
    }

}
?>