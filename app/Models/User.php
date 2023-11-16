<?php

namespace App\Models;

use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends \App\ModelsBase\User implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use \Illuminate\Auth\Authenticatable, Authorizable, CanResetPassword, MustVerifyEmail;
    use HasApiTokens, HasFactory, Notifiable;


    /**
     * @return null|User
     */
    public static function getById(int $id)
    {
        return self::find($id);
    }

    /**
     * @param int $google_id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null|User
     */
    public static function getByGoogleId(string $google_id)
    {
        return self::query()
            ->where('google_id', $google_id)
            ->first();
    }

    /**
     * @param string $name
     * @param string $email
     * @param string|null $password
     * @param string|null $google_id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|User
     */
    public static function createNewUser(string $name, string $email, ?string $password, ?string $google_id , ?string $google_token )
    {
        return self::query()
            ->create([
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'google_id' => $google_id,
                'google_token' => $google_token,
            ]);
    }

    /**
     * @param \Laravel\Socialite\Two\User|\Laravel\Socialite\Contracts\User $google_user
     * @return User|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null|User
     */
    public static function getOrCreateUserViaSocialiteUser( $google_user)
    {
        $user = User::getByGoogleId($google_user->getId());
        if(!$user)
            $user = User::createNewUser(
                $google_user->getName(),
                $google_user->getEmail(),
                null,
                $google_user->getId(),
                $google_user->token,
            );
        return $user;
    }
}
