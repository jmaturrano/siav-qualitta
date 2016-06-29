$(document).ready(function(){

	if($('#form_tipomatricula').length > 0){
		var tipo_vista = ($('#form_tipomatricula').attr('tipo_vista')).toUpperCase();
		if(tipo_vista === 'VER'){
			$('#form_tipomatricula').find('input').attr('disabled', 'disabled');
			$('#form_tipomatricula').find('select').attr('disabled', 'disabled');
		}
	}

	$(document).on('click', '.btn_guardar', function(e){
		$('#form_tipomatricula').submit();
		e.preventDefault();
	});

});