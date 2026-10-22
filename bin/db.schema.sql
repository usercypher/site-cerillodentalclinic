SET time_zone = '+00:00';

-- User Table
CREATE TABLE user (
  id INT AUTO_INCREMENT PRIMARY KEY,
  firstname VARCHAR(50),
  middlename VARCHAR(50),
  lastname VARCHAR(50),
  date_of_birth DATE,
  contact VARCHAR(20),
  email VARCHAR(100) UNIQUE,
  password VARCHAR(255),
  role ENUM('admin','patient','doctor','staff') NOT NULL
);

-- Message Table
CREATE TABLE message (
  id INT AUTO_INCREMENT PRIMARY KEY,
  sender_id INT,
  receiver_id INT,
  subject VARCHAR(255) NOT NULL,
  body TEXT NOT NULL,
  sent_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  read_at DATETIME,
  type ENUM('info','cancellation','reminder','system') DEFAULT 'info',
  FOREIGN KEY (receiver_id) REFERENCES user(id) ON DELETE CASCADE,
  FOREIGN KEY (sender_id) REFERENCES user(id) ON DELETE SET NULL
);

-- API Table
CREATE TABLE api (
  id INT AUTO_INCREMENT PRIMARY KEY,
  content VARCHAR(255) NOT NULL UNIQUE,
  user_id INT,
  FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE
);

-- Chatbot Table
CREATE TABLE chatbot (
  id INT AUTO_INCREMENT PRIMARY KEY,
  knowledge TEXT
);

-- Service Table
CREATE TABLE service (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  description TEXT,
  duration INT NOT NULL,
  price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  appointment_status_default ENUM('pending', 'approved') DEFAULT 'approved'
);

-- Schedule Table
CREATE TABLE schedule (
  id INT AUTO_INCREMENT PRIMARY KEY,
  date DATE NOT NULL,
  time_start TIME NOT NULL,
  time_end TIME NOT NULL,
  user_id INT,
  FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE
);

-- Appointment Table
CREATE TABLE appointment (
  id INT AUTO_INCREMENT PRIMARY KEY,
  type ENUM('regular','break','cancelled') DEFAULT 'regular',
  time_start TIME NOT NULL,
  time_end TIME NOT NULL,
  status ENUM('pending','approved','completed','cancelled') DEFAULT 'approved',
  schedule_id INT,
  user_id INT,
  service_id INT,
  reminder_first TINYINT(1) DEFAULT 0,
  reminder_second TINYINT(1) DEFAULT 0,
  FOREIGN KEY (schedule_id) REFERENCES schedule(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE SET NULL,
  FOREIGN KEY (service_id) REFERENCES service(id)
);
