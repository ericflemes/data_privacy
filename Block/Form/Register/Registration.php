<?php
namespace Elemes\DataPrivacy\Block\Form\Register;

use  Elemes\DataPrivacy\Helper\Data;
use \Magento\Framework\View\Element\Template\Context;

class Registration extends \Magento\Framework\View\Element\Template
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
}
