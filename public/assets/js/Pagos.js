$(document).ready(function(){
	if($('#form_pagos').length > 0){

		var tipo_vista = ($('#form_pagos').attr('tipo_vista')).toUpperCase();
		if(tipo_vista === 'VER'){
			$('#form_pagos').find('input').attr('disabled', 'disabled');
			$('#form_pagos').find('select').attr('disabled', 'disabled');
			$('#form_pagos').find('input[type=checkbox]').checkboxX('refresh');
			$('#form_pagos').find('button').attr('disabled', 'disabled');
			$(".datepicker").datepicker('remove');
		}
	}

	$(document).on('click', '.btn_guardar', function(e){
        $('#form_pagos').submit();
		e.preventDefault();
	});



});