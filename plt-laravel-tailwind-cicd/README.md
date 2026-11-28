# PLT: web học tiếng Trung (Laravel + MySQL + Tailwind + GitHub Actions)

Đây là **bộ file chép đè vào dự án Laravel mới khởi tạo**: giao diện theo file Figma của nhóm (19 màn hình, phía học viên và phía quản trị), dữ liệu mẫu, test cho từng màn hình và pipeline CI/CD.

## 0. Quy tắc làm việc với Git (quan trọng)

Không push thẳng lên `main`. Mỗi việc làm trên một nhánh riêng tách từ `main`:

```bash
git checkout main
git pull
git checkout -b feature/giao-dien-trang-chu     # đặt tên theo việc đang làm
# ... sửa code ...
git add .
git commit -m "Thêm trang chủ"
git push -u origin feature/giao-dien-trang-chu
```

Sau đó mở **Pull Request** vào `main` trên GitHub. Mỗi lần push, GitHub Actions tự chạy test; chỉ merge khi dấu xanh. Pipeline **chỉ deploy khi code vào `main`**, nên nhánh riêng có lỗi cũng không ảnh hưởng server.

Nên bật bảo vệ nhánh: GitHub, Settings, Branches, Add rule cho `main`, tick *Require a pull request before merging* và *Require status checks to pass* (chọn job "Kiểm thử").

## 1. Khởi tạo dự án Laravel

Cần PHP 8.2 trở lên, Composer, Node 20, MySQL.

```bash
composer global require laravel/installer
laravel new plt        # chọn starter kit "None", database MySQL
cd plt
```

