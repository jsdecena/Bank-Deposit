<?php

class BankdepositPaymentModuleFrontController extends ModuleFrontController
{
	public $ssl = true;
	public $display_column_left = false;

	/**
	 * @see FrontController::initContent()
	 */
	public function initContent()
	{
		parent::initContent();

		if (!$this->context->customer->isLogged(true) || empty($this->context->cart) || $this->context->cart->id == NULL)
			Tools::redirect('index.php');

		$this->context->smarty->assign(array(
			'records' => $this->module->actionGetAllBankRecords(),
			'imgUrl'  => $this->module->imgUrl,
			'action'  => $this->context->link->getModuleLink($this->module->name, 'validation')
		));

		$this->setTemplate('bankdetails.tpl');
	}	
}