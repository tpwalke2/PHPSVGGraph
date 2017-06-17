<?php
// required files
require_once('DataItemComposite.php');
require_once('ColorManager.php');
require_once('Exceptions.php');
require_once('common.php');
/**
 * Page-level doc for AbstractGraph.php, this is a test.
 */
/**
 * This class defines generic behaviour and fields necessary for different types of
 * graphs and charts. It is not meant to be instantiated, since it includes methods
 * that require subclasses to implement.
 * <br /><br />
 * Along with the fields and methods defined below, this class also provides a
 * set of properties that subclass implementations can expand. The following
 * list shows properties defined in this class, their meaning, and their default
 * values. NOTE that padding values are additive, thus a padding of 10 on adjacent
 * sides of the graph and legend will show a total space of 20.
 * <br />
 * <ul>
 * <li>body-background<ul>
 *   <li>string value that indicates the color to fill in the background</li>
 *   <li>white</li></ul></li>
 * <li>display-legend<ul>
 *   <li>boolean value that indicates whether to display a legend or not</li>
 *   <li>true</li></ul></li>
 * <li>doc-height<ul>
 *   <li>string value that controls the document height on screen</li>
 *   <li>5in</li></ul></li>
 * <li>doc-width<ul>
 *   <li>string value that controls the document width on screen</li>
 *   <li>5in</li></ul></li>
 * <li>graph-padding-top<ul>
 *   <li>int value indicating padding to use above the graph</li>
 *   <li>10</li></ul></li>
 * <li>graph-padding-left<ul>
 *   <li>int value indicating padding to use to the left of the graph</li>
 *   <li>10</li></ul></li>
 * <li>graph-padding-bottom<ul>
 *   <li>int value indicating padding to use below the graph</li>
 *   <li>5</li></ul></li>
 * <li>graph-padding-right<ul>
 *   <li>int value indicating padding to use to the right of the graph</li>
 *   <li>10</li></ul></li>
 * <li>legend-border-color<ul>
 *   <li>string value indicating color of border around legend item color boxes</li>
 *   <li>black</li></ul></li>
 * <li>legend-font-color<ul>
 *   <li>string value indicating color of legend item label text</li>
 *   <li>black</li></ul></li>
 * <li>legend-font-family<ul>
 *   <li>string value naming the font family for use in the legend</li>
 *   <li>Arial</li></ul></li>
 * <li>legend-font-size<ul>
 *   <li>int value indicating font point-size for use in the legend</li>
 *   <li>12</li></ul></li>
 * <li>legend-font-weight<ul>
 *   <li>string value indicating weight of legend text</li>
 *   <li>bold</li></ul></li>
 * <li>legend-item-padding<ul>
 *   <li>int value indicating padding between legend items, similar to line-height for fonts</li>
 *   <li>2</li></ul></li>
 * <li>legend-padding-top<ul>
 *   <li>int value indicating padding to use above the legend</li>
 *   <li>5</li></ul></li>
 * <li>legend-padding-left<ul>
 *   <li>int value indicating padding to use to the left of the legend</li>
 *   <li>10</li></ul></li>
 * <li>legend-padding-bottom<ul>
 *   <li>int value indicating padding to use below the legend</li>
 *   <li>10</li></ul></li>
 * <li>legend-padding-right<ul>
 *   <li>int value indicating padding to use to the right of the legend</li>
 *   <li>10</li></ul></li>
 * <li>legend-width<ul>
 *   <li>int value indicating width of legend</li>
 *   <li>100</li></ul></li>
 * <li>standalone<ul>
 *   <li>boolean value indicating whether the generated SVG is for a standalone installation</li>
 *   <li>true</li></ul></li>
 * </ul>
 * 
 * @version v1.0 (2/26/2006): Initial version
 * @author Tom Walker
 * @copyright Copyright © 2006, Walker Consultation, All Rights Reserved.
 * @abstract
 * @package svggraph
 */
abstract class AbstractGraph {
  /**
   * This field contains the list of items with which this graph will work.
   * 
   * @access private
   */
  private $gdilItems = NULL;
  
  /**
   * This field contains a color manager with which to provide colors for this graph.
   * 
   * @access protected
   */
  protected $cmGraphColors = NULL;
  
