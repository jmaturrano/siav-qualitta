$(document).ready(function(){

	if($('#form_tipodireccion').length > 0){
		var tipo_vista = ($('#form_tipodireccion').attr('tipo_vista')).toUpperCase();
		if(tipo_vista === 'VER'){
			$('#form_tipodireccion').find('input').attr('disabled', 'disabled');
			$('#form_tipodireccion').find('select').attr('disabled', 'disabled');
		}
	}

	$(document).on('click', '.btn_guardar', function(e){
		$('#form_tipodireccion').submit();
		e.preventDefault();
	});

});