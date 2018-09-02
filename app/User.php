<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/** @SWG\Tag(
 *   name="user",
 * )
 *
 * @SWG\Definition(
 *   definition="user",
 *   @SWG\Property(
 *      property="user",
 *      type="object",
 *      @SWG\Property(
 *         property="id",
 *         type="integer",
 *         description="User ID"
 *      ),
 *      @SWG\Property(
 *         property="name",
 *         type="string",
 *         description="User Name"
 *      ),
 *      @SWG\Property(
 *         property="email",
 *         type="string",
 *         description="User Email"
 *      ),
 *      @SWG\Property(
 *         property="created_at",
 *         type="string",
 *      ),
 *      @SWG\Property(
 *         property="updated_at",
 *         type="string",
 *      ),
 *        @SWG\Property(
 *         property="api_token",
 *         type="string",
 *      ),
 *   )
 * )
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function generateToken()
    {
        $this->api_token = str_random(60);
        $this->save();

        return $this->api_token;
    }
}
