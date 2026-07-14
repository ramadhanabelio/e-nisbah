<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index', [
            'user' => Auth::user()
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nik' => [
                'required',
                'string',
                'max:20',
                Rule::unique('users')->ignore($user->id)
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id)
            ]
        ]);

        $user->update([
            'name' => $request->name,
            'nik' => $request->nik,
            'email' => $request->email,
        ]);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function changePassword()
    {
        return view('profile.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {

            return back()->withErrors([
                'current_password' => 'Password lama tidak sesuai.'
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()
            ->route('profile.change-password')
            ->with('success', 'Password berhasil diubah.');
    }
}
