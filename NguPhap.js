// ===== Dữ liệu tĩnh =====
const nguPhap = [
    // HSK1
    { id: 1, hsk: "HSK1", hanzi: "语素", pinyin: "yǔsù", ten: "Ngữ tố",
      moTa: "Ngữ tố là đơn vị nhỏ nhất có nghĩa trong ngôn ngữ, không thể chia nhỏ hơn mà vẫn giữ được nghĩa.",
      cauTruc: "语素 + 语素 → 词",
      viDu: { zh: "学生 = 学 + 生", py: "xuésheng = xué + shēng", vi: "Học sinh = học + sinh" },
      trangThai: "on-tap" },
    { id: 2, hsk: "HSK1", hanzi: "是字句", pinyin: "shì zì jù", ten: "Câu chữ 是",
      moTa: "Dùng để khẳng định A là B, giới thiệu hoặc xác định người, sự vật.",
      cauTruc: "A + 是 + B",
      viDu: { zh: "我是学生。", py: "Wǒ shì xuésheng.", vi: "Tôi là học sinh." },
      trangThai: "dang-hoc" },
    { id: 3, hsk: "HSK1", hanzi: "吗", pinyin: "ma", ten: "Câu hỏi với 吗",
      moTa: "Thêm 吗 vào cuối câu trần thuật để tạo câu hỏi có/không.",
      cauTruc: "Câu trần thuật + 吗？",
      viDu: { zh: "你是老师吗？", py: "Nǐ shì lǎoshī ma?", vi: "Bạn là giáo viên phải không?" },
      trangThai: "chua-hoc" },
    { id: 4, hsk: "HSK1", hanzi: "的", pinyin: "de", ten: "Trợ từ kết cấu 的",
      moTa: "Nối định ngữ với danh từ, thường biểu thị quan hệ sở hữu hoặc tính chất.",
      cauTruc: "Định ngữ + 的 + Danh từ",
      viDu: { zh: "这是我的书。", py: "Zhè shì wǒ de shū.", vi: "Đây là sách của tôi." },
      trangThai: "chua-hoc" },

    // HSK2
    { id: 5, hsk: "HSK2", hanzi: "了", pinyin: "le", ten: "Trợ từ động thái 了",
      moTa: "Đặt sau động từ, biểu thị hành động đã xảy ra hoặc đã hoàn thành.",
      cauTruc: "Động từ + 了 + Tân ngữ",
      viDu: { zh: "我买了一本书。", py: "Wǒ mǎile yì běn shū.", vi: "Tôi đã mua một quyển sách." },
      trangThai: "dang-hoc" },
    { id: 6, hsk: "HSK2", hanzi: "过", pinyin: "guo", ten: "Trợ từ kinh nghiệm 过",
      moTa: "Biểu thị đã từng trải qua một việc nào đó trong quá khứ.",
      cauTruc: "Động từ + 过 + Tân ngữ",
      viDu: { zh: "我去过北京。", py: "Wǒ qùguo Běijīng.", vi: "Tôi đã từng đến Bắc Kinh." },
      trangThai: "chua-hoc" },
    { id: 7, hsk: "HSK2", hanzi: "比", pinyin: "bǐ", ten: "Câu so sánh với 比",
      moTa: "Dùng để so sánh hơn giữa hai người hoặc hai sự vật.",
      cauTruc: "A + 比 + B + Tính từ",
      viDu: { zh: "今天比昨天冷。", py: "Jīntiān bǐ zuótiān lěng.", vi: "Hôm nay lạnh hơn hôm qua." },
      trangThai: "chua-hoc" },
    { id: 8, hsk: "HSK2", hanzi: "正在…呢", pinyin: "zhèngzài… ne", ten: "Hành động đang diễn ra",
      moTa: "Biểu thị hành động đang tiếp diễn tại thời điểm nói.",
      cauTruc: "正在 + Động từ + 呢",
      viDu: { zh: "我正在吃饭呢。", py: "Wǒ zhèngzài chīfàn ne.", vi: "Tôi đang ăn cơm." },
      trangThai: "chua-hoc" },

    // HSK3
    { id: 9, hsk: "HSK3", hanzi: "把字句", pinyin: "bǎ zì jù", ten: "Câu chữ 把",
      moTa: "Nhấn mạnh việc chủ ngữ tác động lên tân ngữ và tạo ra kết quả.",
      cauTruc: "Chủ ngữ + 把 + Tân ngữ + Động từ + Thành phần khác",
      viDu: { zh: "请把门关上。", py: "Qǐng bǎ mén guānshang.", vi: "Làm ơn đóng cửa lại." },
      trangThai: "chua-hoc" },
    { id: 10, hsk: "HSK3", hanzi: "被字句", pinyin: "bèi zì jù", ten: "Câu bị động với 被",
      moTa: "Biểu thị chủ ngữ chịu tác động của hành động, thường mang nghĩa không mong muốn.",
      cauTruc: "Chủ ngữ + 被 + (Người thực hiện) + Động từ + Thành phần khác",
      viDu: { zh: "我的手机被弟弟弄坏了。", py: "Wǒ de shǒujī bèi dìdi nònghuài le.", vi: "Điện thoại của tôi bị em trai làm hỏng rồi." },
      trangThai: "chua-hoc" },
    { id: 11, hsk: "HSK3", hanzi: "越来越", pinyin: "yuè lái yuè", ten: "Càng ngày càng",
      moTa: "Biểu thị mức độ tăng dần theo thời gian.",
      cauTruc: "越来越 + Tính từ / Động từ tâm lý",
      viDu: { zh: "天气越来越热了。", py: "Tiānqì yuè lái yuè rè le.", vi: "Thời tiết càng ngày càng nóng." },
      trangThai: "chua-hoc" },

    // HSK4
    { id: 12, hsk: "HSK4", hanzi: "不但…而且…", pinyin: "bùdàn… érqiě…", ten: "Không những… mà còn…",
      moTa: "Biểu thị quan hệ tăng tiến, vế sau có mức độ cao hơn vế trước.",
      cauTruc: "不但 + A，而且 + B",
      viDu: { zh: "他不但会说汉语，而且说得很好。", py: "Tā bùdàn huì shuō Hànyǔ, érqiě shuō de hěn hǎo.", vi: "Anh ấy không những biết nói tiếng Trung mà còn nói rất giỏi." },
      trangThai: "chua-hoc" },
    { id: 13, hsk: "HSK4", hanzi: "即使…也…", pinyin: "jíshǐ… yě…", ten: "Cho dù… cũng…",
      moTa: "Biểu thị giả thiết nhượng bộ, kết quả không thay đổi dù điều kiện thế nào.",
      cauTruc: "即使 + Giả thiết，也 + Kết quả",
      viDu: { zh: "即使下雨，我也去。", py: "Jíshǐ xià yǔ, wǒ yě qù.", vi: "Cho dù trời mưa, tôi cũng đi." },
      trangThai: "chua-hoc" },

    // HSK5
    { id: 14, hsk: "HSK5", hanzi: "何况", pinyin: "hékuàng", ten: "Huống hồ",
      moTa: "Dùng ở vế sau để nhấn mạnh trường hợp càng hiển nhiên hơn.",
      cauTruc: "A 都…，何况 B (呢)？",
      viDu: { zh: "大人都搬不动，何况孩子呢？", py: "Dàrén dōu bān bu dòng, hékuàng háizi ne?", vi: "Người lớn còn không khiêng nổi, huống hồ là trẻ con?" },
      trangThai: "chua-hoc" },
    { id: 15, hsk: "HSK5", hanzi: "难免", pinyin: "nánmiǎn", ten: "Khó tránh khỏi",
      moTa: "Biểu thị một việc rất dễ xảy ra, khó mà tránh được.",
      cauTruc: "难免 + (会) + Động từ",
      viDu: { zh: "第一次做难免会出错。", py: "Dì yī cì zuò nánmiǎn huì chūcuò.", vi: "Lần đầu làm khó tránh khỏi sai sót." },
      trangThai: "chua-hoc" },

    // HSK6
    { id: 16, hsk: "HSK6", hanzi: "与其…不如…", pinyin: "yǔqí… bùrú…", ten: "Thay vì… chi bằng…",
      moTa: "So sánh hai lựa chọn và chọn phương án ở vế sau.",
      cauTruc: "与其 + A，不如 + B",
      viDu: { zh: "与其在家等，不如去找他。", py: "Yǔqí zài jiā děng, bùrú qù zhǎo tā.", vi: "Thay vì ở nhà đợi, chi bằng đi tìm anh ấy." },
      trangThai: "chua-hoc" },

    // HSK7-9
    { id: 17, hsk: "HSK7-9", hanzi: "之所以…是因为…", pinyin: "zhīsuǒyǐ… shì yīnwèi…", ten: "Sở dĩ… là vì…",
      moTa: "Nêu kết quả trước rồi giải thích nguyên nhân, nhấn mạnh lý do.",
      cauTruc: "之所以 + Kết quả，是因为 + Nguyên nhân",
      viDu: { zh: "他之所以成功，是因为他很努力。", py: "Tā zhīsuǒyǐ chénggōng, shì yīnwèi tā hěn nǔlì.", vi: "Sở dĩ anh ấy thành công là vì anh ấy rất chăm chỉ." },
      trangThai: "chua-hoc" }
];

