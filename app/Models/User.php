<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'users';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'id_grupoUsuario',
        'situacao',
        'id_funcao',
        'id_supervisor',
        'id_empresa',
        'permite_env_email',
        'permite_env_whats',
        'tel_whats',
        'ramal',
        'url_img'
    ];


    static function index($top = 10000, $id=null){
        $users = User::take($top)
                     ->lock('WITH(NOLOCK)')
                     ->get()
                     ->toArray();

        return $users;
    }
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    /**
     * Get Usuario ID
     *
     * @var array
     */
    static function getById($id){
        $user = User::where('id', $id)
                    ->lock('WITH(NOLOCK)')
                    ->first();

        return $user;
    }

    static function storeImage($imageUrl)
    {

        $response = User::where('id', auth()->user()->id)
                        ->update([
                            'url_img' => $imageUrl
                        ]);

        return $response;
    }

    static function deleteImage()
    {
        $user = User::getById(auth()->user()->id);

        $path = str_replace('imagens/usuario/', '', $user->url_img);

        return Storage::disk('imgUsuario')->delete($path);

    }

    static function updateUser($request)
    {

        $response = User::where('id', Auth()->user()->id)
                        ->update([
                                   'name' => $request->name,
                                   'tel_whats' => $request->telWhats,
                                   'ramal' => $request->ramal
                                 ]);

        return $response;
    }


}
