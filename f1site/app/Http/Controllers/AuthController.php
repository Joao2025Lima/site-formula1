<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage; // ESSENCIAL PARA A FOTO

class AuthController extends Controller
{
    public function showLogin() {
        return view('auth.login');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }
        return back()->with('error', 'E-mail ou senha incorretos.');
    }

    public function showRegister() {
        return view('auth.register');
    }

    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'xp' => 0,
            'level' => 1,
        ]);

        return redirect('/login')->with('success', 'Cadastro realizado! Faça o login.');
    }

    // O MÉTODO QUE ESTAVA FALTANDO E CAUSOU O ERRO
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = Auth::user();

        // Se o piloto já tiver uma foto, deletamos a antiga para não lotar o servidor
        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        // Salva a nova imagem na pasta 'profile_photos' dentro de 'storage/app/public'
        $path = $request->file('photo')->store('profile_photos', 'public');
        
        $user->profile_photo = $path;
        $user->save();

        return back()->with('success', 'Foto do cockpit atualizada com sucesso!');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}