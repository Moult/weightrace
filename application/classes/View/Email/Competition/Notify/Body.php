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

    public function health_tip()
    {
        $tips = array(
            'walk at least 30 minutes daily',
            'eat fruit every day',
            'being vegetarian but eating cheese all the time isn\'t healthier',
            'drink plenty of water',
            'eat slowly',
            'know that healthy food tastes great later',
            'chew food completely before swallowing',
            'don\'t over eat',
            'cut down on sugary foods',
            'weight control is a mixture of lifestyle and diet'
        );

        return $tips[rand(0, count($tips) - 1)];
    }
}
