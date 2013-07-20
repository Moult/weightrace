<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

class View_Email_Competition_Notify_Body extends View_Layout
{
    public $layout = 'plain';

    public function racer_name()
    {
        return $this->data['racer_name'];
    }

    public function racer_id()
    {
        return $this->data['racer_id'];
    }

    public function competition_name()
    {
        return $this->data['competition_name'];
    }

    public function competition_id()
    {
        return $this->data['competition_id'];
    }

    public function racer_password()
    {
        return $this->data['racer_password'];
    }
}
