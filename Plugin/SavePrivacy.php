<?php
namespace Elemes\DataPrivacy\Plugin;

use Elemes\DataPrivacy\Helper\Data;
use \Magento\Framework\App\RequestInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Elemes\DataPrivacy\Model\Privacy;

class SavePrivacy
{
    const attribute = 'data_privacy';
    /**
     * @var Magento\Customer\Api\CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var \Elemes\DataPrivacy\Helper\Data
     */
    protected $helper;
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;
    /**
     * @var Elemes\DataPrivacy\Model\Privacy
     */
    protected $privacy;
    /**
     * @var \Magento\Customer\Model\ResourceModel\Customer
     */
    protected $resourceModel;

    /**
     * @param Data $helper
     * @param RequestInterface $request
     * @param CustomerRepositoryInterface $customerRepository
     * @param Privacy $privacy
     */
    public function __construct(
        Data $helper,
        RequestInterface $request,
        CustomerRepositoryInterface $customerRepository,
        Privacy $privacy
    ) {
        $this->helper = $helper;
        $this->request = $request;
        $this->customerRepository = $customerRepository;
        $this->privacy = $privacy;
    }

    /**
     * @param $customer \Magento\Customer\Model\Data\Customer
     * @param $interceptor
     * @return object
     */
    public function afterCreateAccount($interceptor, \Magento\Customer\Model\Data\Customer $customer) {
        $param = $this->getParam();
        if(empty($param['customer_privacy'])) {
            return false;
        }

        return $this->privacy->setDataPrivacy($param, $customer);
    }

    /**
     * @param $customer \Magento\Customer\Controller\Adminhtml\Index\Save
     * @param $result
     * @return object
     */
    public function afterexecute(\Magento\Customer\Controller\Adminhtml\Index\Save $customer, $result) {
        $param = $this->getParam();
        if(empty($param['customer_privacy'])) {
            return false;
        }
        $this->privacy->setDataPrivacy($param, $customer);
        return $result;
    }

    /**
     * @param  $param array
     * @return array
     */
    public function setValueStandard($param) {

        if(!empty($param['dataPrivacy'])) {
            $param['customer_privacy'] = $this->helper->getValueStandard();
        }
        return $param;
    }

    /**
     * @return array
     */
    public function getParam() {
        $param = $this->request->getParams();
        $param = $this->setValueStandard($param);
        return $param;
    }
}
