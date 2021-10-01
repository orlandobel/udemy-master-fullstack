<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;

use App\Models\User;

class JWTAuth {
    public $key;

    public function __construct() {
        $this->key = 'esto_es_una_clave_super_secreta-99887766';
    }
    
    public function signup($email, $password, $get_token = null) {
        // Buscar si el usuario existe
        $user = User::where([
            'email' => $email,
            'password' => $password
        ])->first();

        // Comprobar si las credenciales son correctas
        $signup = is_object($user)? true : false;
        
        // Generar el toekn con los datos del usuario identifficado
        if($signup) {
            $token = [
                'sub'       => $user->id,
                'email'     => $user->email,
                'name'      => $user->name,
                'surname'   => $user->surname,
                'iat'       => time(),
                'exp'       => time() + (7 * 24 * 60 * 60)
            ];

            $jwt = JWT::encode($token, $this->key, 'HS256');

            // Devolver los datos decodificados o el token en funcion de un parametro
            if(is_null($get_token) || !$get_token)
                return $jwt;
            
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);
            return $decoded;
        } 

        $data = [
            'status' => 'error',
            'message' => 'Login incorrecto'
        ];
              
        return $data;
    }

    public function checkToken($jwt, $getIdentity = false) {
        $auth = false;
        $decoded = null;

        try {
            $jwt = str_replace('"', '', $jwt);

            $decoded = JWT::decode($jwt, $this->key, ['HS256']);    
        } catch(\UnexpectedValueException $e) {
            $auth = false;
        } catch(\DomainException $e) {
            $auth = false;
        }
        
        if(!empty($decoded) && is_object($decoded) && isset($decoded->sub)) {
            $auth = true;
        }

        if($getIdentity) {
            return $decoded;
        }

        return $auth;
    }
}