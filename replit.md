# Marko Digital Marketing Agency Website

## Project Overview
A static website for "Marko" digital marketing agency built with HTML, CSS, JavaScript (jQuery), Bootstrap, and PHP for form processing.

## Recent Changes (September 20, 2025)
- **Implemented multilingual system** - Complete Vietnamese language support with intelligent language switching
- **Created Vietnamese website structure** - `/vi/` folder with translated components and pages  
- **Built dynamic language switching** - JavaScript-powered language detection and component loading
- **Added Vietnamese navigation** - Fully translated header, footer, and navigation menus
- **Implemented event-driven initialization** - Robust script loading with jQuery dependency management
- **Enhanced user experience** - Flag-based language switcher with automatic redirection

## Previous Changes
- **Implemented Notion CMS integration** - Complete dynamic blog system using Notion as headless CMS
- **Created dynamic blog pages** - blog.php and single_post.php with JavaScript-powered content rendering
- **Added comprehensive security** - HTML sanitization, XSS prevention, and safe content handling
- **Enhanced SEO features** - Dynamic title/meta updates and proper content structure
- **Built failsafe system** - Mock content fallback when Notion API is not configured
- **Updated navigation** - All blog links now point to dynamic PHP pages instead of static HTML

## Previous Changes (September 13, 2025)
- **Imported from GitHub** and configured for Replit environment
- **Fixed PHP form processing** - corrected undefined variables in form_process.php 
- **Enhanced JavaScript form submission** - updated submit-form.js to use AJAX for proper form data submission to PHP backend
- **Set up web server** with PHP built-in server and custom routing
- **Configured workflow** to serve website on port 5000 with 0.0.0.0 binding
- **Added cache control headers** to prevent caching issues in Replit iframe environment

## Project Architecture

### Frontend
- **HTML files**: Static pages including index, contact, about, services, blog, etc.
- **CSS**: Bootstrap-based styling with custom styles in `/css/style.css`
- **JavaScript**: jQuery-based interactions, animations, form validation
- **Assets**: Images, fonts (FontAwesome), vendor libraries

### Backend
- **PHP server**: Custom routing script (`server.php`) using PHP built-in server
- **Notion CMS**: `/php/notion_cms.php` provides API integration for dynamic blog content
- **Form processing**: `/php/form_process.php` handles contact form submissions
- **Newsletter processing**: `/php/newsletter_process.php` (basic structure exists)

### Key Components
1. **Multilingual System** - English/Vietnamese support with intelligent language switching
2. **Dynamic Blog System** - Notion CMS integration with real-time content management
3. **Responsive navigation** with mobile menu support and language switcher
4. **Contact form** with client-side validation and AJAX submission to PHP backend
5. **Newsletter signup** form
6. **Image carousels** using Swiper library
7. **Animations** with Animate.css
8. **Theme switching** (dark/light mode)
9. **Security features** - HTML sanitization and XSS prevention

## Technical Setup
- **Runtime**: PHP 8.2.23
- **Server**: PHP built-in server with custom routing
- **Port**: 5000 (configured for Replit proxy)
- **Deployment**: Ready for production deployment

## User Preferences
- Maintains existing design and branding
- Uses original color scheme and layout structure
- Preserves all existing functionality and features

## Current State
- ✅ Website fully functional and responsive
- ✅ **Multilingual system operational** with English/Vietnamese support
- ✅ **Language switching working** with automatic redirection and component loading
- ✅ **Vietnamese homepage complete** with translated content
- ✅ **Dynamic blog system operational** with Notion CMS integration
- ✅ **Mock content system** provides fallback when Notion is not configured
- ✅ Contact forms working with AJAX + PHP backend
- ✅ All assets loading correctly  
- ✅ Server running stable on port 5000
- ✅ Comprehensive setup documentation provided (`notion_cms_setup.md`)
- ✅ Ready for deployment configuration

## Multilingual Features
- **Language Detection**: Automatic language detection based on URL path (`/vi/` for Vietnamese)
- **Dynamic Components**: Language-specific header, footer, and navigation components
- **Smart Switching**: Language switcher with flag icons and intelligent redirection
- **Event-Driven Loading**: Robust initialization system with jQuery dependency management
- **Fallback Navigation**: Vietnamese navigation temporarily links to English pages until translations complete

## Content Management
- **Blog Management**: Use Notion database to create/edit blog posts without coding
- **Setup Guide**: Complete instructions in `notion_cms_setup.md`
- **Fallback Mode**: Website works with sample content even without Notion setup
- **Security**: Built-in XSS protection and content sanitization