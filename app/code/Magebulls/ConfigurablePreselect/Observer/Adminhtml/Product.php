<?php
/**
 * @category Magebulls ConfigurablePreselect
 * @package Magebulls_ConfigurablePreselect
 * @copyright Copyright (c) 2017 Magebulls
 * @author Magebulls Team <info@magebulls.com>
 */
namespace Magebulls\ConfigurablePreselect\Observer\Adminhtml;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Serialize\SerializerInterface;

class Product implements ObserverInterface
{
	/**
	 * @var \Magento\Framework\App\RequestInterface
	 */
	public $request;

	/**
	 * @var SerializerInterface
	 */
	public $serializer;

	public function __construct(
    \Magento\Framework\App\RequestInterface $request,
    SerializerInterface $serializer
	){
    $this->request = $request;
    $this->serializer = $serializer;
	}

    public function execute(Observer $observer)
    {
    	$product = $observer->getEvent()->getProduct();
    	if ($this->request->getPost("configurable-matrix-serialized")) {
    		$simpleProducts= $this->serializer->unserialize($this->request->getPost("configurable-matrix-serialized"));
    		foreach ($simpleProducts as $simpleProduct) {
    			if($simpleProduct['checked']){
    				$product->setStoreId($product->getStoreId())->setData('is_default_simple_selected',$simpleProduct['sku'])->save();
    			}
    		}
    	}
    }
}