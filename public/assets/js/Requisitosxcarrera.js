$(document).ready(function(){
	if($('#form_requisitosxcarrera').length > 0){
		var tipo_vista = ($('#form_requisitosxcarrera').attr('tipo_vista')).toUpperCase();
		if(tipo_vista === 'VER'){
			$('#form_requisitosxcarrera').find('input').attr('disabled', 'disabled');
			$('#form_requisitosxcarrera').find('select').attr('disabled', 'disabled');
			$('#form_requisitosxcarrera').find('input[type=checkbox]').checkboxX('refresh');
		}
	}

	$(document).on('click', '.btn_guardar', function(e){
		$('input[type=checkbox]').prop('checked', true);
		$('#form_requisitosxcarrera').submit();
		e.preventDefault();
	});

});