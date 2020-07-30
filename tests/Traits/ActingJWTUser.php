<?php

namespace Tests\Traits;

use App\Models\User;

trait ActingJWTUser
{
    protected $user;

    public function JWTActingAs()
    {
        $this->user = factory(User::class)->create();
        $this->actingAs($this->user, 'api');

        return $this;
    }
}