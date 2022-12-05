<?php

/**
 * @author Rodrigo Silva
 * @copyright Copyright (c) 2022 Rodrigo Silva (https://github.com/SilRodrigo)
 * @package Rsilva_Vitrine
 */

declare(strict_types=1);

namespace Rsilva\Vitrine\Model\ResourceModel\ProductsDisplay;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    public function _construct()
    {
        $this->_init(
            \Rsilva\Vitrine\Model\ProductsDisplay::class,
            \Rsilva\Vitrine\Model\ResourceModel\ProductsDisplay::class
        );
    }
}
