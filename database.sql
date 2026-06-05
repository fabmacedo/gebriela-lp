CREATE TABLE IF NOT EXISTS site_settings (
  setting_key VARCHAR(80) NOT NULL PRIMARY KEY,
  setting_value TEXT NOT NULL,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO site_settings (setting_key, setting_value) VALUES
('whatsapp_raw', '5574981405295'),
('whatsapp_friendly', '(74) 98140-5295'),
('email_contato', 'contato@gabrielapitaadvogados.com.br'),
('oab_registro', 'OAB/BA 123.456'),
('nome_escritorio', 'Gabriela Pita Advogados Associados'),
('endereco_local', 'Senhor do Bonfim - BA'),
('google_reviews_url', 'https://share.google/4WiH0xnA9gj7wySTe'),
('hero_image_url', 'image/foto-hero.png'),
('favicon_url', ''),
('smtp_enabled', '1'),
('smtp_host', 'mail.gabrielapitaadvogados.com.br'),
('smtp_port', '587'),
('smtp_encryption', 'tls'),
('smtp_username', 'contato@gabrielapitaadvogados.com.br'),
('smtp_password', ''),
('smtp_from_email', 'contato@gabrielapitaadvogados.com.br'),
('smtp_from_name', 'Gabriela Pita Advogados Associados'),
('smtp_to_email', 'contato@gabrielapitaadvogados.com.br'),
('seo_site_url', 'https://gabrielapitaadvogados.com.br/aprova/'),
('seo_home_title', 'Gabriela Pita Advogados Associados | Advocacia Trabalhista, Cível e Previdenciária'),
('seo_home_description', 'Assessoria jurídica estratégica para trabalhadores e empresas, com atuação em direito trabalhista, cível e previdenciário. Atendimento humanizado, técnico e transparente em Senhor do Bonfim - BA.'),
('seo_home_keywords', 'Gabriela Pita, advogada trabalhista, advocacia trabalhista, direito do trabalho, direito previdenciário, direito cível, advogado em Senhor do Bonfim, assessoria jurídica para empresas, rescisão trabalhista, assédio moral no trabalho'),
('seo_blog_title', 'Blog Jurídico | Gabriela Pita Advogados Associados'),
('seo_blog_description', 'Artigos jurídicos sobre direitos trabalhistas, previdenciários e cíveis, com orientações claras para trabalhadores e empresas.'),
('seo_post_title_suffix', 'Gabriela Pita Advogados Associados'),
('seo_author', 'Gabriela Pita Advogados Associados'),
('seo_robots', 'index, follow'),
('seo_og_title', 'Gabriela Pita Advogados Associados'),
('seo_og_description', 'Advocacia estratégica com atendimento próximo, análise técnica individualizada e soluções jurídicas sob medida para trabalhadores e empresas.'),
('seo_og_image', 'image/foto-hero.png'),
('seo_twitter_card', 'summary_large_image'),
('seo_locale', 'pt_BR'),
('seo_schema_type', 'LegalService'),
('seo_area_served', 'Senhor do Bonfim, Bahia, Brasil'),
('seo_business_description', 'Escritório de advocacia com atuação estratégica em direito trabalhista, cível e previdenciário, oferecendo atendimento humanizado e análise técnica individualizada.')
ON DUPLICATE KEY UPDATE setting_key = setting_key;

CREATE TABLE IF NOT EXISTS admin_users (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(80) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO admin_users (username, password_hash) VALUES
('admin', '$2y$12$A2HK0TqkuYSNMTBSrmDLhux2TwX765.FkDhKSw3L5xEFvI9eu6QSa')
ON DUPLICATE KEY UPDATE username = username;

CREATE TABLE IF NOT EXISTS blog_posts (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(180) NOT NULL,
  slug VARCHAR(190) NOT NULL UNIQUE,
  excerpt TEXT NULL,
  content MEDIUMTEXT NOT NULL,
  status ENUM('draft', 'published') NOT NULL DEFAULT 'draft',
  published_at DATETIME NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_status_published_at (status, published_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
