<?php
namespace Elemes\DataPrivacy\Block\Customer;

use  Elemes\DataPrivacy\Helper\Data;
use \Magento\Framework\View\Element\Template\Context;
use \Magento\Framework\View\Element\Template;
use Elemes\DataPrivacy\Model\Privacy;
use Magento\Customer\Model\Session;

class Index extends Template
{

    protected $_helper;
    protected $privacy;
    protected $customerSession;

    const FORM = '/privacy/customer/save';

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

    public function getModuleStatus() {
        return $this->_helper->getIsModuleEnable();
    }

    public function getFields() {
        return $this->_helper->getModalRangesDecode();
    }

    public function getPrivacy() {
        return $this->privacy->getCustomerDataPrivacy($this->customerSession->getCustomerId());
    }

    public function getChecked($field, $data) {
        return  $this->_helper->setChecked($field, $data);
    }

    public function getFormAction() {
        return self::FORM;
    }

}

