var $ = jQuery.noConflict();

/**
 * Main admin JS
 */
$(document).ready(function(){

    /**
     * ===================================================================
     * TRANSLATIONS SECTION
     * ===================================================================
     */

    /**
     *
     * @param string
     * @returns {*}
     */
    const useTranslation = (string) => {

        if(typeof document.adminjs === 'undefined'){
            return string;
        }

        const translations = document.adminjs.translations;

        if(typeof translations === 'undefined'){
            return string;
        }

        if(typeof translations[string] !== 'undefined' && translations[string] !== ''){
            return translations[string]
                .replace(/&amp;/g, "&")
                .replace(/&lt;/g, "<")
                .replace(/&gt;/g, ">")
                .replace(/&quot;/g, '"')
                .replace(/&#039;/g, "'")
                ;
        }

        return string;
    };

    /**
     * Fetch all translations
     *
     * @returns {Promise<Response>}
     */
    const fetchLanguages = () => {

        const baseAjaxUrl = (typeof ajaxurl === 'string') ? ajaxurl : '/wp-admin/admin-ajax.php';

        let formData;
        formData = new FormData();
        formData.append('action', 'languagesAction');

        return fetch(baseAjaxUrl, {
            method: 'POST',
            body: formData
        });
    };

    fetchLanguages()
        .then((response) => response.json())
        .then((translations) => {
            document.adminjs = {
                translations: translations
            };
        })
        .catch((err) => {
            console.error("Something went wrong!", err);
        });

    /**
     * Option pages
     */
    $('.acpt-toggle-indicator').on('click', function () {
        const target = $(this).data('target');
        $(`#${target}`).toggleClass('closed');
    });

    /**
     * Sortable functions
     * @see https://github.com/lukasoppermann/html5sortable
     */
    const initSortable = () => {

        if(typeof sortable !== 'undefined'){

            // gallery
            if($('.gallery-preview').length){

                sortable('.gallery-preview', {
                    acceptFrom: '.gallery-preview',
                    forcePlaceholderSize: true,
                    items: '.image',
                    placeholderClass: 'ph-class',
                    hoverClass: 'hover',
                    copy: false
                });

                // sortable gallery items feature
                $('.gallery-preview').each(function(index) {
                    sortable('.gallery-preview')[index].addEventListener('sortupdate', function(e) {

                        const sortedItems = e.detail.destination.items;
                        let sortedIndexArray = [];

                        sortedItems.map((sortedItem)=>{
                            sortedIndexArray.push($(sortedItem).data('index'));
                        });

                        const $imageWrapper = $(this);
                        const $target = $imageWrapper.data('target');
                        const $placeholder = $('#'+$target+'_copy');
                        const $inputWrapper = $placeholder.next( '.inputs-wrapper' );

                        // update input readonly && update input hidden
                        const $saveValues = $placeholder.val().split(',');
                        const $savedInputs = $inputWrapper.children('input');
                        let $sortedValues = [];
                        let $sortedInputs = [];

                        sortedIndexArray.map((sortedIndex) => {
                            $sortedValues.push($saveValues[sortedIndex]);
                            $savedInputs.each(function () {
                                if($(this).data('index') === sortedIndex){
                                    $sortedInputs.push($(this));
                                }
                            });
                        });

                        $placeholder.val($sortedValues.join(','));
                        $inputWrapper.html($sortedInputs);
                    });
                });

                sortable('.gallery-preview', 'reload');
            }

            // repeater fields
            if($('.acpt-sortable').length){

                sortable('.acpt-sortable', {
                    acceptFrom: '.acpt-sortable',
                    forcePlaceholderSize: true,
                    items: '.sortable-li',
                    handle: '.handle',
                    placeholderClass: 'ph-class',
                    hoverClass: 'hover',
                    copy: false
                });

                sortable('.acpt-sortable', 'reload');
            }

            // nested flexible fields
            $('.acpt-nested-sortable').each(function() {
                const nestedSortableListId = $(this).attr('id');

                sortable('#'+nestedSortableListId, {
                    acceptFrom: '#'+nestedSortableListId,
                    forcePlaceholderSize: true,
                    items: '.sortable-li',
                    handle: '.handle',
                    placeholderClass: 'ph-class',
                    hoverClass: 'hover',
                    copy: false
                });

                sortable('#'+nestedSortableListId, 'reload');
            });
        }
    };

    /**
     * Grouped element handling
     */
    $('body').on('click', '#add-grouped-element', function(e) {

        e.preventDefault();

        const $this = $(this);
        const id = $this.data('group-id');
        const mediaType = $this.data('media-type');
        const list = $this.prev('.acpt-sortable');
        const index = $this.prev("ul.acpt-sortable").find("li").length;
        const noRecordsMessageDiv = $('[data-message-id="'+id+'"]');

        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                "action": "generateGroupedFieldsAction",
                "data": JSON.stringify({"id": id, "mediaType": mediaType, "index": index}),
            },
            success: function(data) {
                list.append(data.fields);
                initSortable();
                initSelectize();
                initCodeMirror();
                initColorPicker();
                initDateRangePicker();

                if(noRecordsMessageDiv){
                    noRecordsMessageDiv.remove();
                }

                // init TinyMCE on last wp-editor element
                const wpEditors = document.querySelectorAll('textarea.wp-editor-area');
                if(wpEditors && wpEditors.length > 0){
                    initTinyMCE(wpEditors[wpEditors.length-1].id);
                }
            },
            dataType: 'json'
        });
    });

    $('body').on('click', 'a.remove-grouped-element', function(e) {

        e.preventDefault();

        const $this = $(this);
        const id = $this.data('target-id');
        const element = $this.data('element');
        const elements = $this.data('elements');
        const $target = $('#'+id);
        const parentList = $target.parent();
        const parentListId = parentList.attr('id');
        $target.remove();

        if(parentList.find('li').length === 0){
            const warningMessage = useTranslation(`No ${elements} saved, generate the first one clicking on "Add ${element}" button`);
            const warningElement = `<p data-message-id="${parentListId}" class="update-nag notice notice-warning inline no-records">${warningMessage}</p>`;
            $('#'+parentListId).html('').append(warningElement);
        }
    });

    /**
     * Flexible content element handling
     */

    // Add block button
    $('body').on('click', '.acpt_add_flexible_btn', function(e) {

        e.preventDefault();

        const $this = $(this);
        const list = $this.next('.acpt_flexible_block_items');

        ($this.hasClass('active')) ? $this.removeClass('active') : $this.addClass('active');
        (list.hasClass('active')) ? list.removeClass('active') : list.addClass('active');
    });

    document.addEventListener("click", function(evt) {

        const targetEl = evt.target;
        const showAddBlockMenu = targetEl.classList.contains('acpt_flexible_block_item') || targetEl.classList.contains('acpt_add_flexible_btn') || targetEl.classList.contains('acpt_add_flexible_btn_label');

        if(showAddBlockMenu === false){
            $('.acpt_flexible_block_items').removeClass('active');
            $('.acpt_add_flexible_btn').removeClass('active');
        }
    });

    // add block
    $('body').on('click', '.acpt_flexible_block_items > li', function(e) {

        e.preventDefault();

        const $this = $(this);
        const dropdownList = $this.parent();
        const blockId = $this.data('value');
        const mediaType = $this.data('media-type');
        const fieldId = $this.data('field-id');
        const blockList = $("ul#"+fieldId);
        const blockListLength = blockList.find("li.acpt_blocks_list_item").length;
        const minBlocks = blockList.data('min-blocks');
        const maxBlocks = blockList.data('max-blocks');
        const button = blockList.next(".acpt_add_flexible_block").find("button");
        const noRecordsMessageDiv = $('[data-message-id="'+fieldId+'"]');

        const newBlocksAllowed = () => {
            if(typeof maxBlocks === 'undefined'){
                return true;
            }

            return blockListLength < maxBlocks;
        };

        if(newBlocksAllowed()){
            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    "action": "generateFlexibleBlockAction",
                    "data": JSON.stringify({
                        "blockId": blockId,
                        "mediaType": mediaType,
                        "index": blockListLength
                    }),
                },
                success: function(data) {
                    blockList.append(data.block);

                    if((blockListLength+1) >= maxBlocks){
                        button.attr("disabled", true);
                    }

                    initSortable();
                    initSelectize();
                    initCodeMirror();
                    initColorPicker();
                    initDateRangePicker();

                    if(noRecordsMessageDiv){
                        noRecordsMessageDiv.remove();
                    }

                    // init TinyMCE on last wp-editor element
                    const wpEditors = document.querySelectorAll('textarea.wp-editor-area');
                    if(wpEditors && wpEditors.length > 0){
                        initTinyMCE(wpEditors[wpEditors.length-1].id);
                    }
                },
                dataType: 'json'
            });
        }

        dropdownList.removeClass('active');
    });

    // add element inside a block
    $('body').on('click', '.acpt_add_flexible_element_btn', function(e){
        e.preventDefault();

        const $this = $(this);
        const blockId = $this.data('group-id');
        const mediaType = $this.data('media-type');
        const index = $this.data('index');
        const list = $("#block-elements-"+blockId+ '-' + index);
        const elementCount = list.find('li').length;
        const noRecordsMessageDiv = $('[data-message-id="block-elements-'+blockId+ '-' + index+'"]');

        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                "action": "generateFlexibleGroupedFieldsAction",
                "data": JSON.stringify({"blockId": blockId, "mediaType": mediaType, "elementIndex": elementCount, "blockIndex": index}),
            },
            success: function(data) {
                list.append(data.fields);
                initSortable();
                initSelectize();
                initCodeMirror();
                initColorPicker();
                initDateRangePicker();

                if(noRecordsMessageDiv){
                    noRecordsMessageDiv.remove();
                }

                // init TinyMCE on last wp-editor element
                const wpEditors = document.querySelectorAll('textarea.wp-editor-area');
                if(wpEditors && wpEditors.length > 0){
                    initTinyMCE(wpEditors[wpEditors.length-1].id);
                }
            },
            dataType: 'json'
        });
    });

    // toggle block visibility
    $('body').on('click', '.acpt_blocks_list_item_toggle_visibility', function(e){
        e.preventDefault();

        const $this = $(this);
        const blockId = $this.data('target-id');
        const targetList = $('*[data-parent-id='+blockId+']');
        const addElementButton = $('*[data-block-id='+blockId+']');
        const parentTitleElement = $this.parent().parent();

        if($this.hasClass('reverse')){ $this.removeClass('reverse'); } else { $this.addClass('reverse'); }
        if(parentTitleElement.hasClass('no-margin')){ parentTitleElement.removeClass('no-margin'); } else { parentTitleElement.addClass('no-margin'); }
        if(targetList.hasClass('hidden')){ targetList.removeClass('hidden'); } else { targetList.addClass('hidden'); }
        if(addElementButton.hasClass('hidden')){ addElementButton.removeClass('hidden'); } else { addElementButton.addClass('hidden'); }
    });

    // delete block
    $('body').on('click', '.acpt_blocks_list_item_delete', function(e){
        e.preventDefault();

        const $this = $(this);
        const blockId = $this.data('target-id');
        const block = $('#'+blockId);
        const blockList = block.parent();
        const blockListId = blockList.attr('id');
        const blockListLength = blockList.find("li.acpt_blocks_list_item").length;
        const minBlocks = blockList.data('min-blocks');
        const maxBlocks = blockList.data('max-blocks');
        const button = blockList.next(".acpt_add_flexible_block").find("button");

        const newBlocksAllowed = () => {
            if(typeof maxBlocks === 'undefined'){
                return true;
            }

            return blockListLength >= maxBlocks;
        };

        if(newBlocksAllowed()){
            button.attr("disabled", false);
        }

        block.remove();

        if(blockListLength === 1){
            const warningMessage = useTranslation(`No blocks saved, generate the first one clicking on "Add block" button`);
            const warningElement = `<p data-message-id="${blockListId}" class="update-nag notice notice-warning inline no-records">${warningMessage}</p>`;

            blockList.append(warningElement);
        }
    });

    /**
     * List content element handling
     */
    $('body').on('click', '#list-add-element', function(e) {

        e.preventDefault();

        const $this = $(this);
        const $listWrapper = $this.prev('.list-wrapper');
        const $lastElement = $listWrapper.children('.list-element').last();
        const $nextId = $listWrapper.children('.list-element').length;
        const $baseId = $listWrapper.parent().find('input[type=hidden]:first-child').val();

        let $cloned = $lastElement.find('input').clone();
        $cloned.val('');
        $cloned.prop('id', $baseId  + '_' + $nextId);

        $listWrapper.append('<div class="list-element">' + $cloned.prop('outerHTML') + '<a class="list-remove-element" data-target-id="'+$cloned.prop('id')+'" href="#">'+useTranslation('Remove element')+'</a></div>');
    });

    /**
     * Remove list element
     */
    $('body').on('click', 'a.list-remove-element', function(e) {

        e.preventDefault();

        const $this = $(this);
        const $targetId = $this.data('target-id');
        const $target = $('#'+$targetId);

        $target.parent().remove();
        $this.remove();
    });

    /**
     * Post relationships handling
     */
    $('body').on('change', '.post-relationship', function(e) {

        e.preventDefault();

        let $val = $( this ).val();

        if(Array.isArray($val)){
            $val = $val.join(',');
        }

        $("#inversedBy").val($val);
    });

    /**
     * Single file delete
     */
    $('body').on('click', '.file-delete-btn', function(e) {
        const $this = $( this );
        e.preventDefault();

        const target = $this.data('target-id');
        $('#'+target).val('');
        $('#'+target+'_id').val('');
        $this.parent('div').next( '.file-preview' ).html( '' );
    });

    /**
     * Upload file button
     */
    $('body').on('click', '.upload-file-btn', function(e) {

        const $this = $( this );
        const input = $this.prev( 'input' );
        const inputId = input.prev( 'input' );
        const parentDiv = $this.parent('div');
        e.preventDefault();

        if (!wp || !wp.media) {
            alert(useTranslation('The media gallery is not available. You must admin_enqueue this function: wp_enqueue_media()'));
            return;
        }

        const file = wp.media( {
            title: 'Upload a File',
            library: {
                type: [ 'application' ]
            },
            multiple: false
        });

        file.on('open', function (e) {
            if(inputId.val() !== ''){
                let selection = file.state().get('selection');
                let attachment = wp.media.attachment(inputId.val());
                selection.add(attachment);
            }
        });

        file.on( 'select', function ( e ) {
            const uploaded_file = file.state().get( 'selection' ).first();
            const file_url = uploaded_file.toJSON().url;
            const file_id = uploaded_file.toJSON().id;

            inputId.val(file_id);
            input.val( file_url );
            parentDiv.next( '.file-preview' ).html( '<div class="file"><div class="preview-file"><span>Preview</span><a target="_blank" href="'+file_url+'">'+useTranslation("Download")+'</a></div></div>' );
        } );

        file.open();
    });

    /**
     * Delete all images button
     */
    $('body').on('click', '.upload-delete-btn', function(e) {
        const $this = $( this );
        e.preventDefault();
        e.stopPropagation();

        const target = $this.data('target-id');
        $('#'+target).val('');
        $('#'+target+'_copy').val('');
        $('#'+target+'_id').val('');

        $this.prev('.button').prev( '.inputs-wrapper' ).html('');
        $this.parent('div').next( '.image-preview' ).html( '' );
        $this.addClass('hidden');
    });

    /**
     * Single image upload
     */
    $('body').on('click', '.upload-image-btn', function(e) {
        const $this = $( this );
        const input = $this.prev( 'input' );
        const inputId = input.prev( 'input' );
        const parentDiv = $this.parent('div');

        e.preventDefault();
        e.stopPropagation();

        if (!wp || !wp.media) {
            alert(useTranslation('The media gallery is not available. You must admin_enqueue this function: wp_enqueue_media()'));
            return;
        }

        const image = wp.media( {
            title: useTranslation('Upload an Image'),
            library: {
                type: [ 'image' ]
            },
            multiple: false
        });

        image.on('open', function (e) {
            if(inputId.val() !== ''){
                let selection = image.state().get('selection');
                let attachment = wp.media.attachment(inputId.val());
                selection.add(attachment);
            }
        });

        image.on( 'select', function ( e ) {
            const uploaded_image = image.state().get( 'selection' ).first();
            const image_url = uploaded_image.toJSON().url;
            const image_id = uploaded_image.toJSON().id;
            const image_name = uploaded_image.toJSON().name;

            inputId.val(image_id);
            input.val(image_url);
            parentDiv.next( '.image-preview' ).html( '<div class="image"><img src="'+image_url+'" alt="'+image_name+'"/></div>' );
        } );

        image.open();
    });

    /**
     * Upload video button
     */
    $('body').on('click', '.upload-video-btn', function(e) {
        const $this = $( this );
        const input = $this.prev( 'input' );
        const inputId = input.prev( 'input' );
        const parentDiv = $this.parent('div');

        e.preventDefault();
        e.stopPropagation();

        if (!wp || !wp.media) {
            alert(useTranslation('The media gallery is not available. You must admin_enqueue this function: wp_enqueue_media()'));
            return;
        }

        const video = wp.media( {
            title: useTranslation('Upload a Video'),
            library: {
                type: [ 'video' ]
            },
            multiple: false
        });

        video.on('open', function (e) {
            if(inputId.val() !== ''){
                let selection = video.state().get('selection');
                let attachment = wp.media.attachment(inputId.val());
                selection.add(attachment);
            }
        });

        video.on( 'select', function ( e ) {
            const uploaded_video = video.state().get( 'selection' ).first();
            const video_url = uploaded_video.toJSON().url;
            const video_id = uploaded_video.toJSON().id;

            inputId.val(video_id);
            input.val(video_url);
            parentDiv.next( '.image-preview' ).html( '<div class="image"><video controls><source src="'+video_url+'" type="video/mp4"></video></div>' );
        } );

        video.open();
    });

    /**
     * Gallery upload
     */
    $('body').on('click', '.upload-gallery-btn', function(e) {
        const $this = $( this );
        const $inputWrapper = $this.prev( '.inputs-wrapper' );
        const $inputIds = $inputWrapper.prev( 'input' ).prev( 'input' );
        const $target = $inputWrapper.data('target');
        const $targetCopy = $inputWrapper.data('target-copy');
        const $placeholder = $('#'+$target+'_copy');
        e.preventDefault();
        e.stopPropagation();

        if (!wp || !wp.media) {
            alert(useTranslation('The media gallery is not available. You must admin_enqueue this function: wp_enqueue_media()'));
            return;
        }

        const gallery = wp.media( {
            title: useTranslation('Select images'),
            library: {
                type: [ 'image' ]
            },
            multiple: true
        });

        gallery.on('open', function (e) {
            if($inputIds.val() !== ''){
                let attachments = [];
                let selection = gallery.state().get('selection');
                $inputIds.val().split(',').forEach((id)=>{
                    attachments.push(wp.media.attachment(id));
                });

                selection.add(attachments);
            }
        });

        gallery.on( 'select', function ( e ) {

            const imageIds = [];
            const imageUrls = [];
            const imageNames = [];

            gallery.state().get( 'selection' ).map(
                function ( attachment ) {
                    attachment.toJSON();
                    imageIds.push(attachment.attributes.id);
                    imageUrls.push(attachment.attributes.url);
                    imageNames.push(attachment.attributes.name);
                } );

            const imagesUrls = [];
            $inputWrapper.html('');

            imageUrls.map((imageUrl, index) => {

                const targetToReplace = ($targetCopy) ? $targetCopy : $target;

                $inputWrapper.append('<input name="'+targetToReplace+'[]" type="hidden" data-index="'+index+'" value="'+imageUrl+'">');
                imagesUrls.push(imageUrl);
            });

            let preview = '';

            if(imageUrls.length > 0){
                $this.next('button').removeClass('hidden');
            }

            imageUrls.map((imageUrl, index)=> {
                preview += '<div class="image" data-index="'+index+'"><img src="'+imageUrl+'" alt="'+imageNames[index]+'"/><div><a class="delete-gallery-img-btn" data-index="'+index+'" href="#">'+useTranslation("Delete")+'</a></div></div>';
            });

            $this.parent('div').next( '.image-preview' ).html( preview );
            $placeholder.val(imagesUrls.join(','));
            $inputIds.val(imageIds.join(','));
        } );

        gallery.open();
    });

    /**
     * Delete single gallery item
     */
    $('body').on('click', '.delete-gallery-img-btn', function(e) {
        const $this = $( this );
        e.preventDefault();
        e.stopPropagation();

        const $index = $this.data('index');
        const $image = $this.parent().parent();
        const $imageWrapper = $image.parent();
        const $target = $imageWrapper.data('target');
        const $inputIds = $('#'+$target+'_id');
        const $placeholder = $('#'+$target+'_copy');
        const $inputWrapper = $placeholder.next( '.inputs-wrapper' );

        // update input readonly
        const $saveValues = $placeholder.val().split(',');
        $saveValues.splice($index, 1);
        $placeholder.val($saveValues.join(','));

        // update input hidden
        $inputWrapper.children('input').each(function () {
            const $childIndex = $(this).data('index');

            if($childIndex === $index){
                $(this).remove();
            }
        });

        // update ids
        const $newInputIdsArray = [];
        const $inputIdsArray = $inputIds.val().split(",");

        $inputIdsArray.forEach((id, index)=>{
            if(index !== $index){
                $newInputIdsArray.push(id);
            }
        });

        $inputIds.val($newInputIdsArray.join(","));

        // delete this image
        $image.remove();
    });

    /**
     * Coremirror
     * @see https://codemirror.net/docs/
     */
    const initCodeMirror = () => {
        if($('textarea.code').length ){
            $('textarea.code').each(function() {
                const id = '#'+ $( this ).attr('id');
                const wpEditor = wp.codeEditor.initialize($(id), {
                    indentUnit: 2,
                    tabSize: 2,
                    mode: 'text/html',
                    autoRefresh: true,
                });
                $(document).on('keyup', '.CodeMirror-code', function(){
                    $(id).html(wpEditor.codemirror.getValue());
                    $(id).trigger('change');
                });
            });
        }
    };

    /**
     * Toggle input
     */
    $('.wppd-ui-toggle').on( 'change', function () {
        const valId = $(this).attr('id');
        $('#'+valId).val(($(this).is(':checked')) ? 1 : 0 );
    });

    /**
     * Currency selector
     */
    $(".currency-selector").on("change", function () {

        const selected = $(this).find( "option:selected" );
        const amount = $(this).parent('div').prev();
        const symbol = amount.prev();

        symbol.text(selected.data("symbol"));
        amount.prop("placeholder", selected.data("placeholder"));
    });

    /**
     * selectize
     * @see https://selectize.dev/docs/api
     */
    const initSelectize = () => {
        if(jQuery().selectize) {

            const formatSelectizeItem = (item, escape) => {

                const relation_label_separator =  "<-------->";

                if(!item.text.includes(relation_label_separator)){
                    return `<div>${item.text}</div>`;
                }

                let explode = item.text.split(relation_label_separator);
                const thumbnail = explode[0];
                const cpt = explode[1];
                const label = explode[2];
                const thumbnailDiv = (thumbnail) ? `<div class="selectize-thumbnail"><img src="${thumbnail}" alt="${label}" width="50" /></div>` : `<div class="selectize-thumbnail"><span class="selectize-thumbnail-no-image"></span></div>`;

                return `<div class="selectize-item">${thumbnailDiv}<div class="selectize-details"><span class='acpt-badge'>${cpt}</span><span>${label}</span></div></div>`;
            };

            $('.acpt-select2').selectize({
                plugins: ["restore_on_backspace", "clear_button"],
                placeholder: '--Select--',
                render: {
                    option: function(option, escape) {
                        return formatSelectizeItem(option, escape);
                    },
                    item: function(item, escape) {
                        return formatSelectizeItem(item, escape);
                    }
                },
            });
        }
    };

    /**
     * Color picker
     */
    const initColorPicker = () => {
        $('.acpt-color-picker').wpColorPicker();
    };

    /**
     * Icon picker
     */
    const ICONIFY_API_ROOT = 'https://api.iconify.design/';

    $('body').on('click', '.acpt-icon-picker-button', function(e) {
        e.preventDefault();

        const $this = $(this);
        const targetId = $this.data('target-id');
        const targetModalId = targetId+'_modal';
        const targetModal = $('#'+targetModalId);

        (targetModal.hasClass('hidden')) ? targetModal.removeClass('hidden') : targetModal.addClass('hidden');
    });

    $('.acpt-icon-picker-delete').on('click', function (e) {
        e.preventDefault();

        const $this = $(this);
        const targetId = $this.data('target-id');

        $(`.acpt-icon-picker-preview[data-target-id=${targetId}]`).html('');
        $(`#${targetId}`).val('');
        $this.addClass('hidden');
    });

    $('body').on('click', '.acpt-icon-picker-provider', function (e) {

        const $this = $(this);
        ($this.hasClass('active')) ? $this.removeClass('active') : $this.addClass('active');

        let visibleProviders = [];
        $('.acpt-icon-picker-provider.active').each(function() {
            const provider =  $(this).data('value');
            visibleProviders.push(provider);
        });

        $('.acpt-icon-picker-icon').each(function () {
            const provider =  $(this).data('prefix');
            const $this = $(this);

            if(visibleProviders.length > 0){
                (visibleProviders.includes(provider)) ? $this.removeClass('hidden') : $this.addClass('hidden');
            } else {
                $this.removeClass('hidden');
            }
        });
    });

    $('body').on('input', '.acpt-icon-picker-search', function(e) {

        const $this = $(this);
        const search = e.target.value;
        const results = $this.next('.acpt-icon-picker-results');
        const targetId = results.data('target-id');

        if(search.length >= 3){
            $.ajax({
                type: 'GET',
                url: `${ICONIFY_API_ROOT}search?query=${search}&limit=96`,
                success: function(data) {
                    results.html('');

                    // create the filter by provider
                    if(data.collections){

                        const providers = Object.keys(data.collections).sort();
                        let providerFilter = `<div class="acpt-icon-picker-providers">`;

                        providers.forEach((provider) => {
                            if(data.collections[provider] && data.collections[provider]?.name){
                                providerFilter += `<div data-target-id="${targetId}" data-value="${provider}" class="acpt-icon-picker-provider">${data.collections[provider]?.name}</div>`;
                            }
                        });

                        providerFilter += `</div>`;

                        results.append(providerFilter);
                    }

                    // append icons
                    if(data.icons.length > 0){
                        data.icons.forEach((icon)=>{
                            const iconSplitted = icon.split(':');
                            const prefix = iconSplitted[0];
                            const iconName = iconSplitted[1];
                            const svgUrl = `${ICONIFY_API_ROOT}${prefix}/${iconName}.svg`;
                            results.append(`<div data-target-id="${targetId}" data-value="${icon}" data-prefix="${prefix}" class="acpt-icon-picker-icon" title="${icon}"><img src="${svgUrl}" width="32" height="32"></div>`);
                        });
                    } else {
                        results.append(`<div>${useTranslation("Sorry, no result match.")}</div>`);
                    }

                    const deleteButton = $(`.acpt-icon-picker-delete[data-target-id="${targetId}"]`);
                    deleteButton.removeClass('hidden');
                },
                error: function(error) {
                    console.error(error);
                    results.append(useTranslation("There was an error fetching icons, retry later."));
                },
            });
        }
    });

    $('body').on('click', '.acpt-icon-picker-icon', function(e) {
        e.preventDefault();

        const $this = $(this);
        const value = $this.data('value');
        const targetId = $this.data('target-id');
        const iconSplitted = value.split(':');
        const prefix = iconSplitted[0];
        const iconName = iconSplitted[1];
        const svgUrl = `${ICONIFY_API_ROOT}${prefix}/${iconName}.svg`;

        $.ajax({
            type: 'GET',
            url: svgUrl,
            success: function(data) {
               const svg = data.children[0].outerHTML;
                $(`.acpt-icon-picker-value[data-target-id="${targetId}"]`).val(svg);
                const targetModal = $('#'+targetId+'_modal');
                $('.acpt-icon-picker-preview[data-target-id="'+targetId+'"]').html(svg);
                (targetModal.hasClass('hidden')) ? targetModal.removeClass('hidden') : targetModal.addClass('hidden');
           },
            error: function(error) {
                console.error(error);

                results.append(useTranslation("There was an error fetching icons, retry later."));
            },
        });
    });

    $('body').on('click', '.close-acpt-icon-picker', function(e) {
        e.preventDefault();

        const $this = $(this);
        const targetModalId = $this.data('target-id');
        const targetModal = $('#'+targetModalId);

        (targetModal.hasClass('hidden')) ? targetModal.removeClass('hidden') : targetModal.addClass('hidden');
    });

    /**
     * Init DateRange picker
     */
    const initDateRangePicker = () => {

        const daterangepickerElement = $('.acpt-daterangepicker');

        if(typeof daterangepicker !== 'undefined' && typeof daterangepickerElement !== 'undefined'){
            const maxDate = daterangepickerElement.data('max-date');
            const minDate = daterangepickerElement.data('min-date');

            daterangepickerElement.daterangepicker({
                    opens: 'top',
                    startDate: maxDate,
                    endDate: minDate,
                    locale: {
                        format: 'YYYY-MM-DD'
                    }
                }
            );
        }
    };

    /**
     * init TinyMCE on dynamic generated children fields
     *
     * @param id
     */
    const initTinyMCE = (id) => {

        if(typeof tinymce === 'undefined'){
            console.log("include here");
        }

        tinymce.init({
            quicktags: false,
            mediaButtons: true,
        });
        tinyMCE.execCommand('mceAddEditor', false, id);
    };

    // init
    initSelectize();
    initCodeMirror();
    initColorPicker();
    initSortable();
});