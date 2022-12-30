<?php

/**
 * @author Rodrigo Silva
 * @copyright Copyright (c) 2022 Rodrigo Silva (https://github.com/SilRodrigo)
 * @package Rsilva_Vitrine
 */

namespace Rsilva\Vitrine\Helper;

use Magento\Catalog\API\ProductRepositoryInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;

/**
 * Catalog Inventory default helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /** @var ProductRepositoryInterface */
    private $_productRepository;

    /**
     *  @var PriceCurrencyInterface
     */
    private $_priceCurrency;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        PriceCurrencyInterface $priceCurrency
    ) {
        $this->_productRepository = $productRepository;
        $this->_priceCurrency = $priceCurrency;
    }

    /**
     * @param \Rsilva\Vitrine\Model\ProductsDisplay
     */
    public function updatePinpoints($vitrine)
    {        
        $pinpoints = [];

        $pinpoints_data = $vitrine->getPinpoints();

        if ($pinpoints_data){
            foreach (json_decode($pinpoints_data, true) as $pinpoint) {
                $product = $this->_productRepository->getById($pinpoint['product']['entity_id']);
                $image_thumbnail_path = $product->getImage();
                $final_image_path = '/media/catalog/product' . $image_thumbnail_path;
                $product->setData('complete_image_url', $final_image_path);
                $product->setData('complete_page_url', $product->getProductUrl());
                $product->setData('price', $product->getPrice());
                $product->setData('formatted_price', $this->_priceCurrency->format($product->getPrice(), false));
                $product->setData('special_price', $product->getSpecialPrice());
                $product->setData('formatted_special_price', $this->_priceCurrency->format($product->getSpecialPrice(), false));
                foreach ($pinpoint['product'] as $key => $value) {
                    $pinpoint['product'][$key] = $product->getData($key) ?? $value;
                }
                $pinpoints[] = $pinpoint;
            }
        }

        $vitrine->setPinpoints(json_encode($pinpoints));
    }
}
