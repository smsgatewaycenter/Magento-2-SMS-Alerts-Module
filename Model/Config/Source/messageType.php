<?php

	namespace SMSGatewayCenter\SMSAlerts\Model\Config\Source;

	use Magento\Framework\Option\ArrayInterface;

	/**
	  Message Type Source Controller
	 */
	class messageType implements ArrayInterface {
		
		/**
		 * Message type array options
		 * @return type
		 */
		public function toOptionArray() {
			return [
				['value' => 'text', 'label' => _('TEXT')],
				['value' => 'unicode', 'label' => _('UNICODE')]
			];
		}

	}
	