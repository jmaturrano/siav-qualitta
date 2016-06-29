$(document).ready(function(){
	if($('#form_modulosxcarrera').length > 0){
		var tipo_vista = ($('#form_modulosxcarrera').attr('tipo_vista')).toUpperCase();
		if(tipo_vista === 'VER'){
			$('#form_modulosxcarrera').find('input').attr('disabled', 'disabled');
			$('#form_modulosxcarrera').find('select').attr('disabled', 'disabled');
		}
	}

	$(document).on('click', '.btn_guardar', function(e){
		$('#form_modulosxcarrera').submit();
		e.preventDefault();
	});

});