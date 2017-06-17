<?php
// have to figure out what we are deriving from
require_once('AbstractGraph.php');
require_once('DataItemComposite.php');
require_once('Exceptions.php');
require_once('ColorManager.php');
require_once('common.php');

/**
 * This class defines specific behaviour and fields necessary for a pie chart.
 * <br /><br />
 * While inheriting the properties defined in the parent class
 * <code>AbstractGraph</code>, the PieChart adds the following properties and default values.
 * <br />
 * <ul>
 *   <li>radius<ul>
 *     <li>number indicating radius of pie in display units (not pixels, as SVG handles sizing relative to the viewport)</li>
 *     <li>90</li></ul></li>
 *   <li>outline-slices<ul>
 *     <li>boolean value indicating whether to outline chart slices</li>
 *     <li>true</li></ul></li>
 *   <li>slice-outline-color<ul>
 *     <li>string containing valid SVG color indicator, not used if outline-slices is false</li>
 *     <li>black</li></ul></li>
 *   <li>slice-width<ul>
 *     <li>int value indicating width of slice outline, not used if outline-slices is false</li>
 *     <li>1</li></ul></li>
 * </ul> 
 * 
 * @example examples/PieChartFunctionExample.php Example of a PieChart setup
 * 
 * @version v1.0 (2/26/2006): Initial version
 * @author Tom Walker
 * @copyright Copyright © 2006, Walker Consultation, All Rights Reserved.
 * @package svggraph
 */
class PieChart extends AbstractGraph {
  /**
   * This function creates a new instance of a PieChart object to provide a view of the given
   * list of data items.
   * 
   * @param GraphDataItemList $itemList list of items to display
   * @param ColorManager $cm color-distribution manager
   * @access public
   */
  public function __construct(GraphDataItemList $itemList, ColorManager $cm) {
    parent::__construct($itemList, $cm);
    $this->setProperty('radius', 90);
    $this->setProperty('outline-slices', true);
    $this->setProperty('slice-outline-color', 'black');
    $this->setProperty('slice-width', 1);
    $this->setProperty('legend-width', $this->getProperty('radius') * 2);
  }
  
