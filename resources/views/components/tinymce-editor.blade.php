@props(['fields' => config('senatitor.fields', [])])

<script src="{{ asset('tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script type="text/javascript">
    const fields = @json($fields);

    tinymce.init({
        selector: 'textarea',
        promotion: false,
        license_key: 'gpl',
        formats: {
            menjorok: {
                block: 'p',
                styles: {
                    'text-indent': '30px'
                }
            },
            aligntop: {
                title: 'Align Top',
                selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,td,img,audio,video',
                classes: 'aligntop'
            },
            alignmiddle: {
                title: 'Align Middle',
                selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,td,img,audio,video',
                classes: 'alignmiddle'
            },
            alignbottom: {
                title: 'Align Bottom',
                selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,td,img,audio,video',
                classes: 'alignbottom'
            }
        },
        style_formats: [{
                title: 'Align Top',
                format: 'aligntop'
            },
            {
                title: 'Align Middle',
                format: 'alignmiddle'
            },
            {
                title: 'Align Bottom',
                format: 'alignbottom'
            }
        ],
        block_formats: 'Paragraph=p; Header 1=h1; Header 2=h2; Header 3=h3; Header 4=h4; Header 5=h5; Header 6=h6; Div=div; Preformatted=pre; Blockquote=blockquote; Menjorok=menjorok',
        style_formats_merge: true,
        table_sizing_mode: 'relative',
        height: "700",
        plugins: ['advlist', 'autolink', 'lists', 'charmap', 'pagebreak', 'searchreplace',
            'wordcount', 'visualblocks', 'visualchars', 'insertdatetime', 'nonbreaking', 'table',
            'directionality', 'emoticons', 'code'
        ],
        toolbar1: "removeformat | bold italic underline subscript superscript | bullist numlist outdent indent lineheight | alignleft aligncenter alignright alignjustify | blocks fontfamily fontsizeinput",
        toolbar2: "kodeisian | insertpagebreak",
        image_advtab: true,
        skin: 'tinymce-5',
        relative_urls: false,
        remove_script_host: false,
        entity_encoding: 'raw',
        font_size_input_default_unit: 'pt',
        line_height_formats: '1 1.15 1.5 2 2.5 3',
        font_family_formats: `Andale Mono=andale mono,times; Arial=arial,helvetica,sans-serif; Arial Black=arial black; Bookman Old Style=bookman old style; Comic Sans MS=comic sans ms,sans-serif; Courier New=courier new,courier; Georgia=georgia,palatino; Helvetica=helvetica; Impact=impact,chicago; Tahoma=tahoma,arial,helvetica,sans-serif; Times New Roman=times new roman,times; Trebuchet MS=trebuchet ms,geneva; Verdana=verdana,geneva;`,
        setup: function(ed) {
            ed.on('init', function() {
                setTimeout(function() {
                    ed.execCommand('fontSize', false, '12pt');
                }, 500);
            });
            ed.on('BeforeExecCommand', function(e) {
                    if (e.command === 'mcePageBreak') {
                        e.preventDefault();
                        insertPagebreak(ed);
                    }
                }),
                ed.ui.registry.addButton('insertpagebreak', {
                    text: 'Tambah Halaman Baru',
                    onAction: function() {
                        insertPagebreak(ed);
                    }
                }),
                ed.ui.registry.addMenuButton('kodeisian', {
                    text: 'Sisipkan Field',
                    fetch: function(callback) {
                        const items = fields.map(function(field) {
                            return {
                                type: 'menuitem',
                                text: field.label,
                                onAction: function() {
                                    ed.insertContent(`[[${field.token}]]`);
                                }
                            };
                        });
                        callback(items);
                    }
                });
        },
        content_style: `
            body {
                font-family: Arial;
                background: white;
            }
            @media (min-width: 840px) {
                html {
                    background: whitesmoke;
                    min-height: 100%;
                    padding: 0 .5rem;
                }
                body {
                    background-color: white;
                    box-shadow: 0 0 4px lightgray;
                    box-sizing: border-box;
                    margin: 1rem auto 0;
                    max-width: 820px;
                    min-height: calc(100vh - 1rem);
                    padding:4rem;
                }
            }
            .aligntop {
                vertical-align: top;
            }
            .alignmiddle {
                vertical-align: middle;
            }
            .alignbottom {
                vertical-align: bottom;
            }
            .new-break > .mce-pagebreak {
                border:none;
                cursor: default;
                display: block;
                height: 25px;
                margin-top: 64px;
                margin-bottom: 64px;
                page-break-before: always;
                width: 120%;
                margin-left: -9.6%;
                background-color: whitesmoke;
            }
        `
    });

    function insertPagebreak(ed) {
        // Insert a page break when the button is clicked
        ed.insertContent(
            '<div class="new-break" style="page-break-after: always;"><!-- pagebreak --></div><p></p>');
        ed.execCommand('removeFormat')
    }
</script>
