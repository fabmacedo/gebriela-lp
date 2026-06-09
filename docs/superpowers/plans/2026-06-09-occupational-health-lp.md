# Occupational Health Landing Page Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Replace the broad institutional site with a focused, humanized landing page about work accidents and occupational illness, then remove the Blog completely from the LP code and LP database.

**Architecture:** Preserve the current PHP/Tailwind single-page structure and visual identity while replacing the home content and anchors. Remove Blog-specific public/admin code and database dependencies, simplify the sitemap to the home page, and keep all destructive database changes scoped to `gabriela_lp`.

**Tech Stack:** PHP 8.3, MySQL 8.4, Tailwind CDN, vanilla JavaScript, Docker Compose

---

### Task 1: Create a focused home-page verification script

**Files:**
- Create: `scripts/check_lp_focus.php`

- [ ] **Step 1: Write the failing verification script**

Create a PHP CLI script that scans public and admin source files and fails when active code still contains Blog routes, Blog database references, the removed `#sobre` anchor, or the phrase “Quem sou eu?”. Exclude documentation and the verification script itself from the scan.

- [ ] **Step 2: Run the script and verify failure**

Run: `php scripts/check_lp_focus.php`

Expected: FAIL listing current Blog and “Quem sou eu?” references.

- [ ] **Step 3: Commit the failing verification**

```powershell
git add scripts/check_lp_focus.php
git commit -m "test: add LP focus verification"
```

### Task 2: Replace the home page with the approved narrative

**Files:**
- Modify: `index.php`
- Modify: `includes/front-header.php`
- Modify: `contact-submit.php`

- [ ] **Step 1: Replace header navigation**

Use the anchors `#situacoes`, `#direitos`, `#documentos`, and `#duvidas`, plus the WhatsApp CTA “Conversar sobre meu caso”, in desktop and mobile navigation.

- [ ] **Step 2: Replace the home sections**

Implement the approved order:

1. Hero: “Seu trabalho deixou marcas na sua saúde?”
2. Situations attended
3. Humanized explanation
4. Points that can be evaluated
5. Document checklist
6. How the service works
7. Google reviews and relevant differentials
8. Specialized FAQ
9. Contact

Keep all legal claims conditional and individual-case focused. Remove `get_published_posts()`, the “Quem sou eu?” block, broad service cards, promotional client count, and Blog section.

- [ ] **Step 3: Focus the lead form**

Use these allowed area options in both `index.php` and `contact-submit.php`:

```php
[
    'Acidente de trabalho',
    'Doença física relacionada ao trabalho',
    'Adoecimento emocional relacionado ao trabalho',
    'Benefício do INSS ou CAT',
    'Dispensa após acidente ou adoecimento',
    'Outra situação relacionada à saúde no trabalho',
]
```

- [ ] **Step 4: Run focused checks**

Run:

```powershell
php -l index.php
php -l includes/front-header.php
php -l contact-submit.php
php scripts/check_text_quality.php
```

Expected: all commands exit `0`.

- [ ] **Step 5: Commit focused home page**

```powershell
git add index.php includes/front-header.php contact-submit.php
git commit -m "feat: focus LP on occupational health"
```

### Task 3: Remove Blog code and public routes

**Files:**
- Delete: `blog.php`
- Delete: `post.php`
- Delete: `admin/posts.php`
- Delete: `admin/post-edit.php`
- Delete: `scripts/seed_blog_posts.php`
- Modify: `includes/site.php`
- Modify: `admin/_header.php`
- Modify: `admin/index.php`
- Modify: `admin/seo.php`
- Modify: `admin/_settings_helpers.php`
- Modify: `404.php`

- [ ] **Step 1: Delete Blog-only pages**

Delete the five Blog-only PHP files listed above.

- [ ] **Step 2: Remove Blog functions and settings**

Remove `seo_blog_title`, `seo_blog_description`, `seo_post_title_suffix`, `get_published_posts()`, and `get_published_posts_page()` from active application code.

