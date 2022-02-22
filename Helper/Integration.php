<?php
namespace Elemes\DataPrivacy\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\HTTP\Client\Curl;

class Integration extends AbstractHelper
{
    /**
     * @var Curl
     */
    protected $curl;
    /**
     * @var Data
     */
    protected $_helper;

    /**
     * @param Data $_helper
     * @param Curl $curl
     */
    public function __construct(
        Data $_helper,
        Curl $curl
    ) {
        $this->_helper = $_helper;
        $this->curl = $curl;
    }
    public function setIntegration($param,$customerId) {

        if($this->_helper->getIntegrationEnable() != false) {
            try {
                $this->curl->setOption(CURLOPT_HEADER, 0);
                $this->curl->setOption(CURLOPT_TIMEOUT, 60);
                $this->curl->addHeader("Content-Type", "application/json");
                $data = $this->formatBody($param, $customerId);
                $this->curl->post($this->setURL(),$data);
                return $this->curl->getBody();
            } catch (Exception $e) {
                return  false;
            }
        }
        return  false;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function setURL() {
        if (!empty($this->_helper->getIntegrationUrl())) {
            return $this->_helper->getIntegrationUrl();
        } else {
            throw new Exception('Url not informed');
        }
    }

    /**
     * @param $param object
     * @param $customerId int
     * @return array
     */
    public function formatBody($param, $customerId) {
        return array_merge((array) json_decode($param),['customerId'=> $customerId]);
    }
}
