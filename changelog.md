# Changelog

All notable changes to this project will be documented in this file.

## Next release

### Added

- 299ko is now available on Nginx
- Shortcodes in content
- Add blocks format & FA icon in TinyMCE
- Add backups in ConfigManager
- Add Child Theme
- Icons system in Antispam plugin

### Fixed

- Fixed Apache 2.2 & 2.4 .htaccess issue
- Links Categories in blog
- Admin links burger (go to home site)
- Support functions.php in theme

### Changed

- Improve Antispam
- Debranding TinyMCE & wrap toolbar on small screens
- Required plugins are now hidden in PluginsManager
- Update to Font Awesome 6.7.2
- Update to TinyMCE 6.6

## V2.0.0

### Added

- Comments blog can be nested
- Compatibility with PHP 8.3
- Users can be stay connected (cookies)
- Antispam fully Translated
- New Users Plugin
- Themes can modify plugins templates
- Blog plugin fully translated & MVC
- Contact plugin fully translated & MVC
- Responses as API
- FileManager MVC & translated
- TinyMCE : Add images from FileManager
- Galerie : MVC & translated
- PluginsManager : MVC & translated
- Page : MVC & translated
- SEO : MVC & translated
- Allow langs in theme
- Russian language

### Fixed

- Description in banner
- URL Generation by router when a port is specified
- Blog metadatas
- Remove Pagination when empty
- Contact : Bad message when mail function is not available
- Blog : empty categories
- TinyMCE : Upload img URL issue when CMS is in a sub directory
- Page : URL issue when CMS is in a sub directory

### Changed

- Page template has to be in .tpl format
- Users connect and managements
- Force use responses & controllers

## V1.3.2

### Added

- Add Categories System
- Add Categories for Blog plugin
- Add a hook `adminToolsTemplates` to display links tools in admin
- Can translate plugin name & description
- Add concatenation of strings in templates (by `~`)

### Changed

- Galerie in MVC
- End of `header.php` & `footer.php` in public theme

### Fixed

- Blog List HTML entities
- Blog TOC empty headings

## V1.3.1

### Added

- Table of Content in Blog

### Changed

- Use spl_autoload to load all common class

### Fixed

- Content out of box with big images in blog
- Install with PHP installed as a CGI
- Mobile view Sidebar

## V1.3.0

### Added

- New brands for SEO plugin
- Lang class
- Admin menu expand
- Public Sidebar
- Router class
- MVC model (for blog, contact & page)
- Groups buttons
- Begin to translate to English

### Changed

- Template Improvements

### Fixed

- Issue during install with logger when data folder dont exist
- Hide page title when selected in params

## V1.2.5

### Added

- OpenGraph for socials networks in blog article
- Socials Description in blog article
- Add button to refresh cache in configManager plugin
- Add func (show class) to display personnalized metas

### Fixed

- 1.2.3 & 1.2.4 blog config issue
- util::urlBuild relative path issue


## V1.2.4

### Changed

- Updates are now displayed in every admin pages
- ConfigManager plugin is using cache to check only once a day a new update

### Fixed

- Files _after and _beforeChangeFiles couldnt get called
- V1.2.3 Issue with blog config changes

## V1.2.3

### Changed

- Experimental : Use tabs instead long page for blog editing item

### Added

- Blog Author bloc
- Experimental : Form Help on Blog/Param

## V1.2.2

### Changed

- FileManager v1.1

### Fixed

- Firefox autocompletion for honeypot
- Progress while FileManager upload ajax view

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
