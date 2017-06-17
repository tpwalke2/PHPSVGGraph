<?php
// pull in required files
require_once('Exceptions.php');
require_once('ItemIterator.php');
 
/**
 * This class defines generic behaviour and fields for use in a data item Composite
 * hierarchy. It is not meant to be instantiated, thus it has been marked as abstract.
 * 
 * This class hierarchy is established solely for numeric items.
 *  
 * @version v1.0 (2/27/2006): Initial release
 * @author Tom Walker
 * @copyright Copyright © 2006, Walker Consultation, All Rights Reserved.
 * @abstract
 * @package svggraph
 */
abstract class AbstractGraphDataItem {
  /**
   * This field holds the numeric item to store.
   * 
   * @access private
   * @var mixed item
   */
  private $gdItem = NULL;
  
  /**
   * This item holds a quick description of the stored item.
   * 
   * @access private 
   * @var string title
   */
  private $sTitle = '';
  
  /**
   * This item holds a larger description of the stored item.
   * 
   * @access private
   * @var string description
   */
  private $sDesc = '';
  
  /**
   * This function is implemented here for transparency. It is meant for overriding in
   * subclasses and throws an UnsupportedOperationException in all cases where it is not
   * overridden.
   * 
   * @return int number of child items
   * @access public
   */
  public function count() {
    throw new UnsupportedOperationException('count operation is not supported by this object.');
  }
  
  /**
   * This function is implemented here for transparency. It is meant for overriding in
   * subclasses and throws an UnsupportedOperationException in all cases where it is not
   * overridden.
   * 
   * @param AbstractGraphDataItem $child data item to add
   * @return mixed the added item if successful, null otherwise 
   * @access public
   */
  public function addChild(AbstractGraphDataItem $child) {
    throw new UnsupportedOperationException('addChild operation is not supported by this object.');
  }
  
  /**
   * This function is implemented here for transparency. It is meant for overriding in
   * subclasses and throws an UnsupportedOperationException in all cases where it is not
   * overridden.
   * 
   * @param AbstractGraphDataItem $child data item to remove
   * @return mixed the removed item if successful, -1 if $child was not found, null otherwise 
   * @access public
   */
  public function removeChild(AbstractGraphDataItem $child) {
  	throw new UnsupportedOperationException('removeChild operation is not supported by this object.');
  }
  
  /**
   * This function constructs a new data item container with the specified item
   * value, title, and description. This function throws an IllegalOperationException
   * if the given item value is not numeric.
   *
   * @access public
   * @param mixed $item numeric item to store, defaults to 0
   * @param string $title short item identifier, defaults to ''
   * @param string $desc item description, defaults to ''
   */
  public function __construct($item = 0, $title = '', $desc = '') {
    $this->setItem($item);
    $this->setTitle($title);
    $this->setDesc($desc);
  }
  
  /**
   * Retrieves the item stored in this node.
   * 
   * @access public
   * @return mixed stored item
   */
  public function getItem() {
    $ret = $this->gdItem;
    return $ret;
  }
  
  /**
   * Sets the numeric item stored in this node. This function throws an
   * IllegalOperationException if the given item is not numeric.
   * 
   * @access public
   * @param mixed $item item to store
   */
  public function setItem($item) {
  	if (is_numeric($item)) {
      $this->gdItem = $item;
  	} else {
  	  throw new IllegalOperationException('Item stored must be numeric');
  	}
  }
  
  /**
   * Retrieves the title for the item stored in this node.
   * 
   * @access public
   * @return string title of stored item
   */
  public function getTitle() {
    $ret = $this->sTitle;
    return $ret;
  }
  
  /**
   * Sets the title of the item stored in this node.
   * 
   * @access public
   * @param string $title stored item title
   */
  public function setTitle($title) {
    $this->sTitle = $title;
  }
  
  /**
   * Retrieves the description for the item stored in this node.
   * 
   * @access public
   * @return string stored item description
   */
  public function getDesc() {
    $ret = $this->sDesc;
    return $ret;
  }
  
