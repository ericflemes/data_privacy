<?php

namespace Elemes\DataPrivacy\Controller\Customer;

use Elemes\DataPrivacy\Model\Privacy;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\Action;
use \Magento\Framework\View\Result\PageFactory;
use Magento\Customer\Model\Session;
use Elemes\DataPrivacy\Model\Elemes\DataPrivacy\Model;


class Save extends Action {

    const URL =  'privacy/customer/';

    protected $_request;
    /**
     * @var Session
     */
    protected $_customerSession;
    /**
     * @var PageFactory
     */
    protected $pageFactory;
    /**
     * @var Privacy
     */
    protected $privacy;

    /**
     * Data constructor.
     * @param Context $context
     * @param PageFactory $pageFactory
     * @param Session $customerSession
     * @param Privacy $privacy
     */
    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        Session $customerSession,
        Privacy $privacy
    ) {

        $this->pageFactory = $pageFactory;
        $this->_customerSession = $customerSession;
        $this->privacy =  $privacy;
        return parent::__construct($context);
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function execute() {

        $post = $this->_request->getParams();
        $entity_id = $this->_customerSession->getCustomer()->getId();
        $resultRedirect = $this->resultRedirectFactory->create();

        try {

            if (empty($entity_id)) {
                throw new Exception("Customer haven't entity id");
            }

            $this->privacy->setDataPrivacy($post, $entity_id);
            $this->messageManager->addSuccess(__('Thanks!'));
            $resultRedirect->setUrl($this->_redirect(self::URL));

        } catch (Exception $e) {

            $this->messageManager->addError($e->getMessage());
            $resultRedirect->setUrl($this->_redirect(self::URL));
        }

    }

}
