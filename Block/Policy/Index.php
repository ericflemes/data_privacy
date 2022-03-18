<?php
namespace Elemes\DataPrivacy\Block\Policy;

use  Elemes\DataPrivacy\Helper\Data;
use \Magento\Framework\View\Element\Template\Context;
use \Magento\Framework\View\Element\Template;

/**
 * Class Index
 * @package Elemes\DataPrivacy\Block\Policy
 */
class Index extends Template
{
    /**
     * @var Data
     */
    protected $helper;
    /**
     * @const URL Form
     */
    const FORM = '/privacy/customer/save';

    /**
     * Data constructor.
     * @param Context $context
     * @param Data $helper
     */
    public function __construct (
        Context $context,
        Data $helper
    ) {
        $this->helper = $helper;
        parent::__construct($context);
    }

    /**
     * @return mixed
     */
    public function getPolicy() {
        return $this->helper->getTextPolicy();
    }
}
