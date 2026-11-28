// plt.js: phần tương tác phía trình duyệt (đọc to, ô viết chữ, luyện tập, ôn tập, điểm danh).
// Mỗi khối chỉ chạy khi trang có phần tử tương ứng (thuộc tính data-...).

const $ = (s, r = document) => r.querySelector(s);
const $$ = (s, r = document) => Array.from(r.querySelectorAll(s));

// Lưu tiến độ ở trình duyệt. Bọc try/catch vì có chế độ riêng tư không cho ghi.
const store = {
  get(k, d) { try { const v = localStorage.getItem(k); return v ? JSON.parse(v) : d; } catch (e) { return d; } },
  set(k, v) { try { localStorage.setItem(k, JSON.stringify(v)); } catch (e) { /* bỏ qua */ } },
};

// Đọc chữ Hán bằng giọng của trình duyệt
function speak(text) {
  if (!('speechSynthesis' in window) || !text) return;
  window.speechSynthesis.cancel();
  const u = new SpeechSynthesisUtterance(text);
  u.lang = 'zh-CN';
  u.rate = 0.85;
  window.speechSynthesis.speak(u);
}
document.addEventListener('click', (e) => {
  const b = e.target.closest('[data-speak]');
  if (b) speak(b.dataset.speak);
});

// So khớp đáp án: bỏ dấu thanh, khoảng trắng và dấu câu, không phân biệt hoa thường
const norm = (s) => String(s || '').normalize('NFD').replace(/[̀-ͯ]/g, '').toLowerCase().replace(/[\s,.!?，。！？、；;:：'"“”]/g, '');

// ---------- Trang chủ: điểm danh, lịch ----------
(function home() {
  const root = $('[data-home]');
  if (!root) return;
  const todayKey = root.dataset.today;
  const s = Object.assign({ xp: 0, streak: 0, days: [] }, store.get('plt.home', {}));
  const btn = $('[data-checkin]', root);
  const paint = () => {
    $('[data-xp]', root).textContent = s.xp + ' XP';
    $('[data-streak]', root).textContent = s.streak + ' ngày';
    $('[data-level]', root).textContent = 'Cấp ' + (Math.floor(s.xp / 500) + 1);
    const done = s.days.includes(todayKey);
    btn.disabled = done;
    btn.textContent = done ? 'Hôm nay bạn đã điểm danh' : 'Điểm danh nhận 50 điểm hôm nay';
    $$('[data-date]', root).forEach((c) => c.classList.toggle('bg-brand', s.days.includes(c.dataset.date)));
    $$('[data-date]', root).forEach((c) => c.classList.toggle('text-white', s.days.includes(c.dataset.date)));
    $('[data-month-total]', root).textContent = s.days.filter((d) => d.slice(0, 7) === todayKey.slice(0, 7)).length;
  };
  btn.addEventListener('click', () => {
    if (s.days.includes(todayKey)) return;
    s.days.push(todayKey);
    s.xp += 50;
    s.streak += 1;
    store.set('plt.home', s);
    paint();
  });
  paint();
})();

// ---------- Luyện viết ----------
(function writing() {
  const root = $('[data-writing]');
  if (!root) return;
  const chars = JSON.parse($('#writing-data').textContent);
  const cv = $('[data-canvas]', root);
  const ctx = cv.getContext('2d');
  const saved = Object.assign({ done: [], points: 0 }, store.get('plt.writing', {}));
  let idx = 0, strokes = 0, drawing = false;

  ctx.lineWidth = 8; ctx.lineCap = 'round'; ctx.lineJoin = 'round'; ctx.strokeStyle = '#111';
  const pos = (e) => { const r = cv.getBoundingClientRect(); return [(e.clientX - r.left) * cv.width / r.width, (e.clientY - r.top) * cv.height / r.height]; };
  cv.addEventListener('pointerdown', (e) => { drawing = true; cv.setPointerCapture(e.pointerId); const [x, y] = pos(e); ctx.beginPath(); ctx.moveTo(x, y); });
  cv.addEventListener('pointermove', (e) => { if (!drawing) return; const [x, y] = pos(e); ctx.lineTo(x, y); ctx.stroke(); });
  const end = () => { if (!drawing) return; drawing = false; strokes += 1; $('[data-strokes]', root).textContent = strokes; };
  cv.addEventListener('pointerup', end);
  cv.addEventListener('pointercancel', end);

  const clear = () => { ctx.clearRect(0, 0, cv.width, cv.height); strokes = 0; $('[data-strokes]', root).textContent = 0; $('[data-feedback]', root).textContent = ''; };
  const total = chars.length;
  const paintStats = () => {
    $('[data-done]', root).textContent = saved.done.length;
    $('[data-left]', root).textContent = Math.max(0, total - saved.done.length);
    $('[data-points]', root).textContent = saved.points;
    $('[data-learned]', root).textContent = saved.done.length;
    const pct = Math.round(saved.done.length / total * 100);
    $('[data-progress-text]', root).textContent = saved.done.length + '/' + total + ' chữ';
    $('[data-progress-pct]', root).textContent = pct + '%';
    $('[data-progress-bar]', root).style.width = pct + '%';
  };
  const show = (i) => {
    idx = (i + total) % total;
    const c = chars[idx];
    $('[data-char]', root).textContent = c.c;
    $('[data-hint-char]', root).textContent = c.c;
    $('[data-py]', root).textContent = c.py;
    $('[data-vi]', root).textContent = c.vi;
    $('[data-hsk]', root).textContent = 'HSK ' + c.hsk;
    $('[data-n]', root).textContent = c.strokes + ' nét';
    $('[data-tab-stroke]', root).textContent = 'Chữ này gồm ' + c.strokes + ' nét. Viết từ trên xuống dưới, từ trái sang phải; nét ngang trước nét sổ.';
    $('[data-tab-example]', root).textContent = c.example || '';
    $('[data-tab-info]', root).textContent = c.info || '';
    $('[data-speak-char]', root).dataset.speak = c.c;
    $$('[data-pick]', root).forEach((b) => b.classList.toggle('bg-pill', Number(b.dataset.pick) === idx));
    clear();
  };

  $$('[data-pick]', root).forEach((b) => b.addEventListener('click', () => show(Number(b.dataset.pick))));
  $('[data-clear]', root).addEventListener('click', clear);
  $('[data-rewrite]', root).addEventListener('click', clear);
  $('[data-hint]', root).addEventListener('click', () => $('[data-hint-char]', root).classList.toggle('opacity-0'));
  $('[data-next]', root).addEventListener('click', () => show(idx + 1));
  $('[data-submit]', root).addEventListener('click', () => {
    const fb = $('[data-feedback]', root);
    if (strokes === 0) { fb.textContent = 'Hãy viết chữ vào ô trước khi nộp bài.'; return; }
    const c = chars[idx];
    const gain = Math.abs(strokes - c.strokes) <= 2 ? 10 : 5;
    if (!saved.done.includes(c.c)) saved.done.push(c.c);
    saved.points += gain;
    store.set('plt.writing', saved);
    fb.textContent = 'Đã nộp: bạn vẽ ' + strokes + ' nét, chữ chuẩn có ' + c.strokes + ' nét. +' + gain + ' điểm.';
    paintStats();
  });
  $$('[data-tabbtn]', root).forEach((b) => b.addEventListener('click', () => {
    $$('[data-tabbtn]', root).forEach((x) => x.classList.toggle('border-brand', x === b));
    $$('[data-tabpanel]', root).forEach((p) => { p.hidden = p.dataset.tabpanel !== b.dataset.tabbtn; });
  }));
  paintStats();
  show(0);
})();

// ---------- Luyện tập: nói, nghe, từ vựng ----------
(function practice() {
  const root = $('[data-practice]');
  if (!root) return;
  const cfg = JSON.parse($('#practice-config').textContent);
  const items = cfg.items;
  const card = $('[data-card]', root);
  let i = 0, right = 0, checked = false;

  const tpl = {
    speak: (it) => `
      <div class="hanzi text-5xl font-bold">${esc(it.zh)}</div>
      ${it.py ? `<div class="mt-2 text-xl text-gray-500">${esc(it.py)}</div>` : ''}
      <div class="mt-1 text-lg text-gray-500">${esc(it.vi)}</div>
      <button type="button" data-speak="${esc(it.zh)}" class="mt-3 rounded-full border px-4 py-1 text-sm">🔊 Nghe mẫu</button>
      <button type="button" data-mic aria-label="Ghi âm" class="mx-auto mt-6 grid size-20 place-items-center rounded-full bg-danger text-3xl text-white">🎤</button>
      <p data-mic-text class="mt-3 text-sm text-gray-500">Vui lòng nhấn nút để bắt đầu ghi âm</p>
      <audio data-playback controls hidden class="mx-auto mt-2"></audio>
      <button type="button" data-skip class="mt-6 text-sm text-gray-600">→ Bỏ qua câu này</button>`,
    listen: (it) => `
      <button type="button" data-play aria-label="Nhấn để nghe" class="mx-auto grid size-28 place-items-center rounded-full bg-go text-5xl">🔊</button>
      <p class="mt-2 text-sm">Nhấn để nghe</p>
      ${it.display ? `<p class="hanzi mt-3 text-2xl">${esc(it.display)}</p>` : ''}
      <input data-answer type="text" placeholder="Gõ pinyin hoặc chữ Hán..." class="mx-auto mt-6 block w-full max-w-md rounded-2xl border-2 border-gray-200 px-4 py-3" />
      <div class="mx-auto mt-3 flex max-w-md justify-between">
        <button type="button" data-reveal class="rounded-full border px-4 py-1 text-xs">Hiện đáp án</button>
        <button type="button" data-check class="rounded-full border border-red-600 bg-danger px-6 py-1 text-xs">Kiểm tra</button>
      </div>`,
    vocab: (it) => `
      <div class="flex items-center justify-center gap-4"><span class="hanzi text-6xl font-bold">${esc(it.zh)}</span>
        <button type="button" data-speak="${esc(it.zh)}" aria-label="Nghe" class="text-3xl">🔊</button></div>
      <p class="mt-4 text-xl font-semibold text-gray-500">${esc(it.vi)}${it.type ? ' (' + esc(it.type.toLowerCase()) + ')' : ''}</p>
      <input data-answer type="text" placeholder="Gõ pinyin (Không cần dấu)" class="mx-auto mt-8 block w-full max-w-xs rounded-full border px-4 py-1 text-center" />
      <div class="mt-4 flex justify-center gap-4">
        <button type="button" data-check class="w-44 rounded-xl bg-go py-1.5 font-semibold">Kiểm tra đáp án</button>
        <button type="button" data-reveal class="w-44 rounded-xl bg-salmon py-1.5 font-semibold">Xem đáp án</button>
      </div>`,
  };

  function esc(s) { return String(s == null ? '' : s).replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c])); }

  function answersOf(it) { return cfg.type === 'vocab' ? [it.py, it.zh] : (it.answers || []); }
  function hintOf(it) { return cfg.type === 'vocab' ? it.zh + ' · ' + it.py : it.hint; }

  function render() {
    if (i >= items.length) { finish(); return; }
    checked = false;
    $('[data-count]', root).textContent = (i + 1) + '/' + items.length;
    $('[data-body]', card).innerHTML = tpl[cfg.type](items[i]);
    $('[data-msg]', card).textContent = '';
    $('[data-next-wrap]', card).hidden = true;
    bind();
  }

  function finish() {
    $('[data-count]', root).textContent = items.length + '/' + items.length;
    const scored = cfg.type !== 'speak';
    $('[data-body]', card).innerHTML = '<h2 class="text-2xl font-bold">Hoàn thành!</h2><p class="mt-2" data-final>' +
      (scored ? 'Bạn trả lời đúng ' + right + '/' + items.length + ' câu.' : 'Bạn đã luyện xong ' + items.length + ' mục.') + '</p>' +
      '<a href="' + esc(cfg.back) + '" class="mt-6 inline-block rounded-xl bg-danger px-6 py-2 font-semibold text-white">Quay lại</a>';
    $('[data-msg]', card).textContent = '';
    $('[data-next-wrap]', card).hidden = true;
  }

  function next() { i += 1; render(); }

  function bind() {
    const it = items[i];
    const body = $('[data-body]', card);
    const play = $('[data-play]', body);
    if (play) play.addEventListener('click', () => speak(it.speak));
    const skip = $('[data-skip]', body);
    if (skip) skip.addEventListener('click', next);
    const reveal = $('[data-reveal]', body);
    if (reveal) reveal.addEventListener('click', () => { $('[data-msg]', card).className = 'mt-4 text-sm text-gray-700'; $('[data-msg]', card).textContent = 'Đáp án: ' + hintOf(it); });
    const chk = $('[data-check]', body);
    if (chk) chk.addEventListener('click', () => {
      const v = norm($('[data-answer]', body).value);
      const msg = $('[data-msg]', card);
      if (!v) { msg.className = 'mt-4 text-sm text-gray-700'; msg.textContent = 'Hãy nhập đáp án trước.'; return; }
      const ok = answersOf(it).some((a) => norm(a) === v);
      if (ok && !checked) right += 1;
      checked = checked || ok;
      msg.className = 'mt-4 text-sm font-semibold ' + (ok ? 'text-green-700' : 'text-red-600');
      msg.textContent = ok ? 'Chính xác!' : 'Chưa đúng, thử lại hoặc bấm Hiện đáp án.';
      $('[data-next-wrap]', card).hidden = !ok && !checked;
    });
    const mic = $('[data-mic]', body);
    if (mic) wireMic(mic, body);
    // Mục nói: cho phép sang câu tiếp sau khi ghi âm
  }

  // Ghi âm bằng MediaRecorder; trình duyệt không hỗ trợ hoặc bị từ chối thì chỉ đổi trạng thái.
  function wireMic(mic, body) {
    const txt = $('[data-mic-text]', body);
    const audio = $('[data-playback]', body);
    let rec = null, chunks = [], on = false;
    mic.addEventListener('click', async () => {
      if (!on) {
        on = true; mic.classList.add('animate-pulse'); txt.textContent = 'Đang ghi âm... nhấn lần nữa để dừng';
        try {
          const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
          rec = new MediaRecorder(stream); chunks = [];
          rec.ondataavailable = (e) => chunks.push(e.data);
          rec.onstop = () => { audio.src = URL.createObjectURL(new Blob(chunks)); audio.hidden = false; stream.getTracks().forEach((t) => t.stop()); };
          rec.start();
        } catch (e) { rec = null; }
      } else {
        on = false; mic.classList.remove('animate-pulse');
        if (rec && rec.state !== 'inactive') rec.stop();
        txt.textContent = 'Đã ghi âm xong. Bấm "Câu tiếp theo" để tiếp tục.';
        $('[data-next-wrap]', card).hidden = false;
      }
    });
  }

  $('[data-next]', card).addEventListener('click', next);
  render();
})();

