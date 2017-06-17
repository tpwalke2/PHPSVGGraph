<?php
// required files
require_once('../DataItemComposite.php');

/**
 * This script contains a variety of situations that test the DataItemComposite objects
 * and structure.
 * 
 * @version v1.0 (2/27/2006): Initial version
 * @author Tom Walker
 * @copyright Copyright © 2006, Walker Consultation, All Rights Reserved.
 * 
 * @package svggraphexamples
 */

$test = new GraphDataItem(5, 'Num public students', 'Students that came from public universities.');

echo $test->__toString();
?>
