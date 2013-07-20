<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

class Repository_Racer_Add implements Welgam\Core\Usecase\Racer\Add\Repository
{
    /**
     * @return bool
     */
    public function does_competition_exist($competition_id)
    {
        return (bool) DB::select('id')
            ->from('competitions')
            ->where('id', '=', $competition_id)
            ->limit(1)
            ->execute()
            ->count();
    }

    /**
     * @return int Unique ID of added racer
     */
    public function add_racer($name, $password, $email, $height, $weight, $male, $race, $goal_weight, $competition_id)
    {
        if ($male === TRUE)
        {
            $gender = 'm';
        }
        else
        {
            $gender = 'f';
        }

        if ($race === 0)
        {
            $ethnicity = 'a';
        }
        elseif ($race === 1)
        {
            $ethnicity = 'h';
        }
        elseif ($race === 2)
        {
            $ethnicity = 'b';
        }
        elseif ($race === 3)
        {
            $ethnicity = 'w';
        }

        list($insert_id, $number_of_rows) = DB::insert('racers', array('name', 'password', 'email', 'height', 'weight', 'gender', 'race', 'goal_weight', 'competition'))
            ->values(array($name, $password, $email, $height, $weight, $gender, $ethnicity, $goal_weight, $competition_id))
            ->execute();

        return $insert_id;
    }

    /**
     * @return string
     */
    public function get_competition_name($competition_id)
    {
        $query = DB::select('name')
            ->from('competitions')
            ->where('id', '=', $competition_id)
            ->limit(1)
            ->execute();
        return $query->get('name');
    }

    /**
     * @return bool
     */
    public function does_competition_have_racers($competition_id)
    {
        return (bool) DB::select('competition')
            ->from('racers')
            ->where('competition', '=', $competition_id)
            ->limit(1)
            ->execute()
            ->count();
    }

    /**
     * @return bool
     */
    public function does_racer_participate_in_competition($racer_id, $racer_password, $competition_id)
    {
        return (bool) DB::select('id')
            ->from('racers')
            ->where('id', '=', $racer_id)
            ->where('password', '=', $racer_password)
            ->where('competition', '=', $competition_id)
            ->limit(1)
            ->execute()
            ->count();
    }
}
