$(document).ready(function(){

	if($('#form_distrito').length > 0){
		if(($('#form_distrito').attr('tipo_vista')).toUpperCase() === 'VER'){
			$('#form_distrito').find('input').attr('disabled', 'disabled');
			//$('#form_oficina').find('select').attr('disabled', 'disabled');
		}
		//$('#form_oficina').find('#ogru_id').attr('disabled', 'disabled');
	}

	$(document).on('click', '#btn_guardar', function(e){
		if($('#form_distrito').length > 0){
			$('#form_distrito').submit();
		}
		
		e.preventDefault();
	});

});