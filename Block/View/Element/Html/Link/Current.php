<?php
namespace Elemes\DataPrivacy\Block\View\Element\Html\Link;

use Magento\Customer\Block\Account\SortLinkInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\DefaultPathInterface;
use Magento\Framework\View\Element\Template\Context;
use Elemes\DataPrivacy\Helper\Data;

/**
 * Class Current
 * @package Codextblog\Demo\Block\View\Element\Html\Link
 */
class Current extends \Magento\Framework\View\Element\Html\Link\Current implements SortLinkInterface
{
    /**
     * @var CustomerSession
     */
    protected $_customerSession;
    /**
     * @var Data
     */
    protected $helper;

    /**
     * Current constructor.
     * @param Context $context
     * @param DefaultPathInterface $defaultPath
     * @param CustomerSession $CustomerSession
     * @param Data $helper
     * @param array $data
     */
    public function __construct(
        Context $context,
        DefaultPathInterface $defaultPath,
        CustomerSession $CustomerSession,
        Data $helper,
        array $data = []
    ) {
        $this->_defaultPath = $defaultPath;
        $this->_customerSession = $CustomerSession;
        $this->helper = $helper;
        parent::__construct($context, $defaultPath);
    }

    protected function _toHtml() {
        if ($this->helper->getIsModuleEnable()) {
            return parent::_toHtml();
        } else {
            return null;
        }

    }

    public function getSortOrder() {
        return $this->getData(self::SORT_ORDER);
    }
}
