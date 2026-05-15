import ClassicEditorBase from '@ckeditor/ckeditor5-editor-classic/src/classiceditor';
import Essentials from '@ckeditor/ckeditor5-essentials/src/essentials';
import Paragraph from '@ckeditor/ckeditor5-paragraph/src/paragraph';
import Bold from '@ckeditor/ckeditor5-basic-styles/src/bold';
import Italic from '@ckeditor/ckeditor5-basic-styles/src/italic';
import Alignment from '@ckeditor/ckeditor5-alignment/src/alignment'; // Import plugin alignment

class ClassicEditor extends ClassicEditorBase {}

ClassicEditor.builtinPlugins = [
    Essentials,
    Paragraph,
    Bold,
    Italic,
    Alignment
];

ClassicEditor.defaultConfig = {
    toolbar: {
        items: [
            'bold', 'italic', 'alignment' // Add alignment to the toolbar
        ]
    },
    alignment: {
        options: [ 'left', 'center', 'right', 'justify' ] // Customize alignment options
    },
    language: 'en'
};

export default ClassicEditor;