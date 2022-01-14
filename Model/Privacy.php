<?php
namespace Elemes\DataPrivacy\Model;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Elemes\DataPrivacy\Helper\Data;

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
    protected $_helper;

    public function __construct(
        CustomerRepositoryInterface $customerRepositoryInterface,
        Json $json,
        Data $helper
    ) {
        $this->customerRepositoryInterface = $customerRepositoryInterface;
        $this->json = $json;
        $this->_helper = $helper;
    }

    /**
     * @param  $customerId int
     * @return mixed
     */
    public function getCustomerDataPrivacy($customerId = null)
    {
        if (empty($customerId)) {
            return false;
        }
        $customer = $this->customerRepositoryInterface->getById($customerId);
        $customerAttributeData = $customer->__toArray();
        if (!empty($customerAttributeData['custom_attributes']['data_privacy']['value'])) {
            return $this->json->unserialize($customerAttributeData['custom_attributes']['data_privacy']['value']);
        }
    }

    public function setCustomerDataPrivacy($customerId, $data)
    {
        if (empty($customerId)) {
            return false;
        }

        print_r($data);
        die;
    }

    /**
     * @param bool $serialize bool
     * @return mixed
     */
    public function getRanges($serialize = true)
    {
        if ($serialize) {
            return $this->json->serialize($this->_helper->getModalRanges());
        }
        return $this->json->unserialize($this->_helper->getModalRanges());
    }

}
