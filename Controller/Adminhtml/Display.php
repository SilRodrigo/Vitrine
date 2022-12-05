<?php

/**
 * @author Rodrigo Silva
 * @copyright Copyright (c) 2022 Rodrigo Silva (https://github.com/SilRodrigo)
 * @package Rsilva_Vitrine
 */

namespace Rsilva\Vitrine\Controller\Adminhtml;

abstract class Display extends \Magento\Backend\App\Action
{

    public const URL_PATH = 'rsilva_vitrine/';    

    /**
     * Display Factory
     * 
     * @var \Rsilva\Vitrine\Model\ProductsDisplay
     */
    protected $_productsDisplay;

    /**
     * Core registry
     * 
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * Result redirect factory
     * 
     * @var \Magento\Backend\Model\View\Result\RedirectFactory
     */
    protected $_resultRedirectFactory;

    /**
     * constructor
     * 
     * @param \Rsilva\Vitrine\Model\ProductsDisplay $productsDisplay
     * @param \Rsilva\Vitrine\Model\ProductsDisplayFactory $productsDisplayFactory
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(        
        \Rsilva\Vitrine\Model\ProductsDisplayFactory $productsDisplayFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory,
        \Magento\Backend\App\Action\Context $context
    ) {
        $this->_productsDisplay       = $productsDisplayFactory;
        $this->_coreRegistry          = $coreRegistry;
        $this->_resultRedirectFactory = $resultRedirectFactory;
        parent::__construct($context);
    }

    /**
     * Init ProductsDisplay
     *
     * @return \Rsilva\Vitrine\Model\ProductsDisplay
     */
    protected function _initPost()
    {
        $id = (int) $this->getRequest()->getParam('entity_id');
        /** @var \Rsilva\Vitrine\Model\ProductsDisplay $productsDisplay */
        $productsDisplay = $this->_productsDisplay->create();
        if ($id) {
            $productsDisplay->load($id);
        }
        $this->_coreRegistry->register('rsilva_vitrine_display', $productsDisplay);
        return $productsDisplay;
    }
}
