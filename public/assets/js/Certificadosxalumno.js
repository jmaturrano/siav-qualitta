$(document).ready(function(){
	if($('#form_certificadosxalumno').length > 0){
		var tipo_vista = ($('#form_certificadosxalumno').attr('tipo_vista')).toUpperCase();
		if(tipo_vista === 'VER'){
			$('#form_certificadosxalumno').find('input').attr('disabled', 'disabled');
			$('#form_certificadosxalumno').find('select').attr('disabled', 'disabled');
			$('#form_certificadosxalumno').find('input[type=checkbox]').checkboxX('refresh');
		}
		if(tipo_vista === 'EDITAR'){
			var cele_anios_vigencia = $('option:selected', '#cele_id').attr('data_anios_vigencia');
			var cele_unidad_vigencia_ = $('option:selected', '#cele_id').attr('data_unidad_vigencia_');
			if(cele_anios_vigencia !== '0'){
				$('#cxal_fecha_vencimiento').removeAttr('disabled');
			}else{
				$('#cxal_fecha_vencimiento').attr('disabled', 'disabled');
			}
			$(".datepickerx input").datepicker('refresh');
		}
	}


	$(document).on('change', '#cele_id', function(e){
		var cele_anios_vigencia = $('option:selected', this).attr('data_anios_vigencia');
		var cele_unidad_vigencia = $('option:selected', this).attr('data_unidad_vigencia');
		var cele_unidad_vigencia_ = $('option:selected', this).attr('data_unidad_vigencia_');

		$('#cxal_fecha_vencimiento').val('');
		if(cele_anios_vigencia !== '0'){
			$('#cxal_fecha_vencimiento').removeAttr('disabled');
		}else{
			$('#cxal_fecha_vencimiento').attr('disabled', 'disabled');
		}
		$(".datepickerx input").datepicker('refresh');
		$('#vigencia').val(cele_unidad_vigencia_);
	});

	$(document).on('click', '.btn_guardar', function(e){
		$('input').removeAttr('disabled');
		$('input[type=checkbox]').prop('checked', true);
		$('#form_certificadosxalumno').submit();
		e.preventDefault();
	});

});