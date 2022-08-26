const MAX_UPLOAD_SIZE = 20971520;

const mediaFiles = {
    files: [],
    descriptions: [],
    size: 0
};

$(document).ready(function() {
    /**
     * GROUP TAB FUNCTIONALITIES
     */

    /**
     * A group tab is toggled. Group tabs have tabs in them as well
     */
    $('.group-tab-toggle').on('show.bs.tab', function() {
        var target = $($(this).attr('href'));
        target.find('.main-tab table tbody').html('<td colspan="3"><div class="text-center"><i class="fa-solid fa-spinner fa-spin"></i></div></td>');
        target.find('.nav-pills .active').removeClass('active');
        target.find('.main-tab-toggle').tab('show');
        target.find('.edit-form .edit-form-upload').removeAttr('required');
    });

    // edit form trigger button is clicked
    $('.edit-trigger-btn').click(function() {
        var tab = $(this).closest('.tab-pane').siblings('.edit-tab');
        tab.find('.edit-form').trigger('reset').removeAttr('data-edit-id');
        tab.find('.edit-form-img').css('background-image', '');
        var t = tab.find('.edit-form-title');
        t.text(t.attr('data-new-text'));
        tab.find('.edit-form-upload').attr('required', 'required');
        $(`.edit-tab-toggle[href="#${tab.attr('id')}"]`).tab('show');
    });

    // cancel edit form button is clicked
    $('.edit-form-cancel-btn').click(function() {
        // get parent group tab and toggle main tab
        $(this).closest('.tab-pane.group-tab').find('.main-tab-toggle').tab('show');
    })

    // upload a single image file
    $('.edit-form-upload').change(function() {
        var target = $(this).closest('form').find('.edit-form-img');
        var file = this.files[0];
        var $this = $(this);

        try {
            if (file.size > MAX_UPLOAD_SIZE) {
                throw new RangeError('File too big');
            }

            var src = URL.createObjectURL(file);

            if ($this.is('[data-show-media]')) {
                var file = file.type.indexOf('image/') !== -1 ? `<img src="${src}" class="img-fluid mx-0"/>` : `<video src="${src}" class="img-fluid mx-0" controls></video>`;
                $(target).html(file);
            } else {
                $(target).css('background-image', `url(${src})`)
            }
        } catch (error) {
            unsetFileInput($this);
            if (error instanceof RangeError || error instanceof TypeError) alert(error.message);
        }
    });

    $('.edit-form').on('reset', function() {
        if ($(this).find('.edit-form-upload[data-show-media]').length !== 0) $(this).find('.edit-form-img').html('');
    });

    // upload multiple files
    $('.multi-media-upload').change(function() {
        var files = this.files;

        if (mediaFiles.size >= MAX_UPLOAD_SIZE) {
            this.disabled = true;
            return;
        }

        var target = $(this).closest('form').find('.multi-media-output');

        for (let i = 0; i < files.length; ++i) {
            let f = files[i];
            let s = f.size;
            let t = f.type;

            try {
                // only image and video files allowed
                if (!(/^(image|video)\//.test(t))) throw new TypeError('One or more invalid file types skipped');

                // total max file size of 20MB
                if ((mediaFiles.size + s) > MAX_UPLOAD_SIZE) throw new RangeError('One or more files causing 20MB overflow were skipped');

                let id = mediaFiles.files.length;

                mediaFiles.size += s;
                mediaFiles.descriptions.push('');
                mediaFiles.files.push(f);

                let m = t.indexOf('image') !== -1 ? `<img src="${URL.createObjectURL(f)}" class="align-self-start mr-3 rounded">` : `<video src="${URL.createObjectURL(f)}" class="align-self-start mr-3 rounded" controls></video>`;

                target.append(`<div class="media mb-2" data-media-id="${id}">${m}<div 
					class="media-body"><div>
						<label class="mb-1 small text-muted">Description <span class="sr-only">media ${(id + 1)}</span></label>
						<input type="text" name="id" id="id${id}" class="form-control form-control-sm media-description-input" maxlength="100">
					</div>
					<div class="mt-2"><button type="button" class="btn btn-sm" data-media-delete>Delete media</button></div></div></div>`);
            } catch (error) {
                if (error instanceof TypeError || error instanceof RangeError) {
                    $(this).parents('form').find('.multi-media-error').html(error.message);
                    continue;
                } else {
                    console.error(error);
                    break;
                }
            }
        }
    });

    // update media description
    $('.media-description-input').on('input', function() {
        try {
            var id = parseInt($(this).closest('.media').attr('data-media-id'));
            if (id != id) throw new Error('media id not found');

            mediaFiles.descriptions[id] = this.value;
        } catch (error) {
            console.error(error);
        }
    });

    // a selected media file is deleted
    $('form').on('click', '[data-media-delete]', function() {
        try {
            var id = parseInt($(this).closest('.media').attr('data-media-id'));
            if (id != id) throw new Error('media id not found');

            var f = mediaFiles.files[id];
            console.log(mediaFiles, f);
            mediaFiles.files.splice(id, 1);
            mediaFiles.descriptions.splice(id, 1);
            mediaFiles.size -= f.size;

            $(this).parents('.media').remove();
            let m = $(this).parents('form').find('.multi-media-output .media');

            for (let i = 0; i < m.length; ++i) {
                $(m[i]).attr('data-media-id', i);
            }

            $(this).parents('form').find('.multi-media-error').text('');
            $(this).parents('form').find('.multi-media-upload').removeAttr('disabled');
        } catch (error) {
            alert('Something went wrong');
            console.error(error);
        }
    });

    $('#sideNavOpen').click(function() {
        $('#sideNavNav').addClass('show');
    });

    $('#sideNavClose').click(function() {
        $('#sideNavNav').removeClass('show');
    });
});

/**
 * Reset an input file element
 * @param {HTMLInputElement} $this 
 */
function unsetFileInput($this) {
    $this.wrap('<form class="file-fm">').closest('form').trigger('reset');
    $this.unwrap();
}

function activateNav($this) {
    // $('#sideNavNav .nav-link, #sideNavNav .nav-item').removeClass('active bg-white nav-link-prev nav-link-next')

    var parent = $this.parent();
    var prev = parent.prev();
    var next = parent.next();
    // :not(#sideUser)

    $this.addClass('active');
    prev.addClass('bg-white');
    prev.children('.nav-link').addClass('nav-link-prev');
    // parent.addClass('bg-white');
    next.addClass('bg-white');
    next.children('.nav-link').addClass('nav-link-next');
}

/**
 * Make a jQuery POST Ajax call using this ajax() method
 * @param {String} url URL to backend script
 * @param {FormData} data data to be sent to server
 * @param {Function} processData function to process data returned
 */
function ajax(url, data, processData, success = function() {}, error = function() {}, complete = function(){}) {
    $.ajax({
        type: 'POST',
        url: url,
        data: data,
        contentType: false,
        cache: false,
        processData: false,
        complete: function(o, s) {
            var d = o.responseText;

            complete();

            if (s !== 'success') throw new Error(`ajax call failed; status: ${s}`);
            processData(d, success, error);
        }
    })
}