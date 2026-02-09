/**
 * Authentication tests for Soli Clean Theme.
 *
 * Tests that the theme properly handles authentication:
 * - Non-logged-in users are redirected to login
 * - Logged-in users can access the front-end
 */

const { test, expect } = require('@playwright/test');

test.describe('Front-end authentication', () => {
	test('should redirect non-logged-in users to wp-login.php', async ({ page }) => {
		// Attempt to visit the homepage
		await page.goto('/');

		// Should be redirected to the login page
		await expect(page).toHaveURL(/wp-login\.php/);

		// Verify login form is present
		await expect(page.locator('#loginform')).toBeVisible();
		await expect(page.locator('#user_login')).toBeVisible();
		await expect(page.locator('#user_pass')).toBeVisible();
	});

	test('should show custom login header', async ({ page }) => {
		await page.goto('/wp-login.php');

		// Check for custom login title (supports both English and Dutch)
		await expect(page.locator('h1.soli-login-title')).toHaveText(/Soli Administrat(ion|ie)/);
		await expect(page.locator('p.soli-login-subtitle')).toHaveText(/Administration and Authentication|Administratie en Authenticatie/);
	});

	test('should allow logged-in users to access front-end', async ({ page, context }) => {
		// Log in as admin with redirect_to parameter
		await page.goto('/wp-login.php?redirect_to=' + encodeURIComponent('/'));
		await page.fill('#user_login', 'admin');
		await page.fill('#user_pass', 'password');

		// Click submit and wait for navigation
		await Promise.all([
			page.waitForNavigation({ waitUntil: 'networkidle' }),
			page.click('#wp-submit')
		]);

		// Should be on the homepage (not on login page)
		const url = page.url();
		expect(url).not.toContain('wp-login.php');
		expect(url).toMatch(/localhost:8889\/?$/);

		// Verify we can see the dashboard
		await expect(page.locator('main.soli-admin-dashboard')).toBeVisible();
	});

	test('should display user info for logged-in users', async ({ page }) => {
		// Log in as admin with redirect to homepage
		await page.goto('/wp-login.php?redirect_to=' + encodeURIComponent('/'));
		await page.fill('#user_login', 'admin');
		await page.fill('#user_pass', 'password');

		// Click submit and wait for navigation
		await Promise.all([
			page.waitForNavigation({ waitUntil: 'networkidle' }),
			page.click('#wp-submit')
		]);

		// Wait for dashboard to load
		await page.waitForSelector('main.soli-admin-dashboard');

		// Check default content is displayed (supports both English and Dutch)
		await expect(page.locator('h1')).toHaveText(/Soli Administrat(ion|ie)/);

		// Verify user fields are present
		await expect(page.locator('.soli-user-field')).toHaveCount(2); // Username and Email

		// Verify action buttons (supports both English and Dutch)
		await expect(page.locator('a.soli-btn-primary')).toHaveText(/Reset password|Wachtwoord herstellen/);
		await expect(page.locator('a.soli-btn-secondary')).toHaveText(/Log out|Uitloggen/);
	});
});
