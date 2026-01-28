@include('partials.admin.toast')
<script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>

<script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/node-waves/node-waves.js') }}"></script>

<script src="{{ asset('assets/vendor/libs/@algolia/autocomplete-js.js') }}"></script>

<script src="{{ asset('assets/vendor/libs/pickr/pickr.js') }}"></script>

<script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

<script src="{{ asset('assets/vendor/libs/hammer/hammer.js') }}"></script>

<script src="{{ asset('assets/vendor/libs/i18n/i18n.js') }}"></script>

<script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

<script src="{{ asset('assets/vendor/libs/tagify/tagify.js') }}"></script>

<!-- endbuild -->

<!-- Vendors JS -->
<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/swiper/swiper.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>

<!-- Main JS -->

<script src="{{ asset('assets/js/main.js') }}"></script>
<script src="{{ asset('assets/js/pages-auth.js') }}"></script>
{{-- <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script> --}}
<script src="{{ asset('assets/js/forms-tagify.js') }}"></script>

{{-- ======================================================
   CKEDITOR + REPEATER SCRIPT (ONLY CHANGES)
====================================================== --}}
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
{{-- <script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/classic/ckeditor.js"></script> --}}

<script>
    window.addEventListener('load', function() {
        const loader = document.getElementById('pageLoader');
        const content = document.getElementById('pageContent');

        if (loader && content) {
            loader.style.display = 'none';
            content.style.display = 'block';
        }
    });
</script>

<script>
    /* ===============================
        GLOBAL STATE
    ================================ */

    let savedSelection = null;
    const historyMap = new WeakMap();

    const ADD_CLASS = {
        'Small Text': 'text-2xl',
        'Bold + Bigger': 'text-3xl sm:text-6xl xl:text-6xl 2xl:text-7xl'
    };

    /* ===============================
       HISTORY (UNDO / REDO)
    ================================ */
    function getHistory(textarea) {
        if (!historyMap.has(textarea)) {
            historyMap.set(textarea, {
                stack: [],
                index: -1
            });
        }
        return historyMap.get(textarea);
    }

    function saveHistory(textarea) {
        const h = getHistory(textarea);
        h.stack = h.stack.slice(0, h.index + 1);
        h.stack.push(textarea.value);
        h.index++;
    }

    function undo(textarea) {
        const h = getHistory(textarea);
        if (h.index > 0) {
            h.index--;
            textarea.value = h.stack[h.index];
        }
    }

    function redo(textarea) {
        const h = getHistory(textarea);
        if (h.index < h.stack.length - 1) {
            h.index++;
            textarea.value = h.stack[h.index];
        }
    }

    /* ===============================
       SAVE SELECTION
    ================================ */
    $(document).on('mouseup keyup', 'textarea.editor', function() {
        savedSelection = {
            textarea: this,
            start: this.selectionStart,
            end: this.selectionEnd
        };
    });

    $(document).on('keydown', 'textarea.editor', function(e) {

        if (e.key !== 'Enter') return;

        const textarea = this;
        const pos = textarea.selectionStart;
        const value = textarea.value;

        const before = value.substring(0, pos);
        const after = value.substring(pos);

        const openUl = before.lastIndexOf('<ul>');
        const closeUl = before.lastIndexOf('</ul>');

        const openLi = before.lastIndexOf('<li>');
        const closeLi = before.lastIndexOf('</li>');

        // ✔ Cursor must be inside <ul><li>
        if (
            openUl === -1 ||
            openUl < closeUl ||
            openLi === -1 ||
            openLi < closeLi
        ) {
            return;
        }

        e.preventDefault();

        // Insert new LI
        const insert = '</li><li>';
        const newValue = before + insert + after;

        textarea.value = newValue;

        const cursorPos = before.length + insert.length;
        textarea.selectionStart = textarea.selectionEnd = cursorPos;

        saveHistory(textarea);
    });


    /* ===============================
       PREVENT TOOLBAR FOCUS LOSS
    ================================ */
    $(document).on(
        'mousedown',
        '.custom-toolbar button',
        e => e.preventDefault()
    );

    /* ===============================
       INIT
    ================================ */
    function initSummernote(context = document) {

        $(context).find('textarea.editor').each(function() {

            if ($(this).prev('.custom-toolbar').length) return;

            const toolbar = `<div class="custom-toolbar mb-2 d-flex align-items-center gap-2">
                <select class="heading-select form-select form-select-sm w-auto">
                    <option value="">Paragraph</option>
                    <option value="p">Paragraph</option>
                    <option value="h1">H1</option>
                    <option value="h2">H2</option>
                    <option value="h3">H3</option>
                    <option value="h4">H4</option>
                    <option value="h5">H5</option>
                    <option value="h6">H6</option>
                </select>

                <select class="add-class-select form-select form-select-sm w-auto">
                    <option value="">Add Class</option>
                    ${Object.entries(ADD_CLASS)
                        .map(([label, cls]) => `<option value="${cls}">${label}</option>`)
                        .join('')}
                </select>

                <button type="button" class="btn btn-outline-dark btn-sm" data-tag="strong"><b>B</b></button>
                <button type="button" class="btn btn-outline-dark btn-sm" data-tag="u"><u>U</u></button>

                <button type="button" class="btn btn-outline-dark btn-sm ul-btn">• Bullets </button>

                <button type="button" class="btn btn-outline-secondary btn-sm erase-selection">Erase</button>
                <button type="button" class="btn btn-outline-danger btn-sm clear-all">Clear All</button>

                <button type="button" class="btn btn-outline-dark btn-sm undo-btn">↶</button>
                <button type="button" class="btn btn-outline-dark btn-sm redo-btn">↷</button>

                <button type="button" class="btn btn-outline-primary btn-sm preview-toggle">Preview</button>
            </div>
            <div class="preview-box border rounded p-3 d-none"></div>`;

            $(this).before(toolbar);
            saveHistory(this);
        });
    }

    /* ===============================
       INLINE TAGS (B / U)
    ================================ */
    $(document).on('click', '.custom-toolbar button[data-tag]', function() {

        const tag = $(this).data('tag');
        const textarea = getTextarea(this);

        if (!isValidSelection(textarea)) return alert('Select text first');

        applyInlineTag(textarea, tag);
        saveHistory(textarea); // ✅ AFTER
    });


    $(document).on('change', '.add-class-select', function() {

        const cls = this.value;
        if (!cls) return;

        const textarea = getTextarea(this);
        if (!isValidSelection(textarea)) return alert('Select text first');

        const {
            start,
            end
        } = savedSelection;
        let selected = textarea.value.substring(start, end);

        // Toggle OFF
        if (new RegExp(`class="[^"]*${cls}[^"]*"`, 'i').test(selected)) {
            selected = selected.replace(/<span[^>]*>|<\/span>/gi, '');
        } else {
            selected = `<span class="${cls}">${selected}</span>`;
        }

        replaceSelection(textarea, selected, start);
        saveHistory(textarea); // ✅ SAVE AFTER CHANGE

        this.value = '';
    });

    function toggleSpanClass(textarea, cls, type) {

        if (!savedSelection || savedSelection.textarea !== textarea) {
            alert('Select text first');
            return;
        }

        textarea.focus();
        textarea.selectionStart = savedSelection.start;
        textarea.selectionEnd = savedSelection.end;

        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        if (start === end) return;

        let selected = textarea.value.substring(start, end);

        // Regex groups
        const sizeRegex = /(text-(xs|sm|base|lg|xl|2xl|3xl|4xl|5xl))/gi;
        const colorRegex = /(text-(black|gray|red|blue|green|orange))/gi;

        const regex = type === 'size' ? sizeRegex : colorRegex;

        // Detect existing class
        const hasClass = new RegExp(`class="[^"]*${cls}[^"]*"`, 'i').test(selected);

        if (hasClass) {
            // 🔁 TOGGLE OFF
            selected = selected.replace(regex, '').replace(/\s{2,}/g, ' ');
            selected = selected.replace(/class="\s*"/gi, '');
            replaceSelection(textarea, selected, start);
            return;
        }

        // Remove old size or color classes
        selected = selected.replace(regex, '');

        const wrapped = `<span class="${cls}">${selected}</span>`;
        replaceSelection(textarea, wrapped, start);
    }

    $(document).on('click', '.ul-btn', function() {

        const textarea = getTextarea(this);

        if (!isValidSelection(textarea)) {
            alert('Select text first');
            return;
        }

        addBulletList(textarea);
        saveHistory(textarea); // ✅ after change
    });

    function addBulletList(textarea) {

        textarea.focus();

        const start = savedSelection.start;
        const end = savedSelection.end;
        const value = textarea.value;

        let selected = value.substring(start, end);

        const before = value.substring(0, start);
        const after = value.substring(end);

        const insideUl =
            before.lastIndexOf('<ul>') > before.lastIndexOf('</ul>');

        // 🧹 Clean block tags
        selected = selected.replace(/<\/?(h[1-6]|p|ul|ol|li)>/gi, '');

        const lines = selected
            .split(/\n+/)
            .map(l => l.trim())
            .filter(Boolean);

        if (!lines.length) return;

        const listItems = lines.map(l => `<li>${l}</li>`).join('');

        // ✅ CASE 1: Already inside UL → add LI only
        if (insideUl) {
            replaceSelection(textarea, listItems, start);
            return;
        }

        // ✅ CASE 2: Normal → create UL
        const wrapped = `<ul>${listItems}</ul>`;
        replaceSelection(textarea, wrapped, start);
    }



    /* ===============================
       BLOCK TAGS (H1–H6 / P)
    ================================ */
    $(document).on('change', '.heading-select', function() {

        const tag = $(this).val() || 'p';
        const textarea = getTextarea(this);

        if (!isValidSelection(textarea)) return alert('Select text first');

        saveHistory(textarea);
        applyBlockTag(textarea, tag);
        this.value = '';
    });

    $(document).on('click', '.erase-selection', function() {

        const textarea = getTextarea(this);
        if (!isValidSelection(textarea)) return;

        removeFormatting(textarea);
        saveHistory(textarea);
    });

    $(document).on('click', '.clear-all', function() {

        const textarea = getTextarea(this);
        if (!textarea) return;

        if (confirm('Remove all formatting?')) {
            textarea.value = stripTags(textarea.value);
            saveHistory(textarea);
        }
    });


    $(document).on('click', '.undo-btn', function() {
        undo(getTextarea(this));
    });

    $(document).on('click', '.redo-btn', function() {
        redo(getTextarea(this));
    });

    $(document).on('keydown', 'textarea.editor', function(e) {
        if (e.ctrlKey && e.key === 'z') {
            e.preventDefault();
            undo(this);
        }
        if (e.ctrlKey && e.key === 'y') {
            e.preventDefault();
            redo(this);
        }
    });

    function getTextarea(el) {
        return $(el).closest('.custom-toolbar')
            .nextAll('textarea.editor').first()[0];
    }

    function isValidSelection(textarea) {
        return textarea &&
            savedSelection &&
            savedSelection.textarea === textarea &&
            savedSelection.start !== savedSelection.end;
    }


    /* ===============================
       PREVIEW TOGGLE
    ================================ */
    $(document).on('click', '.preview-toggle', function() {

        const $toolbar = $(this).closest('.custom-toolbar');
        const $textarea = $toolbar.nextAll('textarea.editor').first();
        const $preview = $toolbar.nextAll('.preview-box').first();

        if ($preview.hasClass('d-none')) {
            $preview.html($textarea.val()).removeClass('d-none');
            $textarea.addClass('d-none');
            $(this).text('Edit');
        } else {
            $preview.addClass('d-none');
            $textarea.removeClass('d-none');
            $(this).text('Preview');
        }
    });

    /* ===============================
       CORE FUNCTIONS
    ================================ */
    function applyInlineTag(textarea, tag) {

        if (!savedSelection || savedSelection.textarea !== textarea) {
            alert('Select text first');
            return;
        }

        textarea.focus();
        textarea.selectionStart = savedSelection.start;
        textarea.selectionEnd = savedSelection.end;

        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        if (start === end) return;

        let selected = textarea.value.substring(start, end);

        // ✅ Remove same tag if already inside selection
        const openTag = new RegExp(`<${tag}>`, 'gi');
        const closeTag = new RegExp(`</${tag}>`, 'gi');

        selected = selected
            .replace(openTag, '')
            .replace(closeTag, '');

        const wrapped = `<${tag}>${selected}</${tag}>`;

        replaceSelection(textarea, wrapped, start);
    }

    function applyClassSpan(textarea, cls, type) {

        if (!savedSelection || savedSelection.textarea !== textarea) {
            alert('Select text first');
            return;
        }

        textarea.focus();
        textarea.selectionStart = savedSelection.start;
        textarea.selectionEnd = savedSelection.end;

        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        if (start === end) return;

        let selected = textarea.value.substring(start, end);

        // Remove previous size or color classes
        if (type === 'size') {
            selected = selected.replace(/class="[^"]*text-(xs|sm|base|lg|xl|2xl|3xl|4xl|5xl)[^"]*"/gi, '');
        }

        if (type === 'color') {
            selected = selected.replace(/class="[^"]*text-(black|gray|red|blue|green|orange)[^"]*"/gi, '');
        }

        const wrapped = `<span class="${cls}">${selected}</span>`;

        replaceSelection(textarea, wrapped, start);
    }


    function applyBlockTag(textarea, tag) {

        if (!savedSelection || savedSelection.textarea !== textarea) {
            alert('Select text first');
            return;
        }

        textarea.focus();

        let value = textarea.value;

        let start = savedSelection.start;
        let end = savedSelection.end;

        // 🔹 Expand selection to full block if inside a block tag
        const before = value.lastIndexOf('<', start);
        const after = value.indexOf('>', end);

        if (before !== -1 && after !== -1) {
            const tagMatch = value.substring(before, after + 1)
                .match(/^<(h[1-6]|p)>$/i);

            if (tagMatch) {
                // expand selection to full block
                start = before;
                const closeTag = `</${tagMatch[1]}>`;
                const closeIndex = value.indexOf(closeTag, after);
                if (closeIndex !== -1) {
                    end = closeIndex + closeTag.length;
                }
            }
        }

        const selected = value.substring(start, end);

        // 🔁 TOGGLE: if already wrapped with same tag → unwrap
        const fullTagRegex = new RegExp(
            `^<${tag}>([\\s\\S]*)<\\/${tag}>$`,
            'i'
        );

        if (fullTagRegex.test(selected)) {
            const unwrapped = selected.replace(
                new RegExp(`</?${tag}>`, 'gi'),
                ''
            );
            replaceSelection(textarea, unwrapped, start);
            return;
        }

        // 🧹 Remove all block tags inside selection
        const clean = selected.replace(/<\/?(h[1-6]|p)>/gi, '');

        const wrapped = `<${tag}>${clean}</${tag}>`;

        replaceSelection(textarea, wrapped, start);
    }

    function removeFormatting(textarea) {

        textarea.focus();
        textarea.selectionStart = savedSelection.start;
        textarea.selectionEnd = savedSelection.end;

        const selected = textarea.value.substring(
            textarea.selectionStart,
            textarea.selectionEnd
        );

        replaceSelection(textarea, stripTags(selected), textarea.selectionStart);
    }

    function stripTags(text) {
        return text.replace(/<\/?[^>]+>/g, '');
    }

    function replaceSelection(textarea, content, start) {

        const end = textarea.selectionEnd;

        textarea.value =
            textarea.value.substring(0, start) +
            content +
            textarea.value.substring(end);

        textarea.selectionStart = start;
        textarea.selectionEnd = start + content.length;
        textarea.focus();
    }

    /* ===============================
       INIT
    ================================ */
    $(document).ready(function() {
        initSummernote();
    });
</script>


<script>
    // function initCKEditor(context = document) {

    //     $(context).find('textarea.editor').each(function() {

    //         // Prevent double initialization
    //         if (this.dataset.ckeditorInitialized) return;

    //         ClassicEditor
    //             .create(this, {
    //                 toolbar: {
    //                     items: [
    //                         'heading', '|',
    //                         'bold', 'italic', 'underline', '|',
    //                         'bulletedList', 'numberedList', '|',
    //                         'link', 'blockQuote', '|',
    //                         'undo', 'redo'
    //                     ]
    //                 },
    //                 heading: {
    //                     options: [{
    //                             model: 'paragraph',
    //                             title: 'Paragraph',
    //                             class: 'ck-heading_paragraph'
    //                         },
    //                         {
    //                             model: 'heading1',
    //                             view: 'h1',
    //                             title: 'Heading 1'
    //                         },
    //                         {
    //                             model: 'heading2',
    //                             view: 'h2',
    //                             title: 'Heading 2'
    //                         },
    //                         {
    //                             model: 'heading3',
    //                             view: 'h3',
    //                             title: 'Heading 3'
    //                         },
    //                         {
    //                             model: 'heading4',
    //                             view: 'h4',
    //                             title: 'Heading 4'
    //                         }
    //                     ]
    //                 }
    //             })
    //             .then(editor => {
    //                 // Mark as initialized
    //                 this.dataset.ckeditorInitialized = true;
    //                 this._ckeditorInstance = editor;
    //             })
    //             .catch(error => {
    //                 console.error(error);
    //             });
    //     });
    // }

    // $(document).ready(function() {
    //     initCKEditor();
    // });
</script>

<script>
    function toggleSectionStatus(index) {

        const input = document.getElementById('section-status-' + index);
        const badge = document.getElementById('section-status-badge-' + index);
        const button = document.getElementById('section-toggle-btn-' + index);

        if (!input || !badge || !button) return;

        if (input.value === 'enable') {
            // Switch to DISABLE
            input.value = 'disable';

            badge.textContent = 'Disabled';
            badge.classList.remove('bg-success');
            badge.classList.add('bg-danger');

            button.textContent = 'Enable';
            button.classList.remove('btn-danger');
            button.classList.add('btn-success');

        } else {
            // Switch to ENABLE
            input.value = 'enable';

            badge.textContent = 'Enabled';
            badge.classList.remove('bg-danger');
            badge.classList.add('bg-success');

            button.textContent = 'Disable';
            button.classList.remove('btn-success');
            button.classList.add('btn-danger');
        }
    }

    // function cacheRepeaterTemplates() {
    //     document.querySelectorAll('[id^="repeater-"]').forEach(wrapper => {
    //         if (!wrapper.dataset.template) {
    //             const first = wrapper.querySelector('.repeater-item');
    //             if (first) {
    //                 wrapper.dataset.template = first.outerHTML;
    //             }
    //         }
    //     });
    // }

    // function renumberRepeaterItems(wrapper) {
    //     if (!wrapper) return;
    //     const items = wrapper.querySelectorAll('.repeater-item');
    //     items.forEach((item, i) => {
    //         const h6 = item.querySelector('h6');
    //         if (h6) h6.textContent = 'Item ' + (i + 1);
    //     });
    // }

    // document.addEventListener("DOMContentLoaded", function() {
    //     cacheRepeaterTemplates();
    // });

    // // --------------------------------------------------
    // // Global click handler
    // // --------------------------------------------------
    // document.addEventListener("click", function(e) {
    //     if (e.target.classList.contains("remove-repeater-item")) {
    //         e.preventDefault();

    //         const item = e.target.closest(".repeater-item");
    //         const wrapper = item?.parentElement;

    //         if (item) {
    //             item.remove();
    //             renumberRepeaterItems(wrapper);
    //             setTimeout(initCK, 200);
    //         }
    //         return;
    //     }

    //     if (e.target.classList.contains("add-repeater-item")) {
    //         e.preventDefault();

    //         const section = e.target.dataset.section;
    //         const field = e.target.dataset.field;

    //         const wrapper = document.querySelector(`#repeater-${section}-${field}`);
    //         if (!wrapper) return;

    //         cacheRepeaterTemplates();

    //         let firstItem = wrapper.querySelector(".repeater-item");

    //         if (!firstItem && wrapper.dataset.template) {
    //             const temp = document.createElement("div");
    //             temp.innerHTML = wrapper.dataset.template.trim();
    //             firstItem = temp.querySelector(".repeater-item");
    //         }

    //         if (!firstItem) {
    //             alert("Repeater has no template.");
    //             return;
    //         }

    //         const index = wrapper.querySelectorAll(".repeater-item").length;
    //         const newItem = firstItem.cloneNode(true);

    //         // Remove CKEditor DOM
    //         newItem.querySelectorAll(".ck, .ck-reset, .ck-editor, .ck-content")
    //             .forEach(el => el.remove());

    //         // Remove preview links
    //         newItem.querySelectorAll("a").forEach(a => a.remove());

    //         // Rename + reset inputs
    //         newItem.querySelectorAll("[name]").forEach(inp => {

    //             // 🔥 FIX: replace ONLY repeater index
    //             inp.name = inp.name.replace(
    //                 new RegExp(`\\[${field}\\]\\[\\d+\\]`, 'g'),
    //                 `[${field}][${index}]`
    //             );

    //             if (inp.tagName === "INPUT") {

    //                 // ❗ Keep _old values
    //                 if (inp.type === "hidden" && inp.name.endsWith("_old")) {
    //                     return;
    //                 }

    //                 // Clear file inputs
    //                 if (inp.type === "file") {
    //                     inp.value = "";
    //                     return;
    //                 }

    //                 // Clear other inputs
    //                 inp.value = "";

    //             } else if (inp.tagName === "TEXTAREA") {

    //                 inp.value = "";

    //                 if (inp.classList.contains("editor")) {
    //                     inp.id = "ed_" + Date.now() + "_" + Math.random().toString(36).slice(2, 6);
    //                 }
    //             }
    //         });

    //         wrapper.appendChild(newItem);
    //         renumberRepeaterItems(wrapper);

    //         setTimeout(initCK, 350);
    //     }
    // });

    // document.addEventListener("click", function(e) {

    //     /* =========================================
    //        ADD NESTED REPEATER ITEM
    //     ========================================= */
    //     if (e.target.classList.contains("add-nested-repeater")) {
    //         e.preventDefault();

    //         const wrapper = e.target.closest(".nested-repeater");
    //         if (!wrapper) return;

    //         const repeaterPath = e.target.dataset.path; // e.g. sections[10][data][activities][0][list]
    //         if (!repeaterPath) return;

    //         const items = wrapper.querySelectorAll(".nested-repeater-item");
    //         if (!items.length) return;

    //         const template = items[0];
    //         const newItem = template.cloneNode(true);
    //         const newIndex = items.length;

    //         // Update heading
    //         const h6 = newItem.querySelector("h6");
    //         if (h6) h6.textContent = "Item " + (newIndex + 1);

    //         newItem.querySelectorAll("[name]").forEach(input => {

    //             const pattern = new RegExp(
    //                 repeaterPath.replace(/\[/g, '\\[').replace(/\]/g, '\\]') + '\\[(\\d+)\\]'
    //             );

    //             input.name = input.name.replace(pattern, repeaterPath + `[${newIndex}]`);

    //             // Clear value
    //             input.value = '';
    //         });

    //         wrapper.insertBefore(newItem, e.target);
    //     }

    //     if (e.target.classList.contains("remove-nested-repeater")) {
    //         e.preventDefault();

    //         const item = e.target.closest(".nested-repeater-item");
    //         const wrapper = item?.closest(".nested-repeater");

    //         if (!item || !wrapper) return;

    //         item.remove();

    //         // 🔁 Renumber remaining items
    //         wrapper.querySelectorAll(".nested-repeater-item").forEach((el, i) => {

    //             const h6 = el.querySelector("h6");
    //             if (h6) h6.textContent = "Item " + (i + 1);

    //             el.querySelectorAll("[name]").forEach(input => {
    //                 input.name = input.name.replace(
    //                     /\[\d+\](?=[^\[\]]*$)/,
    //                     `[${i}]`
    //                 );
    //             });
    //         });
    //     }

    // });
</script>

<script>
    /* ======================================================
   CACHE REPEATER TEMPLATE
====================================================== */
    function cacheRepeaterTemplates() {
        document.querySelectorAll('[id^="repeater-"]').forEach(wrapper => {
            if (!wrapper.dataset.template) {
                const first = wrapper.querySelector('.repeater-item');
                if (first) {
                    wrapper.dataset.template = first.outerHTML;
                }
            }
        });
    }

    /* ======================================================
       RENUMBER ITEMS
    ====================================================== */
    function renumberRepeaterItems(wrapper) {
        if (!wrapper) return;

        wrapper.querySelectorAll('.repeater-item').forEach((item, i) => {
            const h6 = item.querySelector('h6');
            if (h6) h6.textContent = 'Item ' + (i + 1);
        });
    }

    /* ======================================================
       INIT ON LOAD
    ====================================================== */
    document.addEventListener("DOMContentLoaded", function() {
        cacheRepeaterTemplates();
    });

    /* ======================================================
       GLOBAL CLICK HANDLER
    ====================================================== */
    document.addEventListener("click", function(e) {

        /* =========================
           REMOVE REPEATER ITEM
        ========================== */
        if (e.target.classList.contains("remove-repeater-item")) {
            e.preventDefault();

            const item = e.target.closest(".repeater-item");
            const wrapper = item?.parentElement;

            if (item) {
                item.remove();
                renumberRepeaterItems(wrapper);
            }
            return;
        }

        /* =========================
           ADD REPEATER ITEM
        ========================== */
        if (e.target.classList.contains("add-repeater-item")) {
            e.preventDefault();

            const section = e.target.dataset.section;
            const field = e.target.dataset.field;
            const wrapper = document.querySelector(`#repeater-${section}-${field}`);
            if (!wrapper) return;

            cacheRepeaterTemplates();

            const temp = document.createElement("div");
            temp.innerHTML = wrapper.dataset.template.trim();
            const newItem = temp.querySelector(".repeater-item");

            const index = wrapper.querySelectorAll(".repeater-item").length;

            /* --------------------------------------------------
               FIX NAME INDEX + CLEAR VALUES
            -------------------------------------------------- */
            newItem.querySelectorAll("[name]").forEach(inp => {

                inp.name = inp.name.replace(
                    new RegExp(`\\[${field}\\]\\[\\d+\\]`, 'g'),
                    `[${field}][${index}]`
                );

                if (inp.tagName === "INPUT") {
                    if (inp.type === "hidden" && inp.name.endsWith("_old")) return;
                    inp.value = "";
                }

                if (inp.tagName === "TEXTAREA") {
                    inp.value = "";
                }
            });

            /* --------------------------------------------------
               🔥 RESET SUMMERNOTE SAFELY
            -------------------------------------------------- */
            $(newItem).find('textarea.editor').each(function() {

                if ($(this).next('.note-editor').length) {
                    $(this).summernote('destroy');
                }

                $(this).val('');
            });

            /* --------------------------------------------------
               APPEND + RENUMBER + INIT EDITOR
            -------------------------------------------------- */
            wrapper.appendChild(newItem);
            renumberRepeaterItems(wrapper);

            // Init summernote ONLY inside new item
            setTimeout(() => {
                initSummernote(newItem);
            }, 150);
        }

        /* =========================
           ADD NESTED REPEATER
        ========================== */
        if (e.target.classList.contains("add-nested-repeater")) {
            e.preventDefault();

            const wrapper = e.target.closest(".nested-repeater");
            const repeaterPath = e.target.dataset.path;
            if (!wrapper || !repeaterPath) return;

            const items = wrapper.querySelectorAll(".nested-repeater-item");
            if (!items.length) return;

            const template = items[0];
            const newItem = template.cloneNode(true);
            const newIndex = items.length;

            newItem.querySelectorAll("[name]").forEach(input => {
                const pattern = new RegExp(
                    repeaterPath.replace(/\[/g, '\\[').replace(/\]/g, '\\]') + '\\[(\\d+)\\]'
                );
                input.name = input.name.replace(pattern, repeaterPath + `[${newIndex}]`);
                input.value = '';
            });

            const h6 = newItem.querySelector("h6");
            if (h6) h6.textContent = "Item " + (newIndex + 1);

            wrapper.insertBefore(newItem, e.target);
        }

        /* =========================
           REMOVE NESTED REPEATER
        ========================== */
        if (e.target.classList.contains("remove-nested-repeater")) {
            e.preventDefault();

            const item = e.target.closest(".nested-repeater-item");
            const wrapper = item?.closest(".nested-repeater");
            if (!item || !wrapper) return;

            item.remove();

            wrapper.querySelectorAll(".nested-repeater-item").forEach((el, i) => {
                const h6 = el.querySelector("h6");
                if (h6) h6.textContent = "Item " + (i + 1);

                el.querySelectorAll("[name]").forEach(input => {
                    input.name = input.name.replace(
                        /\[\d+\](?=[^\[\]]*$)/,
                        `[${i}]`
                    );
                });
            });
        }
    });
</script>



<script>
    document.addEventListener("click", function(e) {
        if (e.target.classList.contains("delete-gallery-image")) {

            let box = e.target.closest(".gallery-item");

            // Remove the hidden keep[] input so controller knows it is removed
            let keepInput = box.querySelector('input[type="hidden"]');
            if (keepInput) keepInput.remove();

            // Remove image preview
            box.remove();
        }
    });


    $(document).ready(function() {

        // ---------------------------
        // Auto Search Handler
        // ---------------------------
        $('.autoSearch').each(function() {
            let input = $(this);
            let form = input.closest('form');

            let delay = input.data('delay') || 500;
            let minLen = input.data('min') || 3;
            let typingTimer;

            input.on('keyup', function() {
                clearTimeout(typingTimer);

                typingTimer = setTimeout(function() {
                    let value = input.val().trim();

                    if (value.length >= minLen) {
                        form.submit();
                    } else if (value.length === 0) {
                        window.location.href = window.location.pathname;
                    }

                }, delay);
            });
        });

        // ---------------------------
        // Clear Icon Show/Hide
        // ---------------------------
        function toggleClearIcon() {
            $('.search-wrapper').each(function() {
                let wrapper = $(this);
                let input = wrapper.find('.autoSearch');
                let clearBtn = wrapper.find('.clear-search');

                if (input.val().length > 0) {
                    clearBtn.removeClass('d-none'); // show
                } else {
                    clearBtn.addClass('d-none'); // hide
                }
            });
        }

        toggleClearIcon();

        $('.autoSearch').on('input', toggleClearIcon);

        // ---------------------------
        // On clear click
        // ---------------------------
        $('.clear-search').on('click', function() {
            let wrapper = $(this).closest('.search-wrapper');
            let input = wrapper.find('.autoSearch');

            input.val('');
            toggleClearIcon();

            window.location.href = window.location.pathname;
        });

    });

    $(document).ready(function() {

        $('.duplicateBtn').on('click', function() {

            $('#duplicateModal').modal('show');

            let itemId = $(this).data('id');
            let itemName = $(this).data('name');
            let actionUrl = $(this).data('action');

            // set original name
            $('#duplicateOriginalName').val(itemName);

            // clear new name input
            $('#duplicateNewName').val('');

            // set form action based on module (campus/program/page/etc)
            $('#duplicateForm').attr('action', actionUrl + '/' + itemId + '/duplicate');
        });

    });
</script>

<script>
    $(document).ready(function() {

        let deleteUrl = '';
        let $deleteRow = null;

        // Open delete modal
        $(document).on('click', '.deleteBtn', function() {

            let itemId = $(this).data('id');
            let itemName = $(this).data('name');
            let action = $(this).data('action');

            // Build delete URL
            deleteUrl = action + '/' + itemId;

            // Store table row for UI update
            $deleteRow = $(this).closest('tr');

            // Set item name in modal
            $('#deleteItemName').text(itemName);

            // Show modal
            $('#deleteModal').modal('show');
        });

        // Confirm delete
        $('#confirmDeleteBtn').on('click', function() {

            if (!deleteUrl) return;

            $.ajax({
                url: deleteUrl,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(res) {

                    if (res.success) {
                        showToast('success', res.message || 'Deleted successfully');
                        $('#deleteModal').modal('hide');
                        // if ($deleteRow) {
                        //     $deleteRow.remove();
                        // }
                        // Alternatively, reload the page to reflect changes
                        location.reload();
                    } else {
                        showToast('error', res.message || 'Delete failed');
                    }
                },
                error: function() {
                    alert('Delete request failed');
                }
            });
        });

        // Cleanup when modal closes
        $('#deleteModal').on('hidden.bs.modal', function() {
            deleteUrl = '';
            $deleteRow = null;
            $('#deleteItemName').text('?');
        });

    });
</script>
