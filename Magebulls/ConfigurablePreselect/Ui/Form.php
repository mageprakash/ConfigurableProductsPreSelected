<?php
/**
 * @category Magebulls ConfigurablePreselect
 * @package Magebulls_ConfigurablePreselect
 * @copyright Copyright (c) 2017 Magebulls
 * @author Magebulls Team <info@magebulls.com>
 */
namespace Magebulls\ConfigurablePreselect\Ui;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentInterface;

class Form extends \Magento\Ui\Component\Form
{
    /**
     * {@inheritdoc}
     */
    public function getDataSourceData()
    {
        $dataSource = [];

        $id = $this->getContext()->getRequestParam($this->getContext()->getDataProvider()->getRequestFieldName(), null);
        $filter = $this->filterBuilder->setField($this->getContext()->getDataProvider()->getPrimaryFieldName())
            ->setValue($id)
            ->create();
        $this->getContext()->getDataProvider()
            ->addFilter($filter);

        $data = $this->getContext()->getDataProvider()->getData();

        /*add new field to configurable-matrix start*/
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        if($objectManager->get('Magento\Framework\Registry')->registry('current_product')){
            $product = $objectManager->get('Magento\Framework\Registry')->registry('current_product');
            if($product->getTypeId() ==  'configurable'){
                foreach ($data as $key_id => $data_value) {
                    if (array_key_exists('configurable-matrix', $data_value)) {
                        foreach ($data_value['configurable-matrix'] as $configurableMatrixKey => $configurableMatrix){
                            $data[$key_id]['configurable-matrix'][$configurableMatrixKey]['default_value'] = $configurableMatrix['sku'];
                            if($configurableMatrix['sku'] == $product->getIsDefaultSimpleSelected()){
                                $data[$key_id]['configurable-matrix'][$configurableMatrixKey]['checked'] = 1;
                            }
                            else{
                                 $data[$key_id]['configurable-matrix'][$configurableMatrixKey]['checked'] = 0;
                            }
                        }
                    }
                }
            }
        }
        /*add new field to configurable-matrix ends*/
        if (isset($data[$id])) {
            $dataSource = [
                'data' => $data[$id]
            ];
        } elseif (isset($data['items'])) {
            foreach ($data['items'] as $item) {
                if ($item[$item['id_field_name']] == $id) {
                    $dataSource = ['data' => ['general' => $item]];
                }
            }
        }
    return $dataSource;
    }

}
