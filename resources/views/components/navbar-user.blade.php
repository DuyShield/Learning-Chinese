@props([
    'title',
    'subtitle',
    'icon' => 'fa-house',
])

<header class="header">
    <button class="btn-menu" id="btnMenu" type="button" aria-label="Mở menu">
        <i class="fa-solid fa-bars" aria-hidden="true"></i>
    </button>
    <div class="header-icon"><i class="fa-solid {{ $icon }}" aria-hidden="true"></i></div>
    <div>
        <h1>{{ $title }}</h1>
        <p>{{ $subtitle }}</p>
    </div>
    <button type="button"
        data-global-search-open
        class="ml-auto grid size-10 shrink-0 place-items-center rounded-xl text-muted transition hover:bg-primary-light hover:text-primary"
        aria-label="Tìm kiếm từ vựng, ngữ pháp và câu"
        aria-haspopup="dialog"
        aria-controls="globalSearchModal"
        title="Tìm kiếm">
        <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
    </button>
    <div class="hidden items-center gap-2 text-sm font-semibold text-muted sm:flex">
        <i class="fa-regular fa-circle-user text-lg text-primary" aria-hidden="true"></i>
        {{ auth()->user()->full_name }}
    </div>
</header>

<div class="global-search-modal" id="globalSearchModal" aria-hidden="true">
    <div class="global-search-backdrop" data-global-search-close></div>
    <section
        class="global-search-dialog"
        role="dialog"
        aria-modal="true"
        aria-labelledby="globalSearchTitle"
        tabindex="-1"
    >
        <div class="global-search-heading">
            <div>
                <h2 id="globalSearchTitle">Tra cứu tổng hợp</h2>
                <p>Tìm từ vựng, ngữ pháp và câu ví dụ</p>
            </div>
            <button type="button" class="global-search-close" data-global-search-close aria-label="Đóng tìm kiếm">
                <i class="fa-solid fa-xmark" aria-hidden="true"></i>
            </button>
        </div>

        <form
            class="global-search-form"
            id="globalSearchForm"
            data-search-url="{{ route('search.index') }}"
            data-vocabulary-url="{{ route('vocabulary.user') }}"
            data-grammar-url="{{ route('grammar.user') }}"
            role="search"
        >
            <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
            <input
                type="search"
                id="globalSearchInput"
                name="q"
                placeholder="Nhập từ vựng, ngữ pháp hoặc câu cần tìm..."
                aria-label="Nội dung cần tìm"
                maxlength="80"
                autocomplete="off"
            >
            <button type="submit">Tìm kiếm</button>
        </form>

        <div class="global-search-filters" role="group" aria-label="Lọc loại kết quả">
            <button type="button" class="global-search-filter active" data-global-search-filter="all" aria-pressed="true">
                Tất cả
            </button>
            <button type="button" class="global-search-filter" data-global-search-filter="vocabulary" aria-pressed="false">
                Từ vựng
            </button>
            <button type="button" class="global-search-filter" data-global-search-filter="grammar" aria-pressed="false">
                Ngữ pháp
            </button>
            <button type="button" class="global-search-filter" data-global-search-filter="sentences" aria-pressed="false">
                Câu
            </button>
        </div>

        <p class="global-search-status" id="globalSearchStatus" role="status" aria-live="polite">
            Nhập từ khóa để tìm kiếm.
        </p>
        <div class="global-search-results" id="globalSearchResults"></div>
    </section>
</div>
