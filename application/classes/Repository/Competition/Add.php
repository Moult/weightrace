<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

class Repository_Competition_Add implements Welgam\Core\Usecase\Competition\Add\Repository
{
    /**
     * @return int Unique ID of saved competition
     */
    public function add_competition($name, $private, $start_date, $end_date, $stake)
    {
        list($insert_id, $number_of_rows) = DB::insert('competitions', array('name', 'private', 'start_date', 'end_date', 'stake'))
            ->values(array($name, FALSE, $start_date, $end_date, $stake))
            ->execute();

        return $insert_id;
    }
}
