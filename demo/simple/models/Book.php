<?php

class Book {
    private $title;
    public $authors; // This will hold an array of Author objects
    public $publishers; // This will hold an array of Publisher objects

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getTitle() {
        return $this->title;
    }
}
