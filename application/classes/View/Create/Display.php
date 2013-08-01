<?php
defined('SYSPATH') OR die('No direct script access.');

/**
 * Shows site index / homepage.
 */
class View_Create_Display extends View_Layout
{
    public $competition_name = 'Flabicide 2013';
    public $racer1_name = 'Name...';
    public $racer1_weight = 'Weight in kg...';
    public $racer1_height = 'Height in cm...';
    public $racer1_goal_weight = 'Goal weight in kg...';
    public $racer1_email = 'Email...';
    public $racer1_gender = 1;
    public $racer1_ethnicity = 'asian';
    public $racer2_name = 'Name...';
    public $racer2_weight = 'Weight in kg...';
    public $racer2_height = 'Height in cm...';
    public $racer2_goal_weight = 'Goal weight in kg...';
    public $racer2_email = 'Email...';
    public $racer2_gender = 1;
    public $racer2_ethnicity = 'asian';
    public $racer3_name = 'Name...';
    public $racer3_weight = 'Weight in kg...';
    public $racer3_height = 'Height in cm...';
    public $racer3_goal_weight = 'Goal weight in kg...';
    public $racer3_email = 'Email...';
    public $racer3_gender = 1;
    public $racer3_ethnicity = 'asian';
    public $racer4_name = 'Name...';
    public $racer4_weight = 'Weight in kg...';
    public $racer4_height = 'Height in cm...';
    public $racer4_goal_weight = 'Goal weight in kg...';
    public $racer4_email = 'Email...';
    public $racer4_gender = 1;
    public $racer4_ethnicity = 'asian';
    public $stake = 'The loser will have to ...';

    public function remind_success()
    {
        $session = Session::instance();
        return $session->get_once('remind_success');
    }

    public function has_errors()
    {
        return (bool) Session::instance()->get('errors');
    }

    public function errors()
    {
        $error_messages = array();
        $errors = Session::instance()->get_once('errors');
        foreach ($errors as $error)
        {
            if ($error === 'name')
            {
                $error_messages[] = ['error' => 'Please ensure you have a name for your challenge.'];
            }
            elseif ($error === 'start_date')
            {
                $error_messages[] = ['error' => 'Your competition start date cannot be in the past.'];
            }
            elseif ($error === 'end_date')
            {
                $error_messages[] = ['error' => 'Your competition end date should be after the start date.'];
            }
            elseif ($error === 'email')
            {
                $error_messages[] = ['error' => 'You need to provide a valid email address.'];
            }
            elseif ($error === 'participant')
            {
                $error_messages[] = ['error' => 'You do not seem to be a participant in any competitions.'];
            }
        }

        return $error_messages;
    }

    public function racers()
    {
        return array(
            array(
                'n' => 1,
                'tagline' => 'The proud initiator',
                'name' => $this->racer1_name,
                'weight' => $this->racer1_weight,
                'height' => $this->racer1_height,
                'is_male' => ($this->racer1_gender == 1),
                'is_female' => ($this->racer1_gender == 0),
                'is_asian' => ($this->racer1_ethnicity == 'asian'),
                'is_black' => ($this->racer1_ethnicity == 'black'),
                'is_hispanic' => ($this->racer1_ethnicity == 'hispanic'),
                'is_white' => ($this->racer1_ethnicity == 'white'),
                'goal_weight' => $this->racer1_goal_weight,
                'email' => $this->racer1_email
            ),
            array(
                'n' => 2,
                'tagline' => 'The daring nemesis',
                'name' => $this->racer2_name,
                'weight' => $this->racer2_weight,
                'height' => $this->racer2_height,
                'is_male' => ($this->racer2_gender == 1),
                'is_female' => ($this->racer2_gender == 0),
                'is_asian' => ($this->racer2_ethnicity == 'asian'),
                'is_black' => ($this->racer2_ethnicity == 'black'),
                'is_hispanic' => ($this->racer2_ethnicity == 'hispanic'),
                'is_white' => ($this->racer2_ethnicity == 'white'),
                'goal_weight' => $this->racer2_goal_weight,
                'email' => $this->racer2_email
            ),
            array(
                'n' => 3,
                'tagline' => 'The crafty third-party',
                'name' => $this->racer3_name,
                'weight' => $this->racer3_weight,
                'height' => $this->racer3_height,
                'is_male' => ($this->racer3_gender == 1),
                'is_female' => ($this->racer3_gender == 0),
                'is_asian' => ($this->racer3_ethnicity == 'asian'),
                'is_black' => ($this->racer3_ethnicity == 'black'),
                'is_hispanic' => ($this->racer3_ethnicity == 'hispanic'),
                'is_white' => ($this->racer3_ethnicity == 'white'),
                'goal_weight' => $this->racer3_goal_weight,
                'email' => $this->racer3_email
            ),
            array(
                'n' => 4,
                'tagline' => 'The stealthy underdog',
                'name' => $this->racer4_name,
                'weight' => $this->racer4_weight,
                'height' => $this->racer4_height,
                'is_male' => ($this->racer4_gender == 1),
                'is_female' => ($this->racer4_gender == 0),
                'is_asian' => ($this->racer4_ethnicity == 'asian'),
                'is_black' => ($this->racer4_ethnicity == 'black'),
                'is_hispanic' => ($this->racer4_ethnicity == 'hispanic'),
                'is_white' => ($this->racer4_ethnicity == 'white'),
                'goal_weight' => $this->racer4_goal_weight,
                'email' => $this->racer4_email
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
        if ( ! isset($this->start_date))
            return date('d/m/Y', strtotime('today'));
        else
            return $this->start_date;
    }

    public function end_date()
    {
        if ( ! isset($this->end_date))
            return date('d/m/Y', strtotime('next month'));
        else
            return $this->end_date;
    }
}
