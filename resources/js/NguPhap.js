const dataElement = document.getElementById('grammarData');

if (dataElement) {
    const progressUrlTemplate = dataElement.dataset.progressUrlTemplate;
    const nguPhap = JSON.parse(dataElement.textContent || '[]').map((point) => {
        const example = point.examples[0] || null;

        return {
            id: point.id,
            hsk: point.hsk_level,
            hanzi: example?.hanzi || point.title,
            pinyin: example?.pinyin || '',
            ten: point.title,
            moTa: point.explanation,
            cauTruc: point.structure || '',
            viDu: {
                zh: example?.hanzi || '',
                py: example?.pinyin || '',
                vi: example?.meaning || '',
            },
            trangThai: point.progress?.status || 'chua-hoc',
            progressUrl: progressUrlTemplate.replace('__POINT__', point.id),
        };
    });
    const tenTrangThai = {
        'chua-hoc': 'Chưa học',
        'dang-hoc': 'Đang học',
        'on-tap': 'Đang ôn tập',
    };

    let locTrangThai = 'tat-ca';
    let tuKhoa = '';
    let dangMo = null;

    const $ = (id) => document.getElementById(id);

    function boDau(value) {
        return value.normalize('NFD').replace(/[\u0300-\u036f]/g, '')
            .replace(/đ/g, 'd').replace(/[\s…]+/g, '').toLowerCase();
    }

    function thongBao(text) {
        const toast = $('toast');
        toast.textContent = text;
        toast.classList.add('show');
        clearTimeout(toast.timer);
        toast.timer = setTimeout(() => toast.classList.remove('show'), 2200);
    }

    function escapeHtml(value) {
        return String(value).replace(/[&<>"']/g, (character) => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;',
        })[character]);
    }

    function locDanhSach() {
        const tuKhoaKhongDau = boDau(tuKhoa);

        return nguPhap.filter((grammar) =>
            (locTrangThai === 'tat-ca' || grammar.trangThai === locTrangThai)
            && (!tuKhoaKhongDau
                || grammar.hanzi.includes(tuKhoa.trim())
                || boDau(grammar.pinyin).includes(tuKhoaKhongDau)
                || boDau(grammar.ten).includes(tuKhoaKhongDau))
        );
    }

    function capNhatThongKe() {
        const daHoc = nguPhap.filter((grammar) => grammar.trangThai !== 'chua-hoc').length;
        const phanTram = nguPhap.length ? Math.round((daHoc / nguPhap.length) * 100) : 0;

        $('progressText').textContent = `${daHoc}/${nguPhap.length} điểm ngữ pháp đã học (${phanTram}%)`;
        $('progressFill').style.width = `${phanTram}%`;
    }

    function veDanhSach() {
        const danhSach = locDanhSach();
        $('grammarGrid').innerHTML = danhSach.map((grammar) => `
            <article class="grammar-card" data-id="${grammar.id}" tabindex="0" role="button">
                <div class="gc-top">
                    <div class="gc-title">
                        <span class="gc-hanzi">${escapeHtml(grammar.hanzi)}</span>
                        <span class="gc-pinyin">${escapeHtml(grammar.pinyin)}</span>
                    </div>
                    <span class="badge-hsk">${escapeHtml(grammar.hsk || 'Chưa phân loại')}</span>
                </div>
                <p class="gc-name">${escapeHtml(grammar.ten)}</p>
                <p class="gc-desc">${escapeHtml(grammar.moTa)}</p>
                <div class="gc-bottom">
                    <span class="status-tag ${grammar.trangThai}">${escapeHtml(tenTrangThai[grammar.trangThai])}</span>
                    <span class="gc-more">Xem chi tiết <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></span>
                </div>
            </article>
        `).join('');

        $('emptyBox').style.display = danhSach.length ? 'none' : 'flex';
        $('resultCount').textContent = `Hiển thị ${danhSach.length} điểm ngữ pháp`;

        $('grammarGrid').querySelectorAll('.grammar-card').forEach((card) => {
            card.addEventListener('click', () => moChiTiet(Number(card.dataset.id)));
            card.addEventListener('keydown', (event) => {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    moChiTiet(Number(card.dataset.id));
                }
            });
        });

        capNhatThongKe();
    }

    function moChiTiet(id) {
        dangMo = nguPhap.find((grammar) => grammar.id === id);
        if (!dangMo) return;

        $('mHanzi').textContent = dangMo.hanzi;
        $('mPinyin').textContent = dangMo.pinyin;
        $('mHsk').textContent = dangMo.hsk || 'Chưa phân loại';
        $('mName').textContent = dangMo.ten;
        $('mDesc').textContent = dangMo.moTa;
        $('mStructure').textContent = dangMo.cauTruc;
        $('mExZh').textContent = dangMo.viDu.zh;
        $('mExPy').textContent = dangMo.viDu.py;
        $('mExVi').textContent = dangMo.viDu.vi;
        veNutTrangThai();
        $('modal').classList.add('show');
    }

    function dongChiTiet() {
        $('modal').classList.remove('show');
        dangMo = null;
    }

    function veNutTrangThai() {
        document.querySelectorAll('#mStatus button').forEach((button) => {
            button.classList.toggle('active', button.dataset.status === dangMo.trangThai);
        });
    }

    async function capNhatTrangThai(status) {
        if (!dangMo) return;

        const grammar = dangMo;
        const buttons = Array.from(document.querySelectorAll('#mStatus button'));
        buttons.forEach((button) => { button.disabled = true; });

        try {
            const response = await fetch(grammar.progressUrl, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ status }),
            });

            if (!response.ok) {
                throw new Error(`Progress update failed with status ${response.status}`);
            }

            const result = await response.json();
            grammar.trangThai = result.status;
            if (dangMo === grammar) veNutTrangThai();
            veDanhSach();
            thongBao(`Đã chuyển "${grammar.ten}" sang ${tenTrangThai[grammar.trangThai]}`);
        } catch (error) {
            console.error(error);
            thongBao('Không thể cập nhật trạng thái. Vui lòng thử lại.');
        } finally {
            buttons.forEach((button) => { button.disabled = false; });
        }
    }

    document.querySelectorAll('.status-btn').forEach((button) => {
        button.addEventListener('click', () => {
            document.querySelectorAll('.status-btn').forEach((item) => item.classList.remove('active'));
            button.classList.add('active');
            locTrangThai = button.dataset.status;
            veDanhSach();
        });
    });

    $('searchInput').addEventListener('input', (event) => {
        tuKhoa = event.target.value;
        $('btnClear').style.display = tuKhoa ? 'block' : 'none';
        veDanhSach();
    });

    $('btnClear').addEventListener('click', () => {
        $('searchInput').value = '';
        tuKhoa = '';
        $('btnClear').style.display = 'none';
        veDanhSach();
        $('searchInput').focus();
    });

    document.querySelectorAll('#mStatus button').forEach((button) => {
        button.addEventListener('click', () => capNhatTrangThai(button.dataset.status));
    });

    $('btnSound').addEventListener('click', () => {
        if (!('speechSynthesis' in window)) {
            thongBao('Trình duyệt không hỗ trợ phát âm');
            return;
        }

        window.speechSynthesis.cancel();
        const utterance = new SpeechSynthesisUtterance($('mExZh').textContent);
        utterance.lang = 'zh-CN';
        utterance.rate = 0.85;
        window.speechSynthesis.speak(utterance);
    });

    $('modalClose').addEventListener('click', dongChiTiet);
    $('modal').addEventListener('click', (event) => {
        if (event.target.id === 'modal') dongChiTiet();
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') dongChiTiet();
    });

    veDanhSach();

    const selectedGrammarId = Number(new URLSearchParams(window.location.search).get('grammar_id'));
    if (Number.isSafeInteger(selectedGrammarId) && selectedGrammarId > 0) {
        moChiTiet(selectedGrammarId);
    }
}
