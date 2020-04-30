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
            $enable_grades = Configuration::get('MYMOD_GRADES');
            $enable_comments = Configuration::get('MYMOD_COMMENTS');
            $this->context->smarty->assign('enable_grades', $enable_grades);
            $this->context->smarty->assign('enable_comments', $enable_comments);
        }
  
        public function processConfiguration()
        {
            if (Tools::isSubmit('submit_mymodcomments_form'))
            {
                $enable_grades = Tools::getValue('enable_grades');
                $enable_comments = Tools::getValue('enable_comments');
                Configuration::updateValue('MYMOD_GRADES', $enable_grades);
                Configuration::updateValue('MYMOD_COMMENTS', $enable_comments);
                $this->context->smarty->assign('confirmation', 'ok');
            }

        }

        public function getContent()
        {
            $this->processConfiguration();
            $this->assignConfiguration();
            return $this->display(__FILE__, 'getContent.tpl');
            
        }

            public function install()
            {
                if(parent::install() || $this->registerHook('MultiplePayment')){
                    $this->registerHook('MultiplePayment');
                    return true;
                }
                
            }

            public function hookMultiplePayment($params)

            {
                $this->processMultiplePayment();
                $this->assignMultiplePayment();
                return $this->display(__FILE__, 'multiplepayment.tpl');    
            }

            

            public function processMultiplePayment()
            {
            if (Tools::isSubmit('submit_times'))
                {
                    $id_product = Tools::getValue('id_product');
                    $times = Tools::getValue('times');
                    $insert = array(
                        'id_product' => (int)$id_product,
                        'times' => (int)$times,
                            );
                    Db::getInstance()->insert('multiplepayment', $insert);  
   
                }

            }

            public function assignMultiplePayment()
            {
            $id_product = Tools::getValue('id_product');
            $times = Tools::getValue('times');
           

            $price = Db::getInstance()->executeS('

                    SELECT * FROM '._DB_PREFIX_.'product

                    WHERE id_product = '.(int)$id_product);
                foreach($price as $p){
                    echo $p['price'];
                    if(isset($p['price'])){
                        $this->context->smarty->assign('price', $p['price']);
                    }
            
                }
                $this->context->smarty->assign('times', $times);
                $payment_array=['aujourd\'hui', 'dans un mois', 'dans deux mois', 'dans trois mois', 'dans cinq mois'];
                $this->context->smarty->assign('array', $payment_array);

            }

            public function hookDisplayProductTabContent($params)

            {

            $this->processProductTabContent();

            $this->assignProductTabContent();

            return $this->display(__FILE__, 'displayProductTabContent.tpl');

            }


        
    }