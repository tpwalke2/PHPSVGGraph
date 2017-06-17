<?php
require_once('../ColorManager.php');
require_once('../SVGColorManagers.php');
/**
 * Contains test cases for ColorManager objects.
 *
 * @version v1.0 (Mar 9, 2006)
 * @author Tom Walker
 * @copyright Copyright © 2006, Walker Consultation, All Rights Reserved.
 */
$cm = new RandomizedSVGColorWheelManager();

print $cm->__toString();

try {
  while (true) {
    PRINT $cm->nextColor() . "\n";
  }
} catch (ColorManagerException $e) {
  //PRINT "Error: {$e->getMessage()}\n";
}
?>
