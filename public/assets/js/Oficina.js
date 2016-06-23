$(document).ready(function(){

	if($('#form_oficina').length > 0){
		if(($('#form_oficina').attr('tipo_vista')).toUpperCase() === 'VER'){
			$('#form_oficina').find('input').attr('disabled', 'disabled');
			$('#form_oficina').find('select').attr('disabled', 'disabled');
		}
	}

	$(document).on('click', '.btn_guardar', function(e){
		if($('#form_oficina').length > 0){
			$('#form_oficina').submit();
		}
		e.preventDefault();
	});

});