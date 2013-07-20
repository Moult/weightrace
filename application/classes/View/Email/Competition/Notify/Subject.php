<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

class View_Email_Competition_Notify_Subject extends View_Layout
{
    public $layout = 'plain';

    public function racer_name()
    {
        return $this->data['racer_name'];
    }
}
