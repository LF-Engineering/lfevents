=== Find My Blocks ===
Contributors: edeesims
Donate link: https://www.paypal.com/paypalme2/eddysims
Tags: gutenberg, developer, blocks, custom blocks
Requires at least: 5.0
Tested up to: 5.5
Stable tag: trunk
Requires PHP: 5.2.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Find My Blocks is a tool to list all the blocks that are used on you WordPress site and what posts/pages they are used on.

== Description == 

Find My Blocks is a WordPress plugin built to help (as it says in the name) find where you have used specific Gutenberg blocks on your WordPress website.

Gutenberg is a great tool to make managing content on your WordPress website easier. However after a while, updating and maintaining blocks can become a hassle for developers. It can become hard to keep track of what posts/pages a block is used on, or how many times it has used. This makes maintaining Gutenberg websites quite difficult.

Find my blocks is a tool that is here to help. With Find My Blocks you can see:

- A list of all the block types that are being used on your WordPress website.
- The amount of times a specific block type has been used on your WordPress website.
- The posts/pages that a block has been used on.
- The amount of times a block has been used on a specific post/page.
- If the block is a “Reusable Block”
- A filtered list of block names to help you find the block that you are looking for.
- What are your most used, and least used blocks on your website.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/find-my-blocks` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Settings->Find My Blocks screen to locate all of your blocks

== Frequently Asked Questions ==

= Will this work with custom blocks =

Yes, this plugin will work with all blocks registered and used on your site.

== Changelog ==

2.3.0

- Checks for nested blocks.
- Adds a tag to a card if the block is nested

2.2.0

- Hides the "core-embed" blocks with the "Hide Core Blocks" option.
- Fixes the broken link when plugin is first activated.

2.1.0

- Moves the menu item to **Tools > Find My Blocks**.
- Hides core blocks by default. The option to show and hide core blocks is now in settings.
- Settings moved from top of blocks to side on wider screens.

2.0.2

- **May 4, 2020**
- Fixed the navigation showing 'Found in 1 postfalse' when only one post found

2.0.1

- **May 3rd, 2020**
- Fixed a bug where the page refresh didn't properly set the active block

2.0.0

- **May 3rd, 2020**
- Moves Find My Blocks out of the Settings menu and into its own sidebar item
- Layout moved off of WordPress components to allow better testing
- On initial load, the proper first navigation will be selected as active
- When the page is refreshed, the selected block will stay active
- Allows sorting of navigation and cards
- Launches official website https://find-my-blocks.edeesims.com/

1.4.0

- **March 29, 2020**
- Allows the editor role to use the plugin

1.3.0

- **February 3, 2020**
- Sorts the list of the navigation alphabetically.
- Sorts the list of pages alphabetically.
- Some updates to styling to bring more inline with Gutenberg and WordPress core.

1.2.0

- **January 21, 2020**
- Adds a Filter Input to allow you to filter blocks in the menu.
- Updates the style of the header to be just a little bit nicer.
- Adds a loading screen so that you are not bored waiting for the page to load.

1.1.0

- **December 29, 2020**
- Adds a message to notify the amount of times a block has been used in a post.
- Removes some old, unused functionality.

1.0.0

- **December 3, 2019**
- Initial Release