  /**
   * Sets the description of the item stored in this node.
   * 
   * @access public
   * @param string $desc description of stored item
   */
  public function setDesc($desc) {
    $this->sDesc = $desc;
  }
  
  /**
   * Returns a string formatted version of this item.
   * 
   * @example examples/DataItemExample.php An example of how to use this function
   * 
   * @return string data item, formatted
   * @access public
   */
  public function __toString() {
    return "Item:\t\t{$this->gdItem}<br />\nTitle:\t\t{$this->sTitle}<br />\nDescription:\t{$this->sDesc}<br />\n";
  }
}

/**
 * This class defines a data item for graphing. It contains a placeholder for
 * an item to store, a title to quickly identify the item, and an area for a
 * larger description.
 *
 * @version v1.0 (2/26/2006): Initial release
 * @author Tom Walker
 * @copyright Copyright © 2006, Walker Consultation, All Rights Reserved.
 * @package svggraph
 */
class GraphDataItem extends AbstractGraphDataItem {
  /**
   * This function creates a new instance of a data item.
   * 
   * @example examples/DataItemExample.php An example of how to use this constructor
   * 
   * @param mixed $item item to store
   * @param string $title short item identifier, defaults to ''
   * @param string $desc item description, defaults to ''
   * @access public
   */
  public function __construct($item, $title = '', $desc = '') {
    parent::__construct($item, $title, $desc);
  }
}

/**
 * This class defines a graph data item that can hold other graph data items.
 * 
 * @example examples/DataItemIteratorExample.php How to use children iteration
 * 
 * @version v1.0 (2/27/2006): Initial version
 * @author Tom Walker
 * @copyright Copyright © 2006, Walker Consultation, All Rights Reserved.
 * @package svggraph
 */
class GraphDataItemList extends AbstractGraphDataItem implements ItemIterator {
  /**
   * This field stores all child items.
   *
   * @var AbstractGraphDataItem[]
   * @access private
   */
  private $arrChildItems = NULL;
  
  /**
   * This field contains the pointer to the current child in the child iterator.
   *
   * @var integer index into child item array
   * @access private
   */
  private $currChild = 0;
  
  /**
   * This function constructs a new GraphDataItemList.
   *
   * @example examples/DataItemIteratorExample.php An example of an item list construction
   * 
   * @access public
   * @param mixed $item item to store
   * @param string $title short item identifier, defaults to ''
   * @param string $desc item description, defaults to ''
   */
  public function __construct($title = '', $desc = '', $item = 0) {
    parent::__construct($item, $title, $desc);
    $this->arrChildItems = array();
  }
  
  /**
   * This function returns the number of child items stored in this list.
   * 
   * @return int number of child items
   * @access public
   */
  public function count() {
    return count($this->arrChildItems);
  }
  
  /**
   * This function moves the pointer to the first stored child. If there are no child
   * items, an IllegalOperationException is thrown.
   * 
   * @example examples/DataItemIteratorExample.php How to Use the iteration methods
   * 
   * @access public
   */
  public function setFirst() {
    if ($this->isEmpty()) {
      throw new IllegalOperationException('Cannot iterate list when no child items have been stored.');
    } else {
      $this->currChild = 0;
    }
  }
  
  /**
   * This function moves the pointer to the next stored child. If there are no child
   * items or the pointer is at the end of the list, then an IllegalOperationException
   * is thrown.
   * 
   * @example examples/DataItemIteratorExample.php How to Use the iteration methods
   * 
   * @access public
   */
  public function setNext() {
    if ($this->isEmpty()) {
      throw new IllegalOperationException('Cannot iterate list when no child items have been stored.');
    } else if ($this->isDone()) {
      throw new IllegalOperationException('List iteration is complete, cannot advance to next item.'); 
    } else {
      $this->currChild++;
    }
  }
  
  /**
   * This function determines if all children have been visited. If no children have
   * been stored, an IllegalOperationException is thrown.
   * 
   * @example examples/DataItemIteratorExample.php How to Use the iteration methods
   * 
   * @return boolean true if all children have been iterated, false otherwise
   * @access public
   */
  public function isDone() {
    if ($this->isEmpty()) {
      throw new IllegalOperationException('No child items stored, ');
    } else {
      return $this->currChild == count($this->arrChildItems);
    }
  }
  
