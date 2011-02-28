<?php

class Timeline {
    /**
     * The timeline will contain the twits of the following users
     *
     * @var unknown_type
     */
    public $users = array();

    /**
     * @var array
     */
    private $twits;

    /**
     * @return array
     */
    public function getTwits() {
        if (isset($this->twits)) return $this->twits;

        $twits = array();
        foreach ($this->users as $user) {
            foreach ($user->twits as $twit){
                // we assume 1 user will post max 1 twit by second
                $twit->user = $user;
                $twits[$twit->time . '-' . $user->username] = $twit;
            }
        }
        // sort by time
        krsort($twits);

        $this->twits = $twits;

        return $twits;
    }


    public function addUser($user) {
        $this->users[] = $user;
    }
}