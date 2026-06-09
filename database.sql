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
('google_reviews_url', 'https://share.google/f0CHbeOnC5QMY2l4R'),
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
('seo_site_url', 'https://gabrielapitaadvogados.com.br/'),
('seo_home_title', 'Acidente de Trabalho e Doença Ocupacional | Gabriela Pita'),
('seo_home_description', 'Orientação jurídica humanizada para trabalhadores que sofreram acidente de trabalho ou enfrentam doença física ou emocional relacionada ao trabalho.'),
('seo_home_keywords', 'acidente de trabalho, doença ocupacional, burnout relacionado ao trabalho, LER DORT, CAT, estabilidade acidentária, auxílio-acidente, advogado trabalhista'),
('seo_author', 'Gabriela Pita Advogados Associados'),
('seo_robots', 'index, follow'),
('seo_og_title', 'Acidente de Trabalho e Doença Ocupacional | Gabriela Pita'),
('seo_og_description', 'Informação clara e atendimento humanizado para compreender a relação entre trabalho, saúde e possíveis direitos.'),
('seo_og_image', 'image/foto-hero.png'),
('seo_twitter_card', 'summary_large_image'),
('seo_locale', 'pt_BR'),
('seo_schema_type', 'LegalService'),
('seo_area_served', 'Senhor do Bonfim, Bahia, Brasil'),
('seo_business_description', 'Escritório de advocacia com atuação em acidente de trabalho e doença ocupacional, oferecendo atendimento humanizado e análise individual.')
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
