# Translations / Μεταφράσεις

This plugin includes multilingual support with bundled translations.

## Included Languages

- **English** (default)
- **Greek (Ελληνικά)** - Complete translation included

## Files

- `wow-dynamic-deals-for-woo.pot` - Translation template (for translators)
- `wow-dynamic-deals-for-woo-el.po` - Greek translation source
- `wow-dynamic-deals-for-woo-el.mo` - Greek translation binary (compiled)
- `compile-mo.php` - PHP script to compile PO to MO files

## How to Use

The plugin will automatically detect your WordPress language setting and load the appropriate translation.

To change your site language:
1. Go to **Settings → General**
2. Select **Site Language**
3. Choose **Ελληνικά (Greek)** or your preferred language

## For Translators

To add a new language:

1. Use the POT template file as a starting point
2. Translate strings using a tool like:
   - [Poedit](https://poedit.net/) (recommended)
   - [Loco Translate](https://wordpress.org/plugins/loco-translate/) WordPress plugin
   - Online editors

3. Save files as:
   - `wow-dynamic-deals-for-woo-{locale}.po` (source)
   - `wow-dynamic-deals-for-woo-{locale}.mo` (compiled)

### Locale Codes

Common locale codes:
- `el` - Greek (Ελληνικά)
- `de_DE` - German
- `fr_FR` - French
- `es_ES` - Spanish
- `it_IT` - Italian

## Compile MO Files

If you edit the PO file and need to recompile:

```bash
php compile-mo.php
```

Or use Poedit which compiles automatically.

## Contributing Translations

Want to contribute a translation? 

1. Fork the repository
2. Add your translation files
3. Submit a pull request

We welcome all language contributions!
