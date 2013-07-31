<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

class View_Race_View extends View_Layout
{
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
    public $max_value = 0;
    public $min_value = 1000;
    private $auth_racer = array('updates' => array());

    public function add_columns()
    {
        $javascript = '';
        foreach ($this->data['racers'] as $racer)
        {
            $javascript .= "data.addColumn('number', '".$racer['name']."');\n";
            $javascript .= "data.addColumn({type:'boolean',role:'emphasis'});\n";
            $javascript .= "data.addColumn('number', '".$racer['name']." Goal');\n";
            $javascript .= "data.addColumn({type:'boolean',role:'certainty'});\n";
        }
        return $javascript;
    }

    public function add_rows()
    {
        $end_date = new DateTime(substr($this->data['end_date'], 0, 4).'-'.substr($this->data['end_date'], 4, 2).'-'.substr($this->data['end_date'], 6));
        $start_date = new DateTime(substr($this->data['start_date'], 0, 4).'-'.substr($this->data['start_date'], 4, 2).'-'.substr($this->data['start_date'], 6));
        $days = $end_date->diff($start_date)->days + 1;

        foreach ($this->data['racers'] as $key => $racer)
        {
            $updates = array();
            foreach ($racer['updates'] as $update)
            {
                $updates[$update['date']] = $update['weight'];
                if ($update['weight'] > $this->max_value)
                {
                    $this->max_value = $update['weight'];
                }
                if ($update['weight'] < $this->min_value)
                {
                    $this->min_value = $update['weight'];
                }
            }
            ${'racer'.$key.'_updates'} = $updates;

            if ($racer['weight'] > $this->max_value)
            {
                $this->max_value = $racer['weight'];
            }
            if ($racer['goal_weight'] > $this->max_value)
            {
                $this->max_value = $racer['goal_weight'];
            }
            if ($racer['weight'] < $this->min_value)
            {
                $this->min_value = $racer['weight'];
            }
            if ($racer['goal_weight'] < $this->min_value)
            {
                $this->min_value = $racer['goal_weight'];
            }
        }

        $rows = array();

        $date = $start_date;
        for ($day = 0; $day < $days; $day++)
        {
            $row_data = array();
            $row_data[] = '\''.$date->format('j/n').'\'';

            foreach ($this->data['racers'] as $key => $racer)
            {
                if (array_key_exists($date->format('Ymd'), ${'racer'.$key.'_updates'}))
                {
                    $row_data[] = (float) ${'racer'.$key.'_updates'}[$date->format('Ymd')];
                }
                else
                {
                    $row_data[] = '';
                }
                $row_data[] = 'true';
                if ($day === 0 OR $day === $days - 1)
                {
                    $row_data[] = $racer['goal_weight'];
                }
                else
                {
                    $row_data[] = '';
                }
                $row_data[] = 'false';
            }

            $date = $date->add(new DateInterval('P1D'));
            $rows[] = $row_data;
        }

        $javascript = '';
        foreach ($rows as $row)
        {
            $javascript .= "[".implode(',', $row)."],\n";
        }
        return $javascript;
    }

    public function max_value()
    {
        return (ceil($this->max_value / 10) * 10) + 10;
    }

    public function min_value()
    {
        return (floor($this->min_value / 10) * 10) - 10;
    }

    public function competition_name()
    {
        return $this->data['name'];
    }

    public function stake()
    {
        return $this->data['stake'];
    }

    public function has_started()
    {
        return (bool) date('Ymd', strtotime('today')) >= $this->data['start_date'];
    }

    public function days_left()
    {
        $end_date = new DateTime(substr($this->data['end_date'], 0, 4).'-'.substr($this->data['end_date'], 4, 2).'-'.substr($this->data['end_date'], 6));
        $start_date = new DateTime(date('Y-m-d', strtotime('today')));
        return $end_date->diff($start_date)->days;
    }

    public function auth_name()
    {
        foreach ($this->data['racers'] as $racer)
        {
            if ($racer['id'] === $this->racer_id)
            {
                $this->auth_racer = $racer;
                return $racer['name'];
            }
        }
    }

