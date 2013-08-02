<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

class Controller_Unit extends Controller_Core
{
    public function action_switch()
    {
        $this->toggle_imperial_session_status();
        $this->redirect($this->request->post('currenturl'));
    }

    private function toggle_imperial_session_status()
    {
        $session = Session::instance();

        if ($session->get('imperial', FALSE))
        {
            $session->delete('imperial');
        }
        else
        {
            $session->set('imperial', TRUE);
        }
    }
}
