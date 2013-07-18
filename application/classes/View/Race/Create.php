<?php
defined('SYSPATH') OR die('No direct script access.');

/**
 * Shows site index / homepage.
 */
class View_Race_Create extends View_Layout
{
    public function racers()
    {
        return array(
            array(
                'n' => 1,
                'tagline' => 'The proud initiator'
            ),
            array(
                'n' => 2,
                'tagline' => 'The daring nemesis'
            ),
            array(
                'n' => 3,
                'tagline' => 'The crafty third-party'
            ),
            array(
                'n' => 4,
                'tagline' => 'The stealthy underdog'
            )
        );
    }

    public function losers()
    {
        return array(
            array(
                'name' => 'Walter Biggins'
            ),
            array(
                'name' => 'Walter Biggins'
            ),
            array(
                'name' => 'Walter Biggins'
            ),
            array(
                'name' => 'Walter Biggins'
            ),
            array(
                'name' => 'Walter Biggins'
            ),
            array(
                'name' => 'Walter Biggins'
            ),
            array(
                'name' => 'Walter Biggins'
            )
        );
    }

    public function start_date()
    {
        return date('d/m/Y', strtotime('today'));
    }

    public function end_date()
    {
        return date('d/m/Y', strtotime('next month'));
    }
}
