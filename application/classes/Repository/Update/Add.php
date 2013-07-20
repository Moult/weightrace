<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

class Repository_Update_Add implements Welgam\Core\Usecase\Update\Add\Repository
{
    /**
     * @return bool
     */
    public function does_racer_exist($racer_id, $racer_password)
    {
        return (bool) DB::select('id')
            ->from('racers')
            ->where('id', '=', $racer_id)
            ->where('password', '=', $racer_password)
            ->limit(1)
            ->execute()
            ->count();
    }

    /**
     * @return bool
     */
    public function does_update_exist($update_date, $racer_id)
    {
        return (bool) DB::select('id')
            ->from('updates')
            ->where('date', '=', $update_date)
            ->where('racer', '=', $racer_id)
            ->limit(1)
            ->execute()
            ->count();
    }

    /**
     * @return int Unique ID of update
     */
    public function add_update($update_weight, $update_food, $update_date, $racer_id)
    {
        list($insert_id, $updated_rows) = DB::insert('updates', array('weight', 'food', 'date', 'racer'))
            ->values(array($update_weight, $update_food, $update_date, $racer_id))
            ->execute();
        return $insert_id;
    }

    /**
     * @return array($competition_id, $competition_start_date, $competition_end_date)
     */
    public function get_competition_id_and_start_and_end_dates($racer_id)
    {
        $query = DB::query(Database::SELECT, 'SELECT `competitions`.`id`, `competitions`.`start_date`, `competitions`.`end_date` FROM `racers`, `competitions` WHERE `racers`.`id` = :racer_id AND `racers`.`competition` = `competitions`.`id` LIMIT 1');
        $query->param(':racer_id', $racer_id);
        $result = $query->execute();
        return array($result->get('id'), $result->get('start_date'), $result->get('end_date'));
    }

    /**
     * @return void
     */
    public function add_award($award_type, $update_id)
    {
        DB::insert('awards', array('update', 'type'))
            ->values(array($update_id, strtolower(substr($award_type, 0, 1))))
            ->execute();
    }

    /**
     * @return array($racer_weight, $racer_height, $racer_goal_weight)
     */
    public function get_weight_and_height_and_goal_weight($racer_id)
    {
        $result = DB::select('weight', 'height', 'goal_weight')
            ->from('racers')
            ->where('id', '=', $racer_id)
            ->limit(1)
            ->execute();
        return array($result->get('weight'), $result->get('height'), $result->get('goal_weight'));
    }

    /**
     * @return array($update_date, $update_weight)
     */
    public function get_previous_update_date_and_weight($racer_id)
    {
        $result = DB::select('date', 'weight')
            ->from('updates')
            ->where('racer', '=', $racer_id)
            ->where('date', '<', date('Ymd', strtotime('today')))
            ->order_by('date', 'DESC')
            ->limit(1)
            ->execute();
        return array($result->get('date'), $result->get('weight'));
    }
}
