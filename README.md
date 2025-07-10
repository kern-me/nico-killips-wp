# nicokillips-wp
Custom WordPress Theme for nicokillips dot com. 
# Nico Killips WP Theme

A custom WordPress theme with Sass support.

## Development Setup

### Prerequisites

- Node.js and npm installed on your system
- WordPress installation

### Installation

1. Clone this repository to your WordPress themes directory:
   ```
   cd wp-content/themes/
   ```

2. Install dependencies:
   ```
   cd nico-killips-wp
   npm install
   ```

### Development Workflow

#### Working with Sass

The theme uses Sass for styling. The Sass files are located in the `src/sass` directory and are compiled to CSS in the `assets/css` directory.

- Start the Sass compiler in watch mode:
  ```
  npm start
  ```

- Build the Sass files for production:
  ```
  npm run build
  ```

- Lint Sass files:
  ```
  npm run lint
  ```

### Sass Structure

The Sass files are organized in the following structure:

- `src/sass/style.scss` - Main Sass file that imports all other files
- `src/sass/abstracts/` - Variables, mixins, and functions
- `src/sass/base/` - Base styles and typography
- `src/sass/layout/` - Header, footer, sidebar, and content
- `src/sass/components/` - Buttons, forms, and other components
- `src/sass/pages/` - Page-specific styles
- `src/sass/wordpress/` - WordPress-specific styles

### Theme Structure

- `assets/` - Compiled CSS, JavaScript, and other assets
- `src/` - Source files
- `template-parts/` - Template parts for use with get_template_part()
- `functions.php` - Theme functions
- `index.php` - Main template file
- `header.php` - Header template
- `footer.php` - Footer template
- `style.css` - Theme style information

## License

This theme is licensed under the GNU General Public License v2 or later.