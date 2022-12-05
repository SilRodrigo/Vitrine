<?php

/**
 * @author Rodrigo Silva
 * @copyright Copyright (c) 2022 Rodrigo Silva (https://github.com/SilRodrigo)
 * @package Rsilva_Vitrine
 */

declare(strict_types=1);

namespace Rsilva\Vitrine\Ui;

use Magento\Framework\Model\AbstractModel;

interface EntityUiManagerInterface
{
    /**
     * @param int|null $id
     * @return AbstractModel
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get(?int $id);

    /**
     * @param AbstractModel $entity
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(AbstractModel $entity);

    /**
     * @param int $id
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(int $id);
}