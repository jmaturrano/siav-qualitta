$(document).ready(function(){
	if($('#form_compromisosxcarrera').length > 0){
		var tipo_vista = ($('#form_compromisosxcarrera').attr('tipo_vista')).toUpperCase();
		if(tipo_vista === 'VER'){
			$('#form_compromisosxcarrera').find('input').attr('disabled', 'disabled');
			$('#form_compromisosxcarrera').find('select').attr('disabled', 'disabled');
			$('#form_compromisosxcarrera').find('input[type=checkbox]').checkboxX('refresh');
		}
	}

	$(document).on('click', '.btn_guardar', function(e){
		$('input[type=checkbox]').prop('checked', true);
		$('#form_compromisosxcarrera').submit();
		e.preventDefault();
	});

});