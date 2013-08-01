<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

class Controller_Racers extends Controller_Core
{
    private $competition_id;
    private $registrant_id;
    private $registrant_password;
    private $racer_errors = array();

    public function before()
    {
        if ($this->request->is_initial())
            throw New HTTP_Exception_404('Internal request only');
    }

    private function the_racer_name_is_not_filled_out($racer_id)
    {
        return $this->request->post('racer'.$racer_id.'_name') === 'Name...'
            OR $this->request->post('racer'.$racer_id.'_name') === NULL;
    }

    private function get_racer_add_usecase($racer_id)
    {
        $welgam_config = Kohana::$config->load('welgam');
        $competition = new Welgam\Core\Data\Competition;
        $competition->id = $this->competition_id;

        $racer = new Welgam\Core\Data\Racer;
        $racer->name = $this->request->post('racer'.$racer_id.'_name');
        $racer->email = $this->request->post('racer'.$racer_id.'_email');
        $racer->weight = $this->request->post('racer'.$racer_id.'_weight');
        $racer->height = $this->request->post('racer'.$racer_id.'_height');
        $racer->male = (bool) $this->request->post('racer'.$racer_id.'_gender');
        $racer->race = $welgam_config['ethnicity_ids'][$this->request->post('racer'.$racer_id.'_ethnicity')];
        $racer->goal_weight = $this->request->post('racer'.$racer_id.'_goal_weight');
        $racer->competition = $competition;

        $racer_registrant = new Welgam\Core\Data\Racer;
        if ($this->registrant_id !== NULL AND $this->registrant_password !== NULL)
        {
            $racer_registrant->id = $this->registrant_id;
            $racer_registrant->password = $this->registrant_password;
        }

        return new Welgam\Core\Usecase\Racer\Add(
            ['racer' => $racer, 'racer_registrant' => $racer_registrant],
            ['racer_add' => new Repository_Racer_Add],
            ['emailer' => new Tool_Emailer, 'formatter' => new Tool_Formatter, 'validator' => new Tool_Validator]
        );
    }

    private function execute_racer_add_usecase($racer_id)
    {
        try
        {
            list($this->registrant_id, $this->registrant_password) = $this->get_racer_add_usecase($racer_id)->fetch()->interact();
        }
        catch (Welgam\Core\Exception\Validation $e)
        {
            $this->racer_errors[$racer_id] = $e->get_errors();
        }
    }

    public function action_process()
    {
        $this->competition_id = $this->request->param('competition_id');
        $this->registrant_id = $this->request->param('registrant_id');
        $this->registrant_password = $this->request->param('registrant_password');

        for ($racer_id = 1; $racer_id <= 4; $racer_id++)
        {
            if ($this->the_racer_name_is_not_filled_out($racer_id))
                continue;

            $this->execute_racer_add_usecase($racer_id);
        }

        $session = Session::instance();
        $session->set('racer_errors', $this->racer_errors);
        $session->set('racer_post', $this->request->post());
        $session->set('registrant_id', $this->registrant_id);
        $session->set('registrant_password', $this->registrant_password);
    }
}
