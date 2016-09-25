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
    private $uniqueMaskGetterClosure;

    public function __construct($comparisonProperty = null, $itemArray = false)
    {
        if (is_string($comparisonProperty)) {
            $this->comparisonProperty = $comparisonProperty;
        } else {
            $this->comparisonProperty = 'id';
        }

        $this->itemArray = $itemArray;
        $this->setUniqueMaskGetterClosure(function($item) use ($comparisonProperty, $itemArray) {
            if ($itemArray) {
                return $item[$comparisonProperty];
            } else {
                return $item->{$comparisonProperty};
            }
        });
    }

    public function setUniqueMaskGetterClosure(Closure $closure)
    {
        $this->uniqueMaskGetterClosure = $closure;
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

        $uniqueMaskGetterClosure = $this->uniqueMaskGetterClosure;

        $masterIds = array();
        foreach ($masterSet as $key => $masterItem) {
            $masterIds[$key] = $uniqueMaskGetterClosure($masterItem);
        }
        $slaveIds = array();
        foreach ($slaveSet as $key => $slaveItem) {
            $slaveIds[$key] = $uniqueMaskGetterClosure($slaveItem);
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