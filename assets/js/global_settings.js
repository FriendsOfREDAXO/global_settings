var gs_visibleNotice;

function gs_checkConditionalFields(selectEl, activeIds, textIds) {
    var toggle = false;

    for ( var i = 0; i < activeIds.length; i++) {
        if (selectEl.value == activeIds[i]) {
            toggle = activeIds[i];
            break;
        }
    }

    if (toggle) {
        if (gs_visibleNotice) {
            toggleElement(gs_visibleNotice, 'none');
        }

        needle = new getObj('global-settings-field-params-notice-' + toggle);
        if (needle.obj) {
            toggleElement(needle.obj, '');
            gs_visibleNotice = needle.obj;
        }
    } else {
        if (gs_visibleNotice) {
            toggleElement(gs_visibleNotice, 'none');
        }
    }

    var show = 1;
    for ( var i = 0; i < textIds.length; i++) {
        if (selectEl.value == textIds[i]) {
            show = 0;
            break;
        }
    }

    jQuery(function($) {
        if (show == 1) {
            $("#rex-global-settings-field-feld-bearbeiten-erstellen-default").parent().parent().show();
        }else {
            $("#rex-global-settings-field-feld-bearbeiten-erstellen-default").parent().parent().hide();
        }
    });

};

$(document).on('rex:ready', function (event, container) {
    var disableSelect = function (chkbox) {
        var sibling = chkbox;
        while (sibling != null) {
            if (sibling.nodeType == 1 && sibling.tagName.toLowerCase() == "select") {
                $(sibling).prop('disabled', !chkbox.checked);
            }
            sibling = sibling.previousSibling;
        }
    };

    container.find("input[type=checkbox].rex-global-settings-checkbox").click(function () {
        disableSelect(this);
    }).each(function () {
        disableSelect(this);
    })

	$(".rex-global-settings-color-picker,.rex-color-picker").spectrum({
		preferredFormat: 'hex',
		showInput: true,
		allowEmpty:true
	});

	// codemirror fix as otherwise in combination with tabs codemirror is broken
	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
	  	$('.CodeMirror').each(function(i, el){
			el.CodeMirror.refresh();
		});
	});

	// _glob prefix check
	$('#rex-page-global-settings-fields form#rex-addon-editmode').submit(function(e) {
		if ($('#rex-global-settings-field-feld-bearbeiten-erstellen-name').val().substring(0, 5) == "glob_") {
			alert('Don\' use glob_ as prefix!');
			e.preventDefault();
		}
	});

	// set focus on first input
	$('#global-settings-name-field').focus();
});
