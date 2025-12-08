
-- Crea tabella utenti applicazione
CREATE TABLE IF NOT EXISTS users (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  email         VARCHAR(255) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role          ENUM('USER','IT','ADMIN') NOT NULL DEFAULT 'USER',
  status        ENUM('pending_email','active','disabled') NOT NULL DEFAULT 'pending_email',
  verify_token  CHAR(64) DEFAULT NULL,
  first_name    VARCHAR(100) DEFAULT NULL,
  last_name     VARCHAR(100) DEFAULT NULL,
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
