<?php

class BankdepositUploadModuleFrontController extends ModuleFrontController
{
	/**
	 * @see FrontController::initContent()
	 */
	public function initContent()
	{
		parent::initContent();

		if (!$this->context->customer->isLogged(true))
			Tools::redirect('index.php');

		if (Tools::getValue('secure_key') != $this->context->customer->secure_key)
			$viewable = false;
		else
			$viewable = true;

		//CATCH THE DEPOSIT SLIP THE CUSTOMER IS UPLOADING
		if (Tools::isSubmit('upload')) {

			$success 	= null;
			$error 		= null;

			if($result = ImageManager::validateUpload($_FILES['deposit'], '256'))
			{
				$uploadLocationDir = _PS_ROOT_DIR_ . _MODULE_DIR_ . $this->module->name. '/assets/uploads/';

				if (!is_dir($uploadLocationDir)) {
					$error = "Directory is not existing or not a directory at all";
				}

				$fileData = pathinfo(basename($_FILES["deposit"]["name"]));
				
				//RENAME FILE
				$fileName 	= uniqid() . '.' . $fileData['extension'];
				$uploadfile = $uploadLocationDir . basename($fileName);

				if (move_uploaded_file($_FILES['deposit']['tmp_name'], $uploadfile)) {

					$templateVars = array(
							'{id_order}' 	=> Tools::getValue('order'),
							'{name}' 		=> ucwords($this->context->customer->firstname .' '. $this->context->customer->lastname),
							'{link}' 		=> ''._PS_BASE_URL_.__PS_BASE_URI__.'modules/'.$this->module->name.'/assets/uploads/'.basename($fileName).''
						);

					//ORDER REFERENCE
					$reference = Order::getUniqReferenceOf(Tools::getValue('order'));

					//SEND EMAIL
					Mail::Send($this->context->language->id, 'bankdepositslip', 'Deposit Slip for Order '.$reference, $templateVars, Configuration::get('PS_SHOP_EMAIL'),
					Configuration::get('PS_SHOP_NAME'), $this->context->customer->email, $this->context->customer->lastname, null, null,
					_PS_MAIL_DIR_, false, $this->context->shop->id, Configuration::get('BCC'));
				    
				    $params = array( 
				    		'success' => true,
				    		'viewable'=> $viewable,
				    		'message' => "Upload is valid, and was successfully sent to us!\n"
				    	);
				    
				    return Tools::redirect($this->context->link->getModuleLink($this->module->name, 'upload', $params));

				} else {
				    $error = "Possible file upload attack!\n";
				}
			}else{

				$error = $result;
			}
		}

		$orders = $this->getCustomerOrders($this->context->customer->id);

		$this->context->smarty->assign(array(
			'error'			=> $error,	
			'orders'		=> $orders,
			'viewable' 		=> $viewable,
			'action'		=> $this->context->link->getModuleLink($this->module->name, 'upload')
		));		

		$this->setTemplate('upload.tpl');
	}

	public function getCustomerOrders($id_customer)
	{
		$sql = '
				SELECT o.reference, o.id_order
				FROM '._DB_PREFIX_.'orders AS o
				WHERE o.id_customer = '.$id_customer.'
				AND o.current_state = '. Configuration::get('PS_OS_BANK_DEPOSIT');

		return DB::getInstance()->executeS($sql);
	}
}