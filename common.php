<?php
/**
 * This script contains commonly used functions.
 *
 * @author Tom Walker
 * @copyright Copyright © 2006, Walker Consultation, All Rights Reserved.
 * 
 * @package walkerent
 */

/**#@+
 * Positional constants. 
 */
define('POS_LEFT', 1);
define('POS_RIGHT', 2);
define('POS_TOP', 3);
define('POS_BOTTOM', 4);
/**#@-*/

/**
 * This function translates an array into a string using the same format as print_r.
 * 
 * @version v1.0 (3/5/2006): Initial version
 * @param array $arr array to translate
 * @return string translated array
 */
function array_to_str($arr) {
  ob_start();
  print_r($arr);
  $atsResult = ob_get_contents();
  ob_end_clean();
  return $atsResult;
}

/**
 * This function converts the given degrees into radians.
 * 
 * @version v1.0 (3/10/2006): Initial version
 * @param mixed $degrees degrees to convert
 * @return float radian equivalent of degrees 
 */
function deg_to_rad($degrees) {
  return $degrees * (M_PI/180);
}

/**
 * This function converts the given radians to degrees.
 * 
 * @version v1.0 (3/10/2006): Initial release
 * @param mixed $radians radians to convert
 * @return float degree equivalent of radians
 */
function rad_to_deg($radians) {
  return $radians * (180/M_PI);
}
?>
