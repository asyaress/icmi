@once
    @push('head')
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.css" rel="stylesheet">
        <style>
            .note-editor.note-frame {
                border-color: #d5d5d5;
                border-radius: .5rem;
                background: #fff;
            }
            .note-toolbar .note-btn {
                border-color: #d3d3d3;
            }
            .media-picker-card {
                border: 1px solid #e5e7eb;
                border-radius: .5rem;
                padding: .6rem;
                height: 100%;
                background: #fff;
            }
            .media-picker-thumb {
                height: 110px;
                border: 1px solid #ececec;
                border-radius: .4rem;
                display: flex;
                align-items: center;
                justify-content: center;
                overflow: hidden;
                background: #f8f9fa;
                margin-bottom: .5rem;
            }
            .media-picker-thumb img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.js"></script>
        <script>
            (function ($) {
                let activeEditor = null;

                function escapeHtml(text) {
                    return String(text || '')
                        .replaceAll('&', '&amp;')
                        .replaceAll('<', '&lt;')
                        .replaceAll('>', '&gt;')
                        .replaceAll('"', '&quot;')
                        .replaceAll("'", '&#039;');
                }

                function buildMediaCard(item) {
                    const thumb = item.is_image
                        ? `<img src="${escapeHtml(item.preview_url || item.url)}" alt="${escapeHtml(item.name)}">`
                        : `<span class="text-muted text-uppercase fw-semibold">${escapeHtml(item.extension || item.type)}</span>`;

                    return `
                        <div class="col-md-4 col-lg-3">
                            <div class="media-picker-card">
                                <div class="media-picker-thumb">${thumb}</div>
                                <div class="small text-muted mb-1">${escapeHtml((item.type || '').toUpperCase())} • ${escapeHtml(item.size_human || '')}</div>
                                <div class="small fw-semibold mb-2" style="word-break: break-word;">${escapeHtml(item.name)}</div>
                                <button type="button" class="btn btn-sm btn-primary w-100 media-select-btn" data-id="${item.id}">
                                    Pakai Media
                                </button>
                            </div>
                        </div>
                    `;
                }

                async function loadMediaLibrary() {
                    const $list = $('#media-picker-list');
                    const query = $('#media-picker-search').val() || '';
                    const type = $('#media-picker-type').val() || '';
                    const endpoint = `{{ route('admin.media-manager.library') }}?q=${encodeURIComponent(query)}&type=${encodeURIComponent(type)}`;

                    $list.html('<div class="col-12"><div class="text-muted">Memuat media...</div></div>');

                    try {
                        const response = await fetch(endpoint, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            },
                        });
                        const payload = await response.json();
                        const items = Array.isArray(payload.data) ? payload.data : [];

                        if (!items.length) {
                            $list.html('<div class="col-12"><div class="alert alert-light border mb-0">Belum ada media. Upload dulu di menu Media Manager.</div></div>');
                            return;
                        }

                        window.__ICMI_MEDIA_ITEMS = items.reduce((acc, item) => {
                            acc[item.id] = item;
                            return acc;
                        }, {});

                        $list.html(items.map(buildMediaCard).join(''));
                    } catch (error) {
                        $list.html('<div class="col-12"><div class="alert alert-danger mb-0">Gagal memuat media library.</div></div>');
                    }
                }

                function insertSelectedMedia(item) {
                    if (!activeEditor || !item) return;

                    const $editor = $(activeEditor);
                    if (item.is_image) {
                        $editor.summernote('insertImage', item.url, item.name || 'image');
                    } else {
                        const text = item.name || 'Download file';
                        const html = `<p><a href="${escapeHtml(item.url)}" target="_blank" rel="noopener">${escapeHtml(text)}</a></p>`;
                        $editor.summernote('pasteHTML', html);
                    }
                }

                $(document).on('click', '.media-select-btn', function () {
                    const id = Number($(this).data('id'));
                    const item = (window.__ICMI_MEDIA_ITEMS || {})[id];
                    insertSelectedMedia(item);
                    $('#mediaPickerModal').modal('hide');
                });

                $(document).on('click', '#media-picker-load-btn', function () {
                    loadMediaLibrary();
                });

                $(document).on('click', '#media-picker-search-btn', function () {
                    loadMediaLibrary();
                });

                $(document).on('keypress', '#media-picker-search', function (event) {
                    if (event.key === 'Enter') {
                        event.preventDefault();
                        loadMediaLibrary();
                    }
                });

                $(document).on('click', '.js-open-media-picker', function () {
                    activeEditor = $(this).data('target-editor');
                    $('#mediaPickerModal').modal('show');
                    loadMediaLibrary();
                });

                function createMediaManagerButton(editorSelector) {
                    return function (context) {
                        const ui = $.summernote.ui;
                        return ui.button({
                            contents: 'Media',
                            tooltip: 'Pilih dari Media Manager',
                            click: function () {
                                activeEditor = editorSelector;
                                $('#mediaPickerModal').modal('show');
                                loadMediaLibrary();
                            }
                        }).render();
                    };
                }

                function initSummernote(selector, options) {
                    const $el = $(selector);
                    if (!$el.length || $el.hasClass('note-editor-initialized')) return;

                    const editorSelector = selector;

                    $el.summernote($.extend(true, {
                        placeholder: 'Tulis konten di sini...',
                        height: 360,
                        dialogsInBody: true,
                        tabsize: 2,
                        toolbar: [
                            ['style', ['style', 'bold', 'italic', 'underline', 'clear']],
                            ['font', ['strikethrough', 'superscript', 'subscript']],
                            ['fontsize', ['fontsize']],
                            ['color', ['color']],
                            ['para', ['ul', 'ol', 'paragraph']],
                            ['height', ['height']],
                            ['insert', ['link', 'picture', 'video', 'table', 'hr', 'mediaManager']],
                            ['view', ['fullscreen', 'codeview', 'help']]
                        ],
                        buttons: {
                            mediaManager: createMediaManagerButton(editorSelector),
                        },
                    }, options || {}));

                    $el.addClass('note-editor-initialized');
                }

                $(function () {
                    $('.js-summernote').each(function () {
                        const id = $(this).attr('id');
                        if (!id) return;
                        initSummernote('#' + id, { height: 420 });
                    });

                    $('.js-summernote-lite').each(function () {
                        const id = $(this).attr('id');
                        if (!id) return;
                        initSummernote('#' + id, { height: 280 });
                    });
                });
            })(jQuery);
        </script>
    @endpush

    <div class="modal fade" id="mediaPickerModal" tabindex="-1" role="dialog" aria-labelledby="mediaPickerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mediaPickerModalLabel">Pilih Media dari Media Manager</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row g-2 mb-3">
                        <div class="col-md-5">
                            <input type="text" id="media-picker-search" class="form-control" placeholder="Cari nama file...">
                        </div>
                        <div class="col-md-3">
                            <select id="media-picker-type" class="form-select">
                                <option value="">Semua tipe</option>
                                <option value="image">Image</option>
                                <option value="pdf">PDF</option>
                                <option value="document">Document</option>
                                <option value="video">Video</option>
                                <option value="audio">Audio</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="button" id="media-picker-search-btn" class="btn btn-outline-dark w-100">Cari</button>
                        </div>
                        <div class="col-md-2">
                            <button type="button" id="media-picker-load-btn" class="btn btn-outline-secondary w-100">Refresh</button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <a href="{{ route('admin.media-manager.index') }}" target="_blank" class="btn btn-sm btn-primary">Buka Halaman Media Manager</a>
                    </div>

                    <div class="row g-3" id="media-picker-list">
                        <div class="col-12"><div class="text-muted">Klik refresh untuk memuat media.</div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endonce
