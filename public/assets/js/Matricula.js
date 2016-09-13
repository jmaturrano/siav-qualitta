$(document).ready(function(){
	if($('#form_matricula').length > 0){

		$(".datepicker").datepicker('remove');

		var tipo_vista = ($('#form_matricula').attr('tipo_vista')).toUpperCase();
		if(tipo_vista === 'VER'){
			$('#form_matricula').find('input').attr('disabled', 'disabled');
			$('#form_matricula').find('select').attr('disabled', 'disabled');
			$('#form_matricula').find('input[type=checkbox]').checkboxX('refresh');
			$('#form_matricula').find('button').attr('disabled', 'disabled');
		}
		if(tipo_vista === 'EDITAR'){

			$('#moda_id').attr('disabled', 'disabled');
			$('#carr_id').attr('disabled', 'disabled');
			$('#alum_id').attr('disabled', 'disabled');
			$('#lipe_id').attr('disabled', 'disabled');

            $('#gmat_id').removeAttr('disabled');
            $('#matr_codigo').removeAttr('disabled');
            $('#matr_fecha_proceso').removeAttr('disabled');
            $('#matr_costofinal').removeAttr('disabled');
            $('#matr_observacion').removeAttr('disabled');

            $('#form_matricula').find('input[type=checkbox]').checkboxX('refresh');
			$(".datepicker").datepicker('refresh');
			$('.selectpicker').selectpicker('refresh');

		}
	}

	$(document).on('click', '.btn_guardar', function(e){
        bootbox.dialog({
            title: '<h3 class="dialog-alternativo"><span class=\"glyphicon glyphicon-floppy-save\"></span> Guardar Matrícula</h3>',
            message: "¿Está seguro de realizar esta acción?",
            onEscape: function(){
            	bootbox.hideAll();
            },
            animate: false,
            className: 'bootbox-custom',
            buttons: {
	            cancel: {
				    label: 'Cancelar',
				    className: "btn-default",
				    callback: function(){
				    	bootbox.hideAll();
				    }
	            },
	            confirm: {
				    label: 'Ok',
				    className: "btn-danger",
				    callback: function(){
				    	proceso_guardar();
				    }
	            }
            }
        });
		e.preventDefault();
	});

	function proceso_guardar(){
		var costos_validate = 0;
		if($('#table_costos').find('tbody').find('tr').length > 1){
			$('#table_costos').find('tbody').find('tr').each(function(){
				var cxma_costofinal = $(this).find('.cxma_costofinal').find('input').val().trim();
				if(cxma_costofinal === ''){
					costos_validate++;
				}//end if
			});//end each
		}

		if(costos_validate > 0){
			bootbox.alert("<span class=\"glyphicon glyphicon-exclamation-sign\"></span> Por favor complete los campos de Costo Final");
			return false;
		}
		if($('#gmat_id').val() == ''){
			bootbox.alert("<span class=\"glyphicon glyphicon-exclamation-sign\"></span> Por favor seleccione un grupo de inicio");
			return false;
		}
		if($('#matr_codigo').val() == ''){
			bootbox.alert("<span class=\"glyphicon glyphicon-exclamation-sign\"></span> Por favor asigne un código de matrícula");
			return false;
		}
		if($('#matr_fecha_proceso').val() == ''){
			bootbox.alert("<span class=\"glyphicon glyphicon-exclamation-sign\"></span> Por favor seleccione una fecha de matrícula");
			return false;
		}
		$('input[type=checkbox]').prop('checked', true);
		$('input').removeAttr('disabled');
		$('select').removeAttr('disabled');
		$('#form_matricula').submit();
	}


	$(document).on('keyup', '.col_costofinal', function(e){
		var cxma_costofinal = $(this).val();
		if(cxma_costofinal === ''){
			cxma_costofinal = 0;
		}
		verifica_suma_totales();
	});

	function verifica_suma_totales(){
		var total_costofinal = 0;
		$('#table_costos').find('tbody').find('tr').each(function(){
			var $element = $(this).find('.cxma_costofinal').find('input');
			var col_costofinal = $element.val().trim().replace(',', '');
			if(col_costofinal === ''){
				col_costofinal = 0;
				$element.val(col_costofinal.toFixed(2));
			}//end if
			total_costofinal += parseFloat(col_costofinal);
		});//end each
		$('#table_costos').find('tfoot').find('.cxma_costofinal').find('input').val(total_costofinal.toFixed(2));
	}

	$(document).on('change', '.col_obligatorio', function(e){
		var $element = $(this).parent().parent().parent();
		if($(this).val() === '0'){
			$element.find('.col_costofinal').val(0);
		}else{
			var cmat_costo = $element.find('.cmat_costo').find('input').val().replace(',', '');
			$element.find('.col_costofinal').val(parseFloat(cmat_costo).toFixed(2));
		}
		verifica_suma_totales();
	});

	/*
	$(document).on('change', '#carr_id', function(e){
		console.log($(this).val());
		if($(this).val() != ''){
			$('#matr_costoreal').val(0);
		}else{
			$('#matr_costoreal').val('');
		}
	});
	*/

	$(document).on('click', '#btn_verificardisponibilidad', function(e){
		var data_url 	= $(this).attr('data-url');
		var moda_id 	= $('#moda_id').val();
		var carr_id 	= $('#carr_id').val();
		var alum_id 	= $('#alum_id').val();
		var lipe_id 	= $('#lipe_id').val();

		if(moda_id === ''){
			bootbox.alert("<span class=\"glyphicon glyphicon-exclamation-sign\"></span> Por favor seleccione una modalidad");
			return false;
		}
		if(carr_id === ''){
			bootbox.alert("<span class=\"glyphicon glyphicon-exclamation-sign\"></span> Por favor seleccione un Programa");
			return false;
		}
		if(alum_id === ''){
			bootbox.alert("<span class=\"glyphicon glyphicon-exclamation-sign\"></span> Por favor seleccione un alumno");
			return false;
		}
		if(lipe_id === ''){
			bootbox.alert("<span class=\"glyphicon glyphicon-exclamation-sign\"></span> Por favor seleccione una lista de precio");
			return false;
		}

        $.getJSON(data_url.replace('{MODA_ID}', moda_id).replace('{CARR_ID}', carr_id).replace('{ALUM_ID}', alum_id).replace('{LIPE_ID}', lipe_id),function(data){
            var carr_horas 		= data.carr_horas;
            var carr_precio 	= data.carr_precio;
            var matr_existe 	= data.matr_existe;
            var data_gmat 		= data.data_gmat;
            var data_cmat 		= data.data_cmat;
            if(matr_existe === '1'){
            	bootbox.alert("<span class=\"glyphicon glyphicon-exclamation-sign\"></span> Este alumno ya se encuentra matriculado");
            	return false;
            }//end if

            bootbox.alert("<span class=\"glyphicon glyphicon-ok\"></span> Matrícula disponible. Complete los datos y presione Guardar");

            $('#gmat_id').empty().append('<option value="">Seleccione</option>');
            $('#observacion_ajax').html('');
            if(data_gmat){
	            for(var i = 0; i < data_gmat.length; i++){
	            	var gmat_id 			= data_gmat[i].gmat_id;
	            	var gmat_fecha_inicio 	= data_gmat[i].gmat_fecha_inicio;
	            	$('#gmat_id').append('<option value="'+gmat_id+'">'+gmat_fecha_inicio+'</option>');
	            }//end for
            }else{
            	$('#observacion_ajax').html(' * No se ha encontrado un grupo disponible. Verifique la opción: Grupos de Inicio.');
            }

            $('#table_costos').find('tbody').empty();
            $('#table_costos').find('tfoot').empty();
            var html_table = '';
            var costo_total = 0;
            if(data_cmat){
	            for(var j = 0; j < data_cmat.length; j++){
	            	var cmat_id 			= data_cmat[j].cmat_id;
	            	var cmat_descripcion 	= data_cmat[j].cmat_descripcion;
	            	var cmat_costo 			= parseFloat(data_cmat[j].cmat_costo).toFixed(2);
	            	var cmat_obligatorio 	= data_cmat[j].cmat_obligatorio;
	            	var checked 			= '';
	            	if(cmat_obligatorio === 'S'){
	            		checked = 'disabled';
	            	}
	            	html_table = '';
	            	html_table += '<tr class="cmat_id_'+cmat_id+'">';
	            	html_table += '<td class="cmat_id">'+cmat_descripcion+'<input type="hidden" name="cmat_id[]" value="'+cmat_id+'"><input type="hidden" name="cxma_id[]" value="0"></td>';
	            	html_table += '<td class="texto-derecha cmat_costo">'+cmat_costo+'<input type="hidden" name="cmat_costo[]" value="'+cmat_costo+'"></td>';
	            	html_table += '<td class="texto-centrado cxma_costofinal"><input type="text" class="span1 texto-derecha col_costofinal" name="cxma_costofinal[]" value="'+cmat_costo+'"></td>';
	            	html_table += '<td class="texto-centrado cmat_obligatorio"><input type="checkbox" class="col_obligatorio" name="cmat_obligatorio[]" checked="" value="1" data-three-state="false" data-toggle="checkbox-x" '+checked+' ></td>';
	            	html_table += '</tr>';
	            	$('#table_costos').find('tbody').append(html_table);
	            	costo_total += parseFloat(data_cmat[j].cmat_costo);
	            	
	            }//end for
            }else{
            	$('#observacion_ajax').html(' * No se han encontrado los conceptos por matrícula. Verifique si la lista de precio tiene conceptos en la opción: Conceptos Matrícula.');
            }

            $('.cmat_id_1').find('.cmat_costo').html(carr_precio.toFixed(2)+'<input type="hidden" name="cmat_costo[]" value="'+carr_precio.toFixed(2)+'">');
            $('.cmat_id_1').find('.cxma_costofinal').html('<input type="text" class="span1 texto-derecha col_costofinal" name="cxma_costofinal[]" value="'+carr_precio.toFixed(2)+'">');

            costo_total += parseFloat(carr_precio);
            var html_tfoot = '';
            html_tfoot += '<tr>';
            html_tfoot += '<td>Totales</td>';
            html_tfoot += '<td class="texto-derecha cmat_costo">'+costo_total.toFixed(2)+'</td>';
            html_tfoot += '<td class="texto-centrado cxma_costofinal"><input type="text" class="span1 texto-derecha" value="'+costo_total.toFixed(2)+'" disabled></td>';
            html_tfoot += '<td></td>';
            html_tfoot += '</tr>';

            $('.campo_editable').val('');
            $('#table_costos').find('tfoot').html(html_tfoot);
            //$('#matr_costoreal').val(carr_precio.toFixed(2));
            $('#matr_horareal').val(carr_horas);
            $('#gmat_id').removeAttr('disabled');
            $('#matr_codigo').removeAttr('disabled');
            $('#matr_fecha_proceso').removeAttr('disabled');
            $('#matr_costofinal').removeAttr('disabled');
            $('#matr_observacion').removeAttr('disabled');

            $('#form_matricula').find('input[type=checkbox]').checkboxX('refresh');
            $('#gmat_id').selectpicker('refresh');
            $(".datepicker").datepicker('refresh');
        })
        .fail(function(){
            bootbox.alert("<span class=\"glyphicon glyphicon-exclamation-sign\"></span> Ha ocurrido un error verificando los datos. Por favor reinicie el sistema o comuníquese a soporte de persistir el problema");
        });
	});

	$(document).on('change', '#moda_id', function(e){
		limpiar_form();
	});
	$(document).on('change', '#carr_id', function(e){
		limpiar_form();
	});
	$(document).on('change', '#alum_id', function(e){
		limpiar_form();
	});
	$(document).on('change', '#lipe_id', function(e){
		limpiar_form();
	});

	function limpiar_form(){
		$('#observacion_ajax').html('');
		$('#matr_horareal').val('');
		$('#table_costos').find('tfoot').empty();
		$('#table_costos').find('tbody').empty().append('<tr><td class="texto-centrado" colspan="4">No se han encontrado registros...</td></tr>');
		$('#gmat_id').val('');
	}	

});