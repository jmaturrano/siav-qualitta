$(document).ready(function(){
	if($('#form_compromisoscarrera').length > 0){
		var tipo_vista = ($('#form_compromisoscarrera').attr('tipo_vista')).toUpperCase();
		if(tipo_vista === 'VER'){
			$('#form_compromisoscarrera').find('input').attr('disabled', 'disabled');
			$('#form_compromisoscarrera').find('select').attr('disabled', 'disabled');
			$('#form_compromisoscarrera').find('input[type=checkbox]').checkboxX('refresh');
		}
	}

	$(document).on('click', '.btn_guardar', function(e){
		$('input[type=checkbox]').prop('checked', true);
		$('#form_compromisoscarrera').submit();
		e.preventDefault();
	});

});