# Documentation Plugin

This plugin provides a documentation system for the LaswitchTech Core Framework. It supports lightweight markdown rendering and multi-level document search across kernel, application, plugins, themes, and modules.

## Features

- Multi-level documentation search:
  - Kernel: `vendor/laswitchtech/core/docs/`
  - Application: `app/docs/` 
  - Plugins: `lib/plugins/*/docs/`
  - Themes: `lib/themes/*/docs/`
  - Modules: `lib/modules/*/docs/`

- Lightweight markdown renderer that supports:
  - Headers (#, ##, ###)
  - Bold text (**text**)
  - Italic text (*text*)
  - Code blocks (```code```)
  - Inline code (`code`)
  - Lists
  - Links [text](url)
  - Images ![alt](url)

- Panel layout with sidebar TOC + previous/next navigation
- Edit on GitHub link for admin users
- Plugin self-registration with routes

## Installation

The plugin is installed automatically with the framework. Simply enable it through your configuration.

## Usage

Access documentation through:
- `/docs` - Main documentation index page
- `/docs/view?path=path&file=name.md` - View specific documentation file

## Configuration

No special configuration is required. The plugin automatically discovers documentation files in standard locations.