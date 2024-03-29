<?php
/**
* Magento Wizard Admin Mass Status Module
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Wizard
 * @package    Wizard_MassStatus
 * @copyright  Copyright (c) 2015 Wizard (http://www.sujeetkrsingh.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *
 **/
class Wizard_MassStatus_Block_Sales_Order_Grid extends Mage_Adminhtml_Block_Sales_Order_Grid
{
    protected function _prepareMassaction()
    {
        parent::_prepareMassaction();
    	if (Mage::getStoreConfig("mass_status/export_orders/active")) {

            $orderStatus=Mage::getModel('sales/order_status')->getResourceCollection()->getData();

    	   foreach($orderStatus as $status){
           //  if($status['status']!='canceled'){
                $this->getMassactionBlock()->addItem('status'.$status['status'], array(
                     'label'=> Mage::helper('sales')->__($status['label']),
                     'url'  => $this->getUrl('*/massstatus/change').'?status='.$status['status'].'&label='.$status['label']
                ));
            //  }
            }

             $this->getMassactionBlock()->addItem('statusDelete', array(
                     'label'=> Mage::helper('sales')->__('Delete'),
                     'url'  => $this->getUrl('*/massstatus/delete')
                ));


	}

    }
}
?>