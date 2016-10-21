<?php
/**
 * Created on 25/09/16 01:06
 * @author Yuriy Perevoznikov <yuriless@gmail.com>
 */

use YPReconciliation\SetsReconciliation;

class SetsReconciliationTest extends \PHPUnit_Framework_TestCase {

	public function testGetReconciliationActions(){
		$reconciliation = new SetsReconciliation('id', true);
		$result = $reconciliation->getReconciliationActions(array(), array());
		$this->assertInstanceOf('YPReconciliation\ReconciliationActions', $result);
	}

	public function testGetReconciliationActionsForRemoveList(){
		$reconciliation = new SetsReconciliation('id', true);
		$result = $reconciliation->getReconciliationActions(
			array(array('id' => 3)),
			array(array('id' => 1), array('id' => 2))
		);
		$this->assertCount(2, $result->getRemoveList());
	}

	public function testGetReconciliationActionsForAddList(){
		$reconciliation = new SetsReconciliation('id', true);
		$result = $reconciliation->getReconciliationActions(
			array(array('id' => 1), array('id' => 2)),
			array(array('id' => 3))
		);
		$this->assertCount(2, $result->getAddList());
	}

	public function testGetReconciliationActionsForUpdateList(){
		$reconciliation = new SetsReconciliation('id', true);
		$result = $reconciliation->getReconciliationActions(
			array('a' => array('id' => 1), 'b' => array('id' => 2)),
			array(array('id' => 1), array('id' => 2))
		);
		$this->assertCount(2, $result->getUpdateList());
	}

	public function testSetUniqueMaskGetterClosure()
	{
		$reconciliation = new SetsReconciliation();
		$reconciliation->setUniqueMaskGetterClosure(function($item) {
			return $item['name'];
		});
		$result = $reconciliation->getReconciliationActions(
			array(array('name' => 1), array('name' => 2)),
			array(array('name' => 3))
		);
		$this->assertCount(2, $result->getAddList());
		$this->assertCount(1, $result->getRemoveList());
	}
	
}
