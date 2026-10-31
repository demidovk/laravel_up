<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class authController extends Controller
{
    public function showRegister()
    {
        return view('register');
    }

    public function storeRegister(request $request)
    {
        $password=Hash::make($request->password);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        User::create([
            'login'=>$request->login,
            'full_name'=>$request->full_name,
            'avatar'=>$avatarPath,
            'password'=>$password,
            'role'=>$request->role,
        ]);
        
        return redirect()->route('show.login')->with('success', 'Вы успешно зарегистрировались!');
    }

    public function showLogin()
    {
        return view('login');
    }


    public function storeLogin(request $request){

        $user=User::where('login', $request->login)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);

            if ($user->role === 'admin') 
            {
                return redirect()->route('show.admin')->with('success', 'Добро пожаловать в кабинет админа');
            }
            else
            {
                return redirect()->route('show.user')->with('success', 'Добро пожаловать в кабинет пользователя');
            }
        }

        return redirect()->back()->with('error', 'Неверный логин или пароль!');
        
        

    }

    public function showLogout(Request $request)
    {

        Auth::logout();

        $request->session()->invalidate();      // полностью сбрасывает сессию
        $request->session()->regenerateToken(); // обновляет CSRF‑токен

        return redirect()->route('show.login');      // или на любую другую страницу
    }
}

