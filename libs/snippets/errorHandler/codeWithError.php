<?php

class Foo
{
    public function __constructor()
    {
        try {
            $a = function ( $int) {
                console.log($int)
            };
            $a(5);
        } catch (Exception $e) {
            var_dump($e);
        }
    }
}

new Foo();
