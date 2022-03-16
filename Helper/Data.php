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
    protected $scopeConfig;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * Data constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param SerializerInterface $serializer
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        SerializerInterface $serializer
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->serializer = $serializer;
    }

    /**
     * @return mixed
     */
    public function getIsModuleEnable()
    {
        return $this->scopeConfig->getValue(self::MODULE_PATH.'enable',ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return mixed
     */
    public function getModalRanges()
    {
        return $this->scopeConfig->getValue(self::MODULE_PATH.'ranges',ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return mixed
     */
    public function getEnableCookies() {
        return $this->scopeConfig->getValue(self::MODULE_PATH.'enable_cookies',ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return array
     */
    public function getModalRangesDecode()
    {
        return $this->serializer->unserialize($this->getModalRanges());
    }

    /**
     * @return bool
     */
    public function getIntegrationEnable()
    {
        return $this->scopeConfig->getValue(self::MODULE_PATH.'enable_integration',ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function getIntegrationUrl()
    {
        return $this->scopeConfig->getValue(self::MODULE_PATH.'integration_url',ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return array
     */
    public function getValueStandard() {
        $standard = null;
        $values = $this->getModalRangesDecode();
        foreach ($values as $key) {
            $standard[$key['label']] = $key['value'];
        }
        return $standard;
    }

    /**
     * @return string
     */
    public function setChecked($field, $data) {
        $checked = '';
        if(array_key_exists($field,$data)) {
            if($data[$field]) {
                $checked =  'checked';
            }
        }
        return $checked;
    }

    /**
     * @return array
     */
    public function responseFormatBodyApi($success, $message) {
        return ['success' => $success, 'message' => $message];
    }

    public function validateDataPrivacy($data) {
        if($this->getIsModuleEnable()) {
            $err = $this->getErr($data);
            if(count( $err['errors']) >= 1) {
                throw new \Exception( $this->serializer->serialize($err['errors']).'Invalid value');
            }
            if(count($err['value_error'])) {
                throw new \Exception( 'values error set 1 or 0'. $this->serializer->serialize($err['value_error']));
            }
            if(count($data) < $err['total_column']) {
                throw new \Exception( 'required values'. $this->serializer->serialize($err['default']));
            }
        } else {
            throw new \Exception('Module Data Privacy disable.');
        }
    }

    /**
     * @param $data
     * @return array
     */
    public function getErr($data) {
        $column = array_column($this->getModalRangesDecode(), 'label');
        $err = [];
        $err2 = [];
        foreach ($data as $label => $value1) {
            if(($value1 > 1) || ($value1 < 0)) {
                $err2[] = $label;
            }
            if ((in_array("$label", $column) === false)) {
                $err[] = $label;
            }
        }
        return array('errors'=>$err,'total_column'=> count($column),'default'=> $column, 'value_error'=> $err2);
    }
}
