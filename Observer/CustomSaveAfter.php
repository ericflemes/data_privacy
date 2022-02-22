<?php
namespace Elemes\DataPrivacy\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Elemes\DataPrivacy\Helper\Data;
use Magento\Framework\Serialize\SerializerInterface;
use \Magento\Framework\Message\ManagerInterface;
use \Magento\Framework\App\RequestInterface;
use \Magento\Framework\Event\Observer;

class CustomSaveAfter implements ObserverInterface
{
    /**
     * @var RequestInterface
     */
    protected $_request;
    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;
    /**
     * @var Data
     */
    protected $_helper;
    /**
     * @var ManagerInterface
     */
    protected $messageManager;
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @param RequestInterface $request
     * @param CustomerRepositoryInterface $customerRepository
     * @param Data $_helper
     * @param SerializerInterface $serializer
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        RequestInterface $request,
        CustomerRepositoryInterface $customerRepository,
        Data $_helper,
        SerializerInterface $serializer,
        ManagerInterface $messageManager
    ) {
        $this->_request = $request;
        $this->customerRepository = $customerRepository;
        $this->_helper = $_helper;
        $this->serializer = $serializer;
        $this->messageManager = $messageManager;
    }

    /**
     * @param  $observer object
     * @return bool
     * @throws Exception
     */
    public function execute(Observer $observer) {
        try {
           $param = $this->_request->getParams();
           $param = $this->setValueStandard($param);

           if(empty($param['customer_privacy'])) {
               return false;
           }

           $customer = $observer->getEvent()->getCustomer();
           $data_privacy = $this->serializer->serialize($param['customer_privacy']);
           $customer->setCustomAttribute('data_privacy', $data_privacy);
           $this->customerRepository->save($customer);

        } catch (Exception $e) {
            $this->messageManager->addError(__("Error save data privacy, try later").' '.$e->getMessage());
        }
    }

    /**
     * @param  $param array
     * @return array
     */
    public function setValueStandard($param) {

        if(!empty($param['dataPrivacy'])) {
            $param['customer_privacy'] = $this->_helper->getValueStandard();
        }
        return $param;
    }
}
