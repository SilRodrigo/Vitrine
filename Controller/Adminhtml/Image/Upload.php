<?php

/**
 * @author Rodrigo Silva
 * @copyright Copyright (c) 2022 Rodrigo Silva (https://github.com/SilRodrigo)
 * @package Rsilva_Vitrine
 */

namespace Rsilva\Vitrine\Controller\Adminhtml\Image;

use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\UrlInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Store\Model\StoreManagerInterface;

class Upload extends \Magento\Backend\App\Action
{

    /**
     *
     * @var UploaderFactory
     */
    protected $uploaderFactory;

    /** 
     * @var Filesystem\Directory\WriteInterface 
     */
    protected $mediaDirectory;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    public function __construct(
        Context $context,
        UploaderFactory $uploaderFactory,
        Filesystem $filesystem,
        StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->uploaderFactory = $uploaderFactory;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        $this->storeManager = $storeManager;
    }

    public function execute()
    {
        $jsonResult = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        try {
            $fileUploader = $this->uploaderFactory->create(['fileId' => 'image']);
            $fileUploader->setAllowedExtensions(['jpg', 'jpeg', 'png']);
            $fileUploader->setAllowRenameFiles(true);
            $fileUploader->setAllowCreateFolders(true);
            $fileUploader->setFilesDispersion(false);
            /* $fileUploader->validate(); */
            $result = $fileUploader->save($this->mediaDirectory->getAbsolutePath('wysiwyg'));
            $result['url'] = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA)
                . 'wysiwyg/' . ltrim(str_replace('\\', '/', $result['file']), '/');
            return $jsonResult->setData($result);
        } catch (LocalizedException $e) {
            return $jsonResult->setData(['errorcode' => 0, 'error' => $e->getMessage()]);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            error_log($e->getTraceAsString());
            return $jsonResult->setData(['errorcode' => 0, 'error' => __('An error occurred, please try again later.')]);
        }
    }
}
