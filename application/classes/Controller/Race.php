<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

class Controller_Race extends Controller_Core
{
    private function the_racer_name_is_not_filled_out($racer_id)
    {
        return $this->request->post('racer'.$racer_id.'_name') === 'Name...'
            OR $this->request->post('racer'.$racer_id.'_name') === NULL;
    }

    private function process_racers($competition_id, $previous_racer_id = NULL, $previous_racer_password = NULL)
    {
        $welgam_config = Kohana::$config->load('welgam');
        $racer_errors = array();

        for ($racer_id = 1; $racer_id <= 4; $racer_id++)
        {
            if ($this->the_racer_name_is_not_filled_out($racer_id))
                continue;

            $competition = new Welgam\Core\Data\Competition;
            $competition->id = $competition_id;

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
            if ($previous_racer_id !== NULL AND $previous_racer_password !== NULL)
            {
                $racer_registrant->id = $previous_racer_id;
                $racer_registrant->password = $previous_racer_password;
            }

            $usecase = new Welgam\Core\Usecase\Racer\Add(
                ['racer' => $racer, 'racer_registrant' => $racer_registrant],
                ['racer_add' => new Repository_Racer_Add],
                ['emailer' => new Tool_Emailer, 'formatter' => new Tool_Formatter, 'validator' => new Tool_Validator]
            );

            try
            {
                list($previous_racer_id, $previous_racer_password) = $usecase->fetch()->interact();
            }
            catch (Welgam\Core\Exception\Validation $e)
            {
                $racer_errors[$racer_id] = $e->get_errors();
            }
        }

        return array($racer_errors, $previous_racer_id, $previous_racer_password);
    }

    public function action_create()
    {
        if ($this->request->method() === HTTP_Request::POST
            AND $this->request->post('submit') === 'Send me my login')
        {
            // Do something
            die('not yet implemented');
        }
        elseif ($this->request->method() === HTTP_Request::POST)
        {
            $competition = new Welgam\Core\Data\Competition;
            $competition->name = $this->request->post('competition_name');
            $start_date = explode('/', $this->request->post('start_date'));
            $competition->start_date = (int) $start_date[2].$start_date[1].$start_date[0];
            $end_date = explode('/', $this->request->post('end_date'));
            $competition->end_date = (int) $end_date[2].$end_date[1].$end_date[0];
            $competition->stake = $this->request->post('stake');
            $usecase = new Welgam\Core\Usecase\Competition\Add(
                ['competition' => $competition],
                ['competition_add' => new Repository_Competition_Add],
                ['validator' => new Tool_Validator]
            );

            foreach ($this->request->post() as $key => $value)
            {
                $this->view->$key = $value;
            }

            try
            {
                $competition_id = $usecase->fetch()->interact();
            }
            catch (Welgam\Core\Exception\Validation $e)
            {
                return $this->view->competition_errors = $e->get_errors();
            }

            list($racer_errors, $previous_racer_id, $previous_racer_password) = $this->process_racers($competition_id);

            $session = Session::instance();
            $session->set('racer_errors', $racer_errors);
            $session->set('racer_post', $this->request->post());

            $this->redirect(Route::get('view')->uri(array('competition_id' => $competition_id, 'racer_id' => $previous_racer_id, 'racer_password' => $previous_racer_password)));
        }
    }

    public function action_view()
    {
        $session = Session::instance();
        $racer_post = $session->get_once('racer_post', NULL);
        $this->view->racer_errors = $session->get_once('racer_errors', FALSE);
        $this->view->update_errors = $session->get_once('update_errors', FALSE);
        if ($racer_post !== NULL)
        {
            foreach ($racer_post as $key => $value)
            {
                $this->view->$key = $value;
            }
        }

        if ($this->request->post())
        {
            if ($this->request->post('submit') === 'Update my weight')
            {
                $racer = new Welgam\Core\Data\Racer;
                $racer->id = $this->request->param('racer_id');
                $racer->password = $this->request->param('racer_password');
                $update = new Welgam\Core\Data\Update;
                $update->racer = $racer;
                $update->weight = $this->request->post('update_weight');
                if ($this->request->post('update_food') !== 'Today, I ate ... (optional)')
                {
                    $update->food = $this->request->post('update_food');
                }
                $usecase = new Welgam\Core\Usecase\Update\Add(
                    ['update' => $update],
                    ['update_add' => new Repository_Update_Add],
                    ['validator' => new Tool_Validator]
                );

                try
                {
                    $usecase->fetch()->interact();
                }
                catch (Welgam\Core\Exception\Validation $e)
                {
                    $session->set('update_errors', $e->get_errors());
                }

                $previous_racer_id = $this->request->param('racer_id');
                $previous_racer_password = $this->request->param('racer_password');
            }
            else
            {
                list($racer_errors, $previous_racer_id, $previous_racer_password) = $this->process_racers(
                    $this->request->param('competition_id'),
                    $this->request->param('racer_id'),
                    $this->request->param('racer_password')
                );

                $session->set('racer_errors', $racer_errors);
                $session->set('racer_post', $this->request->post());
            }

            $this->redirect(Route::get('view')->uri(array(
                'competition_id' => $this->request->param('competition_id'),
                'racer_id' => $previous_racer_id,
                'racer_password' => $previous_racer_password)));
        }

        $racer = new Welgam\Core\Data\Racer;
        $competition = new Welgam\Core\Data\Competition;
        $competition->id = $this->request->param('competition_id');
        $racer->id = $this->request->param('racer_id');
        $racer->password = $this->request->param('racer_password');
        $racer->competition = $competition;

        $usecase = new Welgam\Core\Usecase\Competition\View(
            ['racer' => $racer],
            ['competition_view' => new Repository_Competition_View]
        );

        $this->view->racer_id = $this->request->param('racer_id');
        $this->view->data = $usecase->fetch()->interact();
    }
}