  /**
   * This function returns the child to which this iterator is currently pointing.
   * 
   * @example examples/DataItemIteratorExample.php How to Use the iteration methods
   * 
   * @return mixed current child 
   * @access public
   */
  public function getCurrentItem() {
    $ret = $this->arrChildItems[$this->currChild];
    return $ret;
  }
  
  /**
   * This function determines if the child item list is empty.
   * 
   * @return boolean true if the list is empty, false otherwise
   * @access public
   */
  public function isEmpty() {
    return empty($this->arrChildItems);
  }
  
  /**
   * This function adds the given child into the structure of this composite list. NOTE:
   * If a user of this class is currently iterating this list, attempting an add operation
   * may disrupt the event. It is recommended, therefore, that you perform all add item
   * operations separately from iterating the list.
   * 
   * @example examples/DataItemIteratorExample.php How to Use the addChild method
   * 
   * @param AbstractGraphDataItem $child child item to store
   * @return mixed the added item if successful, null otherwise
   * @access public
   */
  public function addChild(AbstractGraphDataItem $child) {
  	// set default result
  	$acResult = $child;
  	try {
  		// put value into next available slot
      $this->arrChildItems[] = $child;
  	} catch (Exception $e) {
  		// if a simple assignment screws up, there might be bigger issues than
  		// testing for null, but we'll do it anyway
  		$acResult = NULL;
  	}
  	// our result
    return $acResult;
  }
  
  /**
   * This function removes the given child from storage in this composite list. An exception
   * is thrown if there are no children stored. NOTE: The remove operation will disrupt any
   * in progress iterations.
   * 
   * @param AbstractGraphDataItem $child child item to remove
   * @return mixed the removed item if successful, -1 if $child was not found, null otherwise
   * @access public
   */
  public function removeChild(AbstractGraphDataItem $child) {
  	// set default "not found" result
    $rcResult = -1;
    // make sure we have something to attempt to remove
    if ($this->isEmpty()) {
      throw new IllegalOperationException('No child items stored, '); 
    } else {
      try {
      	// go to beginning of array
        $this->setFirst();
        // loop until we iterate entire child array
        while (!$this->isDone()) {
        	// test for equality
          if ($child == $this->getCurrentItem()) {
          	// found it, set result
          	$rcResult = $child;
          	// delete array value
            unset($this->arrChildItems[$this->currChild]);
            // reindex array
            $this->arrChildItems = array_values($this->arrChildItems);
            // go back to the beginning
            $this->setFirst();
            // kick out
            break;
          } else {
          	// didn't find it, keep going
            $this->setNext();
          }
        }
      } catch (RequiredMessageException $e) {
      	// something happened, set result to null
        $rcResult = NULL;
      }
    }
    // our result
    return $rcResult;
  }
  
  /**
   * This function returns a string-formatted version of this item list including all child
   * items. NOTE: To achieve the formatting for child items, this function uses the built-in
   * iteration mechanism, so any iteration currently in progress will be disrupted.
   * 
   * @return string string-formatted list
   * @access public
   */
  public function __toString() {
  	// show which set we're on
    $tsResult = "Parent List:<br />\n";
    // pre-defined stuff, gets what we want
    $tsResult .= parent::__toString();
    // now to the children
    $tsResult .= "<br />\nn={$this->count()} Children:<br />\n";
    // surround in try block, just in case
    try {
    	// position iterator at front of list
      $this->setFirst();
      // loop until done
      while (!$this->isDone()) {
        // append child string to our results (achieves recursion if child is a list item)
        $tsResult .= $this->getCurrentItem()->__toString() . "<br />\n";
        $this->setNext();
      }
    } catch (RequiredMessageException $e) {
    	// something happened, so do some menial task, the error doesn't matter for now
    	$tsResult .= "error<br />\n";
    	
    }
    return $tsResult;
  }
}
?>