  /**
   * This field contains the properties for this graph.
   * 
   * @access protected
   */
  protected $graphProperties = NULL;
  
  /**
   * This function constructs a new Graph that will provide a view of the given list
   * of items.
   * 
   * @param GraphDataItemList $itemList list of items for this graph
   * @param ColorManager $cm color-distribution manager
   * @access public
   */
  public function __construct(GraphDataItemList $itemList, ColorManager $cm) {
    $this->setItemList($itemList);
    $this->setColorManager($cm);
    $this->graphProps = array();
    $this->setProperty('body-background', 'white');
    $this->setProperty('display-legend', true);
    $this->setProperty('doc-height', '5in');
    $this->setProperty('doc-width', '5in');
    $this->setProperty('graph-padding-top', 10);
    $this->setProperty('graph-padding-left', 10);
    $this->setProperty('graph-padding-bottom', 5);
    $this->setProperty('graph-padding-right', 10);
    $this->setProperty('legend-border-color', 'black');
    $this->setProperty('legend-font-color', 'black');
    $this->setProperty('legend-font-family', 'Arial');
    $this->setProperty('legend-font-size', 12);
    $this->setProperty('legend-font-weight', 'bold');
    $this->setProperty('legend-item-padding', 2);
    $this->setProperty('legend-padding-top', $this->getProperty('graph-padding-bottom'));
    $this->setProperty('legend-padding-left', $this->getProperty('graph-padding-left'));
    $this->setProperty('legend-padding-bottom', $this->getProperty('graph-padding-bottom'));
    $this->setProperty('legend-padding-right', $this->getProperty('graph-padding-right'));
    $this->setProperty('legend-width', 100);
    $this->setProperty('standalone', true);
  }

  /**
   * This function creates a string-formatted version of this list.
   * 
   * @return string string-formatted version of list
   * @access public
   */
  public function __toString() {
    $class = new ReflectionObject($this);
    return $class->getName() . "<br />\n" . array_to_str($this->procItems);
  }

  /**
   * This function generates the complete SVG definition to display this graph. Since
   * this code will necessarily differ from graph to graph, it must be implemented in
   * all subclasses. The SVG definition will include XML header information if the
   * standalone property is set to true. Furthermore, the SVG definition is returned as
   * a string for the host application to best determine how to use the information.
   * 
   * @param boolean $bStandAlone true if definition is to be a standalone, false otherwise, defaults to false
   * @return string SVG definition for this graph
   * @access public
   * @abstract
   */
  abstract public function generateSVG();
  
  /**
   * This function allows access to the item list.
   * 
   * @return GraphDataItemList the item list for this graph
   * @access public
   */
  public function getItemList() {
    return $this->gdilItems;
  }
  
  /**
   * Sets the list of data items displayed by this graph.
   * 
   * @param GraphDataItemList $itemList list of items to display
   * @access public
   */
  public function setItemList(GraphDataItemList $itemList) {
    $this->gdilItems = $itemList;
  }
  
  /**
   * Returns the value for the given property.
   * 
   * @param string $property name of property to retrieve
   * @return mixed property value
   * @access public
   */
  public function getProperty($property) {
    $ret = $this->graphProperties[$property];
    return $ret;
  }
  
  /**
   * Sets the given property to the given value.
   * 
   * @param string $property name of property to set
   * @param mixed $value value to set for property
   * @access public
   */
  public function setProperty($property, $value) {
    $this->graphProperties[$property] = $value;
  }
  
  /**
   * Sets the color manager for this graph.
   * 
   * @param ColorManager $cm color-distribution manager
   * @access public
   */
  public function setColorManager(ColorManager $cm) {
    $this->cmGraphColors = $cm;
  }
  
  /**
   * Returns the Color Manager for this graph.
   * 
   * @return ColorManager color distribution manager for this graph
   * @access protected
   */
  protected function getColorManager() {
    $ret = $this->cmGraphColors;
    return $ret;
  }

  /**
   * This function generates the SVG XML header required for a standalone SVG installation.
   * 
   * @return string contains XML header information
   * @access protected
   */
  protected function generateSVGHeader() {
    return "<?xml version=\"1.0\"?>\n" .
           "<!DOCTYPE svg PUBLIC \"-//W3C//DTD SVG 1.1//EN\" \n" .
           "\"http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd\">\n";
  }
}
?>
