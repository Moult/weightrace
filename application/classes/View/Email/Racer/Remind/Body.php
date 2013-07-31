<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

class View_Email_Racer_Remind_Body extends View_Layout
{
    public $layout = 'plain';

    public function competitions()
    {
        return $this->data['competitions'];
    }
}
