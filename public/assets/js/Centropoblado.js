$(document).ready(function(){

	if($('#form_centropoblado').length > 0){
		if(($('#form_centropoblado').attr('tipo_vista')).toUpperCase() === 'VER'){
			$('#form_centropoblado').find('input').attr('disabled', 'disabled');
			$('#form_centropoblado').find('select').attr('disabled', 'disabled');
		}
		//$('#form_oficina').find('#ogru_id').attr('disabled', 'disabled');
	}

	$(document).on('click', '#btn_guardar', function(e){
		if($('#form_centropoblado').length > 0){
			$('#form_centropoblado').submit();
		}
		
		e.preventDefault();
	});

});