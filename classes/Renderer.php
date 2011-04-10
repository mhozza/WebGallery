<?php

require_once 'Gallery.php';
require_once 'ImageResizer.php';//???


/**
 * class Renderer
 * 
 */
class Renderer
{

  /** Aggregations: */

  /** Compositions: */

   /*** Attributes: ***/  
  

  public function render()
  {
    $g = new Gallery();
    $v['items']= $g->getItems();
  }


} // end of Renderer
?>
