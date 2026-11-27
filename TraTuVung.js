// ===== Dữ liệu tĩnh =====
const tuDien = {
    "你好": {
        pinyin: "nǐ hǎo", nghia: "Xin chào", loai: "Thán từ", hsk: 1,
        dinhNghia: "Xin chào; chào",
        moTa: "Lời chào khi gặp ai, bắt đầu cuộc trò chuyện hoặc khi nhấc điện thoại.",
        viDu: { zh: "你好，今天怎么样？", py: "Nǐ hǎo, jīntiān zěnmeyàng?", vi: "Xin chào, hôm nay bạn ổn không?" }
    },
    "朋友": {
        pinyin: "péngyou", nghia: "Bạn bè", loai: "Danh từ", hsk: 1,
        dinhNghia: "Bạn; bạn bè",
        moTa: "Người có quan hệ thân thiết, quen biết với mình.",
        viDu: { zh: "他是我的好朋友。", py: "Tā shì wǒ de hǎo péngyou.", vi: "Anh ấy là bạn tốt của tôi." }
    },
    "明天": {
        pinyin: "míngtiān", nghia: "Ngày mai", loai: "Danh từ", hsk: 1,
        dinhNghia: "Ngày mai",
        moTa: "Ngày tiếp theo sau hôm nay.",
        viDu: { zh: "我们明天见！", py: "Wǒmen míngtiān jiàn!", vi: "Hẹn gặp lại ngày mai nhé!" }
    },
    "什么": {
        pinyin: "shénme", nghia: "Cái gì", loai: "Đại từ", hsk: 1,
        dinhNghia: "Cái gì; gì",
        moTa: "Đại từ nghi vấn, dùng để hỏi về sự vật, sự việc.",
        viDu: { zh: "你在做什么？", py: "Nǐ zài zuò shénme?", vi: "Bạn đang làm gì vậy?" }
    },
    "太阳": {
        pinyin: "tàiyáng", nghia: "Mặt trời", loai: "Danh từ", hsk: 3,
        dinhNghia: "Mặt trời; ánh nắng",
        moTa: "Ngôi sao chiếu sáng và sưởi ấm Trái Đất.",
        viDu: { zh: "今天太阳很大。", py: "Jīntiān tàiyáng hěn dà.", vi: "Hôm nay nắng rất to." }
    },
    "谢谢": {
        pinyin: "xièxie", nghia: "Cảm ơn", loai: "Động từ", hsk: 1,
        dinhNghia: "Cảm ơn",
        moTa: "Lời nói thể hiện sự biết ơn với người khác.",
        viDu: { zh: "谢谢你的帮助！", py: "Xièxie nǐ de bāngzhù!", vi: "Cảm ơn sự giúp đỡ của bạn!" }
    },
    "学习": {
        pinyin: "xuéxí", nghia: "Học tập", loai: "Động từ", hsk: 1,
        dinhNghia: "Học; học tập",
        moTa: "Tiếp thu kiến thức, kỹ năng qua việc đọc, nghe, luyện tập.",
        viDu: { zh: "我在学习中文。", py: "Wǒ zài xuéxí Zhōngwén.", vi: "Tôi đang học tiếng Trung." }
    },
    "喜欢": {
        pinyin: "xǐhuan", nghia: "Thích", loai: "Động từ", hsk: 1,
        dinhNghia: "Thích; yêu thích",
        moTa: "Có cảm tình, hứng thú với người hoặc sự vật nào đó.",
        viDu: { zh: "我喜欢喝茶。", py: "Wǒ xǐhuan hē chá.", vi: "Tôi thích uống trà." }
    },
    "老师": {
        pinyin: "lǎoshī", nghia: "Giáo viên", loai: "Danh từ", hsk: 1,
        dinhNghia: "Thầy giáo; cô giáo",
        moTa: "Người làm nghề dạy học.",
        viDu: { zh: "她是我们的中文老师。", py: "Tā shì wǒmen de Zhōngwén lǎoshī.", vi: "Cô ấy là giáo viên tiếng Trung của chúng tôi." }
    }
};

let tuHienTai = "你好";
let ganDay = [];
let yeuThich = [];

// ===== Tiện ích =====
const $ = (id) => document.getElementById(id);

function boDau(str) {
    return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "")
        .replace(/đ/g, "d").replace(/\s+/g, "").toLowerCase();
}

function thongBao(text) {
    const toast = $("toast");
    toast.textContent = text;
    toast.classList.add("show");
    clearTimeout(toast.timer);
    toast.timer = setTimeout(() => toast.classList.remove("show"), 1800);
}

function docTo(text) {
    if (!("speechSynthesis" in window)) return thongBao("Trình duyệt không hỗ trợ phát âm");
    speechSynthesis.cancel();
    const voice = new SpeechSynthesisUtterance(text);
    voice.lang = "zh-CN";
    voice.rate = 0.85;
    speechSynthesis.speak(voice);
}

function luu(key, value) {
    try { localStorage.setItem(key, JSON.stringify(value)); } catch (e) {}
}

function doc(key) {
    try { return JSON.parse(localStorage.getItem(key)) || []; } catch (e) { return []; }
}

// ===== Tìm kiếm: chữ Hán, pinyin (có/không dấu) hoặc nghĩa tiếng Việt =====
function locTu(keyword) {
    const kw = boDau(keyword);
    if (!kw) return [];
    return Object.keys(tuDien).filter((tu) => {
        const d = tuDien[tu];
        return tu.includes(keyword.trim())
            || boDau(d.pinyin).includes(kw)
            || boDau(d.nghia).includes(kw);
    });
}

