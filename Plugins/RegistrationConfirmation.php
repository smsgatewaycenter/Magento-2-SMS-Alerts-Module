<?php

	namespace SMSGatewayCenter\SMSAlerts\Plugins;

	class RegistrationConfirmation {

		public function afterCreateAccount(\Magento\Customer\Model\AccountManagement $accountManagement, $customer) {
			$telephone = $customer->getDefaultShippingAddress()->getTelephone();
			//echo("<script>console.log('PHP: ".$telephone."');</script>");
			echo $telephone;
			return $customer;
		}

	}
	