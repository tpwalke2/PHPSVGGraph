<?php
// required files
require_once('Exceptions.php');
require_once('ItemIterator.php');

/**
 * This interface defines behavior for managing a color-distribution scheme. This interface
 * helps provide different sets of incremental colors. 
 *
 * @version v1.0 (2/27/2006): Initial release
 * @author Tom Walker
 * @copyright Copyright © 2006, Walker Consultation, All Rights Reserved.
 * @package walkerent
 */
interface ColorManager {
  /**
   * This function returns a value that indicates a color. The value returned can be any type,
   * but interpretation of said value is left to the host application.
   * 
   * @return mixed value indicating color
   * @access public
   */
  public function nextColor();
  
  /**
   * This function lets the color manager know to reset the colors to the original set.
   * 
   * @access public
   */
  public function resetColors();
}

/**
 * This class provides the ability to set up color-distribution schemes
 * on the fly programmatically.
 * 
 * @example examples/FlexColorManagerExample.php FlexColorManager in use
 * 
 * @version v1.0 (3/13/2006): Initial release
 * @author Tom Walker
 * @copyright Copyright © 2006, Walker Consultation, All Rights Reserved.
 * @package walkerent
 */
class FlexColorManager implements ColorManager, ItemIterator {
  /**
   * Contains an array of color indicators.
   * 
   * @var array color strings
   * @access private
   */
  private $sColors = null;
  
  /**
   * Index into colors array.
   * 
   * @var int index
   * @access private
   */
  private $iCurr = 0;
  
  /**
   * This function constructs a new instance of an iterateable, changeable color
   * distribution manager object.
   * 
   * @access public
   */
  public function __construct() {
    $this->sColors = array();
  }
  
  /**
   * This function returns the next available color. If no colors are available or
   * all colors have been returned, a ColorManagerException is thrown.
   * 
   * @return string string indicator of color
   * @access public
   */
  public function nextColor() {
    $ncRes = '';
    if ($this->isEmpty()) {
      throw new ColorManagerException('No colors available');
    } else if ($this->isDone()) {
      throw new ColorManagerException('All colors iterated');
    } else {
      $ncRes = $this->getCurrentItem();
      $this->setNext();
    }
    return $ncRes;
  }
  
  /**
   * This function resets the color-distribution scheme for redistribution of the stored
   * colors. If there are no colors available, a ColorManagerException is thrown.
   * 
   * @access public
   */
  public function resetColors() {
    if ($this->isEmpty()) {
      throw new ColorManagerException('No colors available');
    } else {
      $this->iCurr = 0;
    }
  }
  
  /**
   * This function moves the iterator pointer to the front of the list of available colors.
   * If no colors are available an ItemIteratorException is thrown.
   * 
   * @access public 
   */
  public function setFirst() {
    try {
      $this->resetColors();
    } catch (RequiredMessageException $e) {
      throw new ItemIteratorException($e->getMessage());
    }
  }
  
  /**
   * This function moves the iterator pointer to the next available
   * color. If no colors are available, or all colors have been visited
   * an ItemIteratorException is thrown.
   * 
   * @access public
   */
  public function setNext() {
    if ($this->isEmpty()) {
      throw new ItemIteratorException('No colors available');
    } else if ($this->isDone()) {
      throw new ItemIteratorException('All colors iterated');
    } else {
      $this->iCurr++;
    }
  }
  
  /**
   * This function determines if all colors have been iterated.
   * 
   * @return boolean true if all colors have been iterated, false otherwise
   * @access public
   */
  public function isDone() {
    return ($this->isEmpty() || ($this->iCurr == (count($this->sColors))));
  }
  
  /**
   * This function returns the currently available color. If all colors
   * have been iterated, or the list is empty, an ItemIteratorException
   * is thrown.
   * 
   * @return string color indicator
   * @access public
   */
  public function getCurrentItem() {
    $gciResult = '';
    if ($this->isEmpty()) {
      throw new ItemIteratorException('No colors available');
    } else if ($this->isDone()) {
      throw new ItemIteratorException('All colors iterated');
    } else {
      $gciResult = $this->sColors[$this->iCurr];
    }
    return $gciResult;
  }
  
  /**
   * Adds the given color to this distribution scheme.
   * 
   * @param string $color color to add
   * @access public
   */
  public function addColor($color) {
    $this->sColors[] = $color;
  }
  
  /**
   * This function determines if there are no colors available.
   * 
   * @return boolean true if there are no colors available, false otherwise
   * @access public
   */
  public function isEmpty() {
    return (count($this->sColors) == 0);
  }
}

/**
 * This class defines a ColorManager interface-specific Exception for implementing classes to utilize
 * to signify error conditions.
 * 
 * @version v1.0 (3/7/2006): Initial release
 * @author Tom Walker 
 * @copyright Copyright © 2006, Tom Walker
 * @package walkerent
 */
class ColorManagerException extends RequiredMessageException {}
?>
