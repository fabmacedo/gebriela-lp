# Duplicate LP Database Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Give the LP repository its own MySQL database and Docker services, copy the current local site data into it, and publish the isolated project to the new GitHub repository.

**Architecture:** Keep the existing PHP/MySQL application structure. Isolate the LP by giving Docker Compose, containers, ports, database, user, and volume LP-specific names; clone the original live database directly between containers so no sensitive dump is stored in Git.

**Tech Stack:** PHP 8.4, Apache, MySQL 8.4, Docker Compose, Git

---

### Task 1: Isolate the LP Docker stack

**Files:**
- Modify: `docker-compose.yml`
- Modify: `config/database.php`

- [ ] **Step 1: Change Docker identifiers and ports**

Set the Compose project to `gabriela-lp`, containers to `gabriela-lp-db` and `gabriela-lp-web`, host ports to `3308` and `8082`, and database/user names to LP-specific values.

- [ ] **Step 2: Change PHP local defaults**

Set the local defaults to port `3308`, database `gabriela_lp`, and user `gabriela_lp_user`.

- [ ] **Step 3: Validate Compose configuration**

Run: `docker compose config --quiet`

Expected: exit code `0`.

### Task 2: Duplicate the live database

**Files:**
- No repository files created

- [ ] **Step 1: Start the isolated LP database**

Run: `docker compose up -d db`

Expected: `gabriela-lp-db` becomes healthy while `gabriela-site-db` remains healthy.

- [ ] **Step 2: Stream the original database into the LP database**

Run a `mysqldump` from `gabriela-site-db` and pipe it directly to the MySQL client in `gabriela-lp-db`.

- [ ] **Step 3: Compare source and destination row counts**

Query `site_settings`, `admin_users`, and `blog_posts` in both databases.

Expected: matching counts in both databases.

- [ ] **Step 4: Prove write isolation**

Create and remove a temporary verification table in `gabriela_lp`, then confirm it never appears in `gabriela_site`.

Expected: the verification table exists only in the LP database during the check.

### Task 3: Verify the application

**Files:**
- No additional files

- [ ] **Step 1: Start the complete LP stack**

Run: `docker compose up -d`

Expected: `gabriela-lp-db` and `gabriela-lp-web` run without replacing the original containers.

- [ ] **Step 2: Verify the LP HTTP endpoint**

Request `http://localhost:8082/`.

Expected: HTTP `200`.

### Task 4: Publish the new repository

**Files:**
- Git metadata only

- [ ] **Step 1: Replace the origin remote**

Set `origin` to `https://github.com/fabmacedo/gebriela-lp.git`.

- [ ] **Step 2: Review tracked changes**

Run: `git status --short` and inspect the diff for secrets or unintended files.

Expected: only LP isolation files and this plan are changed.

- [ ] **Step 3: Commit and push**

Commit the isolation changes and push `main` to the new empty repository.

- [ ] **Step 4: Verify the remote**

Run: `git ls-remote origin refs/heads/main`.

Expected: remote `main` matches local `HEAD`.
