$(document).ready(function(){


	if($('#form_configuracion').length > 0){
		var tipo_vista = ($('#form_configuracion').attr('tipo_vista')).toUpperCase();
		if(tipo_vista === 'VER'){
			$('#form_configuracion').find('input').attr('disabled', 'disabled');
			$('#form_configuracion').find('select').attr('disabled', 'disabled');
			$(".datepicker").datepicker('remove');
		}

	}

	$(document).on('click', '.btn_guardar', function(e){
		$('#form_configuracion').submit();
		e.preventDefault();
	});


});