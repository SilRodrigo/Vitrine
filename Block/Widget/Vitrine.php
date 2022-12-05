<?php

namespace Rsilva\Vitrine\Block\Widget;

use Rsilva\Vitrine\Model\ProductsDisplayFactory;
use Rsilva\Vitrine\Helper\Data as VitrineHelper;
use Magento\Widget\Block\BlockInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Vitrine extends Template implements BlockInterface
{
    const WIDGET_NAME = 'vitrine';

    /** @var ProductsDisplayFactory */
    private $_vitrineCollectionFactory;

    /** @var VitrineHelper */
    private $_vitrineHelper;

    protected $_template = "widget/vitrine.phtml";

    public function __construct(
        Context $context,
        ProductsDisplayFactory $vitrineCollectionFactory,
        VitrineHelper $vitrineHelper
    ) {
        parent::__construct($context);
        $this->_vitrineCollectionFactory = $vitrineCollectionFactory->create();
        $this->_vitrineHelper = $vitrineHelper;
    }

    public function getVitrine()
    {
        $vitrine = $this->_vitrineCollectionFactory->load($this->getData(Vitrine::WIDGET_NAME));
        $this->_vitrineHelper->updatePinpoints($vitrine);
        return $vitrine;
    }
}
