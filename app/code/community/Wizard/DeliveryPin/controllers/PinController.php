<?php
/**
 * Magento GoTechy Admin Status Module
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
 * @category   Gotechy
 * @package    GT_MassStatus
 * @copyright  Copyright (c) 2014 GoTechy (http://www.gotechy.in)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *
 **/
 
class GT_DeliveryPin_PinController extends Mage_Core_Controller_Front_Action{

	public function validateAction(){
		$pin=$this->getRequest()->getParam('pin');
		$file = Mage::getBaseDir().'/var/deliverypin/pincodes.csv';
		$csv = new Varien_File_Csv();
		$data = $csv->getData($file);
		$skipHead=true;
		$result=array();
		foreach($data as $row){
			if($skipHead) {
				$skipHead=false; 
				continue;
			}
		    if($row[0]==$pin){
		    	$result=$row;
			}
		}

		echo json_encode($result);
	}
}