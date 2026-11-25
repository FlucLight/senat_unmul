import tinymce from 'tinymce/tinymce';
import 'tinymce/icons/default';
import 'tinymce/themes/silver';
import 'tinymce/models/dom';
import 'tinymce/plugins/lists';
import 'tinymce/plugins/table';

import 'tinymce/skins/ui/oxide/skin.min.css';

const DEFAULT_CONTENT_STYLE = `
  body { font-family: "Times New Roman", Times, serif; font-size: 12pt; line-height: 1.5; color: #111; margin: 1rem auto; max-width: 820px; padding: 0 1rem; }
  p { margin: 0 0 6pt 0; }
  table { width: 100%; border-collapse: collapse; }
  th, td { border: 1px solid #333; padding: 4px 6px; vertical-align: top; }
  th { background: #f2f2f2; }
  .ph { background: #f3efe6; border: 1px dashed #b8b0a2; padding: 0 2px; }
  .page-break { border-top: 2px dashed #b8b0a2; margin: 24px 0; page-break-after: always; height: 0; }
  [data-ph="qr_verifikasi"] { display: inline-block; }
`;

function initRichEditor(selector, options = {}) {
  const el = typeof selector === 'string' ? document.querySelector(selector) : selector;
  if (!el || el.dataset.richEditorInitialized === '1') return;
  el.dataset.richEditorInitialized = '1';

  tinymce.init({
    target: el,
    license_key: 'gpl',
    menubar: false,
    branding: false,
    promotion: false,
    statusbar: false,
    height: 700,
    plugins: 'lists table',
    toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | table | pagebreakbtn | removeformat',
    skin: false,
    content_css: false,
    content_style: DEFAULT_CONTENT_STYLE,
    noneditable_class: 'ph',
    extended_valid_elements: 'span[class|data-ph|contenteditable|style],div[class|style|data-table]',
    valid_children: '+body[div|p|table|h1|h2|h3|h4|ul|ol|blockquote|hr]',
    paste_as_text: false,
    paste_data_images: false,
    object_resizing: false,
    ...(options.language ? { language: options.language, language_url: options.language_url || '' } : {}),
    setup: (editor) => {
      editor.ui.registry.addButton('pagebreakbtn', {
        text: 'Halaman Baru',
        tooltip: 'Sisipkan pemisah halaman',
        onAction: () => editor.insertContent('<div class="page-break" style="page-break-after: always;"></div><p></p>'),
      });

      editor.on('BeforeExecCommand', (e) => {
        const node = editor.selection.getNode();
        if (node?.closest?.('.ph')) {
          const allowed = ['mceFocus', 'SelectAll'];
          if (!allowed.includes(e.command)) e.preventDefault();
        }
      });

      editor.on('keydown', (e) => {
        const node = editor.selection.getNode();
        if (!node?.closest?.('.ph')) return;
        const navKeys = ['ArrowLeft','ArrowRight','ArrowUp','ArrowDown','Home','End','PageUp','PageDown','Tab','Escape','Shift','Control','Alt','Meta'];
        if (navKeys.includes(e.key) || e.ctrlKey || e.metaKey) return;
        e.preventDefault();
      });

      editor.on('PastePreProcess', (e) => {
        e.content = e.content
          .replace(/<img[^>]*>/gi, '')
          .replace(/<!--\[if[\s\S]*?<!\[endif\]-->/gi, '')
          .replace(/\sstyle="[^"]*mso-[^"]*"/gi, '')
          .replace(/<\/?o:p[^>]*>/gi, '');
      });
    },
    ...options.tinymce,
  });
}

window.initRichEditor = initRichEditor;
window.tinymce = tinymce;
export { initRichEditor, tinymce };
