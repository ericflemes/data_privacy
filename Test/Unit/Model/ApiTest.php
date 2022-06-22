<?php
declare(strict_types=1);

namespace Elemes\DataPrivacy\Test\Unit\Model;

use Exception;
use Elemes\DataPrivacy\Helper\Data;
use Elemes\DataPrivacy\Model\Privacy;
use Magento\Framework\Serialize\Serializer\Json;
use PHPUnit\Framework\TestCase;
use Elemes\DataPrivacy\Model\Api\Custom;
use \Magento\Framework\Webapi\Rest\Request;
use Psr\Log\LoggerInterface;

class ApiTest extends TestCase {

    /**
     * @var Custom $model
     */
    private $model;

    /**
     * @var  Privacy $privacyMock
     */
    private $privacyMock;

    /**
     * @var Json $jsonMock
     */
    private $jsonMock;

    /**
     * @var LoggerInterface $loggerMock
     */
    private $loggerMock;

    /**
     * @var Request $requestMock
     */
    private $requestMock;

    /**
     * @var Data $helperMock
     */
    private $helperMock;

    /**
     * @inheritdoc
     */
    protected function setUp(): void
    {
        $this->loggerMock = $this->createMock(LoggerInterface::class);
        $this->jsonMock = $this->createMock(Json::class);
        $this->privacyMock = $this->createMock(Privacy::class);
        $this->helperMock = $this->createMock(Data::class);
        $this->requestMock = $this->createMock(Request::class);
        $this->model = new Custom(
            $this->loggerMock,
            $this->jsonMock,
            $this->privacyMock,
            $this->requestMock,
            $this->helperMock
        );
    }

    /**
     * Test SetData
     */
    public function testSetData()
    {
        $this->requestMock->expects($this->once())
            ->method('getBodyParams')
            ->willReturn($this->getParamMock());
        $this->helperMock->expects($this->once())
            ->method('validateDataPrivacy')
            ->willReturn('');
        $this->privacyMock->expects($this->any())
            ->method('setDataPrivacy')
            ->willReturn(true);
        $this->helperMock->expects($this->once())
            ->method('responseFormatBodyApi')
            ->willReturn($this->getSuccess());
        $this->jsonMock->expects($this->once())
            ->method('serialize')
            ->willReturn($this->getSuccessJson());
        $this->assertSame($this->getSuccessJson(),$this->model->setData());
    }

    /**
     * Test SetData with Error.
     */
    public function testSetDataWithError()
    {
        $this->requestMock->expects($this->once())
            ->method('getBodyParams')
            ->willReturn($this->getParamMock());
        $this->helperMock->expects($this->once())
            ->method('validateDataPrivacy')
            ->willReturn('');
        $this->privacyMock->expects($this->any())
            ->method('setDataPrivacy')
            ->willReturn(false);
        $this->helperMock->expects($this->once())
            ->method('responseFormatBodyApi')
            ->willReturn($this->getSuccess());
        $this->jsonMock->expects($this->once())
            ->method('serialize')
            ->willReturn($this->getErrorJson());
        $this->assertSame($this->getErrorJson(),$this->model->setData());
    }

    /**
     * Test getData
     */
    public function testGetData()
    {
        $this->privacyMock->expects($this->any())
            ->method('getCustomerDataPrivacy')
            ->willReturn($this->setValueData());
        $this->helperMock->expects($this->any())
            ->method('responseFormatBodyApi')
            ->willReturn($this->getValueData());
        $this->jsonMock->expects($this->once())
            ->method('serialize')
            ->willReturn($this->getValueDataJson());

       $this->assertSame($this->getValueDataJson(),$this->model->getData($this->setValueData()));

    }

    /**
     * Test getData with Error.
     */
   public function testGetDataError()
    {
        $this->privacyMock->expects($this->any())
            ->method('getCustomerDataPrivacy')
            ->willThrowException(
                new Exception('Error.')
            );
        $this->helperMock->expects($this->any())
            ->method('responseFormatBodyApi')
            ->willReturn($this->getError());
        $this->jsonMock->expects($this->once())
            ->method('serialize')
            ->willReturn($this->getErrorJson());
       $this->assertSame($this->getErrorJson(),$this->model->getData($this->setValueData()));
    }


    /**
     * Test getConfigPrivacy.
     */
    public function testgetConfigPrivacy() {
        $this->privacyMock->expects($this->any())
            ->method('getRanges')
            ->willReturn($this->getConfigValue());
        $this->helperMock->expects($this->any())
            ->method('responseFormatBodyApi')
            ->willReturn($this->getConfigValue());
        $this->jsonMock->expects($this->once())
            ->method('serialize')
            ->willReturn($this->getConfigValueResposeJson());
        $this->assertSame($this->getConfigValueResposeJson(),$this->model->getConfigPrivacy());
    }

    /**
     * Test getConfigPrivacy with Error.
     */
    public function testgetConfigPrivacyError() {
        $this->privacyMock->expects($this->any())
            ->method('getRanges')
            ->willThrowException(
                new Exception('Error.')
            );
        $this->helperMock->expects($this->any())
            ->method('responseFormatBodyApi')
            ->willReturn('');
        $this->jsonMock->expects($this->once())
            ->method('serialize')
            ->willReturn($this->getError());
        $this->assertSame($this->getError(),$this->model->getConfigPrivacy());
    }

    /**
     * Param data provider.
     *
     * @return array
     */
    private function getParamMock()
    {
        $params['data']['customer_privacy']['test'] = 1;
        $params['data']['customerId'] = 1;
        return $params;
    }

    /**
     * data provider.
     *
     * @return mixed
     */
    private function getParamMockJson()
    {
        return json_encode($this->getParamMock());
    }

    /**
     * Success data provider.
     *
     * @return array
     */
    private function getSuccess()
    {
        return ['success' => true, 'message' => 'save'];
    }

    /**
     * Success data provider.
     *
     * @return mixed
     */
    private function getSuccessJson()
    {
        return json_encode($this->getSuccess());
    }

    /**
     * Error data provider.
     *
     * @return array
     */
    private function getError()
    {
        return ['success' => false, 'message' => 'Error'];
    }

    /**
     * Erro data provider.
     *
     * @return mixed
     */
    private function getErrorJson()
    {
        return json_encode($this->getError());
    }

    /**
     * data provider.
     *
     * @return array
     */
    private function setValueData() {
        return array (
            'push' => 1,
            'cpf' => 1,
            'telefone' => 1,
            'email' => 0,
        );
    }

    /**
     * data provider.
     *
     * @return array
     */
    private function getValueData() {
        return array (
            'success' => true,
            'message' => $this->setValueData()
        );
    }

    /**
     * data provider.
     *
     * @return mixed
     */
    private function getValueDataJson() {
        return json_encode($this->getValueData());
    }

    private function getConfigValue() {
        return array (
            '_1655240255533_533' =>
                array (
                    'name' => 'Name',
                    'label' => 'name',
                    'value' => '1',
                ),
            '_1655240263529_529' =>
                array (
                    'name' => 'E-mail',
                    'label' => 'email',
                    'value' => '1',
                ),
            '_1655240280054_54' =>
                array (
                    'name' => 'Phone',
                    'label' => 'phone',
                    'value' => '1',
                ),
        );
    }

    private function getConfigValueRespose() {
        return array (
            'success' => true,
            'message' => $this->getConfigValue()
        );
    }

    private function getConfigValueResposeJson() {
        return json_encode($this->getConfigValueRespose());
    }
}
