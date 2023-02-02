<?php

require_once _PS_MODULE_DIR_.'/mymodule/classe/clientClasse.php';

if (!defined('_PS_VERSION_')) {
    exit;
}

class FinalModule extends Module
{



    public function __construct()
    {
        $this->name = 'FinalModule';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'ahmed diarra';
        $this->ps_versions_compliancy = [
            'min' => '1.6',
            'max' => _PS_VERSION_
        ];

        parent::__construct();

        $this->bootstrap =  true;
        $this->displayName = $this->l('FinalModule');
        $this->description = $this->l('Le meilleur module du monde');
    }


    public function install()
    {
        if (
            !parent::install() ||
            !Configuration::updateValue('annee', 11) ||
            !Configuration::updateValue('mois', 180) 
            // !$this->createTable() ||
            // !$this->InstallTab('AdminClient', 'mes clients','AdminCatalog')
        ) {
            return false;
        }
        return true;
    }





    public function getContent()
    {

        return $this->PostProcess() . $this->renderForm();
    }

    public function renderForm()
    {

        $fieldsForm[0]['form'] = [
            'legend' => [
                'title' => $this->l('Settings')
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => $this->l('Modifier le poids'),
                    'name' => 'annee',
                    'required' => true
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Modifier la taille'),
                    'name' => 'mois',
                    'required' => true
                ]

            ],
            'submit' => [
                'title' => $this->l('save'),
                'name' => 'save',
                'class' => 'btn btn-primary'
            ]
        ];

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->fields_value['annee'] = Configuration::get('annee');
        $helper->fields_value['mois'] = Configuration::get('mois');

        return $helper->generateForm($fieldsForm);
    }

    public function PostProcess()
    {
        // verifier que les champs ne sont pas vide.
        // verifier le type 

        if (Tools::isSubmit('save')) {

            $annee = Tools::getValue('annee');
            $mis = Tools::getValue('mois');
            $errors = false;

            if (empty($annee)) {
                $errors = true;
                return $this->displayError('L\ annee ne peut être vide.');
            }

            if (empty($mois)) {
                $errors = true;
                return $this->displayError('Le mois ne peut être vide.');
            }

            if (!Validate::isInt($annee)) {
                $errors = true;
                return $this->displayError('La poids n\'est pas de type float.');
            }

            if (!Validate::isString($mois)) {
                $errors = true;
                return $this->displayError('La taille n\'est pas de type float.');
            }

            if ($errors == false) {
                Configuration::updateValue('annee', $annee);
                Configuration::updateValue('TAILLE', $mois);
                return $this->displayConfirmation('Bravo la modification a bien  été éffectuée');
            }
        }
    }















    
}