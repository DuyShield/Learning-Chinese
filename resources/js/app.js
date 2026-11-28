console.log('Laravel + Vite hoạt động!');

const dailyHanzi = document.getElementById('daily-hanzi');
// ===== Hiển thị từ vựng hàng ngày =====
if (dailyHanzi) {
    const words = JSON.parse(dailyHanzi.dataset.dailyWords);
    if (words.length === 0) {
        dailyHanzi.querySelectorAll('button').forEach((button) => {
            button.disabled = true;
        });
    } else {
        // Các phần tử hiển thị từ vựng
        const character = document.getElementById('daily-hanzi-character');
        const pinyin = document.getElementById('daily-hanzi-pinyin');
        const meaning = document.getElementById('daily-hanzi-meaning');
        const hsk = document.getElementById('daily-hanzi-hsk');
        const status = document.getElementById('daily-hanzi-status');
        const randomButton = document.getElementById('daily-hanzi-random');
        const speakButton = document.getElementById('daily-hanzi-speak');
        let activeWordIndex = -1;
        // Hàm hiển thị từ vựng ngẫu nhiên
        const showRandomWord = () => {
            let nextIndex = Math.floor(Math.random() * words.length);
            if (words.length > 1 && nextIndex === activeWordIndex) {
                nextIndex = (nextIndex + 1) % words.length;
            }
            // Cập nhật từ vựng hiển thị
            activeWordIndex = nextIndex;
            const word = words[activeWordIndex];
            character.textContent = word.hanzi;
            pinyin.textContent = word.pinyin;
            meaning.textContent = word.meaning;
            hsk.textContent = word.hsk_level || 'Từ vựng';
            status.textContent = '';
        };
        // Sự kiện khi nhấn nút "Ngẫu nhiên"
        randomButton.addEventListener('click', showRandomWord);
        // Sự kiện khi nhấn nút "Phát âm" sử dụng Web Speech API của trình duyệt
        speakButton.addEventListener('click', () => {
            if (!('speechSynthesis' in window)) {
                status.textContent = 'Trình duyệt không hỗ trợ phát âm.';
                return;
            }

            window.speechSynthesis.cancel();
            const utterance = new SpeechSynthesisUtterance(character.textContent);
            utterance.lang = 'zh-CN';
            utterance.rate = 0.85;
            window.speechSynthesis.speak(utterance);
            status.textContent = 'Đang phát âm tiếng Trung.';
        });

        showRandomWord();
    }
}

// Bật tắt hiển thị mật khẩu
document.querySelectorAll('[data-password-toggle]').forEach((button) => {
    button.addEventListener('click', () => {
        const input = document.getElementById(button.dataset.passwordToggle);
        if (!input) return;

        input.type = input.type === 'password' ? 'text' : 'password';
    });
});
// Chuyển ảnh sử dụng carousel slideshow trang login, register, reset-password
document.querySelectorAll('[data-auth-carousel]').forEach((carousel) => {
    const slides = Array.from(carousel.querySelectorAll('[data-carousel-slide]'));
    const dots = Array.from(carousel.querySelectorAll('[data-carousel-dot]'));

    if (slides.length < 2) return;
    // Hiển thị slide đầu tiên
    let activeSlide = 0;
    const showSlide = (index) => {
        activeSlide = index;
        slides.forEach((slide, slideIndex) => {
            slide.hidden = slideIndex !== activeSlide;
        });
        dots.forEach((dot, dotIndex) => {
            const isActive = dotIndex === activeSlide;
            dot.setAttribute('aria-current', String(isActive));
            dot.classList.toggle('w-10', isActive);
            dot.classList.toggle('w-2', !isActive);
            dot.classList.toggle('bg-white', isActive);
            dot.classList.toggle('bg-white/35', !isActive);
        });
    };

    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => showSlide(index));
    });

    window.setInterval(() => {
        showSlide((activeSlide + 1) % slides.length);
    }, 10000);
});

const userSidebar = document.getElementById('sidebar');
const userSidebarOverlay = document.getElementById('overlay');
const userSidebarToggle = document.getElementById('btnMenu');

