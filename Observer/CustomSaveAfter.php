<?php
namespace Elemes\DataPrivacy\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;

class CustomSaveAfter implements ObserverInterface
{

    protected $_request;
    protected $customer;
    protected $customerRepository;

    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->_request = $request;
        $this->customerRepository = $customerRepository;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        try {
           $param = $this->_request->getParams();
           if(empty($param['customer_privacy'])) {
               return false;
           }

           $data_privacy =  json_encode($param['customer_privacy']);
           $customer = $observer->getEvent()->getCustomer();
           $customer->setCustomAttribute('data_privacy', $data_privacy);
           $this->customerRepository->save($customer);

        } catch (\Exception $e) {
        }
    }
}
