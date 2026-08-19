<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trix Editor Upload</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.css">
</head>
<body>

    <h1>Trix Editor</h1>

    <form method="POST" action="{{ route('articles.store') }}">
        @csrf
        <input id="x" type="hidden" name="content">
        <trix-editor input="x"></trix-editor>

        <button type="submit">Submit</button>
    </form>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.js"></script>
    <script>
        document.addEventListener("trix-attachment-add", function(event) {
            if (event.attachment.file) {
                uploadTrixImage(event.attachment);
            }
        });

        function uploadTrixImage(attachment) {
            const file = attachment.file;
            const form = new FormData();
            form.append("file", file);

            fetch("{{ route('trix.upload') }}", {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: form
            })
            .then(response => response.json())
            .then(result => {
                attachment.setAttributes({
                    url: result.url,
                    href: result.url
                });
            })
            .catch(error => {
                console.error("Upload error:", error);
            });
        }
    </script>

</body>
</html>
