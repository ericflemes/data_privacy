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
    protected $helper;

    /**
     * @param Data $helper
     * @param Curl $curl
     */
    public function __construct(
        Data $helper,
        Curl $curl
    ) {
        $this->helper = $helper;
        $this->curl = $curl;
    }
    public function setIntegration($param,$customerId) {

        if($this->helper->getIntegrationEnable() != false) {
            if (!empty($customerId)) {
                try {
                    $this->curl->setOption(CURLOPT_HEADER, 0);
                    $this->curl->setOption(CURLOPT_TIMEOUT, 60);
                    $this->curl->addHeader("Content-Type", "application/json");
                    $data = $this->formatBody($param, $customerId);
                    $this->curl->post($this->setURL(), $data);
                    return $this->curl->getBody();
                } catch (Exception $e) {
                    return false;
                }
            }
        }
        return  false;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function setURL() {
        if (!empty($this->helper->getIntegrationUrl())) {
            return $this->helper->getIntegrationUrl();
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
