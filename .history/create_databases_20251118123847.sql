-- Create databases for school_media project
CREATE DATABASE IF NOT EXISTS school_media_development CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE DATABASE IF NOT EXISTS school_media_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Grant all privileges to arif user for both localhost and 127.0.0.1
-- (MySQL treats them as different hosts)
GRANT ALL PRIVILEGES ON school_media_development.* TO 'arif'@'127.0.0.1';
GRANT ALL PRIVILEGES ON school_media_test.* TO 'arif'@'127.0.0.1';
GRANT ALL PRIVILEGES ON school_media_development.* TO 'arif'@'localhost';
GRANT ALL PRIVILEGES ON school_media_test.* TO 'arif'@'localhost';

-- Apply changes
FLUSH PRIVILEGES;

-- Verify databases were created
SHOW DATABASES LIKE 'school_media%';

-- Verify user privileges
SHOW GRANTS FOR 'arif'@'127.0.0.1';
SHOW GRANTS FOR 'arif'@'localhost';