const tenTrangThai = {
    "chua-hoc": "Chưa học",
    "dang-hoc": "Đang học",
    "on-tap": "Đang ôn tập"
};

let hskHienTai = "HSK1";
let locTrangThai = "tat-ca";
let tuKhoa = "";
let dangMo = null;

// ===== Tiện ích =====
const $ = (id) => document.getElementById(id);

function boDau(str) {
    return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "")
        .replace(/đ/g, "d").replace(/[\s…]+/g, "").toLowerCase();
}

function thongBao(text) {
    const toast = $("toast");
    toast.textContent = text;
    toast.classList.add("show");
    clearTimeout(toast.timer);
    toast.timer = setTimeout(() => toast.classList.remove("show"), 1800);
}

// Lưu trạng thái học trên trình duyệt
function luuTrangThai() {
    const data = {};
    nguPhap.forEach((g) => (data[g.id] = g.trangThai));
    try { localStorage.setItem("nguPhapTrangThai", JSON.stringify(data)); } catch (e) {}
}

function docTrangThai() {
    try {
        const data = JSON.parse(localStorage.getItem("nguPhapTrangThai")) || {};
        nguPhap.forEach((g) => { if (data[g.id]) g.trangThai = data[g.id]; });
    } catch (e) {}
}

// ===== Hiển thị danh sách =====
function locDanhSach() {
    const kw = boDau(tuKhoa);
    return nguPhap.filter((g) =>
        g.hsk === hskHienTai &&
        (locTrangThai === "tat-ca" || g.trangThai === locTrangThai) &&
        (!kw || g.hanzi.includes(tuKhoa.trim()) || boDau(g.pinyin).includes(kw) || boDau(g.ten).includes(kw))
    );
}

