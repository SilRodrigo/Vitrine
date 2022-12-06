<?php

/**
 * @author Rodrigo Silva
 * @copyright Copyright (c) 2022 Rodrigo Silva (https://github.com/SilRodrigo)
 * @package Rsilva_Vitrine
 */

namespace Rsilva\Vitrine\Controller\Adminhtml\Display;

use Rsilva\Vitrine\Model\ProductsDisplayFactory;
use Rsilva\Vitrine\Model\ProductsDisplay;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;

use Magento\Framework\Registry;
use Magento\Framework\App\ObjectManager;

/**
 * Class Save for save checkout fields
 */
class Save extends Action
{

    /**
     * @var ProductsDisplayFactory
     */
    private $productsDisplayFactory;

    /**
     * @var ProductsDisplay
     */
    private $model;

    public function __construct(
        Context $context,
        Registry $coreRegistry,
        ProductsDisplayFactory $productsDisplayFactory = null
    ) {
        $this->productsDisplayFactory = $productsDisplayFactory ?: ObjectManager::getInstance()->get(ProductsDisplayFactory::class);
        parent::__construct($context);
    }

    public function execute()
    {
        $postData = $this->getRequest()->getPostValue();
        $id = $this->getRequest()->getParam('entity_id');

        if ($postData) {
            try {
                $this->model = $this->productsDisplayFactory->create();
                $this->model->load($this->model->getEntityId());
                $postData[ProductsDisplay::IMAGE_TYPE] = $postData['image'][0]['type'];
                $postData[ProductsDisplay::IMAGE_NAME] = $postData['image'][0]['name'];
                $postData[ProductsDisplay::IMAGE_SIZE] = $postData['image'][0]['size'];
                $postData[ProductsDisplay::IMAGE_URL] = $postData['image'][0]['url'];
                $postData[ProductsDisplay::IMAGE_PREVIEW_TYPE] = $postData['image'][0]['previewType'];
                $postData[ProductsDisplay::IMAGE_ID] = $postData['image'][0]['id'];
                $this->model->setData($postData);
                $this->model->save();
                $this->messageManager->addSuccessMessage(__('Saved successfully'));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $this->resultRedirectFactory->create()
                    ->setPath('*/*', ['_current' => true]);
            }
        }

        return $this->resultRedirectFactory->create()
            ->setPath('*/*', ['_current' => true]);
    }
}
