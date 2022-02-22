<?php
namespace Elemes\DataPrivacy\Controller\Customer;

use \Magento\Framework\App\Action\Action;
use \Magento\Framework\App\Action\Context;
use \Magento\Framework\View\Result\PageFactory;
use Magento\Customer\Model\Session;

class Index extends Action
{
    /**
     * @var PageFactory
     */
    protected $pageFactory;
    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * Data constructor.
     * @param Context $context
     * @param Session $customerSession
     * @param PageFactory $pageFactory
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        PageFactory $pageFactory
    ) {
        $this->pageFactory = $pageFactory;
        $this->customerSession = $customerSession;
        return parent::__construct($context);
    }

    /**
     * @return object
     */
    public function execute() {

        $resultRedirect = $this->resultRedirectFactory->create();
        if (!$this->customerSession->isLoggedIn()) {

            $resultRedirect->setPath('customer/account/login');
            return $resultRedirect;
        }

        $resultPage = $this->pageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('Data Privacy'));
        return $resultPage;
    }
}
