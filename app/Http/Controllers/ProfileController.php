<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('pages.profile', ['user' => $request->user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'email', 'max:120', Rule::unique('users')->ignore($user->id)],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'hsk_target' => ['nullable', Rule::in(['HSK1', 'HSK2', 'HSK3', 'HSK4', 'HSK5', 'HSK6'])],
        ]);

        $avatar = $request->file('avatar');
        unset($data['avatar']);

        if ($avatar) {
            $avatarPath = $avatar->store('avatars', 'public');
            if (! $avatarPath) {
                return back()->withInput()->withErrors([
                    'avatar' => 'Không thể lưu ảnh đại diện. Vui lòng thử lại.',
                ]);
            }

            $oldAvatarPath = $user->getRawOriginal('avatar_url');
            $data['avatar_url'] = $avatarPath;
        }

        $user->update($data);

        if (isset($oldAvatarPath) && Str::startsWith($oldAvatarPath, 'avatars/')) {
            Storage::disk('public')->delete($oldAvatarPath);
        }

        return redirect()->route('profile.edit')->with('status', 'Thông tin hồ sơ đã được cập nhật.');
    }
}
