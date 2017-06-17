<?php
// required files
require_once('ColorManager.php');
require_once('common.php');

/**
 * This class provides a color-distribution scheme that randomly generates colors starting
 * with the primaries, then the secondaries, then the tertiaries. Thus, there is a limitation of
 * 12 possible colors to distribute. This Manager is implemented as a singleton, meaning that
 * only one instance is in memory at a time. Once the 12 colors have been exhausted, the host
 * application may need to reset the distribution.
 *
 * @version v1.0 (Mar 6, 2006)
 * @author Tom Walker
 * @copyright Copyright © 2006, Walker Consultation, All Rights Reserved.
 * @final
 * @package svggraph
 */
final class RandomizedSVGColorWheelManager implements ColorManager {
	/**
	 * Array containing names of primary colors.
	 * 
	 * @access private
	 */
  private $sPrimaryColors = array();
  /**
   * Array containing names of secondary colors.
   * 
   * @access private
   */
  private $sSecondaryColors = array();
  /**
   * Array containing names and RGB strings of tertiary colors.
   * 
   * @access private
   */
  private $sTertiaryColors = array();
  
  /**
   * Contains names of all available colors for this manager.
   * 
   * @access private
   */
  private $sColors = array();
  
  /**
   * This field contains the current color string.
   */
  private $sCurrColor = '';
  
  /**
   * Constructs a new color manager that works by randomly assigning colors in order from the primary, secondary, and
   * tertiary color sets.
   * 
   * @access public
   */
  public function __construct() {
    $this->resetColors();
  }
  
  /**
   * This function returns the next color available from this color manager. A ColorManagerException is thrown if there
   * are no further colors available.
   * 
   * @return string color description string
   * @access public
   */
  public function nextColor() {
    if ($this->isDone()) {
      throw new ColorManagerException("No more colors available.");
    }
    
    $this->sCurrColor = array_shift($this->sColors);
    $ncResult = $this->sCurrColor;
    $this->sCurrColor = '';
    return $ncResult;
  }
  
  /**
   * Resets this color-distribution manager to include all of the originally available colors.
   *  
   * @access public
   */
  public function resetColors() {
    $this->sPrimaryColors = array('red', 'blue', 'yellow');
    shuffle($this->sPrimaryColors);
    $this->sSecondaryColors = array('purple', 'green', 'orange');
    shuffle($this->sSecondaryColors);
    $this->sTertiaryColors = array('aquamarine', 'chartreuse', 'orangered', 'magenta', 'violet', 'gold');
    shuffle($this->sTertiaryColors);
    $this->sColors = array_merge($this->sPrimaryColors, $this->sSecondaryColors, $this->sTertiaryColors);
  }
  
  /**
   * This function indicates if this color manager has iterated through each available color.
   * 
   * @return boolean true if all colors visited, false otherwise
   * @access private
   */
  private function isDone() {
  	return empty($this->sColors);
  }
  
  /**
   * Returns a string-formatted version of this color distribution manager.
   * 
   * @return string current state of this color manager in string form
   * @access public
   */
  public function __toString() {
    return array_to_str($this->sColors);
  }
}

/**
 * This class provides a color-distribution scheme that randomly generates colors
 * from a list of all SVG colors. 
 *
 * @version v1.0 (Mar 12, 2006)
 * @author Tom Walker
 * @copyright Copyright © 2006, Walker Consultation, All Rights Reserved.
 * @final
 * @package svggraph
 */
final class RandomizedAllSVGColorsManager implements ColorManager {
  /**
   * Contains names of all available colors for this manager.
   * 
   * @access private
   */
  private $sColors = array();
  
  /**
   * This field contains the current color string.
   */
  private $sCurrColor = '';
  
  /**
   * Constructs a new color manager that works by randomly assigning from a list of all available SVG colors.
   * 
   * @access public
   */
  public function __construct() {
    $this->resetColors();
  }
  
  /**
   * This function returns the next color available from this color manager. A ColorManagerException is thrown if there
   * are no further colors available.
   * 
   * @return string color description string
   * @access public
   */
  public function nextColor() {
    if ($this->isDone()) {
      throw new ColorManagerException("No more colors available.");
    }
    
    $this->sCurrColor = array_shift($this->sColors);
    $ncResult = $this->sCurrColor;
    $this->sCurrColor = '';
    return $ncResult;
  }
  
  /**
   * Resets this color-distribution manager to include all of the originally available colors.
   *  
   * @access public
   */
  public function resetColors() {
    $this->sColors = array('aliceblue', 'antiquewhite', 'aqua', 'aquamarine', 'azure', 'beige', 'bisque', 'black', 'blanchedalmond', 'blue', 'blueviolet', 'brown', 'burlywood', 'cadetblue', 'chartreuse', 'chocolate', 'coral', 'cornflowerblue', 'cornsilk', 'crimson', 'cyan', 'darkblue', 'darkcyan', 'darkgoldenrod', 'darkgray', 'darkgreen', 'darkgrey', 'darkkhaki', 'darkmagenta', 'darkolivegreen', 'darkorange', 'darkorchid', 'darkred', 'darksalmon', 'darkseagreen', 'darkslateblue', 'darkslategray', 'darkslategrey', 'darkturquoise', 'darkviolet', 'deeppink', 'deepskyblue', 'dimgray', 'dimgrey', 'dodgerblue', 'firebrick', 'floralwhite', 'forestgreen', 'fuchsia', 'gainsboro', 'ghostwhite', 'gold', 'goldenrod', 'gray', 'green', 'greenyellow', 'grey', 'honeydew', 'hotpink', 'indianred', 'indigo', 'ivory', 'khaki', 'lavender', 'lavenderblush', 'lawngreen', 'lemonchiffon', 'lightblue', 'lightcoral', 'lightcyan', 'lightgoldenrodyellow', 'lightgray', 'lightgreen', 'lightgrey', 'lightpink', 'lightsalmon', 'lightseagreen', 'lightskyblue', 'lightslategray', 'lightslategrey', 'lightsteelblue', 'lightyellow', 'lime', 'limegreen', 'linen', 'magenta', 'maroon', 'mediumaquamarine', 'mediumblue', 'mediumorchid', 'mediumpurple', 'mediumseagreen', 'mediumslateblue', 'mediumspringgreen', 'mediumturquoise', 'mediumvioletred', 'midnightblue', 'mintcream', 'mistyrose', 'moccasin', 'navajowhite', 'navy', 'oldlace', 'olive', 'olivedrab', 'orange', 'orangered', 'orchid', 'palegoldenrod', 'palegreen', 'paleturquoise', 'palevioletred', 'papayawhip', 'peachpuff', 'peru', 'pink', 'plum', 'powderblue', 'purple', 'red', 'rosybrown', 'royalblue', 'saddlebrown', 'salmon', 'sandybrown', 'seagreen', 'seashell', 'sienna', 'silver', 'skyblue', 'slateblue', 'slategray', 'slategrey', 'snow', 'springgreen', 'steelblue', 'tan', 'teal', 'thistle', 'tomato', 'turquoise', 'violet', 'wheat', 'white', 'whitesmoke', 'yellow', 'yellowgreen');
    shuffle($this->sColors);
  }
  
  /**
   * This function indicates if this color manager has iterated through each available color.
   * 
   * @return boolean true if all colors visited, false otherwise
   * @access private
   */
  private function isDone() {
    return empty($this->sColors);
  }
  
  /**
   * Returns a string-formatted version of this color distribution manager.
   * 
   * @return string current state of this color manager in string form
   * @access public
   */
  public function __toString() {
    return array_to_str($this->sColors);
  }
}
?>