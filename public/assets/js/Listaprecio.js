$(document).ready(function(){
	if($('#form_listaprecio').length > 0){
		var tipo_vista = ($('#form_listaprecio').attr('tipo_vista')).toUpperCase();
		if(tipo_vista === 'VER'){
			$('#form_listaprecio').find('input').attr('disabled', 'disabled');
			$('#form_listaprecio').find('select').attr('disabled', 'disabled');
		}
	}

	$(document).on('click', '.btn_guardar', function(e){
		$('#lipe_indvigente').prop('checked', true);
		$('#form_listaprecio').submit();
		e.preventDefault();
	});

});