<?php
class ModelExtensionModuleCurrencyPlus extends Model {
    public function getCurrencysSources() {
        $arr = array(
            'CP_CBRF' => 'ЦБРФ (Россия)', 
            'CP_BANK_UA' => 'НБУ (Украина)',
            'CP_BANK_UA_2' => 'НБУ (Украина) 2',
            'CP_PRIVAT_BANK_3' => 'ПриватБанк (Украина)',
            'CP_PRIVAT_BANK_5' => 'ПриватБанк (Украина) - курс в отделениях', 
            'CP_PRIVAT_BANK_11' => 'ПриватБанк (Украина) - курс по картам и пополнение', 
            'CP_NBRB' => 'НБРБ (Белоруссия)',
            'CP_KZT' => 'НБРК (Казахстан)',
            'CP_ECB' => 'Европейский Центробанк'
        );

        return $arr;
    }


    public function editSetting($group, $data, $store_id = 0) {
        foreach ($data as $key => $value) {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting
                        WHERE store_id = '" . (int)$store_id . "' AND `" . (version_compare(VERSION, '2.0.1') < 0 ? 'group' : 'code') . "` = '" . $this->db->escape($group) . "' AND `key` = '" . $this->db->escape($key) . "' ");

            if ($query->num_rows > 0) {
                if (!is_array($value)) {
                    $this->db->query("UPDATE " . DB_PREFIX . "setting SET `value` = '" . $this->db->escape($value) . "' WHERE store_id = '" . (int)$store_id . "' AND `" . (version_compare(VERSION, '2.0.1') < 0 ? 'group' : 'code') . "` = '" . $this->db->escape($group) . "' AND `key` = '" . $this->db->escape($key) . "' ");
                }
                else {
                    $module_settings = (version_compare(VERSION, '2.1', '<')) ? serialize($value) : json_encode($value);
                    $this->db->query("UPDATE " . DB_PREFIX . "setting SET `value` = '" . $this->db->escape($module_settings) . "', serialized = '1' WHERE store_id = '" . (int)$store_id . "' AND `" . (version_compare(VERSION, '2.0.1') < 0 ? 'group' : 'code') . "` = '" . $this->db->escape($group) . "' AND `key` = '" . $this->db->escape($key) . "' ");
                }
            }
        }
    }


    public function addCurrency($data) {
        if ($this->db->escape($data['code']) == $this->config->get('config_currency')) {
            $data['value'] = 1;
            $data['nominal'] = 1;
            $data['value_official'] = 1;
        }

        $this->db->query("INSERT INTO " . DB_PREFIX . "currency
        SET title = '" . $this->db->escape($data['title']) . "', 
            code = '" . $this->db->escape($data['code']) . "', 
            realcode = '" . $this->db->escape($data['realcode']) . "',
            symbol_left = '" . $this->db->escape($data['symbol_left']) . "', 
            symbol_right = '" . $this->db->escape($data['symbol_right']) . "',
            decimal_place = '" . $this->db->escape($data['decimal_place']) . "', 
            value = '" . $this->db->escape($data['value']) . "',
            nominal = '" . $this->db->escape($data['nominal']) . "', 
            correction_prefix = '" . $this->db->escape($data['correction_prefix']) . "',
            correction = '" . $this->db->escape($data['correction']) . "', 
            value_official = '" . $this->db->escape($data['value_official']) . "',
            auto_update_course = '" . $this->db->escape($data['auto_update_course']) . "', 
            round = '" . $this->db->escape($data['round']) . "', 
            round_type = '" . $this->db->escape($data['round_type']) . "', 
            status = '" . (int)$data['status'] . "', 
            date_modified = NOW()
        ");

        $this->cache->delete('currency');
    }


    public function editCurrency($currency_id, $data) {
        if ($this->db->escape($data['code']) == $this->config->get('config_currency')) {
            $data['value'] = 1;
            $data['nominal'] = 1;
            $data['value_official'] = 1;
        }
        
        $this->db->query("UPDATE " . DB_PREFIX . "currency
        SET title = '" . $this->db->escape($data['title']) . "', 
            code = '" . $this->db->escape($data['code']) . "', 
            realcode = '" . $this->db->escape($data['realcode']) . "',
            symbol_left = '" . $this->db->escape($data['symbol_left']) . "', 
            symbol_right = '" . $this->db->escape($data['symbol_right']) . "',
            decimal_place = '" . $this->db->escape($data['decimal_place']) . "', 
            value = '" . $this->db->escape($data['value']) . "',
            nominal = '" . $this->db->escape($data['nominal']) . "', 
            correction_prefix = '" . $this->db->escape($data['correction_prefix']) . "',
            correction = '" . $this->db->escape($data['correction']) . "', 
            value_official = '" . $this->db->escape($data['value_official']) . "',
            auto_update_course = '" . $this->db->escape($data['auto_update_course']) . "', 
            round = '" . $this->db->escape($data['round']) . "', 
            round_type = '" . $this->db->escape($data['round_type']) . "', 
            status = '" . (int)$data['status'] . "',
            date_modified = NOW() 
        WHERE currency_id = '" . (int)$currency_id . "'");

        $this->cache->delete('currency');
    }


    public function deleteCurrency($currency_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "currency WHERE currency_id = '" . (int)$currency_id . "'");

        $this->cache->delete('currency');
    }


    public function getCurrency($currency_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "currency WHERE currency_id = '" . (int)$currency_id . "'");

        return $query->row;
    }


