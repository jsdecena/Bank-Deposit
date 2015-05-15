<?php
/*
*
* Author: Jeff Simons Decena @2013
*
*/

if (!defined('_PS_VERSION_'))
	exit;

class Bankdeposit extends PaymentModule
{

	public function __construct()
	{
	$this->name = 'bankdeposit';
	$this->tab = 'payments_gateways';
	$this->version = '0.1';
	$this->author = 'Jeff Simons Decena';
	$this->need_instance = 0;
	$this->ps_versions_compliancy = array('min' => '1.5', 'max' => '1.6');	

	parent::__construct();

	$this->bdTable 			= 'bank_deposit';
	$this->moduleUrl 		= $this->context->link->getAdminLink('AdminModules') . '&configure=' . $this->name;
	$this->imgUrl 			= _MODULE_DIR_ . $this->name .'/assets/img/';

	$this->displayName 		= $this->l('Bank Deposit');
	$this->description 		= $this->l('Bank Deposit configuration module');

	$this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

	if (!Configuration::get('BANKDEPOSIT'))      
		$this->warning = $this->l('No name provided');
	}

	public function install()
	{
		return parent::install() &&
			Configuration::updateValue('BANKDEPOSIT', 'BANKDEPOSIT MODULE') &&
			Configuration::updateValue('BCC', 'help@sendah.com') &&
			Configuration::updateValue('PS_OS_BANK_DEPOSIT', $this->create_order_state('Bank Deposit', 'bankdeposit')) &&
			$this->registerHook('payment') &&
			Db::getInstance()->Execute('
				CREATE TABLE `'._DB_PREFIX_.$this->bdTable.'` (
					`id` 				int(10) unsigned NOT NULL AUTO_INCREMENT,
					`country` 			varchar(255) DEFAULT NULL,
					`account_name` 		varchar(255) DEFAULT NULL,
					`account_number` 	varchar(255) DEFAULT NULL,
					`account_type` 		varchar(255) DEFAULT NULL,
					`bank_name` 		varchar(255) DEFAULT NULL,
					`bank_branch` 		varchar(255) DEFAULT NULL,
					`swift_code`		varchar(255) DEFAULT NULL,
					`logo`				varchar(255) DEFAULT NULL,
					`date_add` 			timestamp DEFAULT "0000-00-00",
					`date_upd` 			timestamp DEFAULT "0000-00-00",
					PRIMARY KEY (`id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		    ');
	}

	public function uninstall()
	{
		return parent::uninstall() && 
			Configuration::deleteByName('BANKDEPOSIT') &&
			Configuration::deleteByName('BCC') &&
			$this->deleteOrderStatus(Configuration::get('PS_OS_BANK_DEPOSIT')) &&
			Configuration::deleteByName('PS_OS_BANK_DEPOSIT') &&
			$this->unregisterHook('payment') &&
			Db::getInstance()->Execute('DROP TABLE '._DB_PREFIX_.$this->bdTable.'');
	}

	public function deleteOrderStatus($id)
	{
		return Db::getInstance()->delete('order_state', "id_order_state = " . $id);
	}	

	public function getContent()
	{
		$this->context->smarty->assign(array(
			'url'		=> $this->moduleUrl,
			'imgUrl'	=> $this->imgUrl
		));

		switch (Tools::getValue('page')) {
			case 'add':
				
				$this->context->smarty->assign(array(
					'action_add'		=> $this->actionAddBank()
				));

				return $this->display(__FILE__, 'views/templates/actions/add.tpl');

				break;
			case 'update':

				//GET THIS ITEM TO BE UPDATED
				$sql = '
						SELECT *
						FROM '._DB_PREFIX_.$this->bdTable.' AS bd
						WHERE bd.id = ' . Tools::getValue('id');

				$records = DB::getInstance()->executeS($sql);

				$bankDetails = array();
				foreach ($records as $record) {
					$country 		= $record['country'];
					$account_name 	= $record['account_name'];
					$account_number = $record['account_number'];
					$account_type 	= $record['account_type'];
					$bank_name 		= $record['bank_name'];
					$bank_branch 	= $record['bank_branch'];
					$swift_code 	= $record['swift_code'];
					$id_bank 		= $record['id'];
					$logo 			= $record['logo'];
				}

				$this->context->smarty->assign(array(
					'action_update'		=> $this->actionUpdateBank(),
					'country'			=> $country,
					'account_name'		=> $account_name,
					'account_number'	=> $account_number,
					'account_type'		=> $account_type,
					'bank_name'			=> $bank_name,
					'bank_branch'		=> $bank_branch,
					'swift_code'		=> $swift_code,
					'id_bank'			=> $id_bank,
					'logo'				=> $logo
				));

				return $this->display(__FILE__, 'views/templates/actions/update.tpl');

				break;
			case 'delete':
				if(!Db::getInstance()->delete($this->bdTable, 'id = ' . Tools::getValue('id')))
				{
					$this->context->smarty->assign(array(
						'error' 	=> 'We have problem deleting the bank.'
					));					
				}

				Tools::redirectAdmin($this->moduleUrl . '&success=1&message='.urlencode('You have successfully deleted a bank account.').' ');

				break;				
			default:
				$this->context->smarty->assign(array(
					'records'		=> $this->actionGetAllBankRecords()
				));

				return $this->display(__FILE__, 'views/templates/actions/view.tpl');
				break;
		}
	}

	public function actionGetAllBankRecords()
	{
		$sql = '
				SELECT *
				FROM '._DB_PREFIX_.$this->bdTable.'
		';

		return DB::getInstance()->executeS($sql);
	}

	public function actionAddBank()
	{
		if (Tools::isSubmit('actionAddBank')) {

			$data = array(
					'country' 			=> Tools::getValue('country'),
					'account_name' 		=> Tools::getValue('account_name'),
					'account_number' 	=> Tools::getValue('account_number'),
					'account_type'		=> Tools::getValue('account_type'),
					'bank_name' 		=> Tools::getValue('bank_name'),
					'bank_branch' 		=> Tools::getValue('bank_branch'),
					'swift_code' 		=> Tools::getValue('swift_code'),
					'logo' 				=> Tools::getValue('logo')
				);
			
			if(!Db::getInstance()->insert($this->bdTable, $data))
			{
				$this->context->smarty->assign(array(
					'error' 	=> 'We have problem creating the advisory.'
				));				
			}

			Tools::redirectAdmin($this->moduleUrl . '&success=1&message='.urlencode('You have successfully created a bank account.').' ');
		}
	}

	public function actionUpdateBank()
	{
		if (Tools::isSubmit('actionUpdateBank')) {
			
			$updateData = array(
				'country'			=> Tools::getValue('country'),
				'account_name'		=> Tools::getValue('account_name'),
				'account_number'	=> Tools::getValue('account_number'),
				'account_type'		=> Tools::getValue('account_type'),
				'bank_name'			=> Tools::getValue('bank_name'),
				'bank_branch'		=> Tools::getValue('bank_branch'),
				'swift_code'		=> Tools::getValue('swift_code'),
				'logo'				=> Tools::getValue('logo')
			);

			if (!Db::getInstance()->update($this->bdTable, $updateData, 'id=' . Tools::getValue('id'))) {
				$this->context->smarty->assign(array(
					'error' 	=> 'We have problem creating the advisory.'
				));
			}

			Tools::redirectAdmin($this->moduleUrl . '&success=1&message='.urlencode('You have successfully updated a bank account.').' ');
		}
	}

	public function hookPayment()
	{
		$this->context->smarty->assign(array(
			'action'		=> $this->context->link->getModuleLink($this->name, 'payment')
		));

		return $this->display(__FILE__, 'payment.tpl');
	}

    public function create_order_state($label = 'PS_NEW_STATUS', $template = null, $color = 'blue')
    {
        //Create the new status
        $os = new OrderState();
        $os->name = array(
            '1' => $label,
            '2' => '',
            '3' => ''
        );

        $os->invoice = false;
        $os->unremovable = true;
        $os->color = $color;
        $os->template = $template;
        $os->send_email = false;

        $os->save();
        
        return $os->id;
    }
}