function veDanhSach() {
    const grid = $("grammarGrid");
    const ds = locDanhSach();

    grid.innerHTML = ds.map((g) => `
        <article class="grammar-card" data-id="${g.id}">
            <div class="gc-top">
                <div class="gc-title">
                    <span class="gc-hanzi">${g.hanzi}</span>
                    <span class="gc-pinyin">${g.pinyin}</span>
                </div>
                <span class="badge-hsk">${g.hsk}</span>
            </div>
            <p class="gc-name">${g.ten}</p>
            <p class="gc-desc">${g.moTa}</p>
            <div class="gc-bottom">
                <span class="status-tag ${g.trangThai}">${tenTrangThai[g.trangThai]}</span>
                <span class="gc-more">Xem chi tiết <i class="fa-solid fa-arrow-right"></i></span>
            </div>
        </article>
    `).join("");

    $("emptyBox").style.display = ds.length ? "none" : "flex";
    $("resultCount").textContent = `Hiển thị ${ds.length} điểm ngữ pháp`;

    grid.querySelectorAll(".grammar-card").forEach((card) => {
        card.onclick = () => moChiTiet(Number(card.dataset.id));
    });

    capNhatThongKe();
}

function capNhatThongKe() {
    // Số lượng trên từng tab HSK
    document.querySelectorAll(".tab-count").forEach((el) => {
        el.textContent = nguPhap.filter((g) => g.hsk === el.dataset.count).length;
    });

    // Thanh tiến độ cấp độ hiện tại
    const theoCap = nguPhap.filter((g) => g.hsk === hskHienTai);
    const daHoc = theoCap.filter((g) => g.trangThai !== "chua-hoc").length;
    const phanTram = theoCap.length ? Math.round((daHoc / theoCap.length) * 100) : 0;

    $("progressLevel").textContent = hskHienTai;
    $("progressText").textContent = `${daHoc}/${theoCap.length} điểm ngữ pháp đã học (${phanTram}%)`;
    $("progressFill").style.width = phanTram + "%";
}

