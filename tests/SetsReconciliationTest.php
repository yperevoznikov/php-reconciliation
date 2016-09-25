<?php
/**
 * Created by PhpStorm.
 * User: yuriy
 * Date: 01/09/15
 * Time: 13:15
 */

use YPReconciliation\SetsReconciliation;

class SetsReconciliationTest extends \PHPUnit_Framework_TestCase {

	public function testGetReconciliationActions(){
		$reconciliation = new SetsReconciliation('id', true);
		$result = $reconciliation->getReconciliationActions([], []);
		$this->assertInstanceOf('YPReconciliation\ReconciliationActions', $result);
	}

	public function testGetReconciliationActionsForRemoveList(){
		$reconciliation = new SetsReconciliation('id', true);
		$result = $reconciliation->getReconciliationActions(
			[['id' => 3]],
			[['id' => 1], ['id' => 2]]
		);
		$this->assertCount(2, $result->getRemoveList());
	}

	public function testGetReconciliationActionsForAddList(){
		$reconciliation = new SetsReconciliation('id', true);
		$result = $reconciliation->getReconciliationActions(
			[['id' => 1], ['id' => 2]],
			[['id' => 3]]
		);
		$this->assertCount(2, $result->getAddList());
	}

	public function testGetReconciliationActionsForUpdateList(){
		$reconciliation = new SetsReconciliation('id', true);
		$result = $reconciliation->getReconciliationActions(
			['a' => ['id' => 1], 'b' => ['id' => 2]],
			[['id' => 1], ['id' => 2]]
		);
		$this->assertCount(2, $result->getUpdateList());
	}
	
}
