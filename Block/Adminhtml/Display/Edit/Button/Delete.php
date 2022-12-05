<?php

/**
 * @author Rodrigo Silva
 * @copyright Copyright (c) 2022 Rodrigo Silva (https://github.com/SilRodrigo)
 * @package Rsilva_Vitrine
 */

namespace Rsilva\Vitrine\Block\Adminhtml\Display\Edit\Button;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Rsilva\Vitrine\Controller\Adminhtml\Display;
use Rsilva\Vitrine\Model\ProductsDisplay;

/**
 * Class Delete
 */
class Delete extends Generic implements ButtonProviderInterface
{
    /**
     * Delete button
     *
     * @return array
     */
    public function getButtonData()
    {
        $display = $this->registry->registry(ProductsDisplay::MODULE);
        $displayId = (int)$display->getEntityId();

        if ($displayId) {
            return [
                'id' => 'delete',
                'label' => __('Delete'),
                'on_click' => "deleteConfirm('" . __('Are you sure you want to delete this display?') . "', '"
                    . $this->getDeleteUrl() . "', {data: {}})",
                'class' => 'delete',
                'sort_order' => 10
            ];
        }

        return [];
    }

    /**
     * @param array $args
     * @return string
     */
    public function getDeleteUrl(array $args = [])
    {
        $params = array_merge($this->getDefaultUrlParams(), $args);
        return $this->getUrl(Display::URL_PATH . '*/delete', $params);
    }

    /**
     * @return array
     */
    protected function getDefaultUrlParams()
    {
        return ['_current' => true, '_query' => ['isAjax' => null]];
    }
}