// ===== Popup chi tiết =====
function moChiTiet(id) {
    const g = nguPhap.find((x) => x.id === id);
    if (!g) return;
    dangMo = g;

    $("mHanzi").textContent = g.hanzi;
    $("mPinyin").textContent = g.pinyin;
    $("mHsk").textContent = g.hsk;
    $("mName").textContent = g.ten;
    $("mDesc").textContent = g.moTa;
    $("mStructure").textContent = g.cauTruc;
    $("mExZh").textContent = g.viDu.zh;
    $("mExPy").textContent = g.viDu.py;
    $("mExVi").textContent = g.viDu.vi;

    veNutTrangThai();
    $("modal").classList.add("show");
}

function dongChiTiet() {
    $("modal").classList.remove("show");
    dangMo = null;
}

function veNutTrangThai() {
    document.querySelectorAll("#mStatus button").forEach((btn) => {
        btn.classList.toggle("active", btn.dataset.status === dangMo.trangThai);
    });
}

// ===== Sự kiện =====
document.addEventListener("DOMContentLoaded", function () {
    docTrangThai();
    veDanhSach();

    // Tab HSK
    document.querySelectorAll(".hsk-tab").forEach((tab) => {
        tab.addEventListener("click", function () {
            document.querySelectorAll(".hsk-tab").forEach((t) => t.classList.remove("active"));
            this.classList.add("active");
            hskHienTai = this.dataset.hsk;
            veDanhSach();
        });
    });

    // Lọc trạng thái
    document.querySelectorAll(".status-btn").forEach((btn) => {
        btn.addEventListener("click", function () {
            document.querySelectorAll(".status-btn").forEach((b) => b.classList.remove("active"));
            this.classList.add("active");
            locTrangThai = this.dataset.status;
            veDanhSach();
        });
    });

    // Tìm kiếm
    const input = $("searchInput");
    input.addEventListener("input", function () {
        tuKhoa = this.value;
        $("btnClear").style.display = tuKhoa ? "block" : "none";
        veDanhSach();
    });

    $("btnClear").onclick = function () {
        input.value = "";
        tuKhoa = "";
        this.style.display = "none";
        veDanhSach();
        input.focus();
    };

    // Đổi trạng thái học trong popup
    document.querySelectorAll("#mStatus button").forEach((btn) => {
        btn.addEventListener("click", function () {
            dangMo.trangThai = this.dataset.status;
            luuTrangThai();
            veNutTrangThai();
            veDanhSach();
            thongBao(`Đã chuyển "${dangMo.ten}" sang ${tenTrangThai[dangMo.trangThai]}`);
        });
    });

    // Nghe câu ví dụ
    $("btnSound").onclick = function () {
        if (!("speechSynthesis" in window)) return thongBao("Trình duyệt không hỗ trợ phát âm");
        speechSynthesis.cancel();
        const voice = new SpeechSynthesisUtterance($("mExZh").textContent);
        voice.lang = "zh-CN";
        voice.rate = 0.85;
        speechSynthesis.speak(voice);
    };

    // Đóng popup
    $("modalClose").onclick = dongChiTiet;
    $("modal").addEventListener("click", (e) => { if (e.target.id === "modal") dongChiTiet(); });
    document.addEventListener("keydown", (e) => { if (e.key === "Escape") dongChiTiet(); });

    // Menu trên điện thoại
    $("btnMenu").onclick = () => {
        $("sidebar").classList.add("open");
        $("overlay").classList.add("show");
    };
    $("overlay").onclick = () => {
        $("sidebar").classList.remove("open");
        $("overlay").classList.remove("show");
    };
});