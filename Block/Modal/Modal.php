<?php
namespace Elemes\DataPrivacy\Block\Modal;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Elemes\DataPrivacy\Helper\Data;

class Modal extends Template
{
    /**
     * @var Data
     */
    protected $helper;

    public function __construct(
        Context $context,
        Data $helper,
        array $data = [])
    {
        $this->helper = $helper;
        parent::__construct($context, $data);
    }

    /**
     * @return mixed
     */
    public function getModuleStatus() {
        return $this->helper->getIsModuleEnable();
    }

    /**
     * @return mixed
     */
    public function getEnableCookies() {
        return $this->helper->getEnableCookies();
    }

}
