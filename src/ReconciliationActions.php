<?php
/**
 * Created on 25/09/16 01:06
 * @author Yuriy Perevoznikov <yuriless@gmail.com>
 */

namespace YPReconciliation;

/**
 * Class ReconciliationActions
 * Contains actions to perform in order to make second set ($slaveSet in SetsReconciliation::getReconciliationActions())
 * be the same as first one ($masterSet)
 *
 * @package YPReconciliation
 */
class ReconciliationActions
{

    /**
     * @var array
     */
    private $updateList = array();

    /**
     * @var array
     */
    private $removeList = array();

    /**
     * @var array
     */
    private $addList = array();

    /**
     * @param array $updateList
     * @param array $removeList
     * @param array $addList
     */
    public function __construct(array $updateList, array $removeList, array $addList)
    {
        $this->updateList = $updateList;
        $this->removeList  = $removeList;
        $this->addList = $addList;
    }

    /**
     * @return array
     */
    public function getUpdateList()
    {
        return $this->updateList;
    }

    /**
     * @return array
     */
    public function getRemoveList()
    {
        return $this->removeList;
    }

    /**
     * @return array
     */
    public function getAddList()
    {
        return $this->addList;
    }

}