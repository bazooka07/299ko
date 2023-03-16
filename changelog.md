# Changelog

All notable changes to this project will be documented in this file.

## Unreleased

### Changed
- Change .htaccess for Apache 2.4
- Galerie : Modify toggle view of hidden pictures and category display

### Fixed
- Galerie : Issue on choose category
- Galerie : Issue on hidden pictures

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
