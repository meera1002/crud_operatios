<?php

namespace App\Contracts;

Interface ActivityInterface
{
    public function getUsers();
    public function findUser( $id );
    public function updateUser( $id,$request );
    public function moreActivity( );
    public function saveActivity( $request );
}