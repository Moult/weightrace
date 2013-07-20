<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

class Repository_Competition_View implements Welgam\Core\Usecase\Competition\View\Repository
{
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
     * @return array(
     *     'name' => $competition_name,
     *     'start_date' => $competition_start_date,
     *     'end_Date' => $competition_end_date,
     *     'stake' => $competition_stake,
     *     'racers' => array(
     *         array(
     *             'id' => $racer_id,
     *             'name' => $racer_name,
     *             'password' => $racer_password,
     *             'email' => $racer_email,
     *             'height' => $racer_height,
     *             'weight' => $racer_weight',
     *             'goal_weight' => $racer_goal_weight,
     *             'updates' => array(
     *                 array(
     *                     'id' => $update_id,
     *                     'weight' => $update_weight,
     *                     'date' => $update_date,
     *                     'food' => $update_food,
     *                     'racer' => $racer_id,
     *                     'awards' => 'a,b,c,d,e'
     *                 ),
     *                 ...
     *             )
     *         ),
     *         ...
     *     )
     * )
     */
    public function get_competition_details($competition_id)
    {
        $competition = DB::select('name', 'start_date', 'end_date', 'stake')
            ->from('competitions')
            ->where('id', '=', $competition_id)
            ->limit(1)
            ->execute();

        $racers = DB::select('id', 'name', 'password', 'email', 'height', 'weight', 'goal_weight')
            ->from('racers')
            ->where('competition', '=', $competition_id)
            ->order_by('id', 'ASC')
            ->limit(4)
            ->execute();

        $racers_data = array();
        foreach ($racers as $racer)
        {
            $query = DB::query(Database::SELECT, 'SELECT `updates`.`id`, `updates`.`weight`, `updates`.`date`, `updates`.`food`, `updates`.`racer`, GROUP_CONCAT(`awards`.`type`) AS `awards` FROM `updates`, `awards` WHERE `updates`.`racer` = :racer_id AND `awards`.`update` = `updates`.`id` GROUP BY `updates`.`id`');
            $query->param(':racer_id', $racer['id']);
            $updates = $query->execute();

            $racers_data[] = array(
                'id' => $racer['id'],
                'name' => $racer['name'],
                'weight' => $racer['weight'],
                'goal_weight' => $racer['goal_weight'],
                'updates' => $updates
            );
        }

        return array(
            'id' => $competition_id,
            'name' => $competition->get('name'),
            'start_date' => $competition->get('start_date'),
            'end_date' => $competition->get('end_date'),
            'stake' => $competition->get('stake'),
            'racers' => $racers_data
        );
    }

    /**
     * @return bool
     */
    public function does_competition_have_racer($competition_id, $racer_id, $racer_password)
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
