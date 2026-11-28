<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hồ sơ cá nhân - Zimora</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/TraTuVung.css', 'resources/js/app.js'])
</head>
<body>
<div class="layout">
    <x-sidebar active="profile" />

    <main class="main">
        <x-navbar-user
            title="Hồ sơ cá nhân"
            subtitle="Cập nhật thông tin tài khoản của bạn"
            icon="fa-user"
        />

        <div class="content">
            <section class="card mx-auto max-w-2xl p-6 sm:p-8">
                <div class="mb-7 flex items-center gap-4">
                    <span class="avatar profile-avatar">
                        @if ($user->avatar_url)
                            <img src="{{ $user->avatar_url }}" alt="Ảnh đại diện của {{ $user->full_name }}">
                        @else
                            <img src="{{ asset('images/logoblue.png') }}" alt="Ảnh đại diện mặc định">
                        @endif
                    </span>
                    <div>
                        <h2 class="text-xl font-bold">{{ $user->full_name }}</h2>
                        <p class="mt-1 text-sm text-muted">{{ $user->email }}</p>
                    </div>
                </div>

                @if (session('status'))
                    <p class="mb-5 rounded-xl bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700" role="status">
                        {{ session('status') }}
                    </p>
                @endif

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="full_name" class="mb-1.5 block text-sm font-semibold">Họ và tên</label>
                        <input id="full_name" name="full_name" type="text" value="{{ old('full_name', $user->full_name) }}"
                            required maxlength="100" autocomplete="name"
                            class="w-full rounded-xl border border-line bg-white px-4 py-3 outline-none transition focus:border-primary">
                        @error('full_name')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="username" class="mb-1.5 block text-sm font-semibold">Tên đăng nhập</label>
                        <input id="username" name="username" type="text" value="{{ old('username', $user->username) }}"
                            required maxlength="50" autocomplete="username"
                            class="w-full rounded-xl border border-line bg-white px-4 py-3 outline-none transition focus:border-primary">
                        @error('username')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="mb-1.5 block text-sm font-semibold">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}"
                            required maxlength="120" autocomplete="email"
                            class="w-full rounded-xl border border-line bg-white px-4 py-3 outline-none transition focus:border-primary">
                        @error('email')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="avatar" class="mb-1.5 block text-sm font-semibold">Đổi ảnh đại diện</label>
                        <input id="avatar" name="avatar" type="file" accept="image/*"
                            class="w-full rounded-xl border border-line bg-white px-4 py-3 outline-none transition file:mr-4 file:rounded-lg file:border-0 file:bg-primary-light file:px-4 file:py-2 file:font-semibold file:text-primary-dark focus:border-primary">
                        <p class="mt-1.5 text-xs text-muted">Chọn ảnh JPG, PNG, GIF hoặc WebP, tối đa 2 MB.</p>
                        @error('avatar')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="hsk_target" class="mb-1.5 block text-sm font-semibold">Mục tiêu HSK</label>
                        <select id="hsk_target" name="hsk_target"
                            class="w-full rounded-xl border border-line bg-white px-4 py-3 outline-none transition focus:border-primary">
                            <option value="">Chưa chọn</option>
                            @foreach (['HSK1', 'HSK2', 'HSK3', 'HSK4', 'HSK5', 'HSK6'] as $level)
                                <option value="{{ $level }}" @selected(old('hsk_target', $user->hsk_target) === $level)>
                                    {{ $level }}
                                </option>
                            @endforeach
                        </select>
                        @error('hsk_target')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end border-t border-line pt-5">
                        <button type="submit" class="rounded-xl bg-primary px-5 py-3 font-semibold text-white transition hover:bg-primary-dark">
                            Lưu thay đổi
                        </button>
                    </div>
                </form>
            </section>
        </div>
    </main>
</div>
</body>
</html>
