<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ngữ pháp tiếng Trung</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&family=Noto+Sans+SC:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/NguPhap.css') }}">
</head>
<body>

@php
    // ===== Dữ liệu tĩnh =====
    $menu = [
        ['icon' => 'fa-house',          'text' => 'Trang chủ',  'active' => false],
        ['icon' => 'fa-pen-nib',        'text' => 'Luyện viết', 'active' => false],
        ['icon' => 'fa-microphone',     'text' => 'Luyện nói',  'active' => false],
        ['icon' => 'fa-layer-group',    'text' => 'Từ vựng',    'active' => false],
        ['icon' => 'fa-graduation-cap', 'text' => 'Ngữ pháp',   'active' => true],
        ['icon' => 'fa-brain',          'text' => 'Ôn tập',     'active' => false],
        ['icon' => 'fa-trophy',         'text' => 'Luyện thi',  'active' => false],
    ];

    $capDo = ['HSK1', 'HSK2', 'HSK3', 'HSK4', 'HSK5', 'HSK6', 'HSK7-9'];

    $trangThai = [
        ['key' => 'tat-ca',   'text' => 'Tất cả',      'icon' => 'fa-border-all'],
        ['key' => 'chua-hoc', 'text' => 'Chưa học',    'icon' => 'fa-circle'],
        ['key' => 'dang-hoc', 'text' => 'Đang học',    'icon' => 'fa-book-open-reader'],
        ['key' => 'on-tap',   'text' => 'Đang ôn tập', 'icon' => 'fa-rotate'],
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
            <div class="header-icon"><i class="fa-solid fa-graduation-cap"></i></div>
            <div>
                <h1>Ngữ pháp tiếng Trung</h1>
                <p>Làm chủ mọi điểm ngữ pháp</p>
            </div>
        </header>

        <div class="content">
            <!-- Tab cấp độ HSK -->
            <div class="hsk-tabs" id="hskTabs">
                @foreach ($capDo as $i => $hsk)
                    <button class="hsk-tab {{ $i === 0 ? 'active' : '' }}" data-hsk="{{ $hsk }}">
                        {{ $hsk }}
                        <span class="tab-count" data-count="{{ $hsk }}">0</span>
                    </button>
                @endforeach
            </div>

            <p class="notice">
                <i class="fa-solid fa-circle-info"></i>
                Nội dung bài học được biên soạn bởi PLT Solutions và thuộc sở hữu trí tuệ của PLT Solutions.
                Vui lòng không sao chép hoặc tái phân phối dưới mọi hình thức.
            </p>

            <!-- Tiến độ -->
            <div class="progress-card">
                <div class="progress-info">
                    <span>Tiến độ <b id="progressLevel">HSK1</b></span>
                    <span id="progressText">0/0 điểm ngữ pháp đã học</span>
                </div>
                <div class="progress-bar"><div class="progress-fill" id="progressFill"></div></div>
            </div>

            <!-- Lọc + tìm kiếm -->
            <div class="toolbar">
                <div class="status-filters" id="statusFilters">
                    @foreach ($trangThai as $i => $tt)
                        <button class="status-btn {{ $i === 0 ? 'active' : '' }}" data-status="{{ $tt['key'] }}">
                            <i class="fa-solid {{ $tt['icon'] }}"></i> {{ $tt['text'] }}
                        </button>
                    @endforeach
                </div>

                <div class="search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="searchInput" placeholder="Tìm ngữ pháp theo tên hoặc pinyin...">
                    <button class="btn-clear" id="btnClear" title="Xóa"><i class="fa-solid fa-xmark"></i></button>
                </div>
            </div>

            <p class="result-count" id="resultCount"></p>

            <!-- Danh sách ngữ pháp (JS tự đổ dữ liệu) -->
            <section class="grammar-grid" id="grammarGrid"></section>

            <div class="empty" id="emptyBox">
                <i class="fa-regular fa-folder-open"></i>
                <p>Không tìm thấy điểm ngữ pháp phù hợp</p>
                <span>Thử đổi từ khóa, cấp độ hoặc bộ lọc trạng thái</span>
            </div>
        </div>
    </main>
</div>

<!-- ===== Popup chi tiết ===== -->
<div class="modal" id="modal">
    <div class="modal-box">
        <button class="modal-close" id="modalClose"><i class="fa-solid fa-xmark"></i></button>

        <div class="modal-head">
            <span class="m-hanzi" id="mHanzi"></span>
            <span class="m-pinyin" id="mPinyin"></span>
            <span class="badge-hsk" id="mHsk"></span>
        </div>
        <p class="m-name" id="mName"></p>

        <h3 class="m-title">Giải thích</h3>
        <p class="m-desc" id="mDesc"></p>

        <h3 class="m-title">Cấu trúc</h3>
        <div class="m-structure" id="mStructure"></div>

        <h3 class="m-title">Ví dụ</h3>
        <div class="m-example">
            <div>
                <p class="ex-zh" id="mExZh"></p>
                <p class="ex-py" id="mExPy"></p>
                <p class="ex-vi" id="mExVi"></p>
            </div>
            <button class="icon-btn" id="btnSound" title="Nghe câu ví dụ">
                <i class="fa-solid fa-volume-high"></i>
            </button>
        </div>

        <h3 class="m-title">Trạng thái học</h3>
        <div class="m-status" id="mStatus">
            <button data-status="chua-hoc">Chưa học</button>
            <button data-status="dang-hoc">Đang học</button>
            <button data-status="on-tap">Đang ôn tập</button>
        </div>
    </div>
</div>

<div class="toast" id="toast"></div>

<script src="{{ asset('js/NguPhap.js') }}"></script>
</body>
</html>