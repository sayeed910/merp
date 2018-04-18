<?php


namespace App\Domain;


interface UserRepository
{
    public function findById($id);

    public function save(User $user);

    public function update(User $user);

    public function delete(User $user);
}