    public function has_updated()
    {
        foreach ($this->auth_racer['updates'] as $update)
        {
            if ($update['date'] == date('Ymd', strtotime('today')))
            {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function racers()
    {
        $racers = array();
        $points_array = array();
        $trophy_worth = array(
            'a' => 10,
            'b' => 5,
            'c' => 1,
            'l' => 2,
            'p' => 3,
            'f' => 1
        );
        foreach ($this->data['racers'] as $racer)
        {
            if ($racer['updates'][0]['id'] === NULL)
            {
                $kg_left = abs($racer['weight'] - $racer['goal_weight']);
                $awards = 0;
                $points = 0;
                $current_weight = $racer['weight'];
            }
            else
            {
                $latest_update = array('date' => 0, 'weight' => '');
                $total_awards = 0;
                $total_points = 0;
                foreach ($racer['updates'] as $update)
                {
                    if ( (int) $update['date'] > $latest_update['date'])
                    {
                        $latest_update['date'] = $update['date'];
                        $latest_update['weight'] = $update['weight'];
                    }
                    $awards = explode(',', $update['awards']);
                    $total_awards += count($awards);
                    foreach ($awards as $award)
                    {
                        $total_points += $trophy_worth[$award];
                    }
                }
                $kg_left = abs($racer['goal_weight'] - $latest_update['weight']);
                $awards = $total_awards;
                $points = $total_points;
                $current_weight = $latest_update['weight'];
            }

            $racers[] = array(
                'name' => $racer['name'],
                'goal' => (float) $racer['goal_weight'],
                'kg_left' => $kg_left,
                'current_weight' => (float) $current_weight,
                'awards' => $awards,
                'points' => $points
            );
            $points_array[] = $points;
        }
        $position = array_slice(array('4th', '3rd', '2nd', '1st'), 4 - count($points_array));
        array_multisort($points_array, SORT_ASC, $position);
        $position_key = 0;
        foreach ($racers as $key => $racer)
        {
            $racers[$key]['position'] = $position[$position_key++];
        }
        return $racers;
    }

    public function potential_contestants()
    {
        $registered_contestants = count($this->data['racers']);
        $unregistered_contestants = 4 - $registered_contestants;
        $potential_contestants = array();
        for ($i = $registered_contestants + 1; $i < $registered_contestants + $unregistered_contestants + 1; $i++) {
            $potential_contestants[] = array(
                'n' => $i,
                'name' => $this->{'racer'.$i.'_name'},
                'weight' => $this->{'racer'.$i.'_weight'},
                'height' => $this->{'racer'.$i.'_height'},
                'is_male' => ($this->{'racer'.$i.'_gender'} == 1),
                'is_female' => ($this->{'racer'.$i.'_gender'} == 0),
                'is_asian' => ($this->{'racer'.$i.'_ethnicity'} == 'asian'),
                'is_black' => ($this->{'racer'.$i.'_ethnicity'} == 'black'),
                'is_hispanic' => ($this->{'racer'.$i.'_ethnicity'} == 'hispanic'),
                'is_white' => ($this->{'racer'.$i.'_ethnicity'} == 'white'),
                'goal_weight' => $this->{'racer'.$i.'_goal_weight'},
                'email' => $this->{'racer'.$i.'_email'}
            );
        }
        return $potential_contestants;
    }

    public function has_errors()
    {
        return ( (bool) $this->racer_errors) OR ( (bool) $this->update_errors);
    }

    public function errors()
    {
        $all_errors = array();
        $racers = $this->racer_errors;
        $update_errors = $this->update_errors;
        if ( ! empty($update_errors))
        {
            $all_errors[] = ['message' => 'When updating, make sure you type in a reasonable weight in kg.'];
        }
        if ( ! empty($racers))
        {
            foreach ($racers as $racer_id => $racer)
            {
                foreach ($racer as $error)
                {
                    if ($error === 'email')
                    {
                        $all_errors[] = ['message' => 'Contestant '.$racer_id.' does not have a valid email address.'];
                    }
                    elseif ($error === 'name')
                    {
                        $all_errors[] = ['message' => 'Contestant '.$racer_id.' should have a name.'];
                    }
                    elseif ($error === 'weight')
                    {
                        $all_errors[] = ['message' => 'Contestant '.$racer_id.' should have a reasonable weight in kg.'];
                    }
                    elseif ($error === 'height')
                    {
                        $all_errors[] = ['message' => 'Contestant '.$racer_id.' should have a reasonable height in cm.'];
                    }
                    elseif ($error === 'goal_weight')
                    {
                        $all_errors[] = ['message' => 'Contestant '.$racer_id.' should have a reasonable goal weight in kg.'];
                    }
                }
            }
        }
        return $all_errors;
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
}