  /**
   * This function creates the SVG definition for this pie chart and returns it as a string
   * to be used in a manner suitable for the host application.
   * 
   * @return string SVG definition for this pie chart
   * @access public
   */
  public function generateSVG() {
    $il = $this->getItemList();
    $radius = $this->getProperty('radius');
    // setup chart sizing parameters
    $chartwidth = ($radius * 2) + $this->getProperty('graph-padding-left') + $this->getProperty('graph-padding-right');
    $chartheight = ($radius * 2) + $this->getProperty('graph-padding-top') + $this->getProperty('graph-padding-bottom');
    // legend sizing
    $legendwidth = $this->getProperty('legend-width') + $this->getProperty('legend-padding-left') + $this->getProperty('legend-padding-right');
    //need to derive height from font-sizing and total # of items
    $legendheight = ($il->count() + 1) * ($this->getProperty('legend-font-size') + $this->getProperty('legend-item-padding')) + $this->getProperty('legend-padding-top') + $this->getProperty('legend-padding-bottom');
    $totalheight = $chartheight + $legendheight;
    
    // location of circle
    $chartcenterx = $radius + floor(($this->getProperty('graph-padding-left') + $this->getProperty('graph-padding-right')) / 2);
    $chartcentery = $radius + floor(($this->getProperty('graph-padding-top') + $this->getProperty('graph-padding-bottom')) / 2);
    
    // get total amount of item values
    $charttotal = 0;
    $il->setFirst();
    while (!$il->isDone()) {
      $charttotal += $il->getCurrentItem()->getItem();
      $il->setNext();
    }
    
    // begin drawing chart and legend
    $chart = '';
    $legend = '';
    $currdegrees = 0;
    $il->setFirst();
    $legendcolory = $chartheight + $this->getProperty('legend-padding-top');
    while (!$il->isDone())
    {
      // get the color
      $currcolor = '';
      try {
        $currcolor = $this->getColorManager()->nextColor();
      } catch (ColorManagerException $e) {
        $this->getColorManager()->resetColors();
        $currcolor = $this->getColorManager()->nextColor();
      }
      // store current item
      $curritem = $il->getCurrentItem();
      $currvalue = $curritem->getItem();
      $currtitle = $curritem->getTitle();
      $currdesc = $curritem->getDesc();
      $percent = $currvalue / $charttotal;
      
      // move iterator forward
      $il->setNext();
      
      // setup degrees
      $startdeg = round($currdegrees);
      $currdegrees += $percent * 360;
      $enddeg = round($currdegrees);
      // if the total degrees are greater than half the circle, we need to set a special flag
      $largearc = ((($enddeg - $startdeg) > 180) ? 1 : 0);
      
      // get arc points
      list($startx, $starty) = $this->getArcEndPoint($startdeg, $radius * 2);
      list($endx, $endy) = $this->getArcEndPoint($enddeg, $radius * 2);
      
      // comment describes current slice
      $chart .= "<!--$currtitle: $currvalue (of $charttotal) $startdeg to $enddeg degrees in $currcolor-->\n";
      // writing slice
      $chart .= "<path d=\"M $chartcenterx,$chartcentery ";
      $chart .= 'L ' . floor($chartcenterx + $startx) . ',' . floor($chartcentery + $starty);
      $chart .= " A $radius,$radius 0 $largearc 1 " . floor($chartcenterx + $endx) . ',' . floor($chartcentery + $endy) . ' Z"';
      // do we outline the slice?
      $stroke = '';
      if ($this->getProperty('outline-slices')) {
        $stroke .= ' stroke: ' . $this->getProperty('slice-outline-color') . '; stroke-width: ' . $this->getProperty('slice-width') . ';';
      }
      $chart .= " style=\"fill:$currcolor;$stroke\" />\n";
      
      // setup legend
      $legendcolory += $this->getProperty('legend-font-size') + $this->getProperty('legend-item-padding');
      $legendcolorx = $this->getProperty('legend-padding-left');
      $legenditemy = $legendcolory + $this->getProperty('legend-font-size');
      $legenditemx = $legendcolorx + ($this->getProperty('legend-padding-left') * 2) + $this->getProperty('legend-padding-left');
      
      //draw color box
      $legend .= "<rect x=\"$legendcolorx\" y=\"$legendcolory\" width=\"" . $this->getProperty('legend-padding-left') * 2 . "\" height=\"" . $this->getProperty('legend-font-size') . "\" rx=\"0\" ";
      $legend .= "style=\"fill:$currcolor; stroke:" . $this->getProperty('legend-border-color') . "; stroke-width:1;\" />\n";
      //draw label
      $legend .= "<text x=\"$legenditemx\" y=\"$legenditemy\" fill=\"" . $this->getProperty('legend-font-color') . "\" font-size=\"" . $this->getProperty('legend-font-size') . "\" font-family=\"" . $this->getProperty('legend-font-family') . "\">";
      $legend .= "$currtitle: " . sprintf('%01.2f%%', $percent * 100) . "</text>\n";
    }
    
    // setup default return value based on standalone property
    $gsResult = ($this->getProperty('standalone') ? parent::generateSVGHeader() : '');
    $gsResult .= '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" height="' . $this->getProperty('doc-height') . '" width="' . $this->getProperty('doc-width') . '"';
    $gsResult .= " viewBox=\"0 0 $chartwidth $totalheight\">\n";
    $gsResult .= "<rect x=\"0\" y=\"0\" width=\"$chartwidth\" height=\"$totalheight\" style=\"fill: " . $this->getProperty('body-background') . ";\" />\n";
    $gsResult .= "$chart\n" . ($this->getProperty('display-legend') ? "$legend\n" : '') . "</svg>\n";
    return $gsResult;
  }
  
  /**
   * This function calculates an endpoint of an arc of given degrees.
   *
   * @param mixed $degrees
   * @return array array containing x and y coordinates
   * @access private
   */
  private function getArcEndPoint($degrees) {
    // round off degrees
    $degrees += 0.0001;
    $x = cos(deg_to_rad($degrees)) * $this->getProperty('radius');
    $y = sin(deg_to_rad($degrees)) * $this->getProperty('radius');
    return array($x, $y);
  }
}
?>
