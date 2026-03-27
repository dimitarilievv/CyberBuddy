<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();

        $profile = $user->profile;

        if (!$profile) {
            $profile = $user->profile()->create([]);
        }

        return view('settings', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable|min:8|confirmed',
            'avatar' => 'nullable|image|max:1024',
        ]);

        // update user
        $user->update([
            'name' => $request->name,
            'date_of_birth' => $request->date_of_birth,
            'password' => $request->password
                ? Hash::make($request->password)
                : $user->password,
        ]);

        $profile = $user->profile;

        if (!$profile) {
            $profile = $user->profile()->create([]);
        }

        $data = [
            'surname' => $request->surname,
            'school' => $request->school,
            'grade' => $request->grade,
        ];

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $profile->update($data);

        return back()->with('success', 'Updated!');
    }
}
