$(document).ready(function(){

	if($('#form_modeloaeronave').length > 0){
		var tipo_vista = ($('#form_modeloaeronave').attr('tipo_vista')).toUpperCase();
		if(tipo_vista === 'VER'){
			$('#form_modeloaeronave').find('input').attr('disabled', 'disabled');
			$('#form_modeloaeronave').find('select').attr('disabled', 'disabled');
		}
	}

	$(document).on('click', '.btn_guardar', function(e){
		$('#form_modeloaeronave').submit();
		e.preventDefault();
	});

});