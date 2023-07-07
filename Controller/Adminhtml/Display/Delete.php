<?php

/**
 * @author Rodrigo Silva
 * @copyright Copyright (c) 2023 Rodrigo Silva (https://github.com/SilRodrigo)
 * @package Rsilva_Vitrine
 */

namespace Rsilva\Vitrine\Controller\Adminhtml\Display;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Rsilva\Vitrine\Controller\Adminhtml\Display;

class Delete extends Display implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Rsilva_Vitrine::delete';

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('entity_id');
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            $model = $this->_objectManager->create(\Rsilva\Vitrine\Model\ProductsDisplayFactory::class)->create();

            // entity type check
            $model->load($id);
            if (!$model->getEntityId()) {
                $this->messageManager->addErrorMessage(__('We can\'t delete the display.'));
                return $resultRedirect->setPath(Display::URL_PATH . '*/');
            }

            try {
                $model->delete();
                $this->messageManager->addSuccessMessage(__('You deleted the display.'));
                return $resultRedirect->setPath(Display::URL_PATH . '*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath(
                    Display::URL_PATH . '*/edit',
                    ['attribute_id' => $this->getRequest()->getParam('attribute_id')]
                );
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find an display to delete.'));
        return $resultRedirect->setPath(Display::URL_PATH . '*/');
    }
}
