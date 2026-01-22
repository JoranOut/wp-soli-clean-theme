# wp-soli-clean-theme

WordPress theme for `admin.soli.nl` - a minimal, clean administration theme for Muziekvereniging Soli's admin portal.

## Purpose

This theme provides a stripped-down WordPress environment focused on custom post types and administration:

1. **Clean Admin Interface** - Removes default WordPress entity pages (posts, pages, media, comments)
2. **Authentication Gate** - Forces front-end login for all visitors
3. **Hookable Front-End** - Provides a minimal, extensible front-end template

## Theme Responsibilities

The theme handles presentation and access control:

- **Front-End Authentication**: Redirects all non-authenticated users to `wp-login.php`
- **Admin Cleanup**: Removes posts, pages, media, and comments from wp-admin
- **Extensible Template**: Provides hook points for plugins to add front-end content

All custom post types, business logic, and data management are handled by companion plugins.

## Architecture

```
admin.soli.nl
├── wp-soli-clean-theme (this repo)  - Presentation layer & access control
└── companion plugins                - Custom post types, OIDC, business logic
```

## Development Guidelines

### WordPress Version

Target the latest stable WordPress version. Use modern WordPress APIs and patterns.

### Coding Standards

- Follow [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/)
- Use WordPress escaping functions (`esc_html()`, `esc_attr()`, `esc_url()`, etc.)
- Sanitize all input, escape all output
- Prefix all functions, hooks, and global variables with `soli_admin_`

### Theme Structure

```
wp-soli-clean-theme/
├── functions.php      # Theme setup, admin cleanup, authentication
├── style.css          # Theme metadata and styles
├── index.php          # Minimal front-end template with hooks
├── languages/         # Translation files
└── CLAUDE.md          # Development documentation
```

### Available Hooks

**Front-End Template Hooks:**

- `soli_admin_before_content` - Before the main content area
- `soli_admin_content` - Main content area (default: basic user info)
- `soli_admin_after_content` - After the main content area

Plugins can hook into these to add their own content to the front-end.

### Security

- Never trust user input
- Use nonces for form submissions
- Validate user capabilities before displaying sensitive data
- All front-end pages require authentication (enforced in `functions.php`)

### Template Hierarchy

Since this is a minimal single-purpose theme:
- `index.php` is the only front-end template
- Authentication redirect happens in `functions.php` via `template_redirect` hook
- Admin menu cleanup happens via `admin_menu` and `admin_init` hooks

### Admin Cleanup

The theme removes the following from wp-admin:
- Posts (edit.php, post.php, post-new.php)
- Pages (edit.php?post_type=page)
- Media (upload.php, media-new.php)
- Comments (edit-comments.php, comment.php)
- Related admin bar items

Custom post types registered by plugins remain accessible.

## Git Workflow

- `main` branch is the primary branch
- Create feature branches for new work
- Keep commits focused and descriptive