// ===== Dữ liệu tra cứu từ database =====
const tuCache = new Map();

let tuHienTai = "";
let ganDay = [];
let yeuThich = [];

// ===== Tiện ích =====
const $ = (id) => document.getElementById(id);

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

// ===== Tìm kiếm từ vựng trong database =====
async function locTu(keyword) {
    const url = new URL($("searchForm").dataset.searchUrl, window.location.origin);
    url.searchParams.set("q", keyword);

    const response = await fetch(url, {
        headers: { Accept: "application/json" }
    });

    if (!response.ok) {
        throw new Error(`Tìm từ vựng thất bại (${response.status}).`);
    }

    return response.json();
}

function thongTinTu(record) {
    return {
        pinyin: record.pinyin || "",
        meaning: record.meaning || "",
        partOfSpeech: record.part_of_speech || "",
        hskLevel: record.hsk_level || "",
        exampleHanzi: record.example_hanzi || "",
        examplePinyin: record.example_pinyin || "",
        exampleMeaning: record.example_meaning || ""
    };
}

function banGhiTuGoiY(button) {
    return {
        hanzi: button.dataset.word,
        pinyin: button.dataset.pinyin,
        meaning: button.dataset.meaning,
        part_of_speech: button.dataset.partOfSpeech,
        hsk_level: button.dataset.hskLevel,
        example_hanzi: button.dataset.exampleHanzi,
        example_pinyin: button.dataset.examplePinyin,
        example_meaning: button.dataset.exampleMeaning
    };
}

// ===== Hiển thị từ =====
function hienThiTu(tu, thongTinTu = null) {
    const d = thongTinTu || tuCache.get(tu);
    if (!d) return thongBao("Không tìm thấy từ: " + tu);

    tuCache.set(tu, d);
    tuHienTai = tu;
    $("hanzi").textContent = tu;
    $("pinyin").textContent = d.pinyin;
    $("hsk").textContent = d.hskLevel || "Chưa phân loại";
    $("loai").textContent = d.partOfSpeech || "Chưa phân loại";
    $("defTitle").textContent = d.meaning;
    $("exZh").textContent = d.exampleHanzi || "Chưa có câu ví dụ";
    $("exPy").textContent = d.examplePinyin || "";
    $("exVi").textContent = d.exampleMeaning || "";
    $("btnSoundEx").disabled = !d.exampleHanzi;

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
// ===== Hiển thị danh sách từ gần đây =====
function veGanDay() {
    const list = $("recentList");
    list.innerHTML = "";
    ganDay.forEach((tu) => {
        const chip = document.createElement("button");
        chip.className = "chip";
        chip.textContent = tu;
        chip.onclick = async () => {
            try {
                const ketQua = await locTu(tu);
                const record = ketQua.find((item) => item.hanzi === tu) || ketQua[0];
                if (record) {
                    chonTu(record);
                } else {
                    thongBao("Không tìm thấy từ trong database: " + tu);
                }
            } catch (error) {
                console.error(error);
                thongBao("Không thể tải từ vựng. Vui lòng thử lại.");
            }
        };
        list.appendChild(chip);
    });
    $("recentBox").style.display = ganDay.length ? "flex" : "none";
}

// Yêu thích 
function capNhatNutYeuThich() {
    const btn = $("btnFav");
    const daLuu = yeuThich.includes(tuHienTai);
    btn.classList.toggle("active", daLuu);
    btn.innerHTML = daLuu ? '<i class="fa-solid fa-star"></i>' : '<i class="fa-regular fa-star"></i>';
}

// Dropdown gợi ý khi gõ 
let latestSearchId = 0;
// Hiển thị dropdown gợi ý từ vựng khi gõ
async function veDropdown(keyword) {
    const dropdown = $("dropdown");
    const tuKhoa = keyword.trim();
    const searchId = ++latestSearchId;
    dropdown.innerHTML = "";

    if (!tuKhoa) return dropdown.classList.remove("show");

    try {
        const ketQua = await locTu(tuKhoa);
        if (searchId !== latestSearchId) return;

        if (!ketQua.length) {
            const li = document.createElement("li");
            li.textContent = "Không có kết quả phù hợp";
            dropdown.appendChild(li);
        } else {
            ketQua.forEach((record) => {
                const li = document.createElement("li");
                const hanzi = document.createElement("span");
                const pinyin = document.createElement("span");
                const meaning = document.createElement("span");

                hanzi.className = "d-hanzi";
                hanzi.textContent = record.hanzi;
                pinyin.className = "d-pinyin";
                pinyin.textContent = record.pinyin;
                meaning.className = "d-nghia";
                meaning.textContent = record.meaning;

                li.append(hanzi, pinyin, meaning);
                li.addEventListener("click", () => chonTu(record));
                dropdown.appendChild(li);
            });
        }

        dropdown.classList.add("show");
    } catch (error) {
        if (searchId !== latestSearchId) return;
        console.error(error);
        const li = document.createElement("li");
        li.textContent = "Không thể tải kết quả tìm kiếm. Vui lòng thử lại.";
        dropdown.appendChild(li);
        dropdown.classList.add("show");
    }
}
// Chọn từ từ gợi ý hoặc danh sách gần đây
function chonTu(record) {
    hienThiTu(record.hanzi, thongTinTu(record));
    $("searchInput").value = "";
    $("dropdown").classList.remove("show");
    latestSearchId++;
}

// Tìm kiếm từ vựng khi nhấn Enter
document.addEventListener("DOMContentLoaded", function () {
    ganDay = doc("tuVungGanDay");
    yeuThich = doc("tuVungYeuThich");
    veGanDay();

    const firstSuggestion = document.querySelector(".word-item");
    if (firstSuggestion) {
        const record = banGhiTuGoiY(firstSuggestion);
        tuHienTai = record.hanzi;
        tuCache.set(tuHienTai, thongTinTu(record));
    } else {
        tuHienTai = "";
    }

    capNhatNutYeuThich();

    const input = $("searchInput");

    input.addEventListener("input", () => veDropdown(input.value));

    $("searchForm").addEventListener("submit", async function (e) {
        e.preventDefault();
        const tuKhoa = input.value.trim();
        if (!tuKhoa) return;

        const searchId = ++latestSearchId;
        try {
            const ketQua = await locTu(tuKhoa);
            if (searchId !== latestSearchId) return;
            ketQua.length
                ? chonTu(ketQua[0])
                : thongBao("Không tìm thấy từ: " + tuKhoa);
        } catch (error) {
            if (searchId !== latestSearchId) return;
            console.error(error);
            thongBao("Không thể tải kết quả tìm kiếm. Vui lòng thử lại.");
        }
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
            chonTu(banGhiTuGoiY(this));
            window.scrollTo({ top: 0, behavior: "smooth" });
        });
    });

    // Phát âm
    $("btnSound").onclick = () => docTo(tuHienTai);
    $("btnSoundEx").onclick = () => docTo($("exZh").textContent);

    // Sao chép
    $("btnCopy").onclick = function () {
        const currentWord = tuCache.get(tuHienTai);
        const text = `${tuHienTai} (${currentWord.pinyin}): ${currentWord.meaning}`;
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

});