- [ ] **Step 3: Simplify admin and 404**

Remove the Blog navigation, Blog dashboard card/count queries, Blog SEO fields, and 404 links to Blog or `#sobre`. Point 404 links to the focused LP sections.

- [ ] **Step 4: Run focused verification**

Run:

```powershell
php scripts/check_lp_focus.php
php scripts/check_text_quality.php
```

Expected: no active Blog or “Quem sou eu?” references.

- [ ] **Step 5: Commit Blog code removal**

```powershell
git add -A blog.php post.php admin scripts/seed_blog_posts.php includes/site.php 404.php
git commit -m "refactor: remove Blog from LP"
```

### Task 4: Remove Blog schema and update SEO/sitemap

**Files:**
- Modify: `database.sql`
- Modify: `includes/sitemap-generator.php`
- Modify: `sitemap.xml`

- [ ] **Step 1: Remove Blog seed/schema**

Remove Blog SEO settings and the `blog_posts` table definition from `database.sql`. Update default home SEO and sharing copy for accident-at-work and occupational-illness topics.

- [ ] **Step 2: Simplify dynamic sitemap**

Make `build_sitemap_xml()` output only the LP home URL.

- [ ] **Step 3: Update LP database settings**

Update the LP-only `site_settings` SEO values and delete obsolete Blog setting keys in `gabriela_lp`.

- [ ] **Step 4: Drop the LP Blog table**

Run:

```powershell
docker exec gabriela-lp-db mysql -uroot -proot_pass -e "DROP TABLE IF EXISTS gabriela_lp.blog_posts;"
```

Expected: `blog_posts` absent from `gabriela_lp` and still present in `gabriela_site`.

- [ ] **Step 5: Regenerate the static sitemap**

Run: `Invoke-WebRequest http://localhost:8082/sitemap.php -UseBasicParsing`

Save the generated one-URL XML to `sitemap.xml`.

- [ ] **Step 6: Commit schema and SEO cleanup**

```powershell
git add database.sql includes/sitemap-generator.php sitemap.xml
git commit -m "chore: remove Blog schema and focus SEO"
```

### Task 5: Verify behavior, visual quality, and isolation

**Files:**
- Modify as required by verification findings

- [ ] **Step 1: Run complete source checks**

Run:

```powershell
php scripts/check_lp_focus.php
php scripts/check_text_quality.php
Get-ChildItem -Recurse -Filter '*.php' | ForEach-Object { php -l $_.FullName }
git diff --check
```

Expected: all pass.

- [ ] **Step 2: Verify HTTP behavior**

Verify:

- `http://localhost:8082/` returns `200`;
- `http://localhost:8082/sitemap.xml` returns one home URL;
- `http://localhost:8082/blog.php` and `post.php` return `404`;
- admin dashboard loads without Blog queries;
- original `http://localhost:8081/` remains `200`.

- [ ] **Step 3: Verify database isolation**

Confirm `blog_posts` is absent in `gabriela_lp`, present in `gabriela_site`, and all original table counts remain unchanged.

- [ ] **Step 4: Inspect desktop and mobile visually**

Use the in-app browser to inspect the home at desktop and mobile widths. Verify hierarchy, readability, no overflow, working anchors, FAQ behavior, form options, and no Blog/“Quem sou eu?” links.

- [ ] **Step 5: Run form validation**

Submit an invalid form request and verify the expected validation response without sending external email. Confirm the allowed area list is accepted by server-side validation.

- [ ] **Step 6: Commit verification fixes**

Commit only if verification required code changes.

### Task 6: Publish the completed LP

**Files:**
- Git metadata only

- [ ] **Step 1: Review tracked state**

Run: `git status --short --branch`

Expected: clean worktree and local commits ahead of `origin/main`.

- [ ] **Step 2: Push main**

Run: `git push origin main`

- [ ] **Step 3: Verify remote hash**

Compare `git rev-parse HEAD` with `git ls-remote origin refs/heads/main`.

Expected: matching hashes.
