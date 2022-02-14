<?php
namespace Elemes\DataPrivacy\Helper;

use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Serialize\SerializerInterface;

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
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * Data constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        SerializerInterface $serializer
    ) {
        $this->_scopeConfig = $scopeConfig;
        $this->_storeManager = $storeManager;
        $this->serializer = $serializer;
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

    /**
     * @return array
     */
    public function getModalRangesDecode()
    {
        return $this->serializer->unserialize($this->_scopeConfig->getValue(self::MODULE_PATH.'ranges',ScopeInterface::SCOPE_STORE));
    }

    /**
     * @return array
     */
    public function getValueStandard() {
        $values = $this->getModalRangesDecode();
        foreach ($values as $value => $key) {
            $standard[$key['label']] = $key['value'];
        }
        return $standard;
    }

    public function setChecked($field, $data) {
        $checked = '';
        if(array_key_exists($field,$data)) {
            if($data[$field]) {
                $checked =  'checked';
            }
        }
        return $checked;
    }

    public function responseFormatBodyApi($success, $message) {
        return ['success' => $success, 'message' => $message];
    }
}
