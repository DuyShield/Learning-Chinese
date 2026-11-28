<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Giới thiệu · PLT học tiếng Trung</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Noto+Sans+SC:wght@700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white font-sans text-ink antialiased">
<header class="bg-[#fff6e5]">
    <div class="mx-auto grid max-w-6xl items-center gap-8 px-6 py-12 md:grid-cols-2">
        <div>
            <div class="text-2xl font-bold">Logo</div>
            <h1 class="mt-6 text-4xl font-extrabold leading-tight md:text-5xl">Giới Thiệu Website Học Tiếng Trung</h1>
            <p class="mt-4 text-gray-600">Học từ vựng, ngữ pháp, nghe, nói, viết và luyện thi HSK trên cùng một nơi, dành cho người mới bắt đầu lẫn người muốn nâng cao.</p>
            <div class="mt-8 flex gap-4">
                @auth
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('trang-chu') }}" class="rounded-full bg-sky-200 px-8 py-3 font-semibold">Vào học</a>
                @else
                    <a href="{{ route('login') }}" class="rounded-full bg-sky-200 px-8 py-3 font-semibold">Đăng Nhập</a>
                    <a href="{{ route('register') }}" class="rounded-full bg-peach px-8 py-3 font-semibold">Đăng Ký</a>
                @endauth    -semibold">Đăng Ký</a>
            </div>
        </div>
        <div aria-hidden="true" class="hanzi relative mx-auto grid h-64 w-full max-w-md grid-cols-3 gap-3 text-5xl font-bold text-white">
            <div class="grid place-items-center rounded-2xl bg-danger">汉</div><div class="grid place-items-center rounded-2xl bg-brand">语</div><div class="grid place-items-center rounded-2xl bg-mint text-ink">学</div>
            <div class="grid place-items-center rounded-2xl bg-lemon text-ink">写</div><div class="grid place-items-center rounded-2xl bg-salmon">听</div><div class="grid place-items-center rounded-2xl bg-peach text-ink">说</div>
        </div>
    </div>
</header>
<section class="mx-auto max-w-6xl px-6 py-14">
    <h2 class="text-center text-3xl font-bold">PLT là website học tiếng Trung thú vị và hiệu quả nhất</h2>
    <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ([['Trò chơi trắc nghiệm', 'Ôn từ vựng và ngữ pháp qua câu hỏi chọn đáp án.', 'bg-salmon/60'], ['Học tập toàn diện', 'Từ vựng, ngữ pháp và thẻ ôn tập theo từng cấp HSK.', 'bg-mint/60'], ['Luyện nói', 'Ghi âm và nghe lại phát âm của chính mình.', 'bg-peach'], ['Luyện viết', 'Tập viết từng chữ Hán trên ô luyện tập.', 'bg-lemon'], ['Luyện nghe', 'Nghe từ, nghe điền từ, nghe chép cả câu.', 'bg-sky-200'], ['Bài tập', 'Đề thi thử theo cấp độ để kiểm tra trình độ.', 'bg-violet-200']] as [$t, $d, $c])
            <article class="rounded-2xl p-6 {{ $c }}"><h3 class="text-lg font-bold">{{ $t }}</h3><p class="mt-2 text-sm text-gray-700">{{ $d }}</p></article>
        @endforeach
    </div>
</section>
<footer class="bg-brand px-6 py-8 text-center text-sm">© {{ date('Y') }} PLT Solutions. Nội dung bài học do nhóm tự biên soạn.</footer>
</body>
</html>
