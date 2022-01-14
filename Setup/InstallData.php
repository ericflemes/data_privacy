<?php

namespace Elemes\DataPrivacy\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Model\Config;
use Magento\Customer\Model\Customer;
use Magento\Customer\Api\CustomerMetadataInterface;

class InstallData implements InstallDataInterface
{
    /**
     * @var \Magento\Eav\Setup\EavSetupFactory
     */
    private $eavSetupFactory;

    public function __construct(
        EavSetupFactory $eavSetupFactory, Config $eavConfig
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->eavConfig       = $eavConfig;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $attributeCode = 'data_privacy';
        $eavSetup->addAttribute(
            \Magento\Customer\Model\Customer::ENTITY,
            'data_privacy',
            [
                'type'         => 'varchar',
                'label'        => 'Data Privacy',
                'input'        => 'text',
                'required'     => false,
                'visible'      => false,
                'user_defined' => true,
                'position'     => 999,
                'system'       => 0,
            ]
        );

        $eavSetup->addAttributeToSet(
            CustomerMetadataInterface::ENTITY_TYPE_CUSTOMER,
            CustomerMetadataInterface::ATTRIBUTE_SET_ID_CUSTOMER,
            null,
            $attributeCode);

        $sampleAttribute = $this->eavConfig->getAttribute(Customer::ENTITY, 'data_privacy');
        $sampleAttribute->setData(
            'used_in_forms',
            ['adminhtml_customer']
        );
        $sampleAttribute->save();
    }
}
