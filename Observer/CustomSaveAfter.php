<?php
namespace Elemes\DataPrivacy\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Elemes\DataPrivacy\Helper\Data;
use Magento\Framework\Serialize\SerializerInterface;

class CustomSaveAfter implements ObserverInterface
{

    protected $_request;
    protected $customer;
    protected $customerRepository;
    protected $_helper;
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        CustomerRepositoryInterface $customerRepository,
        Data $_helper,
        SerializerInterface $serializer
    ) {
        $this->_request = $request;
        $this->customerRepository = $customerRepository;
        $this->_helper = $_helper;
        $this->serializer = $serializer;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        try {
           $param = $this->_request->getParams();
           $param = $this->setValueStandard($param);
           if(empty($param['customer_privacy'])) {
               return false;
           }

           $data_privacy = $this->serializer->serialize($param['customer_privacy']);
           $customer = $observer->getEvent()->getCustomer();
           $customer->setCustomAttribute('data_privacy', $data_privacy);
           $this->customerRepository->save($customer);

        } catch (\Exception $e) {
        }
    }

    public function setValueStandard($param) {

        if(!empty($param['dataPrivacy'])) {
            $param['customer_privacy'] = $this->_helper->getValueStandard();
        }
        return $param;
    }
}
