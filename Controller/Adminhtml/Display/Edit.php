<?php

/**
 * @author Rodrigo Silva
 * @copyright Copyright (c) 2022 Rodrigo Silva (https://github.com/SilRodrigo)
 * @package Rsilva_Vitrine
 */

namespace Rsilva\Vitrine\Controller\Adminhtml\Display;

use Rsilva\Vitrine\Controller\Adminhtml\Display;
use Rsilva\Vitrine\Model\ProductsDisplay;

class Edit extends \Rsilva\Vitrine\Controller\Adminhtml\Display
{
    /**
     * Backend session
     * 
     * @var \Magento\Backend\Model\Session
     */
    protected $_backendSession;

    /**
     * Page factory
     * 
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;

    /**
     * Result JSON factory
     * 
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $_resultJsonFactory;

    /**
     * constructor
     * 
     * @param \Magento\Backend\Model\Session $backendSession
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Rsilva\Vitrine\Model\ProductsDisplay $productsDisplay
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Magento\Backend\Model\Session $backendSession,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Rsilva\Vitrine\Model\ProductsDisplayFactory $productsDisplayFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory,
        \Magento\Backend\App\Action\Context $context
    ) {
        $this->_backendSession    = $backendSession;
        $this->_resultPageFactory = $resultPageFactory;
        $this->_resultJsonFactory = $resultJsonFactory;
        parent::__construct($productsDisplayFactory, $registry, $resultRedirectFactory, $context);
    }

    /**
     * is action allowed
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Rsilva_Test::display');
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('entity_id');
        /** @var \Rsilva\Vitrine\Model\ProductsDisplay $productsDisplay */
        $productsDisplay = $this->_initPost();
        /** @var \Magento\Backend\Model\View\Result\Page|\Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('Rsilva_Vitrine::menu');
        $resultPage->getConfig()->getTitle()->set(__('Display'));        
        if ($id) {
            $productsDisplay->load($id);
            if (!$productsDisplay->getId()) {
                $this->messageManager->addError(__('This Display no longer exists.'));
                $resultRedirect = $this->_resultRedirectFactory->create();
                $resultRedirect->setPath(
                    Display::URL_PATH . '*/edit',
                    [
                        ProductsDisplay::ID => $productsDisplay->getId(),
                        '_current' => true
                    ]
                );
                return $resultRedirect;
            }
        }
        $title = $productsDisplay->getId() ? $productsDisplay->getName() : __('New ambient');
        $resultPage->getConfig()->getTitle()->prepend($title);
        $data = $this->_backendSession->getData('rsilva_vitrine_display_data', true);
        if (!empty($data)) {
            $productsDisplay->setData($data);
        }
        return $resultPage;
    }
}
