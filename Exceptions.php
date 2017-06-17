<?php
/**
 * This class defines an Exception subclass that makes the exception description message
 * required. All Walker Consultation exceptions must have a message, so this class will
 * serve as the all-time superclass in all Walker Consultation PHP projects. This class
 * is abstract since it should only be used as a superclass, not for instantiation.
 * 
 * @version v1.0 (2/27/2006): Initial version
 * @author Tom Walker
 * @copyright Copyright © 2006, Walker Consultation, All Rights Reserved.
 * @abstract
 * @package walkerent
 */
abstract class RequiredMessageException extends Exception {
  /**
   * This method overrides the parent constructor and requires a message to be sent.
   *
   * @param string $message descriptive error message
   * @param mixed $code error code
   * @access public
   */
  public function __construct($message, $code = 0) {
    parent::__construct($message, $code);
  }
}

/**
 * This class indicates the case where an operation is performed that is not supported
 * by the object throwing it.
 *  
 * @version v1.0 (2/27/2006): Initial version
 * @author Tom Walker
 * @copyright Copyright © 2006, Walker Consultation, All Rights Reserved.
 * @package walkerent
 */
class UnsupportedOperationException extends RequiredMessageException {
  /**
   * Constructs a new UnsupportedOperationException object.
   *
   * @param string $message descriptive error message
   * @param mixed $code error code
   * @access public
   */
  public function __construct($message, $code = 0) {
    parent::__construct($message, $code);
  }
}

/**
 * This class indicates the case where an operation is attempted, but cannot be carried
 * out. Such an operation usually occurs when a required state within the throwing
 * object is not met when the operation is attempted.
 *  
 * @version v1.0 (2/27/2006): Initial version
 * @author Tom Walker
 * @copyright Copyright © 2006, Walker Consultation, All Rights Reserved.
 * @package walkerent
 */
class IllegalOperationException extends RequiredMessageException {
  /**
   * Constructs a new IllegalOperationException object.
   *
   * @param string $message descriptive error message
   * @param mixed $code error code
   * @access public
   */
  public function __construct($message, $code = 0) {
    parent::__construct($message, $code);
  }
}
?>
