<?php
/**
 * Copyright © 2015 Etech. All rights reserved.
 */

namespace Etech\Imageuploader\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
	
        $installer = $setup;

        $installer->startSetup();

		/**
         * Create table 'imageuploader_imagemodel'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('imageuploader_imagemodel')
        )
		->addColumn(
            'id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'imageuploader_imagemodel'
        )
		->addColumn(
            'image name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64k',
            [],
            'Image Name'
        )
		->addColumn(
            'uploaded image',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64k',
            [],
            'Uploaded Image'
        )
		/*{{CedAddTableColumn}}}*/
		
		
        ->setComment(
            'Etech Imageuploader imageuploader_imagemodel'
        );
		
		$installer->getConnection()->createTable($table);
		/*{{CedAddTable}}*/

        $installer->endSetup();

    }
}
