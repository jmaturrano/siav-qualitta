$(document).ready(function(){

	if($('#form_tipoaeronave').length > 0){
		var tipo_vista = ($('#form_tipoaeronave').attr('tipo_vista')).toUpperCase();
		if(tipo_vista === 'VER'){
			$('#form_tipoaeronave').find('input').attr('disabled', 'disabled');
			$('#form_tipoaeronave').find('select').attr('disabled', 'disabled');
		}
	}

	$(document).on('click', '.btn_guardar', function(e){
		$('#form_tipoaeronave').submit();
		e.preventDefault();
	});

});