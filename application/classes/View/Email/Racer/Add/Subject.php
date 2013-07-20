<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

class View_Email_Racer_Add_Subject extends View_Layout
{
    public $layout = 'plain';

    public function name()
    {
      return $this->data['racer_name'];
    }
}
