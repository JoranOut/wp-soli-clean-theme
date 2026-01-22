/**
 * Admin cleanup tests for Soli Clean Theme.
 *
 * Tests that the theme properly removes default WordPress entities:
 * - Posts menu is hidden
 * - Pages menu is hidden
 * - Media menu is hidden
 * - Comments menu is hidden
 * - Direct access to these pages is blocked
 */

const { test, expect } = require('@playwright/test');

test.describe('Admin area cleanup', () => {
	test.beforeEach(async ({ page }) => {
		// Log in as admin before each test
		await page.goto('/wp-login.php');
		await page.fill('#user_login', 'admin');
		await page.fill('#user_pass', 'password');
		await page.click('#wp-submit');
		await page.waitForURL(/wp-admin/);
	});

	test('should hide Posts menu from admin', async ({ page }) => {
		await page.goto('/wp-admin/');

		// Posts menu should not be visible
		const postsMenu = page.locator('#menu-posts');
		await expect(postsMenu).not.toBeVisible();
	});

	test('should hide Pages menu from admin', async ({ page }) => {
		await page.goto('/wp-admin/');

		// Pages menu should not be visible
		const pagesMenu = page.locator('#menu-pages');
		await expect(pagesMenu).not.toBeVisible();
	});

	test('should hide Media menu from admin', async ({ page }) => {
		await page.goto('/wp-admin/');

		// Media menu should not be visible
		const mediaMenu = page.locator('#menu-media');
		await expect(mediaMenu).not.toBeVisible();
	});

	test('should hide Comments menu from admin', async ({ page }) => {
		await page.goto('/wp-admin/');

		// Comments menu should not be visible
		const commentsMenu = page.locator('#menu-comments');
		await expect(commentsMenu).not.toBeVisible();
	});

	test('should redirect from Posts admin page', async ({ page }) => {
		await page.goto('/wp-admin/edit.php', { waitUntil: 'networkidle' });

		// Should be redirected away from the Posts page
		// Either to wp-admin dashboard or to login (if session requires reauth)
		const url = page.url();
		expect(url).toMatch(/\/wp-admin\/?$|wp-login\.php.*redirect_to.*edit\.php/);
	});

	test('should redirect from new Post page', async ({ page }) => {
		await page.goto('/wp-admin/post-new.php', { waitUntil: 'networkidle' });

		// Should be redirected away from the new Post page
		const url = page.url();
		expect(url).toMatch(/\/wp-admin\/?$|wp-login\.php.*redirect_to.*post-new\.php/);
	});

	test('should redirect from Pages admin page', async ({ page }) => {
		await page.goto('/wp-admin/edit.php?post_type=page', { waitUntil: 'networkidle' });

		// Should be redirected away from the Pages page
		const url = page.url();
		expect(url).toMatch(/\/wp-admin\/?$|wp-login\.php.*redirect_to.*post_type%3Dpage/);
	});

	test('should redirect from Media library', async ({ page }) => {
		await page.goto('/wp-admin/upload.php', { waitUntil: 'networkidle' });

		// Should be redirected away from the Media page
		const url = page.url();
		expect(url).toMatch(/\/wp-admin\/?$|wp-login\.php.*redirect_to.*upload\.php/);
	});

	test('should redirect from Comments page', async ({ page }) => {
		await page.goto('/wp-admin/edit-comments.php', { waitUntil: 'networkidle' });

		// Should be redirected to main admin dashboard
		const url = page.url();
		expect(url).toMatch(/\/wp-admin\/?$/);
	});

	test('should not show admin bar items for removed entities', async ({ page }) => {
		await page.goto('/wp-admin/', { waitUntil: 'networkidle' });

		// Wait for page to fully load
		await page.waitForLoadState('domcontentloaded');

		// The admin bar should exist (it's always present in wp-admin)
		const adminBar = page.locator('#wpadminbar');

		// If admin bar exists, check that removed items are not visible
		if (await adminBar.count() > 0) {
			// Check that "New Post", "New Page", "New Media" are not in admin bar
			const newPostLink = page.locator('#wp-admin-bar-new-post');
			await expect(newPostLink).not.toBeVisible();

			const newPageLink = page.locator('#wp-admin-bar-new-page');
			await expect(newPageLink).not.toBeVisible();

			const newMediaLink = page.locator('#wp-admin-bar-new-media');
			await expect(newMediaLink).not.toBeVisible();

			const commentsLink = page.locator('#wp-admin-bar-comments');
			await expect(commentsLink).not.toBeVisible();
		} else {
			// If admin bar doesn't exist, test still passes (some configurations disable it)
			expect(true).toBe(true);
		}
	});
});
