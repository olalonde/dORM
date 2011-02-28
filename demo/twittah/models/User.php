<?php

class User {
    public $following = array();
    public $followers = array();

    public $twits = array();

    public $username;

    public $password;

    public $email;

    /**
     * @param User $user
     */
    public function follow($user) {
        foreach ($this->following as $usr)
            if ($usr === $user) return;
        $this->following[] = $user;
    }

    /**
     * @param User $user
     */
    public function unfollow($user) {
        foreach ($this->following as $key => $following)
            if ($following === $user) {
                unset($this->following[$key]);
            }
    }

    /**
     * @param twit $twit
     */
    public function addTwit($twit) {
        $twit->user = $this;
        $this->twits[] = $twit;
    }

    public function getTwits() {
        return $this->twits;
    }

    public function twit($message) {
        if (isset($this->twits[0])) $lazy = 1; // lazyload hack
        $twit = new Twit();
        $twit->message = $message;
        $twit->user = $this;
        $this->twits[] = $twit;
    }
}