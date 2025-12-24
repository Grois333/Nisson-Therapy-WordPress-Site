# Nisson Therapy - WordPress Custom Theme

Custom WordPress theme built with ACF Blocks for Nisson Therapy website.

## Project Structure

- **Custom Theme**: `wp-content/themes/nisson-therapy/` (to be created)
- **Custom Plugins**: `wp-content/plugins/` (if needed)
- **ACF Blocks**: Each page section will be a separate ACF block

## Development

This is a custom WordPress theme built from scratch using:
- WordPress
- Advanced Custom Fields (ACF) Blocks
- Custom PHP, CSS, and JavaScript

## Deployment

This project uses GitHub Actions to automatically deploy to the production server via FTP when changes are pushed to the `main` or `master` branch.

### Deployment Process

1. Push changes to `main` or `master` branch
2. GitHub Actions workflow automatically triggers
3. Files are deployed to FTP server: `194.36.184.144`
4. Deployment path: `/domains/ntillimpsychotherapy.com/public_html/`

### What Gets Deployed

- Custom theme files (`wp-content/themes/nisson-therapy/`)
- Custom plugin files (`wp-content/plugins/`)
- Configuration files (`.gitignore`, `.cursorrules`)

### What Doesn't Get Deployed

- WordPress core files
- Default WordPress themes
- Uploads and cache directories
- Development files (`.git`, `.github`, `node_modules`, etc.)

## Local Development

This project is set up for local development using Local by Flywheel or similar WordPress development environment.

## License

Proprietary - All rights reserved