// ===== Hiển thị từ =====
function hienThiTu(tu) {
    const d = tuDien[tu];
    if (!d) return thongBao("Không tìm thấy từ: " + tu);

    tuHienTai = tu;
    $("hanzi").textContent = tu;
    $("pinyin").textContent = d.pinyin;
    $("meaning").textContent = d.nghia;
    $("hsk").textContent = "HSK " + d.hsk;
    $("loai").textContent = d.loai;
    $("defTitle").textContent = d.dinhNghia;
    $("defDesc").textContent = d.moTa;
    $("exZh").textContent = d.viDu.zh;
    $("exPy").textContent = d.viDu.py;
    $("exVi").textContent = d.viDu.vi;

    // Hiệu ứng khi đổi từ
    const card = document.querySelector(".word-card");
    card.classList.remove("flash");
    void card.offsetWidth;
    card.classList.add("flash");

    // Đánh dấu từ gợi ý đang xem
    document.querySelectorAll(".word-item").forEach((el) => {
        el.classList.toggle("active", el.dataset.word === tu);
    });

    capNhatNutYeuThich();
    themGanDay(tu);
}

// ===== Tra gần đây =====
function themGanDay(tu) {
    ganDay = [tu, ...ganDay.filter((t) => t !== tu)].slice(0, 6);
    luu("tuVungGanDay", ganDay);
    veGanDay();
}

function veGanDay() {
    const list = $("recentList");
    list.innerHTML = "";
    ganDay.forEach((tu) => {
        const chip = document.createElement("button");
        chip.className = "chip";
        chip.textContent = tu;
        chip.onclick = () => hienThiTu(tu);
        list.appendChild(chip);
    });
    $("recentBox").style.display = ganDay.length ? "flex" : "none";
}

// ===== Yêu thích =====
function capNhatNutYeuThich() {
    const btn = $("btnFav");
    const daLuu = yeuThich.includes(tuHienTai);
    btn.classList.toggle("active", daLuu);
    btn.innerHTML = daLuu ? '<i class="fa-solid fa-star"></i>' : '<i class="fa-regular fa-star"></i>';
}

// ===== Dropdown gợi ý khi gõ =====
function veDropdown(keyword) {
    const dropdown = $("dropdown");
    const ketQua = locTu(keyword);
    dropdown.innerHTML = "";

    if (!keyword.trim()) return dropdown.classList.remove("show");

    if (!ketQua.length) {
        dropdown.innerHTML = '<li><span class="d-nghia" style="margin:0">Không có kết quả phù hợp</span></li>';
    } else {
        ketQua.forEach((tu) => {
            const li = document.createElement("li");
            li.innerHTML = `<span class="d-hanzi">${tu}</span>
                            <span class="d-pinyin">${tuDien[tu].pinyin}</span>
                            <span class="d-nghia">${tuDien[tu].nghia}</span>`;
            li.onclick = () => chonTu(tu);
            dropdown.appendChild(li);
        });
    }
    dropdown.classList.add("show");
}

function chonTu(tu) {
    hienThiTu(tu);
    $("searchInput").value = "";
    $("dropdown").classList.remove("show");
}

// ===== Sự kiện =====
document.addEventListener("DOMContentLoaded", function () {
    ganDay = doc("tuVungGanDay");
    yeuThich = doc("tuVungYeuThich");
    veGanDay();
    capNhatNutYeuThich();

    const input = $("searchInput");

    input.addEventListener("input", () => veDropdown(input.value));

    $("searchForm").addEventListener("submit", function (e) {
        e.preventDefault();
        if (!input.value.trim()) return;
        const ketQua = locTu(input.value);
        ketQua.length ? chonTu(ketQua[0]) : thongBao("Không tìm thấy từ: " + input.value);
    });

    // Bấm ra ngoài thì ẩn dropdown
    document.addEventListener("click", (e) => {
        if (!e.target.closest(".search-wrap")) $("dropdown").classList.remove("show");
    });

    // Phím "/" để tìm nhanh, Esc để đóng
    document.addEventListener("keydown", (e) => {
        if (e.key === "/" && document.activeElement !== input) {
            e.preventDefault();
            input.focus();
        }
        if (e.key === "Escape") {
            $("dropdown").classList.remove("show");
            input.blur();
        }
    });

    // Từ gợi ý
    document.querySelectorAll(".word-item").forEach((btn) => {
        btn.addEventListener("click", function () {
            hienThiTu(this.dataset.word);
            window.scrollTo({ top: 0, behavior: "smooth" });
        });
    });

    // Phát âm
    $("btnSound").onclick = () => docTo(tuHienTai);
    $("btnSoundEx").onclick = () => docTo($("exZh").textContent);

    // Sao chép
    $("btnCopy").onclick = function () {
        const text = `${tuHienTai} (${tuDien[tuHienTai].pinyin}): ${tuDien[tuHienTai].nghia}`;
        navigator.clipboard.writeText(text)
            .then(() => thongBao("Đã sao chép: " + tuHienTai))
            .catch(() => thongBao("Không sao chép được"));
    };

    // Lưu từ yêu thích
    $("btnFav").onclick = function () {
        if (yeuThich.includes(tuHienTai)) {
            yeuThich = yeuThich.filter((t) => t !== tuHienTai);
            thongBao("Đã bỏ lưu " + tuHienTai);
        } else {
            yeuThich.push(tuHienTai);
            thongBao("Đã lưu " + tuHienTai + " vào từ yêu thích");
        }
        luu("tuVungYeuThich", yeuThich);
        capNhatNutYeuThich();
    };

    // Menu trên điện thoại
    const sidebar = $("sidebar");
    const overlay = $("overlay");
    $("btnMenu").onclick = () => {
        sidebar.classList.add("open");
        overlay.classList.add("show");
    };
    overlay.onclick = () => {
        sidebar.classList.remove("open");
        overlay.classList.remove("show");
    };
});