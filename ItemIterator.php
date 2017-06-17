<?php
// required files
require_once('Exceptions.php');

/**
 * This interface defines behaviour necessary for iterators.
 * 
 * @version v1.0 (2/27/2006): Initial release
 * @author Tom Walker
 * @copyright Copyright © 2006, Walker Consultation, All Rights Reserved.
 * @package walkerent
 */
interface ItemIterator {
  /**
   * This function tells the iterator to go to the first item.
   * 
   * @access public
   */
  public function setFirst();
  
  /**
   * This function tells the iterator to move to the next item.
   * 
   * @access public
   */
  public function setNext();
  
  /**
   * This function determines if the iterator has iterated over all items.
   * 
   * @return boolean true if all items have been iterated, false otherwise
   * @access public
   */
  public function isDone();
  
  /**
   * This function returns the item to which this iterator is currently pointing.
   * 
   * @return mixed current item
   * @access public
   */
  public function getCurrentItem();
}

/**
 * This exception indicates errors in ItemIterator implementing objects.
 * 
 * @version v1.0 (3/13/2006): Initial release
 * @author Tom Walker
 * @copyright Copyright © 2006, Walker Consultation, All Rights Reserved.
 * @package walkerent
 */
class ItemIteratorException extends RequiredMessageException {
}
?>
