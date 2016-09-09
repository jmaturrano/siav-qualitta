$(document).ready(function(){

	if($('#form_provincia').length > 0){
		if(($('#form_provincia').attr('tipo_vista')).toUpperCase() === 'VER'){
			$('#form_provincia').find('input').attr('disabled', 'disabled');
			//$('#form_oficina').find('select').attr('disabled', 'disabled');
		}
		//$('#form_oficina').find('#ogru_id').attr('disabled', 'disabled');
	}

	$(document).on('click', '.btn_guardar', function(e){
		if($('#form_provincia').length > 0){
			$('#form_provincia').submit();
		}
		
		e.preventDefault();
	});

});