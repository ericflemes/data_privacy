<?php

namespace Elemes\DataPrivacy\Model\Api;

use Elemes\DataPrivacy\Model\Privacy;
use Psr\Log\LoggerInterface;
use Magento\Framework\Serialize\Serializer\Json;
use \Magento\Framework\Webapi\Rest\Request;

class Custom
{
    protected $logger;
    protected $privacy;
    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    protected $json;

    /**
     * @var \Magento\Framework\Webapi\Rest\Request
     */
    protected $request;

    public function __construct(
        LoggerInterface $logger,
        Json $json,
        Privacy $privacy,
        Request $request

    )
    {
        $this->logger = $logger;
        $this->json = $json;
        $this->privacy = $privacy;
        $this->request = $request;
    }

    /**
     * @inheritdoc
     */

    public function setData()
    {
        $params = $this->request->getBodyParams();

        $value = $this->privacy->setCustomerDataPrivacy($params['customerId'], $params['data']);
        try {
            // Your Code here
            $response = ['success' => true, 'message' => _('Saved')];
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => $e->getMessage()];
            $this->logger->info($e->getMessage());
        }
        return $this->json->serialize($response);
    }

    public function getData($value)
    {
        $value = $this->privacy->getCustomerDataPrivacy($value['customerId']);
        try {
            // Your Code here
            $response = ['success' => true, 'message' => $value];
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => $e->getMessage()];
            $this->logger->info($e->getMessage());
        }
        return $this->json->serialize($response);
    }

    public function getConfigPrivacy()
    {
        try {
            $value = $this->privacy->getRanges(false);
            $response = ['success' => true, 'message' => $value];
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => $e->getMessage()];
            $this->logger->info($e->getMessage());
        }
        return $this->json->serialize($response);
    }
}
