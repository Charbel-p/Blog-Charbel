<?php

namespace App\Auth;

use Illuminate\Auth\EloquentUserProvider;

class CourrielUserProvider extends EloquentUserProvider
{
    public function retrieveByCredentials(#[\SensitiveParameter] array $credentials)
    {
        if (isset($credentials['email'])) {
            $credentials['courriel'] = $credentials['email'];
            unset($credentials['email']);
        }

        return parent::retrieveByCredentials($credentials);
    }
}
