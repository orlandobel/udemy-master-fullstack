<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //
    public function pruebas(Request $request)
    {
        return "accion de pruebas de UserController";
    }

    public function register(Request $request)
    {
        //dd($request);

        // Recoger los datos del usuario por POST
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        // Validar los datos del usuario
        if (empty($params) || empty($params_array)) {
            $data = [
                'status' => 'error',
                'code' => 400,
                'message' => 'Los datos no pueden estar vacios'
            ];

            return response()->json($data, 400);
        }

        $validate = Validator::make($params_array, [
            'name'      => 'required|alpha',
            'surname'   => 'required|alpha',
            'email'     => 'required|email|unique:users',
            'password'  => 'required'
        ]);

        if ($validate->fails()) {
            $data = [
                'status' => 'error',
                'code' => 400,
                'message' => 'Los datos no son correctos',
                'errors' => $validate->errors()
            ];

            return response()->json($validate->errors(), 400);
        }

        // Cifrar la contraseña
        $pwd = hash('sha256', $params->password);

        // Crear el usuario
        $user = new User();
        $user->name = $params_array['name'];
        $user->surname = $params_array['surname'];
        $user->role = 'ROLE_USER';
        $user->email = $params_array['email'];
        $user->password = $pwd;

        $user->save();

        $data = [
            'status' => 'success',
            'code' => 200,
            'message' => 'El usuario se ha credao correctamente',
            'user' => $user
        ];
        
        return response()->json($data, 200);
    }

    public function login(Request $request)
    {
        $jwtauth = new \JWTAuth();
        $signup = null;

        // Recibir los datos por POST
        $json = $request->input('json');
        $params = json_decode($json);
        $params_array = json_decode($json, True);

        // Validar los datos
        $validated = Validator::make($params_array, [
            'emil' => 'required\email',
            'password' => 'required'
        ]);

        if($validated->fails()) {
            $signup = [
                'status' => 'error',
                'code' => 404,
                'message' => 'El usuario no se ha poido identificar',
                'errors' => $validated->errors()
            ];
        } else {
            $pwd = hash('sha256', $params->password);
            $signup = (empty($params->get_token))? 
                $jwtauth->signup($params->email, $pwd) : $jwtauth->signup($params->email, $pwd, true);
            
        }
        // Cifrar la contraseña
        // Devolver los tokens o datos
        
        $email = 'orlandomalfoy@gmail.com';
        $password = '123456';

        

        return response()->json($signup, 200);
    }

    public function update(Request $request) {
        // Comprovar que el usuario este identificado
        $token = $request->header('Authorization');

        $jwt_auth = new \JWTAuth();
        $checkToken = $jwt_auth->checkToken($token);

        if($checkToken) {
            // Recojer los datos por post
            $json = $request->input(['json']);
            $params_array = json_decode($json, true);

            // Obtener el usuario identificado
            $identified =  $jwt_auth->checkToken($token, true);

            // Validar los datos
            $validate = \Validator::make($params_array, [
                'name'      => 'required|alpha',
                'surname'   => 'required|alpha',
                'email'     => 'required|email|unique:users,'.$identified->sub
            ]);

            // Quitar los campos que no se van a actualizar
            unset($params_array['id']);
            unset($params_array['role']);
            unset($params_array['created_at']);
            unset($params_array['password']);
            unset($params_array['remember_token']);

            // Actualizar el usuario
            $user = User::wher('id', $identified->sub)->update($params_array);

            // Devolver los resultados
            $data = [
                'code' => 200,
                'status' => 'success',
                'message' => $user
            ];
            
        } else {
            // Mensaje de error
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'El usuario no esta autentificado.'
            ];
        }

        return response()->json($data, $data['code']);
    }
}
