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