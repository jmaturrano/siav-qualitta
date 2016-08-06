$(document).ready(function(){

	if($('#form_aerodromo').length > 0){
		if(($('#form_aerodromo').attr('tipo_vista')).toUpperCase() === 'VER'){
			$('#form_aerodromo').find('input').attr('disabled', 'disabled');
			$('#form_aerodromo').find('select').attr('disabled', 'disabled');
		}
	}

	$(document).on('click', '.btn_guardar', function(e){
		if($('#form_aerodromo').length > 0){
			$('#form_aerodromo').submit();
		}
		e.preventDefault();
	});

});