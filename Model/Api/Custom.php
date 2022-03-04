<?php
namespace Elemes\DataPrivacy\Model\Api;

use Elemes\DataPrivacy\Model\Privacy;
use Psr\Log\LoggerInterface;
use Magento\Framework\Serialize\Serializer\Json;
use \Magento\Framework\Webapi\Rest\Request;
use Elemes\DataPrivacy\Helper\Data;

class Custom
{
    /**
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * @var Privacy
     */
    protected $privacy;
    /**
     * @var Data
     */
    protected $helper;
    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    protected $json;

    /**
     * @var \Magento\Framework\Webapi\Rest\Request
     */
    protected $request;

    /**
     * @param LoggerInterface $logger
     * @param Json $json
     * @param Privacy $privacy
     * @param Request $request
     * @param Data $helper
     */
    public function __construct(
        LoggerInterface $logger,
        Json $json,
        Privacy $privacy,
        Request $request,
        Data $helper
    ) {
        $this->logger = $logger;
        $this->json = $json;
        $this->privacy = $privacy;
        $this->request = $request;
        $this->helper = $helper;
    }

    /**
     * @return mixed
     */
    public function setData() {
        $params = $this->request->getBodyParams();

        try {
            $this->helper->validateDataPrivacy($params['data']['customer_privacy']);
            $save = $this->privacy->setDataPrivacy($params['data'], $params['customerId']);
            if($save == true) {
                $response = $this->helper->responseFormatBodyApi(true,__('Saved'));
            } else {
                throw new \Exception('Error');
            }
        } catch (\Exception $e) {
            $response = $this->helper->responseFormatBodyApi(false, $e->getMessage());
            $this->logger->info($e->getMessage());
        }
        return $this->json->serialize($response);
    }

    /**
     * @param $value int
     * @return mixed
     */
    public function getData($value) {
        try {
            $value = $this->privacy->getCustomerDataPrivacy($value);
            $response = $this->helper->responseFormatBodyApi(true, $value);
        } catch (\Exception $e) {
            $response = $this->helper->responseFormatBodyApi(false, $e->getMessage());
            $this->logger->info($e->getMessage());
        }
        return $this->json->serialize($response);
    }

    /**
     * @return mixed
     */
    public function getConfigPrivacy() {
        try {
            $value = $this->privacy->getRanges(false);
            $response = $this->helper->responseFormatBodyApi(true, $value);
        } catch (\Exception $e) {
            $response = $this->helper->responseFormatBodyApi(false, $e->getMessage());
            $this->logger->info($e->getMessage());
        }
        return $this->json->serialize($response);
    }
}
