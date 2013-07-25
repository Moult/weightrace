<?php
defined('SYSPATH') OR die('No direct script access.');

/**
 * Sets up partials, essentially a core file for KOstache.
 */
class View_Layout
{
    public $page_title = 'WeightRace: Competitive weight loss with friends';
    public $meta_description = 'Compete with your friends to lose weight in a fun, easy and free way!';
    public $meta_keywords = 'weightrace, weight, race, compete, lose, loss, gain, game, health, competition';

    /**
     * The base URL of the website.
     *
     * @return string
     */
    public function baseurl()
    {
        return URL::base();
    }

    /**
     * The current page that we are on
     *
     * @return string
     */
    public function currenturl()
    {
        return $this->request->uri();
    }
}
