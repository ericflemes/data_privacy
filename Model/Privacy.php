<?php
namespace Elemes\DataPrivacy\Model;

use Exception;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Elemes\DataPrivacy\Helper\Data;
use Elemes\DataPrivacy\Helper\Integration;
use \Magento\Customer\Model\Customer;
use \Magento\Customer\Model\ResourceModel\CustomerFactory;
use Magento\Framework\Serialize\SerializerInterface;

class Privacy
{
    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $customerRepositoryInterface;
    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    protected $json;
    /**
     * @var \Elemes\DataPrivacy\Helper\Data
     */
    protected $helper;
    /**
     * @var Integration
     */
    protected $integration;
    /**
     * @var \Magento\Customer\Model\Customer
     */
    protected $customer;
    /**
     * @var \Magento\Customer\Model\ResourceModel\CustomerFactory
     */
    protected $customerFactory;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @param CustomerRepositoryInterface $customerRepositoryInterface
     * @param Json $json
     * @param Data $helper
     * @param Integration $integration
     * @param Customer $customer
     * @param CustomerFactory $customerFactory
     * @param SerializerInterface $serializer
     */
    public function __construct(
        CustomerRepositoryInterface $customerRepositoryInterface,
        Json $json,
        Data $helper,
        Integration $integration,
        Customer $customer,
        CustomerFactory $customerFactory,
        SerializerInterface $serializer
    ) {
        $this->customerRepositoryInterface = $customerRepositoryInterface;
        $this->json = $json;
        $this->helper = $helper;
        $this->integration = $integration;
        $this->customer = $customer;
        $this->customerFactory = $customerFactory;
        $this->serializer = $serializer;
    }

    /**
     * @param  $customerId int
     * @return mixed
     */
    public function getCustomerDataPrivacy($customerId = null) {
        if (empty($customerId)) {
            return false;
        }

        $customer = $this->customerRepositoryInterface->getById($customerId);
        $customerAttributeData = $customer->__toArray();

        if (!empty($customerAttributeData['custom_attributes']['data_privacy']['value'])) {
            return $this->json->unserialize($customerAttributeData['custom_attributes']['data_privacy']['value']);
        }
    }

    /**
     * @param bool $serialize bool
     * @return mixed
     */
    public function getRanges($serialize = true) {
        if ($serialize) {
            return $this->json->serialize($this->helper->getModalRanges());
        }
        return $this->json->unserialize($this->helper->getModalRanges());
    }

    /**
     * @param  $customerId int
     * @param  $data array
     * @return bool
     * @throws Exception
     */
    public function setDataPrivacy($param, $customer) {

        if(empty($param['customer_privacy'])) {
            return false;
        }

        try {
            $customerId = $this->getCustomerId($param, $customer);
            $customerData = $this->customer->load($customerId);
            $this->setPrivacy($customerData, $param['customer_privacy']);
            $this->integration->setIntegration($param['customer_privacy'],$customerId);
            return $customer;
        } catch ( Exception $e) {
            return false;
        }
    }

    /**
     * @param Customer $customer
     * @param $customer_privacy
     * @return Customer $customer
     * @throws Exception
     */
    public function setPrivacy(Customer $customer, $customer_privacy) {
        $customerData = $customer->getDataModel();
        $data_privacy = $this->serializer->serialize($customer_privacy);
        $customerData->setCustomAttribute('data_privacy', $data_privacy);
        $customer->updateData($customerData);
        $customer->save();
        return $customer;
    }

    /**
     * @param $customer
     * @return mixed|string
     */
    public function getCustomerId($param = null, $customer) {
        if (is_object($customer)) {
            if(empty($param['customer_id'])) {
                $customerId = $customer->getId();
            } else {
                $customerId = $param['customer_id'];
            }
        } else {
            $customerId = $customer;
        }
        return $customerId;
    }
}
