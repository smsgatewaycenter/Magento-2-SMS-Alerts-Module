<?php

	namespace SMSGatewayCenter\SMSAlerts\Helper;

	use Magento\Framework\App\Helper\AbstractHelper;
	use Magento\Store\Model\ScopeInterface;

	class Data extends AbstractHelper {

		const MAGE_SMSGATEWAYCENTER_SMSALERTS = 'smsgatewaycenter_smsAlerts/';
		const MAGE_SMSGATEWAYCENTER_SMSALERTS_ADMIN = 'smsgatewaycenter_smsAlerts_admin/';
		
		/**
		 * Response type of API method
		 * @var string
		 */
		public $format = 'json';
		
		/**
		 * Get DB Fields saved for SMS Gateway Center API parameter value
		 * @param type $mageValue
		 * @param type $mageStore
		 * @return type
		 */
		public function mageConfig($mageValue, $mageStore = null) {
			return $this->scopeConfig->getValue($mageValue, ScopeInterface::SCOPE_STORE, $mageStore);
		}
		
		public function getAccountSmsConfig($sgcParam, $mageStore = null) {
			return $this->mageConfig(self::MAGE_SMSGATEWAYCENTER_SMSALERTS . 'accountSmsConfig/' . $sgcParam, $mageStore);
		}

		public function getGeneralSmsConfig($sgcParam, $mageStore = null) {
			return $this->mageConfig(self::MAGE_SMSGATEWAYCENTER_SMSALERTS_ADMIN . 'generalSmsConfig/' . $sgcParam, $mageStore);
		}

		public function getAdminSmsConfig($sgcParam, $mageStore = null) {
			/* $logger = \Magento\Framework\App\ObjectManager::getInstance()->get(\Psr\Log\LoggerInterface::class);
			  $logger->info('message'); */
			return $this->mageConfig(self::MAGE_SMSGATEWAYCENTER_SMSALERTS_ADMIN . 'adminSmsConfig/' . $sgcParam, $mageStore);
		}

		public function sgcSmsCurl($userPhone, $textMsg) {
			$userid = $this->getAccountSmsConfig('userId');
			$password = $this->getAccountSmsConfig('password');
			$senderId = $this->getAccountSmsConfig('senderId');
			$msgType = $this->getAccountSmsConfig('msgType');
			$apiEndpoint = $this->getAccountSmsConfig('apiEndpoint');
			$logger = \Magento\Framework\App\ObjectManager::getInstance()->get(\Psr\Log\LoggerInterface::class);
			$logger->info("SGC SMS Info " . $userPhone . " " . $userid . " " . $password . " " . $textMsg . " " . $apiEndpoint);
			$post_data = array(
				"sendMethod" => "simpleMsg",
				"userId" => $userid,
				"password" => urlencode($password),
				"senderId" => $senderId,
				"mobile" => $userPhone,
				"msg" => $textMsg,
				"msgType" => $msgType,
				"duplicateCheck" => "true",
				"format" => "json"
			);
			$curl = curl_init();

			curl_setopt($curl, CURLOPT_URL, $apiEndpoint);
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

			$getSmsResponse = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
				$logger->error("CURL Error while calling xlsUpload: " . $err);
			} else {
				$decoded_response = json_decode($getSmsResponse, true);
				$logger->info($getSmsResponse);
			}
		}

	}

?>