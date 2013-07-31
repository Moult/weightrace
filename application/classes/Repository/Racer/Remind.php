<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

class Repository_Racer_Remind implements Welgam\Core\Usecase\Racer\Remind\Repository
{
    /**
     * @return bool
     */
    public function does_racer_with_email_exist($email)
    {
        return (bool) DB::select('id')
            ->from('racers')
            ->where('email', '=', $email)
            ->limit(1)
            ->execute()
            ->count();
    }

    /**
     * @return array(
     *             array(
     *                 'competition_name' => $competition1_name,
     *                 'competition_id' => $competition1_id,
     *                 'racer_id' => $racer_id,
     *                 'racer_password' => $racer_password
     *             ),
     *             // etc
     *         )
     */
    public function get_access_details_of_competitions($email)
    {
        $competition_details = array();
        $results = DB::select(
                array('competitions.name', 'competition_name'),
                array('competitions.id', 'competition_id'),
                array('racers.id', 'racer_id'),
                array('racers.password', 'racer_password')
            )
            ->from('racers')
            ->join('competitions')
            ->on('racers.competition', '=', 'competitions.id')
            ->where('racers.email', '=', $email)
            ->execute();
        foreach ($results as $result)
        {
            $competition_details[] = array(
                'competition_name' => $result['competition_name'],
                'competition_id' => $result['competition_id'],
                'racer_id' => $result['racer_id'],
                'racer_password' => $result['racer_password']
            );
        }
        return $competition_details;
    }
}