// ---------- Ôn tập: thẻ ghi nhớ ----------
(function review() {
  const stats = $('[data-review-stats]');
  if (stats) {
    const total = Number(stats.dataset.total);
    const m = store.get('plt.review', {});
    const known = Object.values(m).filter((v) => v === 'known').length;
    const again = Object.values(m).filter((v) => v === 'again').length;
    $('[data-known]', stats).textContent = known;
    $('[data-again]', stats).textContent = again;
    $('[data-total]', stats).textContent = total;
    $('[data-pct]', stats).textContent = (total ? Math.round(known / total * 100) : 0) + '%';
  }

  const root = $('[data-review]');
  if (!root) return;
  const items = JSON.parse($('#review-data').textContent);
  const m = store.get('plt.review', {});
  let i = 0, flipped = false;
  const face = $('[data-face]', root);
  const paint = () => {
    if (i >= items.length) {
      const known = items.filter((x) => m[x.id] === 'known').length;
      face.innerHTML = '<h2 class="text-2xl font-bold">Xong bộ ôn tập!</h2><p class="mt-2" data-final>Đã thuộc ' + known + '/' + items.length + ' từ.</p>';
      $('[data-actions]', root).hidden = true; $('[data-flip]', root).hidden = true;
      $('[data-count]', root).textContent = items.length + '/' + items.length;
      return;
    }
    const it = items[i];
    flipped = false;
    $('[data-count]', root).textContent = (i + 1) + '/' + items.length;
    face.innerHTML = '<div class="hanzi text-6xl font-bold">' + it.zh + '</div>';
    $('[data-actions]', root).hidden = true;
  };
  $('[data-flip]', root).addEventListener('click', () => {
    if (i >= items.length) return;
    const it = items[i];
    flipped = !flipped;
    face.innerHTML = '<div class="hanzi text-6xl font-bold">' + it.zh + '</div>' + (flipped ? '<div class="mt-3 text-xl text-gray-500">' + it.py + '</div><div class="mt-1 text-lg">' + it.vi + '</div>' : '');
    $('[data-actions]', root).hidden = !flipped;
  });
  const mark = (v) => { m[items[i].id] = v; store.set('plt.review', m); i += 1; paint(); };
  $('[data-known-btn]', root).addEventListener('click', () => mark('known'));
  $('[data-again-btn]', root).addEventListener('click', () => mark('again'));
  paint();
})();

// ---------- Ngữ pháp: trạng thái học ----------
(function grammarStatus() {
  const root = $('[data-grammar]');
  if (!root) return;
  const st = store.get('plt.grammar', {});
  let filter = 'all';
  const labels = { new: 'Chưa học', learning: 'Đang học', review: 'Đang ôn tập' };
  const apply = () => $$('[data-gid]', root).forEach((c) => {
    const s = st[c.dataset.gid] || 'new';
    $('[data-status]', c).value = s;
    c.hidden = filter !== 'all' && s !== filter;
  });
  $$('[data-status]', root).forEach((sel) => sel.addEventListener('change', () => {
    st[sel.closest('[data-gid]').dataset.gid] = sel.value; store.set('plt.grammar', st); apply();
  }));
  $$('[data-gfilter]', root).forEach((b) => b.addEventListener('click', () => {
    filter = b.dataset.gfilter;
    $$('[data-gfilter]', root).forEach((x) => { x.classList.toggle('bg-salmon', x === b); x.classList.toggle('bg-white', x !== b); });
    apply();
  }));
  apply();
})();
