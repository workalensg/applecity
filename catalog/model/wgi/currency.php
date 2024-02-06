<?php
class ModelWgiCurrency extends Model {

    public function getCharCode($source = 'CP_CBRF') {

        if ($source == 'CP_CBRF') {
            return 'RUB';
        }
        elseif ($source == 'CP_KZT') {
            return 'KZT';
        }
        elseif ($source == 'CP_BANK_UA' or $source == 'CP_BANK_UA_2' 
            or $source == 'CP_PRIVAT_BANK_3' or $source == 'CP_PRIVAT_BANK_5'  or $source == 'CP_PRIVAT_BANK_11'

        ) {
            return 'UAH';
        }
        elseif ($source == 'CP_NBRB') {
            return 'BYN';
        }
        elseif ($source == 'CP_ECB') {
            return 'EUR';
        }

        return 'RUB';
    }


    public function getCurrencySource($source = 'CP_CBRF') {
        if ($source == 'CP_CBRF') {
            return 'https://www.cbr.ru/scripts/XML_daily.asp';
        }
        elseif ($source == 'CP_KZT') {
            return 'https://www.nationalbank.kz/rss/rates_all.xml';
        }
        elseif ($source == 'CP_BANK_UA') {
            return 'https://pf-soft.net/service/currency/';
        }
        elseif ($source == 'CP_BANK_UA_2') {
            return 'https://bank.gov.ua/NBUStatService/v1/statdirectory/exchange';
        }
        elseif ($source == 'CP_PRIVAT_BANK_3') {
            return 'https://api.privatbank.ua/p24api/pubinfo?exchange&coursid=3';
        }
        elseif ($source == 'CP_PRIVAT_BANK_5') {
            return 'https://api.privatbank.ua/p24api/pubinfo?exchange&coursid=5';
        }
        elseif ($source == 'CP_PRIVAT_BANK_11') {
            return 'https://api.privatbank.ua/p24api/pubinfo?exchange&coursid=11';
        }
        elseif ($source == 'CP_NBRB') {
            return 'https://www.nbrb.by/Services/XmlExRates.aspx';
        }
        elseif ($source == 'CP_ECB') {
            return 'https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml';
        }
    }


    public function getCurrencyArr($source = 'CP_CBRF') {
        if ($source == 'CP_CBRF') {
            return array('index' => 'Valute', 'title' => 'CharCode', 'nominal' => 'Nominal', 'value' => 'Value');
        }
        elseif ($source == 'CP_KZT') {
            return array('index' => 'channel', 'index2' => 'item', 'title' => 'title', 'nominal' => 'quant', 'value' => 'description');
        }
        elseif ($source == 'CP_BANK_UA') {
            return array('index' => 'Valute', 'title' => 'CharCode', 'nominal' => 'Nominal', 'value' => 'Value');
        }
        elseif ($source == 'CP_BANK_UA_2') {
            return array('index' => 'currency', 'title' => 'cc', 'value' => 'rate');
        }
        elseif ($source == 'CP_PRIVAT_BANK_3' or $source == 'CP_PRIVAT_BANK_5' or $source == 'CP_PRIVAT_BANK_11') {
            return array('index' => 'exchangerate', 'index3' => '@attributes', 'title' => 'ccy', 'nominal' => 'exchangerate', 'value' => 'sale', 'base' => 'base_ccy');
        }
        elseif ($source == 'CP_NBRB') {
            return array('index' => 'Currency', 'title' => 'CharCode', 'nominal' => 'Scale', 'value' => 'Rate');
        }
        elseif ($source == 'CP_ECB') {
            return array('index' => 'Cube', 'index1' => 'Cube', 'index2' => 'Cube', 'index3' => '@attributes', 'title' => 'currency', 'value' => 'rate');
        }
    }

}