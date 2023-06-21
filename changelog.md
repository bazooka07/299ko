# Changelog

All notable changes to this project will be documented in this file.

## Unreleased

## V1.2.1

### Fixed
- Admin buttons overlap
- TinyMCE : Add vertical scrollbar
- [TinyMCE : Change icons order](https://github.com/299Ko/299ko/issues/23)
- [Dark mode in public view with CSS vars](https://github.com/299Ko/299ko/issues/22)

## V1.2.0

### Added
- ConfigManager can now update site with a simple click
- Global 'IS_ADMIN' available on each page
- Hooks beforeRunPlugin & adminBeforeRunPlugin, called before the displayed page
- Add a description in site to display in banner
- Auto upload and manual upload image in TinyMCE
- CSS vars

### Changed
- Admin menu is generated out of header.php
- Admin menu in CSS, always visible on wide screen
- Add currentPlugin class (CSS)  on current Plugin in Admin menu
- Redirect to item after save (blog, page, galerie)
- TinyMCE v6
- Old Normalize changed by modern-normalize and moved from core to CSS

### Fixed
- SEO : don't show script when Analytics ID is empty
- Galerie : Introduction dont save content in Markdown format
- Galerie : TinyMCE introduction
- Labels for forms

## V1.1.1

### Fixed
- Antispam : Issue on radio click (JS)
- TinyCME : Use FA icon in editor without edit sources

## V1.1.0

### Added
- Add FileManager plugin

### Changed
- Change .htaccess for Apache 2.4
- Galerie : Modify toggle view of hidden pictures and category display
- Admin : Change favicon

### Fixed
- Galerie : Issue on choose category
- Galerie : Issue on hidden pictures
- SEO : Fix width on float icons

## V1.0.2

### Added
- Delete automatically install.php file on admin if exist

### Changed
- Possibility to use icon on menu item (page)
- Use Vanilla JS instead jQuery

### Fixed
- Issue with Pages menu on Admin

## V1.0.1

### Fixed

- Rights on install

## V1.0

### Added
- 2 hooks : beforeSaveEditor and beforeEditEditor, to modify content before save and edit it
- Hook endMainNavigation to add items on main menu
- Gallery support now png, gif, jpg and jpeg images
- FancyBox is now on all the site, not only in Gallery
- Font Awesome is used to display icons
- Templates system (.tpl)

### Changed
- show::msg is now callable everywhere to add a or many messages in next view
- SEO plugin : Icons instead text in links
- SEO plugin : SEO menu place can be changed in admin
- Less permissions on files

### Fixed
- [Install](https://github.com/99kocms/99ko-v4-v5/issues/14)
- [Other install](https://github.com/99kocms/99ko-v4-v5/issues/15)
- [Anchors in content](https://github.com/99kocms/99ko-v4-v5/issues/11)
