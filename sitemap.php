<?php
require_once __DIR__ . '/includes/sitemap-generator.php';

header('Content-Type: application/xml; charset=UTF-8');
echo build_sitemap_xml();
