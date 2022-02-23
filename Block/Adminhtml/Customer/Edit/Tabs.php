<?php
namespace Elemes\DataPrivacy\Block\Adminhtml\Customer\Edit;

use Magento\Customer\Controller\RegistryConstants;
use Magento\Ui\Component\Layout\Tabs\TabInterface;
use Magento\Backend\Block\Widget\Form;
use Magento\Backend\Block\Widget\Form\Generic;
use Elemes\DataPrivacy\Helper\Data;
use Elemes\DataPrivacy\Model\Privacy;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Store\Model\System\Store;
/**
 * Customer account form block
 */
class Tabs extends Generic implements TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $systemStore;
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;
    /**
     * @var \Elemes\DataPrivacy\Helper\Data
     */
    protected $helper;
    /**
     * @var \Magento\Framework\Data\FormFactory
     */
    protected $resultPageFactory;
    /**
     * @var \Elemes\DataPrivacy\Model\Privacy
     */
    protected $privacy;

    const label = 'label';
    const name = 'name';
    const value = 'value';

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param FormFactory $formFactory
     * @param Store $systemStore
     * @param Privacy $privacy
     * @param Data $helper
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Store $systemStore,
        Privacy $privacy,
        Data $helper,
        array $data = []
    ) {
        $this->coreRegistry = $registry;
        $this->systemStore = $systemStore;
        $this->helper = $helper;
        $this->privacy = $privacy;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @return string|null
     */
    public function getCustomerId()
    {
        return $this->coreRegistry->registry(RegistryConstants::CURRENT_CUSTOMER_ID);
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Data Privacy');
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Data Privacy');
    }

    /**
     * @param array $valueCustomer
     * @param array $valuesForm
     * @return mixed
     */
    public function getFormCustomer( $valueCustomer,  $valuesForm)
    {
        foreach ($valueCustomer as $key => $value) {
            foreach ($valuesForm as $value2) {
                if ($value2[self::label] == $key) {
                    unset($valueCustomer[$key]);
                }
            }
        }
        if(count($valueCustomer)>= 1) {
            foreach ($valueCustomer as $key => $item)
            {
                $formFilds["customer"][self::name]  = $key;
                $formFilds["customer"][self::value] = $item;
                $formFilds["customer"][self::label] = ucwords($key);
            }
            return $formFilds;
        }
        return false;
    }

    /**
     * @param array $valueCustomer
     * @param array $field
     * @return array
     */
    public function selectValue(array $valueCustomer, array $field)
    {
        foreach ($valueCustomer as $value => $key) {
            if ($value == $field[self::label]) {
                $field[self::value] = $key;
            }
        }
        return $field;
    }

    /**
     * @return \Magento\Framework\Data\Form
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function form()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('user_');
        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Data Privacy')]);

        if ($this->helper->getIsModuleEnable()) {

            $valueCustomer = $this->privacy->getCustomerDataPrivacy($this->getCustomerId());
            $valuesForm = $this->privacy->getRanges(false);

            if(!empty($valueCustomer)) {
                $formCustomer = $this->getFormCustomer($valueCustomer, $valuesForm);
                if($formCustomer) {
                    $valuesForm = array_merge($valuesForm, $formCustomer);
                }
            }

            foreach ($valuesForm as $field) {
                if(!empty($valueCustomer)) {
                    $field = $this->selectValue($valueCustomer, $field);
                }
                $fieldset->addField(
                    $field[self::name],
                    'select',
                    [
                        'name' => "customer_privacy[".$field[self::label]."]",
                        'data-form-part' => 'customer_form',
                        'label' => $field[self::label],
                        'title' => $field[self::label],
                        'value' => $field[self::value],
                        'values' => array(
                            array(
                                'value' => 1,
                                'label' => __('Yes')
                            ),
                            array(
                                'value' => 0,
                                'label' => __('No')
                            )
                        )
                    ]
                );
            }


        }
        return $form;
    }

    /**
     * @return bool
     */
    public function canShowTab()
    {
        if ($this->getCustomerId() && ($this->helper->getIsModuleEnable())) {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        if ($this->getCustomerId()) {
            return false;
        }
        return true;
    }

    /**
     * Tab class getter
     *
     * @return string
     */
    public function getTabClass()
    {
        return '';
    }

    /**
     * Return URL link to Tab content
     *
     * @return string
     */
    public function getTabUrl()
    {
        return '';
    }

    /**
     * Tab should be loaded trough Ajax call
     *
     * @return bool
     */
    public function isAjaxLoaded()
    {
        return false;
    }

    public function initForm()
    {
        if (!$this->canShowTab()) {
            return $this;
        }
        $form = $this->form();
        $this->setForm($form);
        return $this;
    }
    /**
     * @return string
     */
    protected function _toHtml()
    {
        if ($this->canShowTab()) {
            $this->initForm();
            return parent::_toHtml();
        } else {
            return '';
        }
    }
}
