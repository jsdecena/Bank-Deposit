<?php

class BankdepositConfirmationModuleFrontController extends ModuleFrontController
{
	public $ssl = true;
	public $display_column_left = false;

	/**
	 * @see FrontController::initContent()
	 */
	public function initContent()
	{
		if (!$this->context->customer->isLogged(true) || empty($this->context->cart))
			Tools::redirect('index.php');

		parent::initContent();

		$this->setTemplate('confirmation.tpl');
	}	
}