<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

class Controller_Create extends Controller_Core
{
    private function has_pressed_send_me_my_login()
    {
        return $this->request->method() === HTTP_Request::POST
            AND $this->request->post('submit') === 'Send me my login';
    }

    private function get_racer_remind_usecase()
    {
        $racer = new Welgam\Core\Data\Racer;
        $racer->email = $this->request->post('email');
        return new Welgam\Core\Usecase\Racer\Remind(
            ['racer' => $racer],
            ['racer_remind' => new Repository_Racer_Remind],
            ['emailer' => new Tool_Emailer, 'formatter' => new Tool_Formatter, 'validator' => new Tool_Validator]
        );
    }

    private function has_pressed_ready_set_go()
    {
        return $this->request->method() === HTTP_Request::POST
            AND $this->request->post('submit') === 'Ready. Set. Go.';
    }

    private function get_competition_add_usecase()
    {
        $competition = new Welgam\Core\Data\Competition;
        $competition->name = $this->request->post('competition_name');
        $start_date = explode('/', $this->request->post('start_date'));
        $competition->start_date = (int) $start_date[2].$start_date[1].$start_date[0];
        $end_date = explode('/', $this->request->post('end_date'));
        $competition->end_date = (int) $end_date[2].$end_date[1].$end_date[0];
        $competition->stake = $this->request->post('stake');
        return new Welgam\Core\Usecase\Competition\Add(
            ['competition' => $competition],
            ['competition_add' => new Repository_Competition_Add],
            ['validator' => new Tool_Validator]
        );
    }

    private function execute_racer_remind_usecase()
    {
        try
        {
            $this->get_racer_remind_usecase()->fetch()->interact();
            Session::instance()->set('remind_success', TRUE);
        }
        catch (Welgam\Core\Exception\Validation $e)
        {
            Session::instance()->set('errors', $e->get_errors());
        }
        catch (Welgam\Core\Exception\Authorisation $e)
        {
            Session::instance()->set('errors', array('participant'));
        }
    }

    private function execute_competition_add_usecase()
    {
        try
        {
            return $this->get_competition_add_usecase()->fetch()->interact();
        }
        catch (Welgam\Core\Exception\Validation $e)
        {
            Session::instance()->set('errors', $e->get_errors());
        }
    }

    private function execute_racer_add_usecases($competition_id)
    {
        Request::factory(Route::get('process racers')->uri(array('competition_id' => $competition_id)))
            ->method(Request::POST)
            ->post($this->request->post())
            ->execute();
        $session = Session::instance();
        return array($session->get_once('registrant_id'), $session->get_once('registrant_password'));
    }

    public function action_display()
    {
        if ($this->has_pressed_send_me_my_login())
        {
            $this->execute_racer_remind_usecase();
            $this->redirect(Route::get('homepage')->uri());
        }
        elseif ($this->has_pressed_ready_set_go())
        {
            $competition_id = $this->execute_competition_add_usecase();
            if ($competition_id === NULL)
            {
                $this->redirect(Route::get('homepage')->uri());
            }
            list($registrant_id, $registrant_password) = $this->execute_racer_add_usecases($competition_id);
            $this->redirect(Route::get('view')->uri(array('competition_id' => $competition_id, 'racer_id' => $registrant_id, 'racer_password' => $registrant_password)));
        }

        foreach ($this->request->post() as $key => $value)
        {
            $this->view->$key = $value;
        }
    }
}
