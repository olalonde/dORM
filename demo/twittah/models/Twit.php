<?php

class Twit {
    /**
     * UNIX timestamp
     *
     * @var integer
     */
    public $time;

    /**
     * @var string
     */
    public $message;

    /**
     * Update author
     *
     * @var User
     */
    public $user;

    public function __construct() {$this->time = time();}
}