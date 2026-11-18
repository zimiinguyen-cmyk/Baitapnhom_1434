CREATE DATABASE IF NOT EXISTS student_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE student_db;
CREATE TABLE IF NOT EXISTS users (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 username VARCHAR(50) NOT NULL UNIQUE,
 password VARCHAR(255) NOT NULL,
 role VARCHAR(20) NOT NULL DEFAULT 'admin'
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS students (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 student_code VARCHAR(20) NOT NULL UNIQUE,
 fullname VARCHAR(100) NOT NULL,
 class_name VARCHAR(50) NOT NULL,
 email VARCHAR(100) DEFAULT NULL,
 phone VARCHAR(20) DEFAULT NULL,
 avatar VARCHAR(255) DEFAULT NULL,
 hobbies TEXT,
 major VARCHAR(100) DEFAULT NULL,
 skills TEXT,
 created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 created_by INT UNSIGNED DEFAULT NULL,
 CONSTRAINT fk_student_admin FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
 INDEX idx_students_fullname (fullname),
 INDEX idx_students_class (class_name)
) ENGINE=InnoDB;
INSERT IGNORE INTO students (student_code,fullname,class_name,email,phone,hobbies,major,skills) VALUES
('B25DTCN101','Nguyễn Minh Anh','D25TXCN08-B','minhanh@example.com','0912345678','Đọc sách, công nghệ','Công nghệ thông tin','HTML, CSS, JavaScript'),
('B25DTCN102','Trần Hoàng Nam','D25TXCN08-B','hoangnam@example.com','0987654321','Lập trình, bóng đá','Công nghệ thông tin','PHP, MySQL'),
('B25DTCN103','Lê Thu Hà','D25TXCN09-B','thuha@example.com','0901234567','Thiết kế, âm nhạc','Công nghệ thông tin','Figma, CSS'),
('B25DTCN104','Phạm Quốc Bảo','D25TXCN09-B','quocbao@example.com','0934567890','AI, robot','Công nghệ thông tin','Python, SQL');
