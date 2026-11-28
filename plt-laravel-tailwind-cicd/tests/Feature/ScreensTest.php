<?php

namespace Tests\Feature;

use App\Models\ExamQuestion;
use App\Models\ExamResult;
use App\Models\User;
use App\Models\Vocabulary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Mỗi màn hình trong Figma có ít nhất một test ở file này (tên test ghi rõ màn hình nào).
 * Chạy: php artisan test
 */
class ScreensTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();   // test không cần build file CSS/JS
        $this->seed();          // nạp dữ liệu mẫu giống môi trường thật
    }

    private function student(): User
    {
        return User::where('email', 'nguyenvana@email.com')->firstOrFail();
    }

    private function admin(): User
    {
        return User::where('email', 'admin@plt.test')->firstOrFail();
    }

    public function test_dung_loai_database_khi_ci_yeu_cau(): void
    {
        $expect = getenv('EXPECT_DB');
        if (! $expect) {
            $this->markTestSkipped('Chạy ở máy cá nhân, không ép loại database.');
        }
        $this->assertSame($expect, config('database.default'), 'CI phải chạy test trên đúng loại database đã khai báo.');
    }

    // ---------- Màn hình công khai ----------

    public function test_man_hinh_gioi_thieu(): void
    {
        $this->get('/gioi-thieu')->assertOk()
            ->assertSee('Giới Thiệu Website Học Tiếng Trung')
            ->assertSee('Đăng Nhập')->assertSee('Đăng Ký')
            ->assertSee('Luyện nói')->assertSee('Luyện viết')->assertSee('Luyện nghe');
    }

    public function test_trang_goc_chuyen_ve_gioi_thieu(): void
    {
        $this->get('/')->assertRedirect(route('gioi-thieu'));
    }

    // ---------- Quyền truy cập ----------

    public static function studentPages(): array
    {
        return [
            'Trang chủ' => ['/trang-chu', 'Điểm danh nhận 50 điểm hôm nay'],
            'Luyện viết' => ['/luyen-viet', 'Ô luyện tập'],
            'Luyện nói (chọn)' => ['/luyen-noi', 'Công cụ luyện nói'],
            'Luyện nghe (chọn)' => ['/luyen-nghe', 'Công cụ luyện nghe'],
            'Luyện ngữ pháp (chọn)' => ['/luyen-ngu-phap', 'Công cụ luyện ngữ pháp'],
            'Từ vựng (tra từ)' => ['/tu-vung', 'Danh Sách Từ Gợi Ý'],
            'Từ vựng (luyện)' => ['/tu-vung/luyen', 'practice-config'],
            'Ngữ pháp' => ['/ngu-phap', 'Nội dung bài học được biên soạn bởi PLT Solutions'],
            'Ôn tập' => ['/on-tap', 'Bắt Ôn Tập'],
            'Ôn tập (bộ thẻ)' => ['/on-tap/1', 'Lật thẻ'],
            'Luyện thi' => ['/luyen-thi', 'Chọn cấp độ thi thử'],
            'Làm đề thi' => ['/luyen-thi/1', 'Nộp bài'],
        ];
    }

    #[DataProvider('studentPages')]
    public function test_khach_phai_dang_nhap_de_vao_man_hinh_hoc_vien(string $url): void
    {
        $this->get($url)->assertRedirect(route('login'));
    }

    #[DataProvider('studentPages')]
    public function test_hoc_vien_mo_duoc_tung_man_hinh(string $url, string $text): void
    {
        $this->actingAs($this->student())->get($url)->assertOk()->assertSee($text, false);
    }

    public function test_hoc_vien_khong_vao_duoc_khu_quan_tri(): void
    {
        $this->actingAs($this->student())->get('/admin')->assertForbidden();
        $this->actingAs($this->student())->get('/admin/users')->assertForbidden();
    }

    public function test_khach_vao_quan_tri_bi_chuyen_ve_dang_nhap(): void
    {
        $this->get('/admin')->assertRedirect(route('login'));
    }

    // ---------- Đăng nhập, đăng ký ----------

    public function test_dang_nhap_dung_mat_khau(): void
    {
        $this->post('/dang-nhap', ['email' => 'nguyenvana@email.com', 'password' => 'password'])->assertRedirect(route('trang-chu'));
        $this->assertAuthenticated();
    }

    public function test_dang_nhap_admin_vao_dashboard(): void
    {
        $this->post('/dang-nhap', ['email' => 'admin@plt.test', 'password' => 'password'])->assertRedirect(route('admin.dashboard'));
    }

    public function test_dang_nhap_sai_mat_khau(): void
    {
        $this->from('/dang-nhap')->post('/dang-nhap', ['email' => 'nguyenvana@email.com', 'password' => 'sai'])
            ->assertRedirect('/dang-nhap')->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_tai_khoan_bi_khoa_khong_dang_nhap_duoc(): void
    {
        // Vũ Thị F (vuthif@email.com) có is_active = false trong dữ liệu mẫu
        $this->post('/dang-nhap', ['email' => 'vuthif@email.com', 'password' => 'password'])->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_dang_ky_luon_tao_hoc_vien_khong_nhan_role_tu_form(): void
    {
        $this->post('/dang-ky', [
            'name' => 'Người Mới', 'email' => 'moi@email.com', 'password' => 'matkhau1', 'password_confirmation' => 'matkhau1', 'role' => 'Admin',
        ])->assertRedirect(route('trang-chu'));
        $this->assertSame('Student', User::where('email', 'moi@email.com')->value('role'));
    }

    public function test_dang_xuat(): void
    {
        $this->actingAs($this->student())->post('/dang-xuat')->assertRedirect(route('gioi-thieu'));
        $this->assertGuest();
    }

    // ---------- Từ vựng, ngữ pháp ----------

    public function test_tra_tu_bang_pinyin_khong_dau(): void
    {
        $this->actingAs($this->student())->get('/tu-vung?q=ni+hao')->assertOk()->assertSee('你好')->assertSee('Xin chào');
    }

    public function test_tra_tu_bang_nghia_tieng_viet(): void
    {
        $this->actingAs($this->student())->get('/tu-vung?q=' . urlencode('Bệnh viện'))->assertOk()->assertSee('医院');
    }

    public function test_tra_tu_khong_co_ket_qua(): void
    {
        $this->actingAs($this->student())->get('/tu-vung?q=zzzzzz')->assertOk()->assertSee('Không tìm thấy từ');
    }

    public function test_ngu_phap_loc_theo_cap_do(): void
    {
        $this->actingAs($this->student())->get('/ngu-phap?hsk=1')->assertOk()->assertSee('语素')->assertDontSee('把字句');
        $this->actingAs($this->student())->get('/ngu-phap?hsk=3')->assertOk()->assertSee('把字句');
    }

    public function test_ngu_phap_cap_chua_co_noi_dung(): void
    {
        $this->actingAs($this->student())->get('/ngu-phap?hsk=5')->assertOk()->assertSee('đang được biên soạn');
    }

    // ---------- Luyện nói, nghe, ngữ pháp ----------

    public static function practiceUrls(): array
    {
        return [
            'nói: đọc từ vựng' => ['/luyen-noi/bai?mode=tu&hsk=1'],
            'nói: theo câu' => ['/luyen-noi/bai?mode=cau&hsk=1'],
            'nói: hội thoại' => ['/luyen-noi/bai?mode=hoi-thoai&hsk=1&topic=' . 'Trường học'],
            'nghe: nghe từ' => ['/luyen-nghe/bai?mode=tu&hsk=1'],
            'nghe: điền từ' => ['/luyen-nghe/bai?mode=dien&hsk=2'],
            'nghe: chép câu' => ['/luyen-nghe/bai?mode=chep&hsk=3'],
            'ngữ pháp: chữ đơn' => ['/luyen-ngu-phap/bai?mode=don&hsk=1&count=10'],
            'ngữ pháp: từ ghép' => ['/luyen-ngu-phap/bai?mode=ghep&hsk=1&count=20'],
            'ngữ pháp: câu' => ['/luyen-ngu-phap/bai?mode=cau&hsk=1&count=10'],
            'HSK chưa có từ mẫu thì lấy toàn bộ' => ['/luyen-nghe/bai?mode=tu&hsk=6'],
        ];
    }

    #[DataProvider('practiceUrls')]
    public function test_vao_bai_luyen_tap(string $url): void
    {
        $res = $this->actingAs($this->student())->get($url)->assertOk()->assertSee('practice-config', false);
        $this->assertStringContainsString('"items":[{', $res->getContent());
    }

    public function test_bai_luyen_tap_thieu_che_do_quay_ve_man_chon(): void
    {
        $this->actingAs($this->student())->get('/luyen-nghe/bai')->assertRedirect(route('luyen-nghe'));
    }

    public function test_bai_luyen_nghe_chua_dap_an_de_kiem_tra(): void
    {
        $res = $this->actingAs($this->student())->get('/luyen-nghe/bai?mode=tu&hsk=1');
        $this->assertStringContainsString('"answers"', $res->getContent());
        $this->assertStringContainsString('"speak"', $res->getContent());
    }

    // ---------- Ôn tập, luyện thi ----------

    public function test_bo_on_tap_khong_ton_tai(): void
    {
        $this->actingAs($this->student())->get('/on-tap/9')->assertNotFound();
    }

    public function test_de_thi_chua_bien_soan_tra_404(): void
    {
        $this->actingAs($this->student())->get('/luyen-thi/4')->assertNotFound();
    }

    public function test_nop_bai_thi_dung_het_duoc_100_diem_va_luu_lich_su(): void
    {
        $answers = ExamQuestion::where('hsk', 1)->pluck('answer', 'id')->all();
        $before = ExamResult::count();

        $this->actingAs($this->student())->post('/luyen-thi/1', ['answers' => $answers])
            ->assertOk()->assertSee('Đúng 5/5 câu')->assertSee('Đạt');

        $this->assertSame($before + 1, ExamResult::count());
        $this->assertSame(100, ExamResult::latest('id')->first()->score);
    }

    public function test_nop_bai_thi_sai_het_khong_dat(): void
    {
        $answers = ExamQuestion::where('hsk', 2)->get()->mapWithKeys(fn ($q) => [$q->id => ($q->answer + 1) % 4])->all();

        $this->actingAs($this->student())->post('/luyen-thi/2', ['answers' => $answers])
            ->assertOk()->assertSee('Đúng 0/5 câu')->assertSee('Chưa đạt');
    }

    public function test_nop_bai_thi_bo_trong_tinh_la_sai(): void
    {
        $this->actingAs($this->student())->post('/luyen-thi/3', [])->assertOk()->assertSee('Đúng 0/5 câu');
    }

    // ---------- Quản trị ----------

    public function test_dashboard_hien_so_lieu_that(): void
    {
        $this->actingAs($this->admin())->get('/admin')->assertOk()
            ->assertSee('Dashboard')->assertSee('Tổng người dùng')->assertSee('Người dùng gần đây')
            ->assertSee((string) User::count());
    }

    public static function adminKinds(): array
    {
        return [
            'users' => ['users', 'Danh sách users'],
            'từ vựng' => ['tu-vung', 'Danh sách từ vựng'],
            'giáo trình' => ['giao-trinh', 'Danh sách giáo trình'],
            'ngữ pháp' => ['ngu-phap', 'Danh sách ngữ pháp'],
            'lịch sử kiểm tra' => ['lich-su', 'Lịch sử kiểm tra'],
        ];
    }

    #[DataProvider('adminKinds')]
    public function test_admin_mo_duoc_tung_danh_sach(string $kind, string $title): void
    {
        $this->actingAs($this->admin())->get("/admin/{$kind}")->assertOk()->assertSee($title);
    }

    public function test_man_hinh_danh_sach_quan_ly_co_tab(): void
    {
        $this->actingAs($this->admin())->get('/admin/quan-ly')->assertOk()
            ->assertSee('Danh Sách Quản Lý')->assertSee('Tổng số')->assertSee('Hoạt động')->assertSee('Không hoạt động');
    }

    public function test_thong_ke_users_dung_voi_du_lieu(): void
    {
        $active = User::where('is_active', true)->count();
        $this->actingAs($this->admin())->get('/admin/users')->assertOk()
            ->assertSeeInOrder(['Tổng số', (string) User::count(), 'Hoạt động', (string) $active]);
    }

    public function test_giao_trinh_loc_theo_cap_do(): void
    {
        $this->actingAs($this->admin())->get('/admin/giao-trinh?f=2')->assertOk()
            ->assertSee('Giáo trình HSK 2')->assertDontSee('Giáo trình HSK 5');
    }

    public function test_lich_su_loc_khong_dat(): void
    {
        $this->actingAs($this->admin())->get('/admin/lich-su?f=khong-dat')->assertOk()
            ->assertSee('Không đạt')->assertDontSee('Nguyễn Văn A'); // 85 điểm, đạt
    }

    public function test_tim_kiem_tu_vung_trong_admin(): void
    {
        $this->actingAs($this->admin())->get('/admin/tu-vung?q=' . urlencode('晚上'))->assertOk()
            ->assertSee('Buổi tối')->assertDontSee('Bệnh viện');
    }

    public function test_them_sua_xoa_tu_vung(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin)->get('/admin/tu-vung/them')->assertOk()->assertSee('Hán từ');

        $this->actingAs($admin)->post('/admin/tu-vung', ['zh' => '书', 'py' => 'shū', 'vi' => 'Sách', 'hsk' => 1])
            ->assertRedirect(route('admin.list', 'tu-vung'));
        $word = Vocabulary::where('zh', '书')->firstOrFail();

        $this->actingAs($admin)->put("/admin/tu-vung/{$word->id}", ['zh' => '书', 'py' => 'shū', 'vi' => 'Quyển sách', 'hsk' => 1]);
        $this->assertSame('Quyển sách', $word->fresh()->vi);

        $this->actingAs($admin)->delete("/admin/tu-vung/{$word->id}")->assertRedirect(route('admin.list', 'tu-vung'));
        $this->assertNull(Vocabulary::find($word->id));
    }

    public function test_them_tu_vung_thieu_truong_bat_buoc_bi_tu_choi(): void
    {
        $this->actingAs($this->admin())->post('/admin/tu-vung', ['zh' => '', 'py' => '', 'vi' => '', 'hsk' => 99])
            ->assertSessionHasErrors(['zh', 'py', 'vi', 'hsk']);
    }

    public function test_them_user_moi_dung_mat_khau_mac_dinh(): void
    {
        $this->actingAs($this->admin())->post('/admin/users', ['name' => 'Giáo Viên Mới', 'email' => 'gv@email.com', 'role' => 'Teacher', 'is_active' => '1'])
            ->assertRedirect(route('admin.list', 'users'));
        $user = User::where('email', 'gv@email.com')->firstOrFail();
        $this->assertTrue($user->is_active);
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('password', $user->password));
    }

    public function test_khong_the_trung_email_user(): void
    {
        $this->actingAs($this->admin())->post('/admin/users', ['name' => 'Trùng', 'email' => 'nguyenvana@email.com', 'role' => 'Student'])
            ->assertSessionHasErrors('email');
    }

    public function test_admin_khong_tu_xoa_minh(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin)->delete("/admin/users/{$admin->id}")->assertSessionHasErrors('xoa');
        $this->assertNotNull(User::find($admin->id));
    }

    public function test_loai_danh_sach_khong_ton_tai_tra_404(): void
    {
        $this->actingAs($this->admin())->get('/admin/khong-co')->assertNotFound();
    }
}
