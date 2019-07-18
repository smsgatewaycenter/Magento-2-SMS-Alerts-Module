<?php

	namespace SMSGatewayCenter\SMSAlerts\Observer;

	use Magento\Customer\Helper\Address as CustomerAddress;
	use Magento\Customer\Model\Address\AbstractAddress;
	use Magento\Framework\Event\Observer;
	use Magento\Framework\Event\ObserverInterface;
	use Magento\Sales\Model\Order;
	use Magento\Sales\Model\Order\Address;

	/**
	 * Successful Order Placed Class Observer
	 */
	class sgcSuccessfulOrderPlaced implements ObserverInterface {

		protected $logger;
		protected $helperData;

		public function __construct(
		\Psr\Log\LoggerInterface $logger, \SMSGatewayCenter\SMSAlerts\Helper\Data $helperData
		) {
			$this->logger = $logger;
			$this->helperData = $helperData;
		}

		/**
		 * Client After Successful Order Placement Event Handler
		 * @param Observer $observer
		 * @return void
		 */
		public function execute(Observer $observer) {
			try {
				$this->logger->info("in sgcSuccessfulOrderPlaced observer");

				$order = $observer->getOrder();
				$orderId = $order->getIncrementId();
				$orderAddress = $order->getShippingAddress();
				if (!$orderAddress instanceof Address) {
					return;
				}
				$telephone = $orderAddress->getTelephone();
				$items = $order->getAllItems();

				foreach ($items as $item) {
					//$product = $item->getProduct();
					$textMsg = $this->helperData->getAdminConfig('sgcSuccessfulOrderPlacedMsg');
					$textMsg = str_replace('{itemName}', $item->getName(), $textMsg);
					$textMsg = str_replace('{itemPrice}', $item->getPrice(), $textMsg);
					$textMsg = str_replace('{orderId}', $orderId, $textMsg);
					$textMsg = str_replace('{firstName}', $firstname, $textMsg);
					$textMsg = str_replace('{lastName}', $lastname, $textMsg);
					$this->helperData->sgcSmsCurl($telephone, $textMsg);
				}
			} catch (\Exception $e) {
				$this->logger->critical($e->getMessage());
			}
		}

	}
	