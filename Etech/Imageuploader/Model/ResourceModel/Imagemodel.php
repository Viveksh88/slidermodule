<?php
/**
 * Copyright © 2015 Etech. All rights reserved.
 */
namespace Etech\Imageuploader\Model\ResourceModel;

/**
 * Imagemodel resource
 */
class Imagemodel extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('imageuploader_imagemodel', 'id');
    }

  
}
