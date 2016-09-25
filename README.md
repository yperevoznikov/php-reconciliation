# Reconciliation algorithm on PHP

[![Build Status](https://travis-ci.org/yperevoznikov/reconciliation.svg?branch=master)](https://travis-ci.org/yperevoznikov/reconciliation)

Reconciliation algorithm on PHP language for everyday usage.   
Data synchronization (set Reconciliation problem) is the process of establishing consistency among data from a source to a target data storage and vice versa and the continuous harmonization of the data over time.  
[Data synchronization (Wikiperia)](https://en.wikipedia.org/wiki/Data_synchronization)

## Usage example
```
// Create Reconciliation Algo Class
$reconciliation = new SetsReconciliation();

// Optionally, it's possible to set custom function to create unique identifier
$reconciliation->setUniqueMaskGetterClosure(function($item) {
	return $item['name'];
});

// Perform action...
$sourceSet = array(array('name' => 1), array('name' => 2));
$targetSet = array(array('name' => 3));
$result = $reconciliation->getReconciliationActions($sourceSet, $targetSet);

// Remove Elements from $targetSet
foreach ($result->getRemoveList() as $item) {
  // remove $item from $targetSet
}

// Add Elements to $targetSet
foreach ($result->getAddList() as $item) {
  // add new $item from $targetSet
}

// Sometimes need to update elements in $targetSet , like so
foreach ($result->getUpdateList() as $item) {
  // update $item in $targetSet
}

```
