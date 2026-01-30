# Soli Clean Theme

~Plugin Name: wp-soli-clean-theme~
~Current Version:1.0.1~

A minimal WordPress theme for `admin.soli.nl` that strips away default WordPress entities and provides a clean, extensible administration interface.

## Features

### 🔒 Front-End Authentication
All front-end pages require user authentication. Non-logged-in visitors are automatically redirected to the login page.

### 🧹 Clean Admin Interface
Removes default WordPress content types from wp-admin:
- Posts
- Pages
- Media
- Comments

Custom post types registered by plugins remain fully accessible.

### 🔌 Extensible Front-End
The front-end template provides action hooks for plugins to inject content:
- `soli_admin_before_content`
- `soli_admin_content`
- `soli_admin_after_content`

## Installation

1. Upload the theme to `/wp-content/themes/wp-soli-clean-theme/`
2. Activate the theme in WordPress admin
3. All front-end visitors will now be required to log in

## Requirements

- WordPress 6.0+
- PHP 8.0+

## Usage

### For Plugin Developers

Hook into the front-end template to add your own content:

```php
add_action( 'soli_admin_content', 'my_plugin_dashboard' );

function my_plugin_dashboard() {
    echo '<h2>My Custom Dashboard</h2>';
    // Your content here
}
```

Remove the default content by unhooking:

```php
remove_action( 'soli_admin_content', 'soli_admin_default_content' );
```

## Development

See [CLAUDE.md](CLAUDE.md) for detailed development guidelines and architecture documentation.

## License

GPL v3 or later

## About Muziekvereniging Soli

Soli is a music association based in Driehuis, Netherlands, founded in 1909. Visit [soli.nl](https://soli.nl) to learn more.
