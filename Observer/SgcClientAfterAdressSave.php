<?php

	namespace SMSGatewayCenter\SMSAlerts\Observer;

	use Magento\Customer\Api\GroupManagementInterface;
	use Magento\Customer\Helper\Address as HelperAddress;
	use Magento\Customer\Model\Address;
	use Magento\Customer\Model\Address\AbstractAddress;
	use Magento\Customer\Model\Session as CustomerSession;
	use Magento\Customer\Model\Vat;
	use Magento\Framework\App\Area;
	use Magento\Framework\App\Config\ScopeConfigInterface;
	use Magento\Framework\App\State as AppState;
	use Magento\Framework\DataObject;
	use Magento\Framework\Escaper;
	use Magento\Framework\Event\ObserverInterface;
	use Magento\Framework\Message\ManagerInterface;
	use Magento\Framework\Registry;
	use Magento\Store\Model\ScopeInterface;

	/**
	 * Client Address Save Observer Model
	 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
	 */
	class SgcClientAfterAdressSave implements ObserverInterface {

		/**
		 * VAT ID validation processed flag code
		 */
		const VIV_PROCESSED_FLAG = 'viv_after_address_save_processed';

		/**
		 * @var HelperAddress
		 */
		protected $_customerAddress;

		/**
		 * @var Registry
		 */
		protected $_coreRegistry;

		/**
		 * @var Vat
		 */
		protected $_customerVat;

		/**
		 * @var GroupManagementInterface
		 */
		protected $_groupManagement;

		/**
		 * @var AppState
		 */
		protected $appState;

		/**
		 * @var ScopeConfigInterface
		 */
		protected $scopeConfig;

		/**
		 * @var ManagerInterface
		 */
		protected $messageManager;

		/**
		 * @var Escaper
		 */
		protected $escaper;

		/**
		 * @var CustomerSession
		 */
		private $customerSession;
		protected $_logger;
		protected $helperData;

		/**
		 * @param Vat $customerVat
		 * @param HelperAddress $customerAddress
		 * @param Registry $coreRegistry
		 * @param GroupManagementInterface $groupManagement
		 * @param ScopeConfigInterface $scopeConfig
		 * @param ManagerInterface $messageManager
		 * @param Escaper $escaper
		 * @param AppState $appState
		 * @param CustomerSession $customerSession
		 */
		public function __construct(
			Vat $customerVat,
			HelperAddress $customerAddress,
			Registry $coreRegistry,
			GroupManagementInterface $groupManagement, 
			ScopeConfigInterface $scopeConfig, 
			ManagerInterface $messageManager, 
			Escaper $escaper, 
			AppState $appState, 
			CustomerSession $customerSession, 
			\Psr\Log\LoggerInterface $logger, 
			\SMSGatewayCenter\SMSAlerts\Helper\Data $helperData
		) {
			$this->_customerVat = $customerVat;
			$this->_customerAddress = $customerAddress;
			$this->_coreRegistry = $coreRegistry;
			$this->_groupManagement = $groupManagement;
			$this->scopeConfig = $scopeConfig;
			$this->messageManager = $messageManager;
			$this->escaper = $escaper;
			$this->appState = $appState;
			$this->customerSession = $customerSession;
			$this->_logger = $logger;
			$this->helperData = $helperData;
		}

		/**
		 * Client After Address Save Event Handler
		 * @param \Magento\Framework\Event\Observer $observer
		 * @return void
		 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
		 */
		public function execute(\Magento\Framework\Event\Observer $observer) {
			/** @var $customerAddress Address */
			$customerAddress = $observer->getCustomerAddress();
			$telephone = $customerAddress->getTelephone();
			$textMsg = $this->helperData->getAdminSmsConfig('sgcClientAddressTextMsg');
			$this->helperData->sgcSmsCurl($telephone, $textMsg);
		}

	}