    public function getCurrencyByCode($currency, $currency_id = '') {
        if ($currency_id) {
            $wtr = ' AND currency_id <> '.(int)$currency_id;
        }
        else {
            $wtr = '';
        }

        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "currency WHERE code = '" . $this->db->escape($currency) . "' " . $wtr);

        return $query->row;
    }


    public function getCurrencyByRealCode($currency) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "currency WHERE realcode = '" . $this->db->escape($currency) . "'");

        return $query->row;
    }


    public function getTotalProductByCurrencyCode($currency) {
        $query = $this->db->query("SELECT COUNT(product_id) as total FROM " . DB_PREFIX . "product
        WHERE base_currency_code = '" . $this->db->escape($currency) . "' ");

        return $query->row['total'];
    }

    public function getCurrencies($data = array()) {
        if ($data) {
            $sql = "SELECT * FROM " . DB_PREFIX . "currency";

            $sort_data = array(
                'title',
                'code',
                'realcode',
                'value',
                'date_modified'
            );

            if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
                $sql .= " ORDER BY " . $data['sort'];
            } else {
                $sql .= " ORDER BY title";
            }

            if (isset($data['order']) && ($data['order'] == 'DESC')) {
                $sql .= " DESC";
            } else {
                $sql .= " ASC";
            }

            if (isset($data['start']) || isset($data['limit'])) {
                if ($data['start'] < 0) {
                    $data['start'] = 0;
                }

                if ($data['limit'] < 1) {
                    $data['limit'] = 20;
                }

                $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
            }

            $query = $this->db->query($sql);

            return $query->rows;
        }
        else {
            $currency_data = $this->cache->get('currency');

            if (!$currency_data) {
                $currency_data = array();

                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "currency ORDER BY title ASC");

                foreach ($query->rows as $result) {
                    $currency_data[$result['code']] = array(
                        'currency_id'   => $result['currency_id'],
                        'title'         => $result['title'],
                        'code'          => $result['code'],
                        'realcode'      => $result['realcode'],
                        'symbol_left'   => $result['symbol_left'],
                        'symbol_right'  => $result['symbol_right'],
                        'decimal_place' => $result['decimal_place'],
                        'value'         => $result['value'],
                        'value_official' => $result['value_official'],
                        'status'        => $result['status'],
                        'date_modified' => $result['date_modified']
                    );
                }

                $this->cache->set('currency', $currency_data);
            }

            return $currency_data;
        }
    }


    public function getTotalCurrencies() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "currency");

        return $query->row['total'];
    }


    public function isFrontEnd() {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "modification WHERE `code` = 'LouiseCurrencyPlusFrontEnd' AND `status` = 1 ");
        if (count($query->rows) > 0) {
            return true;
        }
        else {
            return false;
        }
    }

}
?>