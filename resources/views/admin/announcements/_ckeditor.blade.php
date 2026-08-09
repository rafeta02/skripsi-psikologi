<script>
    $(document).ready(function () {
        var allEditors = document.querySelectorAll('.ckeditor');
        for (var i = 0; i < allEditors.length; ++i) {
            ClassicEditor.create(allEditors[i], {
                toolbar: [
                    'heading', '|',
                    'bold', 'italic', 'link', '|',
                    'bulletedList', 'numberedList', '|',
                    'blockQuote', '|',
                    'undo', 'redo'
                ],
                link: {
                    addTargetToExternalLinks: true
                }
            });
        }
    });
</script>
