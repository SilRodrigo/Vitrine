<?php

/**
 * @author Rodrigo Silva
 * @copyright Copyright (c) 2022 Rodrigo Silva (https://github.com/SilRodrigo)
 * @package Rsilva_Vitrine
 */

namespace Rsilva\Vitrine\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Rsilva\Vitrine\Model\ResourceModel\ProductsDisplay as DisplayResource;
use Rsilva\Vitrine\Model\ResourceModel\ProductsDisplay\Collection;
use Rsilva\Vitrine\Model\ResourceModel\ProductsDisplay\CollectionFactory;

/**
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class ProductsDisplay extends AbstractModel
{
    public const XML_PATH_CONFIG = 'rsilva_vitrine/display';
    public const MODULE = 'rsilva_vitrine_display';
    public const MAGENTO_REQUIRE_CONFIG_VALUE = 'req';
    public const DEFAULT_STORE_ID = 0;

    public const ID = 'entity_id';
    public const NAME = 'name';
    public const IMAGE_NAME= 'image_name';
    public const IMAGE_TYPE= 'image_type';
    public const IMAGE_SIZE= 'image_size';
    public const IMAGE_PATH= 'image_path';
    public const IMAGE_FILE= 'image_file';
    public const IMAGE_URL= 'image_url';
    public const IMAGE_PREVIEW_TYPE= 'image_preview_type';
    public const IMAGE_ID= 'image_id';
    public const PINPOINTS= 'pinpoints';
    public const REQUIRED = 'required';
    public const ENABLED = 'enabled';
    public const SORT_ORDER = 'sort_order';

    /**
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _construct()
    {
        $this->_init(DisplayResource::class);
    }

    /**
     * Set/Get attribute wrapper
     *
     * @param   string $method
     * @param   array $args
     * @return  mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function __call($method, $args)
    {
        switch (substr($method, 0, 3)) {
            case 'get':
                $key = $this->_underscore(substr($method, 3));
                $index = isset($args[0]) ? $args[0] : null;
                return $this->getData($key, $index);
            case 'set':
                $key = $this->_underscore(substr($method, 3));
                $value = isset($args[0]) ? $args[0] : null;
                return $this->setData($key, $value);
            case 'uns':
                $key = $this->_underscore(substr($method, 3));
                return $this->unsetData($key);
            case 'has':
                $key = $this->_underscore(substr($method, 3));
                return isset($this->_data[$key]);
        }
        throw new \Magento\Framework\Exception\LocalizedException(
            new \Magento\Framework\Phrase('Invalid method %1::%2', [get_class($this), $method])
        );
    }
}
