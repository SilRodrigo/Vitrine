<?php

/**
 * @author Rodrigo Silva
 * @copyright Copyright (c) 2022 Rodrigo Silva (https://github.com/SilRodrigo)
 * @package Rsilva_Vitrine
 */

namespace Rsilva\Vitrine\Model\Widget\Config\Source;

use Rsilva\Vitrine\Model\ResourceModel\ProductsDisplay\CollectionFactory;

class Select implements \Magento\Framework\Option\ArrayInterface
{

    /** @var CollectionFactory */
    private $_vitrineCollectionFactory;

    public function __construct(
        CollectionFactory $vitrineCollectionFactory
    ) {
        $this->_vitrineCollectionFactory = $vitrineCollectionFactory->create();
    }

    public function toOptionArray()
    {       
        $values = [] ;
        foreach ($this->_vitrineCollectionFactory as $value) {
            $values[] = ['value' => $value->getEntityId(), 'label' => $value->getName()];
        }; 
        
        return $values;
    }
}
