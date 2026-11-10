# Responsive FileManager + TinyMCE Demo

A simple demonstration of how [TinyMCE](https://www.tiny.cloud/) works with the Responsive FileManager. It shows two ways to pick files:

1. **TinyMCE integration:** the `#content` editor uses the bundled `responsivefilemanager` plugin for images, media, and links.
2. **Standalone picker:** the `image_url` field has a **Browse** button that opens the File Manager in a Bootstrap 5 modal and writes the selected URL into the field.

## Requirements

- PHP with a web server (for example WAMP, XAMPP, or the PHP built-in server)
- Composer dependencies installed (`vendor/tinymce/tinymce`)
- Internet access to load Bootstrap 5 from the jsDelivr CDN

## Getting started

1. Place the project in your web root, for example `C:\wamp64\www\responsivefilemanager`.
2. Open `http://localhost/responsivefilemanager/index.php` in a browser.
3. Use the **Browse** button next to *Image Upload URL*, or the file manager button in the TinyMCE toolbar.

`demo.html` shows the File Manager embedded in an iframe.

## Project structure

| Path | Purpose |
| --- | --- |
| `index.php` | Demo page: TinyMCE editor, standalone image URL picker, Bootstrap modal |
| `demo.html` | Minimal iframe example of the File Manager |
| `filemanager/` | Responsive FileManager (`dialog.php`, config, language files, assets) |
| `tinymce/plugins/responsivefilemanager/` | TinyMCE plugin that opens the File Manager |
| `vendor/tinymce/tinymce/` | TinyMCE 8 library and its plugins |
| `source/` | Uploaded originals |
| `thumbs/` | Generated thumbnails |

## How it works

- The File Manager sends the selected file back with `window.postMessage` using `{ sender: 'responsivefilemanager', url, field_id }`.
- The TinyMCE plugin listens for that message and inserts the file into the editor.
- The standalone picker on `index.php` listens for the same message, checks that it came from its own iframe, and sets the `image_url` field.

## Configuration

- File Manager settings: `filemanager/config/config.php` (upload folders, file types, access keys).
- TinyMCE settings: the `tinymce.init()` call in `index.php`.

## Thanks to

- [radekhulan/responsivefilemanager](https://github.com/radekhulan/responsivefilemanager)
- [trippo/ResponsiveFilemanager](https://github.com/trippo/ResponsiveFilemanager)

## License

See [LICENSE.TXT](LICENSE.TXT).
