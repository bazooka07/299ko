# 299Ko

299ko est un CMS PHP à fichier plat, simple, léger et personnalisable, permettant la création de sites vitrines en quelques clics. Il prend en charge les plugins et les thèmes. 299ko est désormais multilingue et utilise son propre moteur de templates (syntaxe similaire à Twig) pour la création de rendus, afin de rendre la création de sites web accessible à tous.

**Pas besoin de base de données :** le CMS utilise des fichiers .json pour stocker ses données, ce qui rend la maintenance et les sauvegardes très faciles.

## Features

### Core Features
- **Lightweight**: Minimal footprint with maximum performance
- **Secure**: Built-in security features and best practices
- **Fast**: Optimized for speed with intelligent caching
- **Flexible**: Plugin-based architecture for easy customization
- **Modern**: Built with modern PHP practices and standards

### Advanced Caching System
- **Smart Caching**: Intelligent page caching with configurable duration
- **HTML Minification**: Automatic HTML compression preserving scripts and styles
- **Lazy Loading**: Automatic lazy loading for images and iframes
- **Cache Management**: Complete cache control with statistics and cleanup
- **UTF-8 Support**: Full UTF-8 character support with proper encoding

### Plugin System
- **Modular Architecture**: Easy to extend with plugins
- **Hook System**: Comprehensive hook system for customization
- **Asset Management**: Integrated CSS/JS loading through core hooks
- **Configuration**: Centralized plugin configuration management

### Theme System
- **Template Engine**: Powerful template system with variable support
- **Responsive Design**: Built-in responsive design support
- **Customization**: Easy theme customization and development
- **Multi-theme Support**: Support for multiple themes

### Admin Interface
- **User-Friendly**: Intuitive admin panel with modern design
- **Configuration Management**: Comprehensive settings management
- **Cache Control**: Built-in cache management interface
- **Help System**: Integrated help system with tooltips

## Requirements

- **PHP**: 7.4 or higher
- **Web Server**: Apache or Nginx
- **Storage**: File-based storage (JSON files) - no database required
- **Extensions**: 
  - mbstring (for UTF-8 support)
  - json
  - curl
  - gd (for image processing)

## Installation

Le guide d'installation est disponible ici : https://docs.299ko.ovh/english/setup/installation

### Quick Setup
1. **Download** the latest release
2. **Upload** files to your web server
3. **Set permissions**:
   ```bash
   chmod 755 data/
   chmod 755 upload/
   chmod 644 .htaccess
   ```
4. **Access** your site and follow the installation wizard
5. **Configure** your site settings

## Configuration

### Cache Settings
The CMS includes a comprehensive caching system that can be configured through the admin panel:

- **Enable/Disable Cache**: Toggle caching on/off
- **Cache Duration**: Set cache expiration time (default: 3600 seconds)
- **HTML Minification**: Enable/disable HTML compression
- **Lazy Loading**: Automatic lazy loading for images and iframes
- **Cache Cleanup**: One-click cache cleanup with statistics

### Performance Optimization
- **Caching**: Intelligent page caching reduces load times
- **Minification**: HTML compression reduces file sizes
- **Lazy Loading**: Improves initial page load performance
- **UTF-8 Encoding**: Proper character encoding prevents corruption

## Development

### Plugin Development
Plugins can be created by following the plugin structure:

```
plugin/
├── myplugin/
│   ├── plugin.php
│   ├── controllers/
│   ├── template/
│   └── langs/
```

### Theme Development
Themes can be customized by creating theme files:

```
theme/
├── mytheme/
│   ├── layout.tpl
│   ├── template/
│   └── assets/
```

### Hooks System
The CMS provides a comprehensive hook system for customization:

```php
// Add a hook
core::getInstance()->addHook('publicContent', 'myFunction');

// Call hooks
core::getInstance()->callHook('publicContent', $content);
```

## Security

- **CSRF Protection**: Built-in CSRF token protection
- **Input Validation**: File-based data validation and sanitization
- **XSS Protection**: Output escaping and sanitization
- **File Upload Security**: Secure file upload handling
- **Access Control**: Role-based access control for admin interface

## Performance

- **Caching**: Intelligent caching system reduces server load
- **Minification**: HTML compression reduces file sizes
- **Lazy Loading**: Improves initial page load performance
- **UTF-8 Support**: Proper character encoding for international content
- **External Assets**: CDN-hosted minified libraries (Font Awesome, Fancybox)

## Links

- **Site officiel** : https://299ko.ovh
- **Support (forum)** : https://community.299ko.ovh/
- **Documentation** : https://docs.299ko.ovh/
- **Facebook** : https://facebook.com/299kocms
- **Twitter** : https://twitter.com/299kocms
- **GitHub** : https://github.com/299Ko

## Support

- **Documentation**: Comprehensive documentation available
- **Community**: Active community support
- **Issues**: Report bugs and request features on GitHub
- **Updates**: Regular updates and security patches

## License

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <https://www.gnu.org/licenses/>.

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## Changelog

See [CHANGELOG.md](changelog.md) for a complete list of changes and updates.

## Credits

- **Author**: Maxence Cauderlier <mx.koder@gmail.com>
- **License**: GPL v3
- **Website**: https://github.com/299Ko/299ko
