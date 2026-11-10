<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="vendor/tinymce/tinymce/tinymce.min.js"></script>
    <script>
    // Initialise TinyMCE on the #content textarea
    tinymce.init({
        selector: '#content',
        license_key: 'gpl',

        // Load the Responsive FileManager plugin shipped in this repo
        external_plugins: {
            responsivefilemanager: new URL('tinymce/plugins/responsivefilemanager/plugin.min.js', window
                .location.href).href
        },
        // All GPL plugins shipped in vendor/tinymce/tinymce/plugins, plus the Responsive FileManager plugin
        plugins: 'accordion advlist anchor autolink autoresize autosave charmap code codesample directionality emoticons fullscreen help image importcss insertdatetime link lists media nonbreaking pagebreak preview quickbars responsivefilemanager save searchreplace table visualblocks visualchars wordcount',
        menubar: 'file edit view insert format tools table help',
        toolbar: 'save | undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media responsivefilemanager table codesample emoticons charmap insertdatetime anchor pagebreak nonbreaking | ltr rtl | removeformat | searchreplace visualblocks visualchars | code preview fullscreen | help',
        quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote',
        height: 500,

        // Responsive FileManager configuration (path relative to this page)
        external_filemanager_path: 'filemanager/',
        filemanager_title: 'File Manager',
        file_picker_types: 'file image media',
        // Hand TinyMCE's file picker requests over to the plugin's global helper
        file_picker_callback: function(callback, value, meta) {
            if (window.tinymceRFMFilePicker) {
                window.tinymceRFMFilePicker(callback, value, meta);
            }
        },

        relative_urls: false
    });
    </script>
    <style>
    #rfmModal .modal-body {
        height: 75vh;
    }

    #rfmModal iframe {
        width: 100%;
        height: 100%;
        border: 0;
    }
    </style>
</head>

<body class="bg-light">
    <div class="container py-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="h4 mb-4">Editor</h1>
                <form method="post">
                    <div class="mb-3">
                        <label for="image_url" class="form-label">Image Upload URL</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="image_url" name="image_url">
                            <button type="button" class="btn btn-outline-secondary" id="image_url_browse"
                                data-bs-toggle="modal" data-bs-target="#rfmModal">Browse</button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea class="form-control" id="content" name="content"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="rfmModal" tabindex="-1" aria-labelledby="rfmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title h5" id="rfmModalLabel">File Manager</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <iframe id="rfm-frame" title="File Manager"></iframe>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Standalone File Manager picker: opens the File Manager in a Bootstrap modal
    // and writes the selected image URL into #image_url.
    (function() {
        var modalEl = document.getElementById('rfmModal');
        var frame = document.getElementById('rfm-frame');
        var input = document.getElementById('image_url');
        var dialogUrl = 'filemanager/dialog.php?type=1&lang=en_EN';

        // Load the File Manager only when the modal opens
        modalEl.addEventListener('show.bs.modal', function() {
            frame.src = dialogUrl;
        });

        // Unload the File Manager when the modal closes
        modalEl.addEventListener('hidden.bs.modal', function() {
            frame.src = 'about:blank';
        });

        // The File Manager replies with postMessage; accept only replies from our iframe
        window.addEventListener('message', function(event) {
            if (event.source !== frame.contentWindow) return;
            if (!event.data || event.data.sender !== 'responsivefilemanager') return;

            // Convert same-origin absolute URLs to a path so the field stores a relative URL
            var url = event.data.url; //absolute URL from the file manager

            // Convert absolute URL to relative URL if it's from the same origin
            /* try {
                 var urlObj = new URL(url, window.location.origin);
                 if (urlObj.origin === window.location.origin) {
                     url = urlObj.pathname;
                 }
             } catch (e) {
                 url = String(url).replace(/^https?:\/\/[^\/]+/, '');
             } */

            input.value = url;
            // Notify listeners that the field changed programmatically
            input.dispatchEvent(new Event('change', {
                bubbles: true
            }));
            bootstrap.Modal.getOrCreateInstance(modalEl).hide();
        }, false);
    })();
    </script>
</body>

</html>
