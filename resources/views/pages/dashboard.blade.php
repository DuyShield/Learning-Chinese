<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bảng điều khiển - Zimora</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&family=Noto+Sans+SC:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/TraTuVung.css', 'resources/js/app.js'])
</head>
<body>
    <div class="layout">
        <x-sidebar active="dashboard" />

        <main class="main">
            <x-navbar-user
                title="Trang chủ"
                subtitle="Tổng quan hành trình học tiếng Trung"
                icon="fa-house"
            />

            <div class="content">
                <section class="rounded-2xl bg-linear-to-r from-primary to-[#4677e6] p-7 text-white shadow-soft sm:p-9">
                    <p class="text-sm font-semibold text-white/80">KHÔNG GIAN HỌC TẬP CỦA BẠN</p>
                    <h2 class="mt-2 text-2xl font-bold sm:text-3xl">Xin chào, {{ auth()->user()->full_name }}!</h2>
                    <p class="mt-3 max-w-xl leading-7 text-white/85">Sẵn sàng tiếp tục hành trình chinh phục tiếng Trung chưa? Chọn một hoạt động để bắt đầu hôm nay.</p>
                    <a href="{{ route('vocabulary.user') }}" class="mt-6 inline-flex items-center gap-2 rounded-xl bg-white px-5 py-3 font-semibold text-primary-dark transition hover:bg-blue-50">
                        Tra cứu từ vựng
                        <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                    </a>
                </section>

                <section class="mt-7">
                    <h2 class="text-lg font-bold">Bắt đầu học</h2>
                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        <a href="{{ route('vocabulary.user') }}" class="card flex items-center gap-4 p-5 transition hover:-translate-y-0.5 hover:shadow-md">
                            <span class="grid size-12 shrink-0 place-items-center rounded-xl bg-primary-light text-xl text-primary">
                                <i class="fa-solid fa-layer-group" aria-hidden="true"></i>
                            </span>
                            <span>
                                <span class="block font-bold">Tra cứu từ vựng</span>
                                <span class="mt-1 block text-sm text-muted">Tìm chữ Hán, pinyin và nghĩa.</span>
                            </span>
                        </a>
                        <a href="{{ url('/#lo-trinh') }}" class="card flex items-center gap-4 p-5 transition hover:-translate-y-0.5 hover:shadow-md">
                            <span class="grid size-12 shrink-0 place-items-center rounded-xl bg-amber-50 text-xl text-amber-600">
                                <i class="fa-solid fa-graduation-cap" aria-hidden="true"></i>
                            </span>
                            <span>
                                <span class="block font-bold">Khám phá lộ trình</span>
                                <span class="mt-1 block text-sm text-muted">Xem các cấp độ học HSK.</span>
                            </span>
                        </a>
                    </div>
                </section>
            </div>
        </main>
    </div>
</body>
</html>
