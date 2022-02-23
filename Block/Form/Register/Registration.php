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
    protected $helper;

    /**
     * @var  Context
     */
    protected $context;

    /**
     * Data constructor.
     * @param Context $context
     * @param Data $helper
     */
    public function __construct(
        Context $context,
        Data $helper
    ) {
        $this->helper = $helper;
        parent::__construct($context);
    }

    /**
     * @return bool
     */
    public function getModuleStatus() {
        return $this->helper->getIsModuleEnable();
    }

    /**
     * @return array
     */
    public function getFields() {
        return $this->helper->getModalRangesDecode();
    }
}
