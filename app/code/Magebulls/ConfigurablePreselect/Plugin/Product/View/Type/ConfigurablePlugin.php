<?php 

namespace Magebulls\ConfigurablePreselect\Plugin\Product\View\Type;

class ConfigurablePlugin
{
	protected $jsonEncoder;
    protected $jsonDecoder;

    public function __construct(
    	\Magento\Framework\Json\DecoderInterface $jsonDecoder,
    	\Magento\Framework\Json\EncoderInterface $jsonEncoder
    ){
    	$this->jsonEncoder = $jsonEncoder;
    	$this->jsonDecoder = $jsonDecoder;
	}

	public function afterGetJsonConfig(\Magento\ConfigurableProduct\Block\Product\View\Type\Configurable $subject, $result)
    {
		$result = $this->jsonDecoder->decode($result);
        $currentProduct = $subject->getProduct();
        if ($currentProduct->getDescription()) {
            $result['productDescription'] = $currentProduct->getDescription();
            $result['productName'] = $currentProduct->getName();
            $result['productShortDescription'] = $currentProduct->getShortDescription();
        }

		foreach ($subject->getAllowProducts() as $product) {
            $result['associated_products'][$product->getId()][] =
                [
                    'description' => $product->getData('description'),
                    'short_description' => $product->getData('short_description'),
                    'product_name' => $product->getData('name'),
                    'sku' => $product->getData('sku'),
                ];
        }

        return $this->jsonEncoder->encode($result);
    }
}