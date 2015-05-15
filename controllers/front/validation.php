<?php

class BankdepositValidationModuleFrontController extends ModuleFrontController
{
	protected $_html;

	/**
	 * @see FrontController::postProcess()
	 */
	public function postProcess()
	{
		$cart = $this->context->cart;
		if ($cart->id_customer == 0 || $cart->id_address_delivery == 0 || $cart->id_address_invoice == 0 || !$this->module->active)
			Tools::redirect('index.php?controller=order&step=1');

		// Check that this payment option is still available in case the customer changed his address just before the end of the checkout process
		$authorized = false;
		foreach (Module::getPaymentModules() as $module)
			if ($module['name'] == $this->module->name)
			{
				$authorized = true;
				break;
			}
		if (!$authorized)
			die($this->module->l('This payment method is not available.', 'validation'));

		$customer = new Customer($cart->id_customer);
		if (!Validate::isLoadedObject($customer))
			Tools::redirect('index.php?controller=order&step=1');

		$currency = $this->context->currency;
		$total = (float)$cart->getOrderTotal(true, Cart::BOTH);

		$this->module->validateOrder($cart->id, Configuration::get('PS_OS_BANK_DEPOSIT'), $total, $this->module->displayName, NULL, NULL, (int)$currency->id, false, $customer->secure_key);

		$this->_html = '<ul style="margin:0">';

		$records = $this->module->actionGetAllBankRecords();

		foreach ($records as $record) {
			$this->_html .= '<li style="display:block; list-style:none; margin-bottom:20px; line-height:26px; border-bottom:1px solid #ddd">';
			$this->_html .= '
				<p><span>Country:</span> '.$record['country'].'</p>
				<p><span>Account Name:</span> '.$record['account_name'].'</p>
				<p><span>Account Number:</span> '.$record['account_number'].'</p>
				<p><span>Account Type:</span> '.$record['account_type'].'</p>
			';

			if ($record['bank_name'])
				$this->_html .= '<p><span>Bank Name:</span> '.$record['bank_name'].'</p>';
			if ($record['bank_branch'])
				$this->_html .= '<p><span>Bank Branch:</span> '.$record['bank_branch'].'</p>';
			if ($record['swift_code'])
				$this->_html .= '<p><span>Swift Code:</span> '.$record['swift_code'].'</p>';
			$this->_html .= '</li>';
		}

		$this->_html .= '</ul>';

		$orderDetails = array(
			'id_order' 		=> $this->module->currentOrder,
			'secure_key'	=> $customer->secure_key
		);

		$customerName = $customer->firstname .' '. $customer->lastname;

		$mailVars = array(
				'{reference}' 	=> Order::getUniqReferenceOf($this->module->currentOrder),
				'{method}'		=> $this->module->displayName,
				'{shopName}'	=> Configuration::get('PS_SHOP_NAME'),
				'{name}'		=> $customerName,
				'{banks}' 		=> $this->_html,
				'{link}'  		=> $this->context->link->getModuleLink($this->module->name, 'upload', $orderDetails)
			);

		//SEND EMAIL
		Mail::Send($this->context->language->id, 'bankdeposit', 'Payment via Bank Deposit', $mailVars, $customer->email,
		$customerName, Configuration::get('PS_SHOP_EMAIL'), Configuration::get('PS_SHOP_NAME'), null, null,
		_PS_MAIL_DIR_, false, $this->context->shop->id, Configuration::get('BCC'));
		
		$params = array(
				'id_cart' 	=> $cart->id,
				'id_module' => $this->module->id,
				'id_order'	=> $this->module->currentOrder,
				'key'		=> $customer->secure_key
			);

		Tools::redirect($this->context->link->getModuleLink($this->module->name, 'confirmation', $params));
	}	
}