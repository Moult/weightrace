<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

class Controller_View extends Controller_Core
{
    public function action_display()
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

        if ($this->has_pressed_update_my_weight())
        {
            $this->execute_update_add_usecase();
            $this->redirect(Route::get('view')->uri(array(
                'competition_id' => $this->request->param('competition_id'),
                'racer_id' => $this->request->param('racer_id'),
                'racer_password' => $this->request->param('racer_password'))));
        }
        elseif ($this->has_pressed_add_contestant())
        {
            list($registrant_id, $registrant_password) = $this->execute_racer_add_usecases();
            $this->redirect(Route::get('view')->uri(array(
                'competition_id' => $this->request->param('competition_id'),
                'racer_id' => $registrant_id,
                'racer_password' => $registrant_password)));
        }

        $this->view->racer_id = $this->request->param('racer_id');
        $this->view->data = $this->get_competition_view_usecase()->fetch()->interact();
    }

    private function has_pressed_update_my_weight()
    {
        return $this->request->method() === HTTP_Request::POST
            AND $this->request->post('submit') === 'Update my weight';
    }

    private function execute_update_add_usecase()
    {
        try
        {
            $this->get_update_add_usecase()->fetch()->interact();
        }
        catch (Welgam\Core\Exception\Validation $e)
        {
            Session::instance()->set('update_errors', $e->get_errors());
        }
    }

    private function get_update_add_usecase()
    {
        $racer = new Welgam\Core\Data\Racer;
        $racer->id = $this->request->param('racer_id');
        $racer->password = $this->request->param('racer_password');
        $update = new Welgam\Core\Data\Update;
        $update->racer = $racer;
        if (Session::instance()->get('imperial', FALSE))
        {
            $update->weight = $this->request->post('update_weight') * 0.453592;
        }
        else
        {
            $update->weight = $this->request->post('update_weight');
        }

        if ($this->request->post('update_food') !== 'Today, I ate ... (optional)')
        {
            $update->food = $this->request->post('update_food');
        }

        return new Welgam\Core\Usecase\Update\Add(
            ['update' => $update],
            ['update_add' => new Repository_Update_Add],
            ['validator' => new Tool_Validator]
        );
    }

    private function has_pressed_add_contestant()
    {
        return $this->request->method() === HTTP_Request::POST
            AND $this->request->post('submit') === 'Add contestant';
    }

    private function execute_racer_add_usecases()
    {
        Request::factory(Route::get('process racers')->uri(array(
                'competition_id' => $this->request->param('competition_id'),
                'registrant_id' => $this->request->param('racer_id'),
                'registrant_password' => $this->request->param('racer_password')
            )))
            ->method(Request::POST)
            ->post($this->request->post())
            ->execute();
        $session = Session::instance();
        return array($session->get_once('registrant_id'), $session->get_once('registrant_password'));
    }

    private function get_competition_view_usecase()
    {
        $racer = new Welgam\Core\Data\Racer;
        $competition = new Welgam\Core\Data\Competition;
        $competition->id = $this->request->param('competition_id');
        $racer->id = $this->request->param('racer_id');
        $racer->password = $this->request->param('racer_password');
        $racer->competition = $competition;

        return new Welgam\Core\Usecase\Competition\View(
            ['racer' => $racer],
            ['competition_view' => new Repository_Competition_View]
        );
    }
}
