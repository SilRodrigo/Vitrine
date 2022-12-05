<?php

/**
 * @author Rodrigo Silva
 * @copyright Copyright (c) 2022 Rodrigo Silva (https://github.com/SilRodrigo)
 * @package Rsilva_Vitrine
 */

declare(strict_types=1);

namespace Rsilva\Vitrine\Model\ProductsDisplay;

use Rsilva\Vitrine\Model\ProductsDisplay;
use Rsilva\Vitrine\Model\ResourceModel\ProductsDisplay\CollectionFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Magento\Ui\DataProvider\ModifierPoolDataProvider;

use Magento\Framework\Registry;

class DataProvider extends ModifierPoolDataProvider
{

    /**
     * @var RequestInterface
     */
    protected $request;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        RequestInterface $request,
        Registry $registry,
        array $meta = [],
        array $data = [],
        PoolInterface $pool = null
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data, $pool);
        $this->collection = $collectionFactory->create();
        $this->registry = $registry;
        $this->productDisplayFactory = $collectionFactory;
        $this->request = $request;
    }

    public function getData()
    {
        $data = parent::getData();
        $display = $this->registry->registry('rsilva_vitrine_display');
        if ($display->getData()) {
            $display->setData('image', [0 => [
                'name' => $display->getData(ProductsDisplay::IMAGE_NAME),
                'type' => $display->getData(ProductsDisplay::IMAGE_TYPE),
                'size' => $display->getData(ProductsDisplay::IMAGE_SIZE),
                'path' => $display->getData(ProductsDisplay::IMAGE_PATH),
                'file' => $display->getData(ProductsDisplay::IMAGE_FILE),
                'url' => $display->getData(ProductsDisplay::IMAGE_URL),
                'previewType' => $display->getData(ProductsDisplay::IMAGE_PREVIEW_TYPE),
                'id' => $display->getData(ProductsDisplay::IMAGE_ID),
            ]]);
            $data[$display->getEntityId()] = $display->getData();
        }

        return $data;
    }
}
