# MRRP Testimonial Widget

![Version](https://img.shields.io/badge/version-1.0.0-blue.svg)
![WordPress](https://img.shields.io/badge/WordPress-5.0%2B-blue.svg)
![Elementor](https://img.shields.io/badge/Elementor-3.0%2B-pink.svg)
![License](https://img.shields.io/badge/license-GPL%20v2%2B-green.svg)

A professional, portable Elementor widget plugin for displaying testimonials with a stunning horizontal scrolling slider, full-width background images, and interactive avatar navigation.

## ✨ Features

- 🎨 **Full-Width Slider Design** - Immersive full-screen background images
- 💬 **Semi-Transparent Overlays** - Elegant dark overlay boxes for quote text
- 👤 **Avatar Navigation** - Click avatars to jump between testimonials
- 🎯 **Active State Highlighting** - Green border on active avatar
- 📱 **Fully Responsive** - Perfect on desktop, tablet, and mobile
- ⚡ **CDN-Based Assets** - Tailwind CSS and Swiper.js loaded via CDN
- 🔄 **Auto-play Support** - Configurable auto-play with pause on hover
- 🎭 **Smooth Animations** - Fade and slide transitions
- 🎛️ **Elementor Integration** - Full visual editing in Elementor
- 🔌 **Portable** - Works on any WordPress site with Elementor

## 📋 Requirements

- WordPress 5.0 or higher
- Elementor 3.0 or higher
- PHP 7.0 or higher

## 🚀 Installation

### Method 1: WordPress Admin

1. Download the plugin ZIP file
2. Go to WordPress Admin → Plugins → Add New
3. Click "Upload Plugin" and select the ZIP file
4. Click "Install Now" and then "Activate"

### Method 2: Manual Installation

1. Download the plugin ZIP file
2. Extract the ZIP file
3. Upload the `mrrp-testimonial-widget` folder to `/wp-content/plugins/`
4. Activate the plugin through the 'Plugins' menu in WordPress

### Method 3: From GitHub

```bash
cd /path/to/wordpress/wp-content/plugins/
git clone https://github.com/yourusername/mrrp-testimonial-widget.git
```

Then activate the plugin in WordPress Admin.

## 📖 Usage

1. Edit a page with Elementor
2. Search for "MRRP Testimonial" in the widget panel
3. Drag the widget to your page
4. Add testimonials using the repeater control:
   - Quote text
   - Author name
   - Author title/role
   - Background image (full-size)
   - Avatar image (thumbnail)
5. Customize slider settings:
   - Autoplay on/off
   - Autoplay delay
   - Transition speed
   - Loop on/off
   - Pause on hover
6. Style the widget:
   - Overlay background color
   - Overlay width and padding
   - Quote text color and typography
   - Avatar size and active color
7. Publish and enjoy!

## 🎨 Widget Controls

### Content Tab

**Testimonials Repeater:**
- Quote Text (textarea)
- Author Name (text)
- Author Title (text)
- Background Image (media upload)
- Avatar Image (media upload)

**Slider Settings:**
- Autoplay (yes/no)
- Autoplay Delay (1000-10000ms)
- Transition Speed (300-2000ms)
- Loop (yes/no)
- Pause on Hover (yes/no)

### Style Tab

**Overlay Box:**
- Background Color
- Width (responsive)
- Border Radius
- Padding (responsive)

**Quote Text:**
- Text Color
- Typography (font family, size, weight, etc.)

**Avatar Navigation:**
- Avatar Size (responsive)
- Active Border Color
- Active Border Width
- Inactive Opacity

## 🎯 Design Pattern

The widget implements a horizontal scrolling testimonial slider with:

- **Full-width slides** occupying 100% viewport
- **Background images** for each testimonial
- **Semi-transparent overlay box** positioned on the left
- **Avatar thumbnail navigation** at the bottom
- **Active state highlighting** with green border
- **Smooth transitions** between slides

## 🛠️ Technical Details

### CDN Libraries Used

- **Tailwind CSS v3.4** - Utility-first CSS framework
- **Swiper.js v11** - Modern touch slider

### File Structure

```
mrrp-testimonial-widget/
├── mrrp-testimonial-widget.php          # Main plugin file
├── README.md                             # This file
├── LICENSE                               # GPL v2 license
├── .gitignore                           # Git ignore rules
├── includes/
│   └── class-mrrp-testimonial-widget.php    # Widget class
├── assets/
│   ├── css/
│   │   └── testimonial-widget.css       # Custom styles
│   └── js/
│       └── testimonial-widget.js        # Swiper initialization
```

### Conditional Asset Loading

Assets are loaded only when the widget is used on a page, ensuring optimal performance.

## 📱 Responsive Breakpoints

- **Desktop (≥1200px)**: Overlay 35% width, 48px avatars
- **Tablet (768-1199px)**: Overlay 45% width, 40px avatars
- **Mobile (<768px)**: Overlay 90% width, 36px avatars

## 🎨 Customization

### Custom CSS

You can add custom CSS in Elementor's Custom CSS or your theme's stylesheet:

```css
/* Change overlay background */
.mrrp-overlay-box {
    background: rgba(0, 0, 0, 0.85) !important;
}

/* Change active avatar color */
.mrrp-avatar-item.active .mrrp-avatar-image {
    border-color: #FF5722 !important;
}
```

### Filters & Hooks

Coming soon in future versions.

## 🐛 Troubleshooting

**Widget not appearing in Elementor?**
- Make sure Elementor is installed and activated
- Check that you're using Elementor 3.0 or higher
- Try deactivating and reactivating the plugin

**Slider not working?**
- Check browser console for JavaScript errors
- Ensure Swiper.js CDN is loading (check Network tab)
- Clear browser cache and try again

**Images not displaying?**
- Check that images are uploaded correctly
- Verify image URLs are accessible
- Try re-uploading images

## 📝 Changelog

### Version 1.0.0 (2026-02-02)
- Initial release
- Horizontal scrolling testimonial slider
- Full-width background images
- Avatar navigation with active states
- Responsive design
- Elementor integration
- CDN-based asset loading

## 📄 License

This plugin is licensed under the GPL v2 or later.

```
Copyright (C) 2026 MRRP

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
```

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 💬 Support

For support, please open an issue on GitHub or contact the author.

## 👨‍💻 Author

**MRRP**
- GitHub: [@yourusername](https://github.com/yourusername)

## 🙏 Acknowledgments

- Built with [Elementor](https://elementor.com/)
- Slider powered by [Swiper.js](https://swiperjs.com/)
- Styling with [Tailwind CSS](https://tailwindcss.com/)

---

Made with ❤️ for the WordPress community
