<?php
namespace Elemes\DataPrivacy\Block\Customer;

use  Elemes\DataPrivacy\Helper\Data;
use \Magento\Framework\View\Element\Template\Context;
use \Magento\Framework\View\Element\Template;
use Elemes\DataPrivacy\Model\Privacy;
use Magento\Customer\Model\Session;

class Index extends Template
{

    /**
     * @var Data
     */
    protected $_helper;
    /**
     * @var Privacy
     */
    protected $privacy;
    /**
     * @var Session
     */
    protected $customerSession;
    /**
     * @const URL Form
     */
    const FORM = '/privacy/customer/save';

    /**
     * Data constructor.
     * @param Context $context
     * @param Data $_helper
     * @param Session $customerSession
     * @param Privacy $privacy
     */
    public function __construct (
        Context $context,
        Data $_helper,
        Session $customerSession,
        Privacy $privacy
    ) {
        $this->_helper = $_helper;
        $this->customerSession = $customerSession;
        $this->privacy = $privacy;
        parent::__construct($context);
    }

    /**
     * @return mixed
     */
    public function getModuleStatus() {
        return $this->_helper->getIsModuleEnable();
    }

    /**
     * @return array
     */
    public function getFields() {
        return $this->_helper->getModalRangesDecode();
    }

    /**
     * @return false|mixed
     */
    public function getPrivacy() {
        return $this->privacy->getCustomerDataPrivacy($this->customerSession->getCustomerId());
    }

    /**
     * @param $field
     * @param $data
     * @return string
     */
    public function getChecked($field, $data) {
        return  $this->_helper->setChecked($field, $data);
    }

    /**
     * @return string
     */
    public function getFormAction() {
        return self::FORM;
    }

}

