<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

class Controller_Race extends Controller_Core
{
    public function action_create()
    {
        if ($this->request->method() === HTTP_Request::POST)
        {
            $validator = new Tool_Validator;

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
                ['validator' => $validator]
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

    private function process_racers($competition_id, $previous_racer_id = NULL, $previous_racer_password = NULL)
    {
        $emailer = new Tool_Emailer;
        $formatter = new Tool_Formatter;
        $validator = new Tool_Validator;

        // Add as many racers as possible
        $racer_errors = array();

        for ($i = 1; $i < 5; $i++) {
            if ($this->request->post('racer'.$i.'_name') !== 'Name...'
                AND $this->request->post('racer'.$i.'_name') !== NULL)
            {
                $competition = new Welgam\Core\Data\Competition;
                $competition->id = $competition_id;
                $racer = new Welgam\Core\Data\Racer;
                $racer->name = $this->request->post('racer'.$i.'_name');
                $racer->email = $this->request->post('racer'.$i.'_email');
                $racer->weight = $this->request->post('racer'.$i.'_weight');
                $racer->height = $this->request->post('racer'.$i.'_height');
                $racer->male = (bool) $this->request->post('racer'.$i.'_gender');
                if ($this->request->post('racer'.$i.'_ethnicity') === 'asian')
                {
                    $racer->race = 0;
                }
                elseif ($this->request->post('racer'.$i.'_ethnicity') === 'hispanic')
                {
                    $racer->race = 1;
                }
                elseif ($this->request->post('racer'.$i.'_ethnicity') === 'black')
                {
                    $racer->race = 2;
                }
                elseif ($this->request->post('racer'.$i.'_ethnicity') === 'white')
                {
                    $racer->race = 3;
                }
                $racer->goal_weight = $this->request->post('racer'.$i.'_goal_weight');
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
                    ['emailer' => $emailer, 'formatter' => $formatter, 'validator' => $validator]
                );

                try
                {
                    list($previous_racer_id, $previous_racer_password) = $usecase->fetch()->interact();
                }
                catch (Welgam\Core\Exception\Validation $e)
                {
                    $racer_errors[$i] = $e->get_errors();
                }
            }
        }

        return array($racer_errors, $previous_racer_id, $previous_racer_password);
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
