<?php

/**
 * @author Rodrigo Silva
 * @copyright Copyright (c) 2022 Rodrigo Silva (https://github.com/SilRodrigo)
 * @package Rsilva_Vitrine
 */

namespace Rsilva\Vitrine\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ProductsDisplay extends AbstractDb
{

    protected function _construct()
    {
        $this->_init('rsilva_vitrine_products_display', 'entity_id');
    }
}
