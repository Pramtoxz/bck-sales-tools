<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://cdn.ckbox.io/ckbox/2.4.0/styles/themes/lark.css">
        <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/42.0.0/ckeditor5.css" />
        <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5-premium-features/42.0.0/ckeditor5-premium-features.css">
    </head>
    <body>
        <style>
            #container {
                width: 1000px;
                margin: 20px auto;
            }
            .ck-editor__editable[role="textbox"] {
                /* editing area */
                min-height: 200px;
            }
            .ck-content .image {
                /* block images */
                max-width: 80%;
                margin: 20px auto;
            }
        </style>
        <div id="container">
            <div id="editor">
            </div>
        </div>
        <script src="https://cdn.ckbox.io/ckbox/2.4.0/ckbox.js"></script>

        <script type="importmap">
            {
                "imports": {
                    "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/42.0.0/ckeditor5.js",
                    "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/42.0.0/",
                    "ckeditor5-premium-features": "https://cdn.ckeditor.com/ckeditor5-premium-features/42.0.0/ckeditor5-premium-features.js",
                    "ckeditor5-premium-features/": "https://cdn.ckeditor.com/ckeditor5-premium-features/42.0.0/"
                }
            }
        </script>
        <script type="module">
            // This sample still does not showcase all CKEditor 5 features (!)
            // Visit https://ckeditor.com/docs/ckeditor5/latest/features/index.html to browse all the features.
            import {
                ClassicEditor,
                Autoformat,
                Bold,
                Italic,
                Underline,
                BlockQuote,
                Base64UploadAdapter,
                CloudServices,
                CKBox,
                CKBoxImageEdit,
                Essentials,
                FindAndReplace,
                Font,
                Heading,
                Image,
                ImageCaption,
                ImageResize,
                ImageStyle,
                ImageToolbar,
                ImageUpload,
                PictureEditing,
                Indent,
                IndentBlock,
                Link,
                List,
                MediaEmbed,
                Mention,
                Paragraph,
                PasteFromOffice,
                SourceEditing,
                Table,
                TableColumnResize,
                TableToolbar,
                TextTransformation,
                HtmlEmbed,
                CodeBlock,
                RemoveFormat,
                Code,
                SpecialCharacters,
                HorizontalLine,
                PageBreak,
                TodoList,
                Strikethrough,
                Subscript,
                Superscript,
                Highlight,
                Alignment
            } from 'ckeditor5';

            import {
                ExportPdf,
                ExportWord
            } from 'ckeditor5-premium-features';

            ClassicEditor.create( document.querySelector( '#editor' ), {
                plugins: [
                    Autoformat,
                    BlockQuote,
                    Bold,
                    CloudServices,
                    CKBox,
                    Essentials,
                    FindAndReplace,
                    Font,
                    Heading,
                    Image,
                    ImageCaption,
                    ImageResize,
                    ImageStyle,
                    ImageToolbar,
                    ImageUpload,
                    Base64UploadAdapter,
                    Indent,
                    IndentBlock,
                    Italic,
                    Link,
                    List,
                    MediaEmbed,
                    Mention,
                    Paragraph,
                    PasteFromOffice,
                    PictureEditing,
                    SourceEditing,
                    Table,
                    TableColumnResize,
                    TableToolbar,
                    TextTransformation,
                    Underline,
                    HtmlEmbed,
                    CodeBlock,
                    RemoveFormat,
                    Code,
                    SpecialCharacters,
                    HorizontalLine,
                    PageBreak,
                    TodoList,
                    Strikethrough,
                    Subscript,
                    Superscript,
                    Highlight,
                    Alignment,
                    CKBoxImageEdit,
                    ExportPdf,
                    ExportWord
                ],
                toolbar: {
                    items: [
                        'undo', 'redo',
                        '|',
                        'sourceEditing',
                        '|',
                        'exportPDF','exportWord',
                        '|',
                        'findAndReplace', 'selectAll',
                        '|',
                        'heading',
                        '|',
                        'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor',
                        '-',
                        'bold', 'italic', 'underline',
                        {
                            label: 'Formatting',
                            icon: 'text',
                            items: [ 'strikethrough', 'subscript', 'superscript', 'code', '|', 'removeFormat' ]
                        },
                        '|',
                        'specialCharacters', 'horizontalLine', 'pageBreak',
                        '|',
                        'link', 'insertImage', 'ckbox', 'ckboxImageEdit', 'insertTable',
                        {
                            label: 'Insert',
                            icon: 'plus',
                            items: [ 'highlight', 'blockQuote', 'mediaEmbed', 'codeBlock', 'htmlEmbed' ]
                        },
                        'alignment',
                        '|',
                        'bulletedList', 'numberedList', 'todoList',
                        {
                            label: 'Indents',
                            icon: 'plus',
                            items: [ 'outdent', 'indent' ]
                        }
                    ],
                    shouldNotGroupWhenFull: true
                },
                list: {
                    properties: {
                        styles: true,
                        startIndex: true,
                        reversed: true
                    }
                },
                // https://ckeditor.com/docs/ckeditor5/latest/features/headings.html#configuration
                heading: {
                    options: [
                        { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                        { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                        { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                        { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                        { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
                        { model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5' },
                        { model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6' }
                    ]
                },
                placeholder: 'Welcome to CKEditor 5 + CKBox!',
                image: {
                    resizeOptions: [
                        {
                            name: 'resizeImage:original',
                            label: 'Default image width',
                            value: null
                        },
                        {
                            name: 'resizeImage:50',
                            label: '50% page width',
                            value: '50'
                        },
                        {
                            name: 'resizeImage:75',
                            label: '75% page width',
                            value: '75'
                        }
                    ],
                    toolbar: [
                        'imageTextAlternative',
                        'toggleImageCaption',
                        '|',
                        'imageStyle:inline',
                        'imageStyle:wrapText',
                        'imageStyle:breakText',
                        '|',
                        'resizeImage'
                    ],
                },
                link: {
                    addTargetToExternalLinks: true,
                    defaultProtocol: 'https://'
                },
                table: {
                    contentToolbar: [ 'tableColumn', 'tableRow', 'mergeTableCells' ],
                },
                ckbox: {
                    // You need to provide your own token endpoint here
                    // Sign up to CKBox to get one: https://ckeditor.com/ckbox/
                    tokenUrl: 'https://api.ckbox.io/token/demo',
                    theme: 'lark'
                }
            } )
            .then( ( editor ) => {
                window.editor = editor;
            } )
            .catch( ( error ) => {
                console.error( error.stack );
            } );

        </script>
    </body>
</html>