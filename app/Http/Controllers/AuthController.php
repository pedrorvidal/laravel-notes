<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function loginSubmit(Request $request)
    {
        //Form Validation
        $request->validate(
            //rules
            [
                'text_username' => 'required|email',
                'text_password' => 'required|min:6|max:16'
            ],
            //error messages
            [
                'text_username.required' => 'O username é obrigatório',
                'text_username.email' => 'Username deve ser um email válido',
                'text_password.required' => 'Password é obrigatório',
                'text_password.min' => 'A Password deve ter pelo menos :min caracteres',
                'text_password.max' => 'A Password deve ter no máximo :max caracteres',
            ]
        );

        //get user input
        $username = $request->input('text_username');
        $password = $request->input('text_password');

        //check if user exists
        $user = User::where('username', $username)
            ->where('deleted_at', NULL)
            ->first();

        if (!$user) {
            return redirect()
                ->back()
                ->withInput()
                ->with('loginError', 'Usuário/senha incorretos.');
        }

        // check if password is correct
        if (!password_verify($password, $user->password)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('loginError', 'Usuário/senha incorretos.');
        }
        //update last_login
        $user->last_login = date('Y-m-d H:i:s');
        $user->save();

        //create session
        session([
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
            ],
        ]);

        echo "Login com sucesso!";
        dd($user);
    }

    public function logout()
    {
        session()->forget('user');
        return redirect()->to('/login');
    }
}
