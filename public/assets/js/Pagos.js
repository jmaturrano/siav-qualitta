$(document).ready(function(){
	if($('#form_pagos').length > 0){

		var tipo_vista = ($('#form_pagos').attr('tipo_vista')).toUpperCase();
		// if(tipo_vista === 'VER'){
			$('#form_pagos').find('input').attr('disabled', 'disabled');
			$('#form_pagos').find('select').attr('disabled', 'disabled');
			$('#form_pagos').find('input[type=checkbox]').checkboxX('refresh');
			$('#form_pagos').find('button').attr('disabled', 'disabled');
			$(".datepicker").datepicker('remove');
		// }
		if(tipo_vista === 'EDITAR'){
			$('#emat_id').removeAttr('disabled');
		}
	}

	$(document).on('click', '.btn_guardar', function(e){

        bootbox.dialog({
            title: '<h3 class="dialog-alternativo"><span class=\"glyphicon glyphicon-floppy-save\"></span> Cambiar estado matrícula</h3>',
            message: "¿Está seguro de realizar esta acción?",
            onEscape: function(){
            	bootbox.hideAll();
            },
            animate: false,
            className: 'bootbox-custom',
            buttons: {
	            cancel: {
				    label: 'Cancelar',
				    className: "btn-default",
				    callback: function(){
				    	bootbox.hideAll();
				    }
	            },
	            confirm: {
				    label: 'Ok',
				    className: "btn-danger",
				    callback: function(){
				    	$('#form_pagos').submit();
				    }
	            }
            }
        });
		e.preventDefault();
	});



});