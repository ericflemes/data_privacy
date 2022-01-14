<?php
namespace Elemes\DataPrivacy\Helper;

use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\StoreManagerInterface;

class Data extends AbstractHelper
{
    const MODULE_PATH = 'elemes_data_privacy/general/';

    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Data constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager
    ) {
        $this->_scopeConfig = $scopeConfig;
        $this->_storeManager = $storeManager;
    }

    /**
     * @return mixed
     */
    public function getIsModuleEnable()
    {
        return $this->_scopeConfig->getValue(self::MODULE_PATH.'enable',ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return mixed
     */
    public function getModalRanges()
    {
        return $this->_scopeConfig->getValue(self::MODULE_PATH.'ranges',ScopeInterface::SCOPE_STORE);
    }
}
