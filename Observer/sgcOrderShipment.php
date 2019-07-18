<?php

	namespace SMSGatewayCenter\SMSAlerts\Observer;

	use Magento\Customer\Helper\Address as CustomerAddress;
	use Magento\Customer\Model\Address\AbstractAddress;
	use Magento\Framework\Event\Observer;
	use Magento\Framework\Event\ObserverInterface;
	use Magento\Sales\Model\Order;
	use Magento\Sales\Model\Order\Address;

	/**
	 * Client Order Shipment Class Observer
	 */
	class sgcOrderShipment implements ObserverInterface {

		protected $logger;
		protected $helperData;

		public function __construct(
			\Psr\Log\LoggerInterface $logger, 
			\SMSGatewayCenter\SMSAlerts\Helper\Data $helperData
		) {
			$this->logger = $logger;
			$this->helperData = $helperData;
		}

		/**
		 * Client Order Shipment Event Handler
		 * @param Observer $observer
		 * @return void
		 */
		public function execute(Observer $observer) {
			try {
				$this->logger->info("in sgcOrderShipment observer");
				$shipment = $observer->getEvent()->getShipment();
				$orderId = $shipment->getOrder()->getIncrementId();
				$orderAddress = $shipment->getBillingAddress();
				
				$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
				$order1 = $objectManager->create('Magento\Sales\Model\Order')->loadByIncrementId($orderId);
				$items = $order1->getAllItems();
				
				if (!$orderAddress instanceof Address) {
					return;
				}
				$firstname = $orderAddress->getFirstName();
				$lastname = $orderAddress->getLastname();
				$telephone = $orderAddress->getTelephone();
				foreach ($items as $item) {
					$textMsg = $this->helperData->getAdminSmsConfig('sgcorderShipmentMsg');
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
	