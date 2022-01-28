<?php
namespace Elemes\DataPrivacy\Block\Customer;

use  Elemes\DataPrivacy\Helper\Data;
use \Magento\Framework\View\Element\Template\Context;

class Index extends \Magento\Framework\View\Element\Template
{
    protected $_helper;

    public function __construct(
        Context $context,
        Data $_helper
    ) {
        $this->_helper = $_helper;
        parent::__construct($context);
    }

    public function getModuleStatus() {
        return $this->_helper->getIsModuleEnable();
    }

    public function getFields() {
        return $this->_helper->getModalRangesDecode();
    }

}

