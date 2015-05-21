<?php

class BankdepositUploadModuleFrontController extends ModuleFrontController
{
	/**
	 * @see FrontController::initContent()
	 */
	public function initContent()
	{
		parent::initContent();
		
		$orders = $this->getCustomerOrders($this->context->customer->id, Tools::getValue('reference'));
		
		//ORDER REFERENCE
		$reference = Order::getUniqReferenceOf(Tools::getValue('order'));

		if (!$this->context->customer->isLogged(true) || empty($orders) || Tools::getValue('secure_key') != $this->context->customer->secure_key)
			Tools::redirect('index.php');
		
		$success 	= null;
		$error 		= null;
		
		//CATCH THE DEPOSIT SLIP THE CUSTOMER IS UPLOADING
		if (Tools::isSubmit('upload')) {

			if($result = ImageManager::validateUpload($_FILES['deposit'], '256') && file_exists($_FILES['deposit']['tmp_name']))
			{
				$fileData = pathinfo(basename($_FILES["deposit"]["name"]));

				$imageFileTypes = array('jpg', 'jpeg', 'png', 'gif');

				if (!in_array($fileData['extension'], $imageFileTypes)){
				    $params = array( 
				    		'success' 	=> false,
				    		'reference'	=> $reference,
				    		'secure_key' => $this->context->customer->secure_key,
				    		'message' 	=> "Oops only image files type are only allowed to be uploaded."
				    	);
				    
				    return Tools::redirect($this->context->link->getModuleLink($this->module->name, 'upload', $params));
				
				}else{
					
					$uploadLocationDir = _PS_ROOT_DIR_ . _MODULE_DIR_ . $this->module->name. '/assets/uploads/';

					if (!is_dir($uploadLocationDir)) {
						$error = "Directory is not existing or not a directory at all";
					}
					
					//RENAME FILE
					$fileName 	= uniqid() . '.' . $fileData['extension'];
					$uploadfile = $uploadLocationDir . basename($fileName);
					$absoluteFileURL = _PS_BASE_URL_.__PS_BASE_URI__.'modules/'.$this->name.'/assets/uploads/'.basename($fileName);

					$file_attachment = null;
					if (!empty($_FILES['deposit']['name']))
					{
						$file_attachment['content'] = file_get_contents($_FILES['deposit']['tmp_name']);
						$file_attachment['name'] = $_FILES['deposit']['name'];
						$file_attachment['mime'] = $_FILES['deposit']['type'];
					}				

					if (move_uploaded_file($_FILES['deposit']['tmp_name'], $uploadfile)) {

						$templateVars = array(
								'{id_order}' 	=> Tools::getValue('order'),
								'{name}' 		=> ucwords($this->context->customer->firstname .' '. $this->context->customer->lastname),
								'{link}' 		=> $absoluteFileURL
							);

						//SEND EMAIL
						if(Mail::Send($this->context->language->id, 'bankdepositslip', 'Deposit Slip for Order '.$reference, $templateVars, Configuration::get('PS_SHOP_EMAIL'),
						Configuration::get('PS_SHOP_NAME'), $this->context->customer->email, $this->context->customer->lastname, $file_attachment, null,
						_PS_MAIL_DIR_, false, $this->context->shop->id, Configuration::get('SENDAHBCC')))
						{
							$msg = new Message();
							$msg->message 	= "Deposit slip uploaded - " . date("Y-m-d H:i:s");
							$msg->id_order 	= intval(Tools::getValue('order'));
							$msg->private 	= 1;
							$msg->add();

							$reference = @$orders[0]['reference'];

						    $params = array( 
						    		'success' 	=> true,
						    		'reference'	=> $reference,
						    		'secure_key' => $this->context->customer->secure_key,
						    		'message' 	=> "Upload is valid, and was successfully sent to us!\n"
						    	);
						    
						    return Tools::redirect($this->context->link->getModuleLink($this->module->name, 'upload', $params));
						}else{
							$this->context->smarty->assign(array('error' => 'Ooops problem sending the mail.'));
						}

					}else {
					    $error = "Possible file upload attack!\n";
					}
				}
			}else{

			    $params = array( 
			    		'success' 	=> false,
			    		'reference'	=> $reference,
			    		'secure_key' => $this->context->customer->secure_key,
			    		'message' 	=> "Oops! You need to upload a deposit slip or file is too big for upload."
			    	);
			    
			    return Tools::redirect($this->context->link->getModuleLink($this->module->name, 'upload', $params));
			}
		}

		$this->context->smarty->assign(array(
			'error'			=> $error,	
			'orders'		=> $orders,
			'action'		=> $this->context->link->getModuleLink($this->module->name, 'upload')
		));		

		$this->setTemplate('upload.tpl');
	}

	public function getCustomerOrders($id_customer, $reference)
	{
		$sql = '
				SELECT o.reference, o.id_order, o.total_paid
				FROM '._DB_PREFIX_.'orders AS o
				WHERE o.id_customer = '.$id_customer.'
				AND o.reference = "'. $reference .'"
				AND o.current_state = '. Configuration::get('PS_OS_BANK_DEPOSIT') .'
				ORDER BY o.id_order ASC';

		return DB::getInstance()->executeS($sql);
	}
}