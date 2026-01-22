# E2E Tests for Soli Clean Theme

Playwright end-to-end tests for the Soli Clean Theme.

## Setup

Install dependencies:

```bash
npm install
```

Start the WordPress environment:

```bash
npm run env:start
```

## Running Tests

Run all tests:

```bash
npm run test:e2e
```

Run tests in watch mode:

```bash
npm run test:e2e:watch
```

Run tests in debug mode:

```bash
npm run test:e2e:debug
```

## Test Coverage

### Authentication Tests (`authentication.spec.js`)
- ✓ Non-logged-in users are redirected to wp-login.php
- ✓ Custom login header is displayed
- ✓ Logged-in users can access the front-end
- ✓ User info is displayed for logged-in users

### Admin Cleanup Tests (`admin-cleanup.spec.js`)
- ✓ Posts menu is hidden from admin
- ✓ Pages menu is hidden from admin
- ✓ Media menu is hidden from admin
- ✓ Comments menu is hidden from admin
- ✓ Direct access to Posts/Pages/Media/Comments is blocked (redirects)
- ✓ Admin bar items for removed entities are not visible

## Test Credentials

The tests use the default wp-env credentials:
- Username: `admin`
- Password: `password`

## Troubleshooting

If tests fail with connection errors:

```bash
# Reset the environment
npm run env:reset
```

If tests fail randomly:

```bash
# Clean everything and start fresh
wp-env clean all
npm run env:start
```
