# PTIT Student Hub

Website quản lý và giới thiệu hồ sơ sinh viên bằng PHP 8.1+ và MySQL 8.0+ hoặc MariaDB, chạy trên XAMPP hoặc Laragon.

## Chức năng

- Trang chủ dạng Grid/Card, tìm theo tên hoặc mã sinh viên, lọc lớp, phân trang.
- Xem chi tiết hồ sơ gồm mã SV, lớp, ngành, email, điện thoại, sở thích, kỹ năng.
- Đăng nhập quản trị bằng Session, mật khẩu được băm bằng password_hash.
- Dashboard thống kê tổng hồ sơ và số sinh viên theo lớp.
- Thêm, sửa, xóa hồ sơ, kiểm tra dữ liệu, xác nhận trước khi xóa.
- Upload ảnh JPG/PNG/WEBP tối đa 2 MB, đổi tên ảnh ngẫu nhiên.
- API đọc danh sách tại api/students.php?q=...
- Giao diện responsive cho máy tính và điện thoại.

## Cài đặt bằng XAMPP trên Windows

1. Giải nén thư mục `student-profile-app` vào `C:\xampp\htdocs\student-profile-app`.
2. Mở XAMPP Control Panel, bật Apache và MySQL.
3. Truy cập `http://localhost/phpmyadmin`, chọn Import, nhập file `database.sql`. File SQL tự tạo database `student_db` nếu tài khoản có quyền.
4. Mặc định kết nối MySQL bằng `127.0.0.1`, database `student_db`, user `root`, mật khẩu rỗng. Nếu khác, sửa `config/db.php` hoặc đặt biến môi trường `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`.
5. Truy cập `http://localhost/student-profile-app/setup_admin.php`, tạo tài khoản Admin với mật khẩu ít nhất 10 ký tự.
6. **Xóa file `setup_admin.php` khỏi thư mục website ngay sau khi tạo Admin.**
7. Mở `http://localhost/student-profile-app/` để xem trang chủ.
8. Mở `http://localhost/student-profile-app/admin/login.php` để đăng nhập quản trị.

## Yêu cầu môi trường

- PHP 8.1 trở lên, các extension `pdo_mysql`, `fileinfo`, `mbstring`, `gd` (hoặc hỗ trợ getimagesize).
- MySQL 8 hoặc MariaDB tương thích.
- Apache được phép ghi vào thư mục `uploads`.
- Với ảnh tối đa 2 MB, cấu hình `upload_max_filesize` và `post_max_size` trong php.ini phải đủ lớn (khuyến nghị lần lượt 4M và 8M).
- Mặc định web chạy tại đường dẫn `/student-profile-app/`. Nếu đổi tên thư mục, các liên kết tương đối vẫn hoạt động.

## Cấu trúc

```text
student-profile-app/
  admin/
    login.php
    logout.php
    dashboard.php
    manage.php
    form.php
    delete.php
  api/
    students.php
  assets/
    css/style.css
    js/main.js
  config/db.php
  includes/functions.php
  includes/header.php
  includes/footer.php
  uploads/default-avatar.svg
  uploads/.htaccess
  index.php
  detail.php
  setup_admin.php
  database.sql
  README.md
```

## Kiểm thử thủ công

1. Truy cập trang chủ, kiểm tra 4 sinh viên mẫu và tìm kiếm theo tên/mã.
2. Chọn lọc lớp `D25TXCN08-B` và xem chi tiết một hồ sơ.
3. Truy cập `admin/dashboard.php` khi chưa đăng nhập: phải chuyển về trang login.
4. Đăng nhập Admin, thêm sinh viên mới kèm ảnh hợp lệ.
5. Thử thêm mã sinh viên trùng: phải báo lỗi.
6. Sửa hồ sơ, kiểm tra thông tin cập nhật và ảnh mới.
7. Xóa hồ sơ, chọn Hủy để kiểm tra không xóa; sau đó xác nhận để xóa.
8. Kiểm tra hiển thị trên màn hình điện thoại.

## Lưu ý

Dữ liệu trong `database.sql` là dữ liệu minh họa, không phải thông tin sinh viên thực tế. Trước khi triển khai công khai cần bổ sung HTTPS, giới hạn lượt đăng nhập, cơ chế phân quyền chi tiết và chính sách bảo vệ dữ liệu cá nhân. Không đưa file `setup_admin.php` lên môi trường production.
