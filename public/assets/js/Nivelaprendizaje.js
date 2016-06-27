$(document).ready(function(){
	if($('#form_nivelaprendizaje').length > 0){
		var tipo_vista = ($('#form_nivelaprendizaje').attr('tipo_vista')).toUpperCase();
		if(tipo_vista === 'VER'){
			$('#form_nivelaprendizaje').find('input').attr('disabled', 'disabled');
			$('#form_nivelaprendizaje').find('select').attr('disabled', 'disabled');
		}
	}

	$(document).on('click', '.btn_guardar', function(e){
		$('#form_nivelaprendizaje').submit();
		e.preventDefault();
	});

});