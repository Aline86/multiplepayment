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
                'min' => '1.7',
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
            $times = Configuration::get('TIMES');            
            $this->context->smarty->assign('thetime', $times);   
        }
  
        public function processConfiguration()
        {
            if (Tools::isSubmit('submit_times'))
            {
                $times = Tools::getValue('times');
                if($times==2 || $times==3 || $times==4 || $times==5){
                    Configuration::updateValue('TIMES', $times);
                    $this->context->smarty->assign('confirmation', 'ok');
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
         public function uninstall()
            {
                // Appeler la méthode de désinstallation parente
                if (!parent::uninstall()) {
                    return false;
                }
                // Effacer les valeurs de configuration
                Configuration::deleteByName('TIMES');
                // Tout s’est bien passé !
                return true;
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
                $this->assignMultiplePayment();
                return $this->display(__FILE__, 'multiplepayment.tpl');    
            }

            public function assignMultiplePayment()
            {
            $id_product = Tools::getValue('id_product');           
            $times = Configuration::get('TIMES');
            $price = Db::getInstance()->executeS('
                    SELECT * FROM '._DB_PREFIX_.'product
                    WHERE id_product = '.(int)$id_product);                 
                foreach($price as $p){
                   
                    if(isset($p['price'])){
                        $this->context->smarty->assign('price', $p['price']);
                    }           
                }
                $this->context->smarty->assign('times', $times);
                $payment_array=['aujourd\'hui', 'dans 1 mois', 'dans 2 mois', 'dans 3 mois', 'dans 5 mois'];
                $this->context->smarty->assign('array', $payment_array);
            }        
    }