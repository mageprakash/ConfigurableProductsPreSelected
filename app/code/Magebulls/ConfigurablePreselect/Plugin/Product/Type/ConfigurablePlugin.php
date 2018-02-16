<?php 

namespace Magebulls\ConfigurablePreselect\Plugin\Product\Type;

class ConfigurablePlugin
{
    public function afterGetUsedProductCollection(\Magento\ConfigurableProduct\Model\Product\Type\Configurable $subject, $result)
    {
        $result->addAttributeToSelect(array('description','short_description','name','sku'));
        return $result;
    }
}