`laravel new` (Laravel 12) đã cài sẵn Tailwind CSS và Vite. Kiểm tra `package.json` có `tailwindcss` và `@tailwindcss/vite`, và `vite.config.js` có `tailwindcss()` trong `plugins`. Nếu thiếu, làm theo [hướng dẫn cài Tailwind với Vite trong Laravel](https://tailwindcss.com/docs/installation/framework-guides/laravel/vite):

```bash
npm install tailwindcss @tailwindcss/vite
```

```js
// vite.config.js
import tailwindcss from '@tailwindcss/vite'
export default defineConfig({ plugins: [laravel({ /* giữ nguyên */ }), tailwindcss()] })
```

## 2. Chép bộ file này vào dự án

Chạy trong thư mục bộ file này (đổi `../plt` thành đường dẫn dự án của bạn):

```bash
cp -r app database resources routes tests phpunit.xml .github ../plt/
rm ../plt/tests/Feature/ExampleTest.php    # test mẫu của Laravel mong "/" trả 200, nhưng "/" đã chuyển hướng
```

Các file của Laravel bị **ghi đè**: `app/Models/User.php`, `database/seeders/DatabaseSeeder.php`, `resources/css/app.css`, `resources/js/app.js`, `routes/web.php`, `phpunit.xml`.

Thêm vào `config/app.php` để lịch và ngày giờ theo giờ Việt Nam: `'timezone' => 'Asia/Ho_Chi_Minh',`.

## 3. Cấu hình MySQL và chạy

Tạo database rỗng (ví dụ `plt`), rồi sửa `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=plt
DB_USERNAME=root
DB_PASSWORD=
```

```bash
php artisan migrate --seed     # tạo bảng + nạp dữ liệu mẫu
npm install
npm run dev                    # giữ cửa sổ này chạy (Vite + Tailwind)
php artisan serve              # mở cửa sổ khác, truy cập http://127.0.0.1:8000
```

Tài khoản mẫu (mật khẩu `password`): học viên `nguyenvana@email.com`, quản trị `admin@plt.test`. **Đổi mật khẩu và xóa tài khoản mẫu trước khi đưa lên server thật.**

## 4. Test

```bash
php artisan test
```

Mỗi màn hình có test riêng trong `tests/Feature/ScreensTest.php`: mở được, đúng nội dung, phân quyền (khách, học viên, admin), tra từ, nộp bài thi, thêm/sửa/xóa trong trang quản trị. Ở máy cá nhân test chạy trên SQLite trong bộ nhớ (không đụng dữ liệu MySQL của bạn); trên GitHub Actions test chạy trên MySQL thật.

## 5. Bảng màn hình Figma và đường dẫn

| Màn hình Figma | Đường dẫn |
| --- | --- |
| Giới Thiệu | `/gioi-thieu` |
| Trang Chủ | `/trang-chu` |
| Luyện Viết | `/luyen-viet` |
| Luyện Nói (chọn / luyện) | `/luyen-noi`, `/luyen-noi/bai` |
| Luyện Nghe (chọn / luyện) | `/luyen-nghe`, `/luyen-nghe/bai` |
| Luyện ngữ pháp (chọn / luyện) | `/luyen-ngu-phap`, `/luyen-ngu-phap/bai` |
| Từ Vựng (tra từ / luyện) | `/tu-vung`, `/tu-vung/luyen` |
| Ngữ Pháp | `/ngu-phap` |
| Ôn Tập | `/on-tap`, `/on-tap/{bộ}` |
| Luyện Thi | `/luyen-thi`, `/luyen-thi/{hsk}` |
| Admin: Dashboard | `/admin` |
| Admin: Danh Sách Quản Lý | `/admin/quan-ly` |
| Admin: users, từ vựng, giáo trình, ngữ pháp, lịch sử kiểm tra | `/admin/users`, `/admin/tu-vung`, `/admin/giao-trinh`, `/admin/ngu-phap`, `/admin/lich-su` |

## 6. CI/CD với GitHub Actions

File `.github/workflows/ci-cd.yml`:

1. **Kiểm thử** (mọi nhánh, mọi Pull Request): cài PHP, Composer, Node, build Tailwind bằng Vite, migrate trên MySQL, `php artisan test`.
2. **Deploy** (chỉ khi push vào `main` và test đạt): chép file build, kéo mã mới trên server qua SSH, `composer install --no-dev`, `migrate --force`, cache cấu hình.

Job Deploy là **khung mẫu**, cần chỉnh theo server mà thầy hướng dẫn. Khi có server, thêm 5 secret ở GitHub (Settings, Secrets and variables, Actions): `SSH_HOST`, `SSH_USER`, `SSH_KEY` (khóa riêng SSH), `SSH_PORT` (không bắt buộc, mặc định 22), `DEPLOY_PATH` (thư mục dự án trên server). Trên server cần sẵn: PHP, Composer, Git, MySQL, file `.env` thật, và thư mục dự án đã `git clone`. Tạo environment `production` (Settings, Environments) và bật *Required reviewers* nếu muốn có người duyệt trước khi lên server.

Tuyệt đối không ghi mật khẩu, khóa SSH hay file `.env` vào Git.

## 7. Còn lại đang lưu ở trình duyệt

Điểm danh, XP, tiến độ luyện viết, trạng thái thẻ ôn tập và trạng thái học ngữ pháp hiện lưu trong `localStorage` của trình duyệt (xem `resources/js/plt.js`). Muốn lưu theo tài khoản thì thêm bảng trong MySQL (ví dụ `user_progress`) và API ghi/đọc.

## 8. Chưa kiểm chứng

Bộ file này được viết khi không có mạng nên **chưa chạy thử trong Laravel thật**. Đã kiểm tra: cú pháp PHP, cân bằng thẻ Blade, và logic JavaScript trên trình duyệt thật (36 kiểm tra: luyện từ vựng, nghe, nói, viết, ôn tập, điểm danh). Chưa chạy: `php artisan test`, `npm run build`, giao diện Tailwind, workflow trên GitHub. Lần đầu chạy, nếu có test đỏ hoặc trang lỗi thì gửi thông báo lỗi để sửa.
