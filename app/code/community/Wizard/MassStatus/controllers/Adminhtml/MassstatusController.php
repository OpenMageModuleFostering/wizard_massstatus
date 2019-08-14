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
 
class Wizard_MassStatus_Adminhtml_MassstatusController extends Mage_Adminhtml_Controller_Action{

	public function changeAction(){
		$orderIds = $this->getRequest()->getPost('order_ids', array());
		$status=$this->getRequest()->getParam('status');
		$label=$this->getRequest()->getParam('label');
		
        foreach ($orderIds as $orderId) {
	        $order=Mage::getModel('sales/order')->load($orderId);
	      //  if($order->getData('status')!='canceled'){
	           $order->setStatus($status);
	           $order->save();
	      //  }
       	    
        }

        $this->_getSession()->addSuccess($this->__('Status of %s order(s) have been changed to '.$label.'.', count($orderIds)));
        $this->_redirect('*/sales_order/index');
	}

	public function deleteAction()
    { 
		
		$orderIds = $this->getRequest()->getPost('order_ids');
        $flag = false;
		$write = Mage::getSingleton('core/resource')->getConnection('core_write');	
		$query="show tables";
		$rsc_table=$write->fetchCol($query);	
		
		$table_sales_flat_order = Mage::getSingleton('core/resource')->getTableName('sales_flat_order');						
		$table_sales_flat_creditmemo_comment= Mage::getSingleton('core/resource')->getTableName('sales_flat_creditmemo_comment');
		$table_sales_flat_creditmemo_item= Mage::getSingleton('core/resource')->getTableName('sales_flat_creditmemo_item');
		$table_sales_flat_creditmemo= Mage::getSingleton('core/resource')->getTableName('sales_flat_creditmemo');
		$table_sales_flat_creditmemo_grid= Mage::getSingleton('core/resource')->getTableName('sales_flat_creditmemo_grid');
		$table_sales_flat_invoice_comment= Mage::getSingleton('core/resource')->getTableName('sales_flat_invoice_comment');
		$table_sales_flat_invoice_item= Mage::getSingleton('core/resource')->getTableName('sales_flat_invoice_item');
		$table_sales_flat_invoice= Mage::getSingleton('core/resource')->getTableName('sales_flat_invoice');
		$table_sales_flat_invoice_grid= Mage::getSingleton('core/resource')->getTableName('sales_flat_invoice_grid');
		$table_sales_flat_quote_address_item= Mage::getSingleton('core/resource')->getTableName('sales_flat_quote_address_item');
		$table_sales_flat_quote_item_option= Mage::getSingleton('core/resource')->getTableName('sales_flat_quote_item_option');
		$table_sales_flat_quote= Mage::getSingleton('core/resource')->getTableName('sales_flat_quote');
		$table_sales_flat_quote_address= Mage::getSingleton('core/resource')->getTableName('sales_flat_quote_address');
		$table_sales_flat_quote_item= Mage::getSingleton('core/resource')->getTableName('sales_flat_quote_item');
		$table_sales_flat_quote_payment= Mage::getSingleton('core/resource')->getTableName('sales_flat_quote_payment');
		$table_sales_flat_shipment_comment= Mage::getSingleton('core/resource')->getTableName('sales_flat_shipment_comment');
		$table_sales_flat_shipment_item= Mage::getSingleton('core/resource')->getTableName('sales_flat_shipment_item');
		$table_sales_flat_shipment_track= Mage::getSingleton('core/resource')->getTableName('sales_flat_shipment_track');
		$table_sales_flat_shipment= Mage::getSingleton('core/resource')->getTableName('sales_flat_shipment');
		$table_sales_flat_shipment_grid= Mage::getSingleton('core/resource')->getTableName('sales_flat_shipment_grid');		
		$table_sales_flat_order_address= Mage::getSingleton('core/resource')->getTableName('sales_flat_order_address');
		$table_sales_flat_order_item= Mage::getSingleton('core/resource')->getTableName('sales_flat_order_item');
		$table_sales_flat_order_payment= Mage::getSingleton('core/resource')->getTableName('sales_flat_order_payment');
		$table_sales_flat_order_status_history= Mage::getSingleton('core/resource')->getTableName('sales_flat_order_status_history');					
		$table_sales_flat_order_grid= Mage::getSingleton('core/resource')->getTableName('sales_flat_order_grid');						
		$table_log_quote= Mage::getSingleton('core/resource')->getTableName('log_quote');				
        $quoteId='';		
		if (!empty($orderIds)) {
            foreach ($orderIds as $orderId) {
					$query=null;
					$order = Mage::getModel('sales/order')->load($orderId);					
					if($order->increment_id){
						/*$query="show tables like 'sales_flat_order'";
						$rs=$write->fetchAll($query);*/						
						$incId=$order->increment_id;
						if(in_array($table_sales_flat_order,$rsc_table)){
							$query='SELECT entity_id   FROM  '.$table_sales_flat_order.'    WHERE increment_id="'.mysql_escape_string($incId).'"';
							
							$rs=$write->fetchAll($query);												
						
							$query='SELECT quote_id    FROM   '.$table_sales_flat_order.'        WHERE entity_id="'.mysql_escape_string($orderId).'"';
							$rs1=$write->fetchAll($query);
							$quoteId=$rs1[0]['quote_id'];							
						}		
						
						$query='SET FOREIGN_KEY_CHECKS=1';
						$rs3=$write->query($query);
						//print_r($rsc_table);
						//echo $table_sales_flat_creditmemo_comment;
						if(in_array($table_sales_flat_creditmemo_comment,$rsc_table)){
						//echo "DELETE FROM ".$table_sales_flat_creditmemo_comment."    WHERE parent_id IN (SELECT entity_id FROM ".$table_sales_flat_creditmemo." WHERE order_id=".$orderId.")";
						//die;
						$write->query("DELETE FROM ".$table_sales_flat_creditmemo_comment."    WHERE parent_id IN (SELECT entity_id FROM ".$table_sales_flat_creditmemo." WHERE order_id='".mysql_escape_string($orderId)."')");
						}
						//die;
						
						
						if(in_array('sales_flat_creditmemo_item',$rsc_table)){
						$write->query("DELETE FROM ".$table_sales_flat_creditmemo_item."       WHERE parent_id IN (SELECT entity_id FROM ".$table_sales_flat_creditmemo." WHERE order_id='".mysql_escape_string($orderId)."')");
						}
						
						
						if(in_array($table_sales_flat_creditmemo,$rsc_table)){
						$write->query("DELETE FROM ".$table_sales_flat_creditmemo."            WHERE order_id='".mysql_escape_string($orderId)."'");
						}
						
						
						
						if(in_array($table_sales_flat_creditmemo_grid,$rsc_table)){
						$write->query("DELETE FROM ".$table_sales_flat_creditmemo_grid."        WHERE order_id='".mysql_escape_string($orderId)."'");
						}
						
						
						if(in_array($table_sales_flat_invoice_comment,$rsc_table)){
						
						$write->query("DELETE FROM ".$table_sales_flat_invoice_comment." WHERE parent_id IN (SELECT entity_id FROM ".$table_sales_flat_invoice." WHERE order_id='".mysql_escape_string($orderId)."')");
						}
						
						if(in_array($table_sales_flat_invoice_item,$rsc_table)){
						
						$write->query("DELETE FROM ".$table_sales_flat_invoice_item."     WHERE parent_id IN (SELECT entity_id FROM ".$table_sales_flat_invoice." WHERE order_id='".mysql_escape_string($orderId)."')");
						}
						
						
						if(in_array($table_sales_flat_invoice,$rsc_table)){
						
						$write->query("DELETE FROM ".$table_sales_flat_invoice."         WHERE order_id='".mysql_escape_string($orderId)."'");
						}
						
						if(in_array($table_sales_flat_invoice_grid,$rsc_table)){
						
						$write->query("DELETE FROM ".$table_sales_flat_invoice_grid."     WHERE order_id='".mysql_escape_string($orderId)."'");
						}	
						
						if($quoteId){						
							if(in_array($table_sales_flat_quote_address_item,$rsc_table)){							
							$write->query("DELETE FROM ".$table_sales_flat_quote_address_item."     WHERE parent_item_id  IN (SELECT address_id FROM ".$table_sales_flat_quote_address." WHERE quote_id=".$quoteId.")");
							}
							
							$table_sales_flat_quote_shipping_rate= Mage::getSingleton('core/resource')->getTableName('sales_flat_quote_shipping_rate');
							if(in_array($table_sales_flat_quote_shipping_rate,$rsc_table)){
							$write->query("DELETE FROM ".$table_sales_flat_quote_shipping_rate."    WHERE address_id IN (SELECT address_id FROM ".$table_sales_flat_quote_address." WHERE quote_id=".$quoteId.")");
							}
							
							if(in_array($table_sales_flat_quote_item_option,$rsc_table)){
							$write->query("DELETE FROM ".$table_sales_flat_quote_item_option."     WHERE item_id IN (SELECT item_id FROM ".$table_sales_flat_quote_item." WHERE quote_id=".$quoteId.")");
							}
						
							
							if(in_array($table_sales_flat_quote,$rsc_table)){
							$write->query("DELETE FROM ".$table_sales_flat_quote."                 WHERE entity_id=".$quoteId);
							}
							
							if(in_array($table_sales_flat_quote_address,$rsc_table)){
							$write->query("DELETE FROM ".$table_sales_flat_quote_address."         WHERE quote_id=".$quoteId);
							}
							
							if(in_array($table_sales_flat_quote_item,$rsc_table)){
							$write->query("DELETE FROM ".$table_sales_flat_quote_item."             WHERE quote_id=".$quoteId);
							}
							
							if(in_array('sales_flat_quote_payment',$rsc_table)){
							$write->query("DELETE FROM ".$table_sales_flat_quote_payment."         WHERE quote_id=".$quoteId);
							}
							
						}
						
						
						if(in_array($table_sales_flat_shipment_comment,$rsc_table)){
						$write->query("DELETE FROM ".$table_sales_flat_shipment_comment."    WHERE parent_id IN (SELECT entity_id FROM ".$table_sales_flat_shipment." WHERE order_id='".mysql_escape_string($orderId)."')");
						}
						
						if(in_array($table_sales_flat_shipment_item,$rsc_table)){
						$write->query("DELETE FROM ".$table_sales_flat_shipment_item."         WHERE parent_id IN (SELECT entity_id FROM ".$table_sales_flat_shipment." WHERE order_id='".mysql_escape_string($orderId)."')");
						}
						
						
						if(in_array($table_sales_flat_shipment_track,$rsc_table)){						
						$write->query("DELETE FROM ".$table_sales_flat_shipment_track."         WHERE order_id  IN (SELECT entity_id FROM ".$table_sales_flat_shipment." WHERE order_id='".mysql_escape_string($orderId)."')");
						}
						
						
						if(in_array($table_sales_flat_shipment,$rsc_table)){
						
						$write->query("DELETE FROM ".$table_sales_flat_shipment."             WHERE order_id='".mysql_escape_string($orderId)."'");
						}
						
						
						if(in_array($table_sales_flat_shipment_grid,$rsc_table)){
						$write->query("DELETE FROM ".$table_sales_flat_shipment_grid."         WHERE order_id='".mysql_escape_string($orderId)."'");
						}
						
						if(in_array($table_sales_flat_order,$rsc_table)){
						$write->query("DELETE FROM ".$table_sales_flat_order."                     WHERE entity_id='".mysql_escape_string($orderId)."'");
						}
						
						if(in_array($table_sales_flat_order_address,$rsc_table)){
						$write->query("DELETE FROM ".$table_sales_flat_order_address."            WHERE parent_id='".mysql_escape_string($orderId)."'");
						}
						
						if(in_array($table_sales_flat_order_item,$rsc_table)){						
						$write->query("DELETE FROM ".$table_sales_flat_order_item."                 WHERE order_id='".mysql_escape_string($orderId)."'");
						}
						if(in_array($table_sales_flat_order_payment,$rsc_table)){
						$write->query("DELETE FROM ".$table_sales_flat_order_payment."             WHERE parent_id='".mysql_escape_string($orderId)."'");
						}
						if(in_array($table_sales_flat_order_status_history,$rsc_table)){
						$write->query("DELETE FROM ".$table_sales_flat_order_status_history."     WHERE parent_id='".mysql_escape_string($orderId)."'");
						}
						if($incId&&in_array($table_sales_flat_order_grid,$rsc_table)){						
							$write->query("DELETE FROM ".$table_sales_flat_order_grid."                 WHERE increment_id='".mysql_escape_string($incId)."'");
	
						}
						
						$query="show tables like '%".$table_log_quote."'";
						$rsc_table_l=$write->fetchCol($query);	
						if($quoteId&&$rsc_table_l){						
								$write->query("DELETE FROM ".$table_log_quote." WHERE quote_id=".$quoteId);							
						}
						$write->query("SET FOREIGN_KEY_CHECKS=1");						
					}					
			}	
		$this->_getSession()->addSuccess($this->__('Order(s) deleted.'));
		}else{
		$this->_getSession()->addError($this->__('Order(s) error.'));
		}		
		$this->_redirect('*/sales_order/index');		
    }

}