if (userSidebar && userSidebarOverlay && userSidebarToggle) {
    userSidebarToggle.addEventListener('click', () => {
        userSidebar.classList.add('open');
        userSidebarOverlay.classList.add('show');
    });

    userSidebarOverlay.addEventListener('click', () => {
        userSidebar.classList.remove('open');
        userSidebarOverlay.classList.remove('show');
    });
}

// ===== Tìm kiếm tổng hợp trong modal của navbar-user =====
// Markup nằm trong resources/views/components/navbar-user.blade.php.
// Endpoint lấy từ data-search-url và trả JSON từ SearchController::index.
const globalSearchModal = document.getElementById('globalSearchModal');
const globalSearchForm = document.getElementById('globalSearchForm');
const globalSearchInput = document.getElementById('globalSearchInput');
const globalSearchStatus = document.getElementById('globalSearchStatus');
const globalSearchResults = document.getElementById('globalSearchResults');
const globalSearchOpen = document.querySelector('[data-global-search-open]');
const globalSearchFilters = document.querySelectorAll('[data-global-search-filter]');

if (
    globalSearchModal
    && globalSearchForm
    && globalSearchInput
    && globalSearchStatus
    && globalSearchResults
    && globalSearchOpen
) {
    let searchTimer;
    let activeSearch;
    let restoreFocus;
    let activeSearchFilter = 'all';
    let totalSearchResults = 0;

    // Chỉ thêm giá trị có dữ liệu; textContent giữ nguyên nội dung mà không thực thi HTML.
    const addText = (parent, tag, value, className = '') => {
        if (value === null || value === undefined || value === '') return null;

        const element = document.createElement(tag);
        element.className = className;
        element.textContent = String(value);
        parent.append(element);
        return element;
    };

    // Tạo một nhóm kết quả và tiêu đề kèm số lượng. Trả số lượng để cập nhật tổng bên dưới.
    const renderSection = (title, category, items, renderItem) => {
        if (!Array.isArray(items) || items.length === 0) return 0;

        const section = document.createElement('section');
        section.className = 'global-search-section';
        section.dataset.category = category;
        addText(section, 'h3', `${title} (${items.length})`, 'global-search-section-title');

        const list = document.createElement('div');
        list.className = 'global-search-result-list';
        items.forEach((item) => list.append(renderItem(item)));
        section.append(list);
        globalSearchResults.append(section);

        return items.length;
    };

    const createDetailUrl = (baseUrl, parameter, id) => {
        const url = new URL(baseUrl, window.location.origin);
        url.searchParams.set(parameter, String(id));
        return url.toString();
    };

    const createResultLink = (baseUrl, parameter, id, label) => {
        const link = document.createElement('a');
        link.className = 'global-search-result global-search-result-link';
        link.href = createDetailUrl(baseUrl, parameter, id);
        link.setAttribute('aria-label', label);
        return link;
    };

    const applySearchFilter = () => {
        let visibleCount = 0;

        globalSearchResults.querySelectorAll('.global-search-section').forEach((section) => {
            const isVisible = activeSearchFilter === 'all'
                || section.dataset.category === activeSearchFilter;
            section.hidden = !isVisible;
            if (isVisible) visibleCount += section.querySelectorAll('.global-search-result').length;
        });

        globalSearchFilters.forEach((button) => {
            const isActive = button.dataset.globalSearchFilter === activeSearchFilter;
            button.classList.toggle('active', isActive);
            button.setAttribute('aria-pressed', String(isActive));
        });

        if (totalSearchResults > 0) {
            globalSearchStatus.textContent = activeSearchFilter === 'all'
                ? `Tìm thấy ${totalSearchResults} kết quả phù hợp.`
                : `Hiển thị ${visibleCount} kết quả phù hợp.`;
        }
    };

    /*
     * Hợp đồng JSON phải khớp với SearchController::index:
     * - vocabularies: từ, pinyin, nghĩa, cấp HSK và câu ví dụ của từ.
     * - grammarPoints: tiêu đề, cấu trúc, giải thích, ghi chú và examples.
     * - vocabularySentences / grammarSentences: câu ví dụ tìm thấy.
     * Laravel serialize quan hệ grammarPoint thành grammar_point (snake_case).
     * Khi thêm nhóm dữ liệu, cập nhật cả response ở controller và phần render này.
     */
    const renderResults = (data) => {
        globalSearchResults.replaceChildren();

        // Từ vựng được tìm trong các trường hanzi, pinyin, meaning, part_of_speech.
        const vocabularyCount = renderSection('Từ vựng', 'vocabulary', data.vocabularies, (word) => {
            const card = createResultLink(
                globalSearchForm.dataset.vocabularyUrl,
                'word_id',
                word.id,
                `Mở từ vựng ${word.hanzi}`,
            );

            const heading = document.createElement('div');
            addText(heading, 'span', word.hanzi, 'font-zh text-xl font-bold');
            addText(heading, 'span', word.pinyin, 'ml-2 text-primary');
            addText(heading, 'span', word.hsk_level || 'Chưa phân loại', 'ml-2 text-xs text-muted');
            card.append(heading);
            addText(card, 'p', word.meaning, 'mt-2');
            addText(card, 'p', word.part_of_speech, 'mt-1 text-sm text-muted');

            if (word.example_hanzi) {
                const example = document.createElement('div');
                example.className = 'global-search-result-example';
                addText(example, 'p', word.example_hanzi, 'font-zh');
                addText(example, 'p', word.example_pinyin, 'mt-1 text-sm text-primary');
                addText(example, 'p', word.example_meaning, 'mt-1 text-sm text-muted');
                card.append(example);
            }

            return card;
        });

        // Mỗi điểm ngữ pháp hiển thị toàn bộ ví dụ đã được controller eager-load.
        const grammarCount = renderSection('Ngữ pháp', 'grammar', data.grammarPoints, (grammar) => {
            const card = createResultLink(
                globalSearchForm.dataset.grammarUrl,
                'grammar_id',
                grammar.id,
                `Mở ngữ pháp ${grammar.title}`,
            );
            addText(card, 'h4', grammar.title, 'global-search-result-title');
            addText(card, 'p', grammar.hsk_level || 'Chưa phân loại', 'global-search-result-meta');
            addText(card, 'p', grammar.structure, 'mt-2 font-semibold text-primary');
            addText(card, 'p', grammar.explanation, 'mt-2');
            addText(card, 'p', grammar.note, 'mt-2 text-sm text-muted');

            (grammar.examples || []).forEach((example) => {
                const exampleBox = document.createElement('div');
                exampleBox.className = 'global-search-result-example';
                addText(exampleBox, 'p', example.hanzi, 'font-zh');
                addText(exampleBox, 'p', example.pinyin, 'mt-1 text-sm text-primary');
                addText(exampleBox, 'p', example.meaning, 'mt-1 text-sm text-muted');
                card.append(exampleBox);
            });

            return card;
        });

        // Chuẩn hóa câu từ hai bảng về cùng cấu trúc để dùng chung một renderer.
        const sentences = [
            ...(data.vocabularySentences || []).map((sentence) => ({
                hanzi: sentence.example_hanzi,
                pinyin: sentence.example_pinyin,
                meaning: sentence.example_meaning,
                source: `Ví dụ từ vựng: ${sentence.hanzi}`,
                target: 'vocabulary',
                id: sentence.id,
            })),
            ...(data.grammarSentences || []).map((sentence) => ({
                hanzi: sentence.hanzi,
                pinyin: sentence.pinyin,
                meaning: sentence.meaning,
                source: sentence.grammar_point
                    ? `Ví dụ ngữ pháp: ${sentence.grammar_point.title}`
                    : 'Ví dụ ngữ pháp',
                target: 'grammar',
                id: sentence.grammar_point_id,
            })),
        ];
        const sentenceCount = renderSection('Câu ví dụ', 'sentences', sentences, (sentence) => {
            const isVocabulary = sentence.target === 'vocabulary';
            const card = createResultLink(
                isVocabulary
                    ? globalSearchForm.dataset.vocabularyUrl
                    : globalSearchForm.dataset.grammarUrl,
                isVocabulary ? 'word_id' : 'grammar_id',
                sentence.id,
                `Mở ${sentence.source}`,
            );
            addText(card, 'p', sentence.hanzi, 'font-zh text-lg');
            addText(card, 'p', sentence.pinyin, 'mt-1 text-sm text-primary');
            addText(card, 'p', sentence.meaning, 'mt-1 text-sm text-muted');
            addText(card, 'p', sentence.source, 'mt-3 text-xs text-muted');
            return card;
        });

        totalSearchResults = vocabularyCount + grammarCount + sentenceCount;
        globalSearchStatus.textContent = totalSearchResults
            ? `Tìm thấy ${totalSearchResults} kết quả phù hợp.`
            : 'Không tìm thấy kết quả phù hợp.';
        applySearchFilter();
    };

    /*
     * Trim từ khóa rồi gọi API GET với query `q`.
     * Request cũ bị hủy khi người dùng tiếp tục gõ; lỗi mạng/HTTP được báo ngay trên modal.
     */
    const search = async () => {
        const query = globalSearchInput.value.trim();
        if (!query) {
            totalSearchResults = 0;
            globalSearchResults.replaceChildren();
            globalSearchStatus.textContent = 'Nhập từ khóa để tìm kiếm.';
            activeSearch?.abort();
            activeSearch = null;
            return;
        }

        activeSearch?.abort();
        const controller = new AbortController();
        activeSearch = controller;
        totalSearchResults = 0;
        globalSearchStatus.textContent = 'Đang tìm kiếm...';
        globalSearchResults.replaceChildren();

        try {
            const url = new URL(globalSearchForm.dataset.searchUrl, window.location.origin);
            url.searchParams.set('q', query);
            const response = await fetch(url, {
                headers: { Accept: 'application/json' },
                signal: controller.signal,
            });
            if (!response.ok) {
                throw new Error(`Search failed with status ${response.status}`);
            }

            const data = await response.json();
            if (activeSearch === controller) renderResults(data);
        } catch (error) {
            if (error.name === 'AbortError') return;
            console.error(error);
            globalSearchResults.replaceChildren();
            globalSearchStatus.textContent = 'Không thể tìm kiếm lúc này. Vui lòng thử lại.';
        } finally {
            if (activeSearch === controller) activeSearch = null;
        }
    };

    // Đóng modal cũng dọn debounce/request để không cập nhật kết quả sau khi modal đã đóng.
    // Trả focus về nút mở modal để hỗ trợ thao tác bằng bàn phím.
    const closeModal = () => {
        window.clearTimeout(searchTimer);
        globalSearchModal.classList.remove('open');
        globalSearchModal.setAttribute('aria-hidden', 'true');
        activeSearch?.abort();
        activeSearch = null;
        if (restoreFocus) restoreFocus.focus();
    };

    globalSearchOpen.addEventListener('click', () => {
        // Lưu nút mở để closeModal có thể khôi phục focus.
        restoreFocus = globalSearchOpen;
        globalSearchModal.classList.add('open');
        globalSearchModal.setAttribute('aria-hidden', 'false');
        globalSearchInput.focus();
    });

    globalSearchModal.querySelectorAll('[data-global-search-close]').forEach((button) => {
        button.addEventListener('click', closeModal);
    });

    globalSearchFilters.forEach((button) => {
        button.addEventListener('click', () => {
            activeSearchFilter = button.dataset.globalSearchFilter;
            applySearchFilter();
        });
    });

    globalSearchModal.addEventListener('click', (event) => {
        if (event.target === globalSearchModal) closeModal();
    });

    globalSearchInput.addEventListener('input', () => {
        window.clearTimeout(searchTimer);
        activeSearch?.abort();
        activeSearch = null;
        // Chờ người dùng ngừng gõ 250ms rồi mới gửi request, giảm truy vấn lặp.
        searchTimer = window.setTimeout(search, 250);
    });

    globalSearchForm.addEventListener('submit', (event) => {
        event.preventDefault();
        window.clearTimeout(searchTimer);
        // Enter/nút Tìm kiếm chạy ngay, không chờ hết thời gian debounce.
        search();
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && globalSearchModal.classList.contains('open')) {
            closeModal();
        }
    });
}