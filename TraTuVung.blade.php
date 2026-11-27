<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Từ vựng</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&family=Noto+Sans+SC:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/TraTuVung.css') }}">
</head>
<body>

@php
    // ===== Dữ liệu tĩnh =====
    $menu = [
        ['icon' => 'fa-house',          'text' => 'Trang chủ',  'active' => false],
        ['icon' => 'fa-pen-nib',        'text' => 'Luyện viết', 'active' => false],
        ['icon' => 'fa-microphone',     'text' => 'Luyện nói',  'active' => false],
        ['icon' => 'fa-layer-group',    'text' => 'Từ vựng',    'active' => true],
        ['icon' => 'fa-graduation-cap', 'text' => 'Ngữ pháp',   'active' => false],
        ['icon' => 'fa-brain',          'text' => 'Ôn tập',     'active' => false],
        ['icon' => 'fa-trophy',         'text' => 'Luyện thi',  'active' => false],
    ];

    $goiY = [
        ['tu' => '朋友', 'pinyin' => 'péngyou',  'nghia' => 'Bạn bè'],
        ['tu' => '明天', 'pinyin' => 'míngtiān', 'nghia' => 'Ngày mai'],
        ['tu' => '什么', 'pinyin' => 'shénme',   'nghia' => 'Cái gì'],
        ['tu' => '太阳', 'pinyin' => 'tàiyáng',  'nghia' => 'Mặt trời'],
        ['tu' => '谢谢', 'pinyin' => 'xièxie',   'nghia' => 'Cảm ơn'],
        ['tu' => '学习', 'pinyin' => 'xuéxí',    'nghia' => 'Học tập'],
        ['tu' => '喜欢', 'pinyin' => 'xǐhuan',   'nghia' => 'Thích'],
        ['tu' => '老师', 'pinyin' => 'lǎoshī',   'nghia' => 'Giáo viên'],
    ];
@endphp

<div class="layout">
    <!-- ===== Sidebar ===== -->
    <aside class="sidebar" id="sidebar">
        <div class="logo">
            <span class="logo-icon">中</span>
            <span>Logo</span>
        </div>

        <nav class="menu">
            @foreach ($menu as $item)
                <a href="#" class="menu-item {{ $item['active'] ? 'active' : '' }}">
                    <i class="fa-solid {{ $item['icon'] }}"></i>
                    <span>{{ $item['text'] }}</span>
                </a>
            @endforeach
        </nav>

        <div class="sidebar-bottom">
            <a href="#" class="user-box">
                <div class="avatar"><i class="fa-solid fa-user"></i></div>
                <div>
                    <p class="user-name">Profile User</p>
                    <p class="user-sub">Xem hồ sơ</p>
                </div>
            </a>
            <a href="#" class="btn-logout">
                <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
            </a>
        </div>
    </aside>
    <div class="overlay" id="overlay"></div>

    <!-- ===== Nội dung chính ===== -->
    <main class="main">
        <header class="header">
            <button class="btn-menu" id="btnMenu"><i class="fa-solid fa-bars"></i></button>
            <div class="header-icon"><i class="fa-solid fa-layer-group"></i></div>
            <div>
                <h1>Từ vựng</h1>
                <p>Nắm vững từ vựng tiếng Trung</p>
            </div>
        </header>

        <div class="content">
            <!-- Tìm kiếm -->
            <div class="search-wrap">
                <form class="search-box" id="searchForm" autocomplete="off">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="searchInput" placeholder="Nhập chữ Hán, pinyin hoặc nghĩa tiếng Việt...">
                    <kbd>/</kbd>
                    <button type="submit">Tra từ <i class="fa-solid fa-arrow-right"></i></button>
                </form>
                <ul class="dropdown" id="dropdown"></ul>
            </div>

            <div class="recent" id="recentBox">
                <span class="recent-label"><i class="fa-solid fa-clock-rotate-left"></i> Tra gần đây:</span>
                <div id="recentList"></div>
            </div>

            <!-- Thẻ từ vựng -->
            <section class="card word-card">
                <div class="word-top">
                    <div>
                        <div class="word-head">
                            <span class="hanzi" id="hanzi">你好</span>
                            <span class="pinyin" id="pinyin">nǐ hǎo</span>
                        </div>
                        <p class="meaning" id="meaning">Xin chào</p>
                        <div class="badges">
                            <span class="badge" id="hsk">HSK 1</span>
                            <span class="badge badge-light" id="loai">Thán từ</span>
                        </div>
                    </div>

                    <div class="actions">
                        <button class="icon-btn primary" id="btnSound" title="Phát âm">
                            <i class="fa-solid fa-volume-high"></i>
                        </button>
                        <button class="icon-btn" id="btnCopy" title="Sao chép">
                            <i class="fa-regular fa-copy"></i>
                        </button>
                        <button class="icon-btn" id="btnFav" title="Lưu từ">
                            <i class="fa-regular fa-star"></i>
                        </button>
                    </div>
                </div>

                <h3 class="section-title">Định nghĩa</h3>
                <div class="definition">
                    <p class="def-title" id="defTitle">Xin chào; chào</p>
                    <p class="def-desc" id="defDesc">Lời chào khi gặp ai, bắt đầu cuộc trò chuyện hoặc khi nhấc điện thoại.</p>
                </div>

                <h3 class="section-title">Ví dụ</h3>
                <div class="example">
                    <div>
                        <p class="ex-zh" id="exZh">你好，今天怎么样？</p>
                        <p class="ex-py" id="exPy">Nǐ hǎo, jīntiān zěnmeyàng?</p>
                        <p class="ex-vi" id="exVi">Xin chào, hôm nay bạn ổn không?</p>
                    </div>
                    <button class="icon-btn small" id="btnSoundEx" title="Nghe câu ví dụ">
                        <i class="fa-solid fa-volume-low"></i>
                    </button>
                </div>
            </section>

            <!-- Gợi ý -->
            <div class="suggest-head">
                <h2>Danh sách từ gợi ý</h2>
                <span>Bấm vào từ để xem chi tiết</span>
            </div>
            <section class="suggest-grid">
                @foreach ($goiY as $item)
                    <button class="word-item" data-word="{{ $item['tu'] }}">
                        <span class="wi-hanzi">{{ $item['tu'] }}</span>
                        <span class="wi-pinyin">{{ $item['pinyin'] }}</span>
                        <span class="wi-nghia">{{ $item['nghia'] }}</span>
                    </button>
                @endforeach
            </section>
        </div>
    </main>
</div>

<div class="toast" id="toast"></div>

<script src="{{ asset('js/TraTuVung.js') }}"></script>
</body>
</html>