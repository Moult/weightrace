<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * @license MIT
 * Full license text in LICENSE file
 */

class Task_Notify extends Minion_Task
{
    protected $_defaults = array();

    /**
     * This sends a reminder to everybody using WeightRace
     *
     * @return null
     */
    protected function _execute(array $params)
    {
        $competition_notify = new Repository_Competition_Notify;
        $emailer = new Tool_Emailer;
        $formatter = new Tool_Formatter;
        $usecase = new Welgam\Core\Usecase\Competition\Notify(
            ['competition_notify' => $competition_notify],
            ['emailer' => $emailer, 'formatter' => $formatter]
        );
        $usecase->fetch()->interact();
    }
}
