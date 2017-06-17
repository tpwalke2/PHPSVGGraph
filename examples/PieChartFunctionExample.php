<?php
// required files
require_once('../PieChart.php');
require_once('../DataItemComposite.php');
require_once('../Exceptions.php');
require_once('../SVGColorManagers.php');

/**
 * This script contains various situations showing the PieChart class.
 *
 * @version v1.0 (3/4/2006)
 * @author Tom Walker
 * @copyright Copyright © 2006, Walker Consultation, All Rights Reserved.
 * 
 * @package svggraphexamples
 */

$ilItemList = new GraphDataItemList('List of items');

for ($i = 1; $i <= 5; $i++) {
  $ilItemList->addChild(new GraphDataItem($i, "String of $i", "Test description of string #$i"));
}
$cm1 = new RandomizedSVGColorWheelManager();
$cm2 = new RandomizedAllSVGColorsManager();

$pcChart = new PieChart($ilItemList, $cm2);
$pcChart->setProperty('legend-font-size', 7);
//echo $pcChart->__toString() . "<br />\n\n";

//echo "****SVG Generation****\n";
//header("Content-type: image/svg-xml");
echo $pcChart->generateSVG(true);
?>
