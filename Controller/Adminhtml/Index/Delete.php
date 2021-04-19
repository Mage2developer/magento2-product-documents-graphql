<?php
/**
 * BhattiDhara
 * Copyright (C) 2021 BhattiDhara
 *
 * NOTICE OF LICENSE
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see http://opensource.org/licenses/gpl-3.0.html
 *
 * @category BhattiDhara
 * @package Mage2_ProductDocs
 * @copyright Copyright (c) 2021 BhattiDhara
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License,version 3 (GPL-3.0)
 * @author BhattiDhara
 */

declare(strict_types=1);

namespace Mage2\ProductDocs\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Mage2\ProductDocs\Api\DocumentsRepositoryInterface;

/**
 * Class Delete
 */
class Delete extends Action implements HttpPostActionInterface
{
    /**
     * @var DocumentsRepositoryInterface
     */
    protected $documentsRepository;

    /**
     * Delete constructor.
     *
     * @param Action\Context $context
     * @param DocumentsRepositoryInterface $documentsRepository
     */
    public function __construct(
        Action\Context $context,
        DocumentsRepositoryInterface $documentsRepository
    ) {
        parent::__construct($context);
        $this->documentsRepository = $documentsRepository;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');

        if ($id) {
            try {
                $this->documentsRepository->deleteById((int)$id);
                $this->messageManager->addSuccessMessage(__('You deleted the document.'));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
            return $this->resultRedirectFactory->create()->setPath('*/*/');
        }
        $this->messageManager->addErrorMessage(__('We can\'t find a document to delete.'));

        return $this->resultRedirectFactory->create()->setPath('*/*/');
    }
}
