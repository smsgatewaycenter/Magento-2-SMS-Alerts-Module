<?php

	namespace SMSGatewayCenter\SMSAlerts\Observer;

	use Magento\Customer\Helper\Address as CustomerAddress;
	use Magento\Customer\Model\Address\AbstractAddress;
	use Magento\Framework\Event\Observer;
	use Magento\Framework\Event\ObserverInterface;
	use Magento\Sales\Model\Order;
	use Magento\Sales\Model\Order\Address;

	/**
	 * Client Invoice Process Class Observer
	 */
	class sgcInvoiceProcess implements ObserverInterface {

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
		 * Client Invoice Process Event Handler
		 * @param Observer $observer
		 * @return void
		 */
		public function execute(Observer $observer) {
			try {
				$this->logger->info("in sgcInvoiceProcess Observer");
				$invoice = $observer->getEvent()->getInvoice();
				$orderId = $invoice->getOrder()->getIncrementId();
				$orderAddress = $invoice->getBillingAddress();
				
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
					$textMsg = $this->helperData->getAdminSmsConfig('sgcInvoiceProcessMsg');
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
	