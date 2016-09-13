$(document).ready(function(){
	if($('#form_grupomatricula').length > 0){


		var tipo_vista = ($('#form_grupomatricula').attr('tipo_vista')).toUpperCase();
		if(tipo_vista === 'VER'){
			$('#form_grupomatricula').find('input').attr('disabled', 'disabled');
			$('#form_grupomatricula').find('select').attr('disabled', 'disabled');
			$('#form_grupomatricula').find('input[type=checkbox]').checkboxX('refresh');
			$('#form_grupomatricula').find('button').attr('disabled', 'disabled');
			$(".datepicker").datepicker('remove');
		}

	}

	$(document).on('click', '.btn_guardar', function(e){
        $('#form_grupomatricula').submit();
		e.preventDefault();
	});


});