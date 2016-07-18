$(document).ready(function(){
	if($('#form_conceptosmatricula').length > 0){
		var tipo_vista = ($('#form_conceptosmatricula').attr('tipo_vista')).toUpperCase();
		if(tipo_vista === 'VER'){
			$('#form_conceptosmatricula').find('input').attr('disabled', 'disabled');
			$('#form_conceptosmatricula').find('select').attr('disabled', 'disabled');
			$('#form_conceptosmatricula').find('input[type=checkbox]').checkboxX('refresh');
		}
	}

	$(document).on('click', '.btn_guardar', function(e){
		console.log('hiiiii');
		$('input[type=checkbox]').prop('checked', true);
		$('#form_conceptosmatricula').submit();
		e.preventDefault();
	});

});