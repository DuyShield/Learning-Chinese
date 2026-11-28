<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Từ vựng</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&family=Noto+Sans+SC:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/TraTuVung.css', 'resources/js/app.js', 'resources/js/TraTuVung.js'])
</head>
<body>

<div class="layout">
    <x-sidebar active="vocabulary" />

    <!-- ===== Nội dung chính ===== -->
    <main class="main">
        <x-navbar-user
            title="Từ vựng"
            subtitle="Nắm vững từ vựng tiếng Trung"
            icon="fa-layer-group"
        />

        <div class="content">
            <!-- Tìm kiếm -->
            <div class="search-wrap">
                <form
                    class="search-box"
                    id="searchForm"
                    data-search-url="{{ route('vocabulary.user.search') }}"
                    autocomplete="off"
                >
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
                            <span class="hanzi" id="hanzi">{{ $tuBanDau?->hanzi ?? '—' }}</span>
                            <span class="pinyin" id="pinyin">{{ $tuBanDau?->pinyin ?? '' }}</span>
                        </div>
                        <div class="badges">
                            <span class="badge" id="hsk">{{ $tuBanDau?->hsk_level ?? 'Chưa phân loại' }}</span>
                            <span class="badge badge-light" id="loai">{{ $tuBanDau?->part_of_speech ?? 'Chưa phân loại' }}</span>
                        </div>
                    </div>

                    <div class="actions">
                        <button class="icon-btn primary" id="btnSound" title="Phát âm" @disabled(!$tuBanDau)>
                            <i class="fa-solid fa-volume-high"></i>
                        </button>
                        <button class="icon-btn" id="btnCopy" title="Sao chép" @disabled(!$tuBanDau)>
                            <i class="fa-regular fa-copy"></i>
                        </button>
                        <button class="icon-btn" id="btnFav" title="Lưu từ" @disabled(!$tuBanDau)>
                            <i class="fa-regular fa-star"></i>
                        </button>
                    </div>
                </div>

                <h3 class="section-title">Nghĩa</h3>
                <div class="definition">
                    <p class="def-title" id="defTitle">{{ $tuBanDau?->meaning ?? 'Chọn một từ hoặc tìm kiếm trong từ điển.' }}</p>
                </div>

                <h3 class="section-title">Ví dụ</h3>
                <div class="example">
                    <div>
                        <p class="ex-zh" id="exZh">{{ $tuBanDau?->example_hanzi ?? 'Chưa có câu ví dụ' }}</p>
                        <p class="ex-py" id="exPy">{{ $tuBanDau?->example_pinyin ?? '' }}</p>
                        <p class="ex-vi" id="exVi">{{ $tuBanDau?->example_meaning ?? '' }}</p>
                    </div>
                    <button class="icon-btn small" id="btnSoundEx" title="Nghe câu ví dụ" @disabled(!$tuBanDau?->example_hanzi)>
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
                @forelse ($goiY as $item)
                    <button
                        class="word-item"
                        data-word="{{ $item->hanzi }}"
                        data-pinyin="{{ $item->pinyin }}"
                        data-meaning="{{ $item->meaning }}"
                        data-part-of-speech="{{ $item->part_of_speech }}"
                        data-hsk-level="{{ $item->hsk_level }}"
                        data-example-hanzi="{{ $item->example_hanzi }}"
                        data-example-pinyin="{{ $item->example_pinyin }}"
                        data-example-meaning="{{ $item->example_meaning }}"
                    >
                        <span class="wi-hanzi">{{ $item->hanzi }}</span>
                        <span class="wi-pinyin">{{ $item->pinyin }}</span>
                        <span class="wi-nghia">{{ $item->meaning }}</span>
                    </button>
                @empty
                    <p>Chưa có dữ liệu từ vựng để gợi ý.</p>
                @endforelse
            </section>
        </div>
    </main>
</div>

<div class="toast" id="toast"></div>

</body>
</html>