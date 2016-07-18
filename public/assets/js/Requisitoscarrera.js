$(document).ready(function(){
	if($('#form_requisitoscarrera').length > 0){
		var tipo_vista = ($('#form_requisitoscarrera').attr('tipo_vista')).toUpperCase();
		if(tipo_vista === 'VER'){
			$('#form_requisitoscarrera').find('input').attr('disabled', 'disabled');
			$('#form_requisitoscarrera').find('select').attr('disabled', 'disabled');
			$('#form_requisitoscarrera').find('input[type=checkbox]').checkboxX('refresh');
		}
	}

	$(document).on('click', '.btn_guardar', function(e){
		$('input[type=checkbox]').prop('checked', true);
		$('#form_requisitoscarrera').submit();
		e.preventDefault();
	});

});