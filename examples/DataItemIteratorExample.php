<?php
// required files
require_once('../DataItemComposite.php');
require_once('../Exceptions.php');

/**
 * This script contains various situations to show the iteration behaviour and structure
 * in the DataItemComposite class.
 *
 * @version v1.0 (2/27/2006)
 * @author Tom Walker
 * @copyright Copyright &copy; 2006, Walker Consultation, All Rights Reserved.
 * 
 * @package svggraphexamples
 */

$list = new GraphDataItemList('List of items');

for ($i = 0; $i <= 6; $i++) {
  $list->addChild(new GraphDataItem($i, "String of $i", "Test description of string #$i"));
}

echo "run #1<br />\n<br />\n" . $list->__toString() . "<br />\n";

try {
	echo "run #2<br />\n";
  $list->setFirst();
  while (!$list->isDone()) {
    echo $list->getCurrentItem()->__toString() . "<br />\n";
    $list->setNext();
  }
} catch (RequiredMessageException $e) {
  echo "The following error occured while iterating the list: {$e->getMessage()}<br />\n<br />\n";
}

echo "run #3<br />\n<br />\n" . $list->__toString() . "<br />\n";
?>
