<?php

namespace Tests;

use App\Models\User;

trait InteractsWithUsers
{
    public function setUpUser(array $attributes = [])
    {
        $this->logout();

        $this->user = User::factory()->create($attributes);

        $this->login();

        return $this;
    }

    public function login()
    {
        $this->actingAs($this->user);
    }

    public function logout()
    {
        $this->user = null;
    }
}
