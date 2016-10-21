<?php
/**
 * Created on 25/09/16 01:06
 * @author Yuriy Perevoznikov <yuriless@gmail.com>
 */

namespace YPReconciliation;

use Closure;

/**
 * Class SetsReconciliation
 * @package YPReconciliation
 */
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

    /**
     * @param null|string $comparisonProperty
     * @param bool $itemArray
     */
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

    /**
     * Naive implementation of reconciliation algorithm
     *
     * @param array $masterSet
     * @param array $slaveSet
     * @return ReconciliationActions
     */
    protected function algorithm1(array $masterSet, array $slaveSet)
    {
        $toUpdate = array();
        $toRemove = array();
        $toAdd = array();

        $uniqueMaskGetterClosure = $this->uniqueMaskGetterClosure;

        $masterIds = array();
        $masterItems = array();
        foreach ($masterSet as $key => $masterItem) {
            $masterIds[$key] = $uniqueMaskGetterClosure($masterItem);
            $masterItems[$key] = $masterItem;
        }
        $slaveIds = array();
        $slaveKeys = array();
        foreach ($slaveSet as $key => $slaveItem) {
            $slaveIds[$key] = $uniqueMaskGetterClosure($slaveItem);
            $slaveKeys[$key] = $slaveItem;
        }

        foreach ($masterIds as $key => $masterId) {
            if (!in_array($masterId, $slaveIds)) {
                $toAdd[] = $masterItems[$key];
            }
        }

        foreach ($slaveIds as $key => $slaveId) {
            if (!in_array($slaveId, $masterIds)) {
                $toRemove[] = $slaveKeys[$key];
            }
        }

        $commonItems = array_intersect($masterIds, $slaveIds);
        foreach ($commonItems as $key => $commonId) {
            $toUpdate[] = $masterItems[$key];
        }

        return new ReconciliationActions($toUpdate, $toRemove, $toAdd);
    }

}