<?php
// required files
require_once('../Exceptions.php');
require_once('../ItemIterator.php');
require_once('../DataItemComposite.php');
require_once('../PieChart.php');

/**
 * This script demonstrates usage of the FlexColorManager object.
 *
 * @version v1.0 (Mar 14, 2006)
 * @author Tom Walker
 * @copyright Copyright © 2006, Walker Consultation, All Rights Reserved.
 */
 
$cm = new FlexColorManager();
$il = new GraphDataItemList('List of items');
$sColors = array('red'=>1, 'green'=>2, 'blue'=>3, 'black'=>4, 'gold'=>5);

foreach ($sColors as $key=>$val) {
  $cm->addColor($key);
  $il->addChild(new GraphDataItem($val, "$key data", ''));
}



$pcChart = new PieChart($il, $cm);
$pcChart->setProperty('legend-font-size', 8);
echo $pcChart->generateSVG(true);
?>
