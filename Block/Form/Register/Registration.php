<?php
namespace Elemes\DataPrivacy\Block\Form\Register;

use  Elemes\DataPrivacy\Helper\Data;
use \Magento\Framework\View\Element\Template\Context;
use \Magento\Framework\View\Element\Template;

class Registration extends Template
{
    /**
     * @var Data
     */
    protected $_helper;

    /**
     * @var  Context
     */
    protected $context;

    /**
     * Data constructor.
     * @param Context $context
     * @param Data $_helper
     */
    public function __construct(
        Context $context,
        Data $_helper
    ) {
        $this->_helper = $_helper;
        parent::__construct($context);
    }

    /**
     * @return bool
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
}
