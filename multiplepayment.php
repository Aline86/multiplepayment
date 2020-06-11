<?php
 
    if (!defined('_PS_VERSION_')) {
        exit;
    }
    class Multiplepayment extends Module
    {
        public function __construct()
        {
            $this->name = 'multiplepayment';
            $this->tab = 'billing_invoicing';
            $this->version = '0.1.0';
            $this->author = 'Claire-Aline Haestie';
            $this->need_instance = 0;
            $this->ps_versions_compliancy = [
                'min' => '1.6',
                'max' => _PS_VERSION_
            ];
            $this->bootstrap = true;
     
            parent::__construct();
     
            $this->displayName = $this->l('Multiple Payment');
            $this->description = $this->l('Module d\'affichage de paiement en plusieurs fois');
     
            $this->confirmUninstall = $this->l('Êtes-vous sûr de vouloir désinstaller ce module ?');
     
            if (!Configuration::get('MULTIPLEPAYMENT_PAGENAME')) {
                $this->warning = $this->l('Aucun nom fourni');
            }
        }
            public function assignConfiguration()

        {
            $times = Configuration::get('MULTIPLEPAYMENT_TIMES'); 
            $from_prices = Configuration::get('MULTIPLEPAYMENT_FROMPRICE');            
            $this->context->smarty->assign('time', $times);   
            $this->context->smarty->assign('from_prices', $from_prices);  
        }
  
        public function processConfiguration()
        {
            if (Tools::isSubmit('submit_times'))
            {
                $times = Tools::getValue('times');
                $from_price = Tools::getValue('from_price');
                if($times>=1 && $from_price >= 1){
                    Configuration::updateValue('MULTIPLEPAYMENT_TIMES', $times);
                    Configuration::updateValue('MULTIPLEPAYMENT_FROMPRICE', $from_price);
                    $this->context->smarty->assign('confirmation', 'ok');
                    $this->context->smarty->assign('time', $times);
                    $this->context->smarty->assign('from_prices', $from_price);
                }else{
                    $this->context->smarty->assign('configerror', 'error');
                }
                
            }
        }

        public function getContent()
        {
            $this->context->controller->addCSS($this->_path.'views/css/getContent.css', 'all');
            $this->processConfiguration();
            $this->assignConfiguration();
            return $this->display(__FILE__, 'getContent.tpl');            
        }

            public function install()
            {
                if(parent::install() || $this->registerHook('MultiplePayment')
                    || $this->registerHook('displayHeader')){
                    $this->registerHook('MultiplePayment');
                    $this->registerHook('displayHeader');
                    return true;
                }
            }
            
            public function loadSQLFile($sql_file)
            {
                $sql_content = file_get_contents($sql_file);
                $sql_content = str_replace('PREFIX_', _DB_PREFIX_, $sql_content);
                $sql_requests = preg_split('/;\s*[\r\n]+/', $sql_content);
                $result = true;
                foreach ($sql_requests as $request) {
                    if (!empty($request)) {
                        $result &= Db::getInstance()->execute(trim($request));
                    }
                }
                return $result;
            }
            
            public function hookDisplayHeader($params)
            {
                $this->context->controller->addCSS($this->_path.'views/css/multiplepayment.css', 'all');
                $this->context->controller->addJS($this->_path.'views/js/multiplepayment.js');
            }

            public function hookMultiplePayment($params)
            {
                $this->assignMultiplePayment($params);
                return $this->display(__FILE__, 'multiplepayment.tpl');    
            }

            public function assignMultiplePayment($params)
            {
                $times = Configuration::get('MULTIPLEPAYMENT_TIMES');              
                $from_price = Configuration::get('MULTIPLEPAYMENT_FROMPRICE');
                $last_payment=0;
                foreach($params as $product){
                    foreach($product as $p =>$value){
                        if($p=='price_tax_exc'){
                            $this->context->smarty->assign('price', $value);
                            $last_payment=$value;
                        }
                    }                   
                }
                $last_price = number_format($last_payment*1.2-round($last_payment*1.2/$times, 2)*($times-1), 2);       
                $payment_array=['aujourd\'hui'];                
                for($i=1; $i<$times; $i++){
                    array_push($payment_array, 'dans '.$i.' mois');  
                }
                $this->context->smarty->assign('from_the_price', $from_price); 
                $this->context->smarty->assign('times', $times);
                $this->context->smarty->assign('last', $last_price);
                $this->context->smarty->assign('array', $payment_array);
            }        
    }