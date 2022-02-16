<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Util\Util;

class UserController extends Controller
{
    public function update(Request $request){

        $request->validate([
            'name' => ['required'],
        ]);

        $update = User::updateUser($request);

        return response()->json($update, 201);
    }

    public function getById($id){
        $user = User::getById($id);

        $user->url_img = Util::ImageToBase64($user->url_img);

        return response()->json($user, 200);
    }

    public function storeImage(Request $request){

        $res = User::deleteImage();

        if(!$request->hasFile('image')){
            return;
        }

        $image = $request->file('image');

        $path = '/' . auth()->user()->id;

        // Nome original do arquivo
        $nome_arquivo = $image->getClientOriginalName();
        
        $caminho = 'imagens/usuario' . $path . '/' . $nome_arquivo;

        User::storeImage($caminho);

        // Método para salvar o arquivo no disco
        $image->storeAs($path, $nome_arquivo, 'imgUsuario');


        return response()->json($res, 200);
    }

}


