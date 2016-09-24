<?php

namespace YPReconciliation;

use Closure;

class SetsReconciliation
{

    /**
     * @var string
     */
    private $comparisonProperty;

    /**
     * @var bool
     */
    private $itemArray;

    /**
     * @var Closure
     */
    private $uniqIdClosure;

    public function __construct($comparisonProperty = 'id', $itemArray = false)
    {
        $this->comparisonProperty = $comparisonProperty;
        $this->itemArray = $itemArray;
        $this->uniqIdClosure = function($item) use ($comparisonProperty, $itemArray) {
            if ($itemArray) {
                return $item[$comparisonProperty];
            } else {
                return $item->{$comparisonProperty};
            }
        };
    }

    /**
     * @param array $masterSet
     * @param array $slaveSet
     * @return ReconciliationActions
     */
    public function getReconciliationActions(array $masterSet, array $slaveSet)
    {
        return $this->algorithm1($masterSet, $slaveSet);
    }

    public function algorithm1(array $masterSet, array $slaveSet)
    {
        $toUpdate = array();
        $toRemove = array();
        $toAdd = array();

        $uniqIdClosure = $this->uniqIdClosure;

        $masterIds = array();
        foreach ($masterSet as $key => $masterItem) {
            $masterIds[$key] = $uniqIdClosure($masterItem);
        }
        $slaveIds = array();
        foreach ($slaveSet as $key => $slaveItem) {
            $slaveIds[$key] = $uniqIdClosure($slaveItem);
        }

        foreach ($masterIds as $key => $masterId) {
            if (!in_array($masterId, $slaveIds)) {
                $toAdd[] = $masterSet[$key];
            }
        }

        foreach ($slaveIds as $key => $slaveId) {
            if (!in_array($slaveId, $masterIds)) {
                $toRemove[] = $slaveSet[$key];
            }
        }

        $commonItems = array_intersect($masterIds, $slaveIds);
        foreach ($commonItems as $key => $commonId) {
            $toUpdate[] = $masterIds[$key];
        }

        return new ReconciliationActions($toUpdate, $toRemove, $toAdd);
    }

}