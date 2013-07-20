<?php
/**
 * @license MIT
 * Full license text in LICENSE file
 */

class Tool_Emailer implements Welgam\Core\Tool\Emailer
{
    private $instance;

    public function __construct()
    {
        $this->instance = Swift_Message::newInstance();
        $this->instance->setFrom('noreply@weightrace.net');
    }

    /**
     * Sets the 'to' email address.
     *
     * Example:
     * $emailer->set_to(array('foo@bar.com', 'bar@foo.com'));
     *
     * @param array $to An array of email addresses to send to.
     *
     * @return void
     */
    public function set_to($to)
    {
        $this->instance->setTo($to);
    }

    /**
     * Sets the 'from' email address.
     *
     * Example:
     * $emailer->set_from(array('foo@bar.com', 'bar@foo.com'));
     *
     * @param array $from An array of email addresses to send from.
     *
     * @return void
     */
    public function set_from($from)
    {
        $this->instance->setFrom($from);
    }

    /**
     * Sets whether or not the email is HTML.
     *
     * Example:
     * $emailer->set_html(TRUE);
     *
     * @param bool $is_html TRUE is the email is HTML, else FALSE for plaintext
     *
     * @return void
     */
    public function set_html($is_html)
    {
    }

    /**
     * Sets the subject of the email.
     *
     * Example:
     * $emailer->set_subject('Foobar');
     *
     * @param string $subject The subject of the email
     *
     * @return void
     */
    public function set_subject($subject)
    {
        $this->instance->setSubject($subject);
    }

    /**
     * Sets the body of the email.
     *
     * Example:
     * $emailer->set_body('Foo bar foo bar foo bar');
     *
     * @param string $body The body of the email
     *
     * @return void
     */
    public function set_body($body)
    {
        $this->instance->setBody($body);
    }

    /**
     * Sends out a new email.
     *
     * Example:
     * $mail->send();
     *
     * @return void
     */
    public function send()
    {
        $config = Kohana::$config->load('email');
        $smtp = $config->get('smtp');
        $transport = Swift_SmtpTransport::newInstance($smtp['host'], $smtp['port'], 'ssl')
            ->setUsername($smtp['username'])
            ->setPassword($smtp['password']);
        $mailer = Swift_Mailer::newInstance($transport);
        $mailer->send($this->instance);
    }
}
