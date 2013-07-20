<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

class Repository_Competition_Notify implements Welgam\Core\Usecase\Competition\Notify\Repository
{
    /**
     * @return array(
     *     array($competition_id, $competition_name),
     *     ...
     * );
     */
    public function get_competition_ids_and_names()
    {
        $result_array = array();
        $results = DB::select('id', 'name')
            ->from('competitions')
            ->execute();
        foreach ($results as $result)
        {
            $result_array[] = array(
                $result['id'],
                $result['name']
            );
        }
        return $result_array;
    }

    /**
     * @return array(
     *     array($racer_id, $racer_password, $racer_name, $racer_email),
     *     ...
     * );
     */
    public function get_racers_ids_passwords_names_emails($competition_id)
    {
        $result_array = array();
        $results = DB::select('id', 'password', 'name', 'email')
            ->from('racers')
            ->where('competition', '=', $competition_id)
            ->limit(4)
            ->execute();
        foreach ($results as $result)
        {
            $result_array[] = array(
                $result['id'],
                $result['password'],
                $result['name'],
                $result['email']
            );
        }
        return $result_array;
    }
}
