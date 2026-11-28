<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ngữ pháp tiếng Trung - Zimora</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&family=Noto+Sans+SC:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/TraTuVung.css', 'resources/css/NguPhap.css', 'resources/js/app.js', 'resources/js/NguPhap.js'])
</head>
<body>
<div class="layout">
    <x-sidebar active="grammar" />

    <main class="main">
        <x-navbar-user
            title="Ngữ pháp tiếng Trung"
            subtitle="Làm chủ mọi điểm ngữ pháp"
            icon="fa-graduation-cap"
        />

        <div class="content">
        {{-- Toolbar --}}
            <div class="toolbar">
                <div class="status-filters" id="statusFilters">
                    <button class="status-btn active" data-status="tat-ca" type="button">
                        <i class="fa-solid fa-border-all" aria-hidden="true"></i> Tất cả
                    </button>
                    <button class="status-btn" data-status="chua-hoc" type="button">
                        <i class="fa-regular fa-circle" aria-hidden="true"></i> Chưa học
                    </button>
                    <button class="status-btn" data-status="dang-hoc" type="button">
                        <i class="fa-solid fa-book-open-reader" aria-hidden="true"></i> Đang học
                    </button>
                    <button class="status-btn" data-status="on-tap" type="button">
                        <i class="fa-solid fa-rotate" aria-hidden="true"></i> Đang ôn tập
                    </button>
                </div>
                {{-- Search --}}
                <div class="grammar-search">
                    <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                    <input type="search" id="searchInput" placeholder="Tìm ngữ pháp theo tên hoặc pinyin...">
                    <button class="btn-clear" id="btnClear" type="button" title="Xóa">
                        <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                    </button>
                </div>
            </div>

            <p class="result-count" id="resultCount"></p>
            <section class="grammar-grid" id="grammarGrid" aria-live="polite"></section>
            {{-- Hiển thị khi ko có dữ liệu --}}
            <div class="empty" id="emptyBox">
                <i class="fa-regular fa-folder-open" aria-hidden="true"></i>
                <p>Không tìm thấy điểm ngữ pháp phù hợp</p>
                <span>Thử đổi từ khóa hoặc bộ lọc trạng thái</span>
            </div>
        </div>
    </main>
</div>
{{-- Tải dữ liệu ngữ pháp --}}
<script
    id="grammarData"
    type="application/json"
    data-progress-url-template="{{ route('grammar.progress.update', ['grammarPoint' => '__POINT__']) }}"
>@json($grammarPoints)</script>

{{-- Modal Hiển thị thông tin ngữ pháp--}}
<div class="modal" id="modal">
    <div class="modal-box">
        <button class="modal-close" id="modalClose" type="button" aria-label="Đóng">
            <i class="fa-solid fa-xmark" aria-hidden="true"></i>
        </button>
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
            <button class="icon-btn" id="btnSound" type="button" title="Nghe câu ví dụ">
                <i class="fa-solid fa-volume-high" aria-hidden="true"></i>
            </button>
        </div>
        <h3 class="m-title">Trạng thái học</h3>
        <div class="m-status" id="mStatus">
            <button data-status="chua-hoc" type="button">Chưa học</button>
            <button data-status="dang-hoc" type="button">Đang học</button>
            <button data-status="on-tap" type="button">Đang ôn tập</button>
        </div>
    </div>
</div>

<div class="toast" id="toast" role="status" aria-live="polite"></div>
</body>
</html>
