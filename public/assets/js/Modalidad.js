$(document).ready(function(){
	if($('#form_modalidad').length > 0){
		var tipo_vista = ($('#form_modalidad').attr('tipo_vista')).toUpperCase();
		if(tipo_vista === 'VER'){
			$('#form_modalidad').find('input').attr('disabled', 'disabled');
			$('#form_modalidad').find('select').attr('disabled', 'disabled');
		}
	}

	$(document).on('click', '.btn_guardar', function(e){
		$('#form_modalidad').submit();
		e.preventDefault();
	});

	$(document).on('click', '#btn_agregarcurso', function(e){
		var indicador = '';
		if($('#curs_id').val() === ''){
			indicador = '1';
		}else{
			indicador = '0';
		}
        bootbox.dialog({
            title: '<h3 class="dialog-agregar"><span class=\"glyphicon glyphicon-exclamation-sign\"></span> Agregar un elemento</h3>',
            message: "¿Está seguro de agregar el curso(s) seleccionado?",
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
				    	$('#agregar_curso_todo').val(indicador);
				    	$('#form_modalidadxcurso_buscar').submit();
				    }
	            }
            }
        });
		e.preventDefault();
	});

	$(document).on('click', '#btn_buscarcurso', function(e){
		var item_subruta 	= $(this).attr('data-subruta');
		var carr_id 		= $('#carr_id').val();
		var modu_id 		= $('#modu_id').val();
		var lipe_id 		= $('#lipe_id').val();

		$('#modu_costo').removeAttr('disabled');

		if(carr_id === ''){
			bootbox.alert("<span class=\"glyphicon glyphicon-exclamation-sign\"></span> Selecione una carrera...");
			return false;
		}
		if(modu_id === ''){
			bootbox.alert("<span class=\"glyphicon glyphicon-exclamation-sign\"></span> Selecione un módulo...");
			return false;
		}
		if(lipe_id === ''){
			bootbox.alert("<span class=\"glyphicon glyphicon-exclamation-sign\"></span> Selecione una lista de precios...");
			return false;
		}
        $.getJSON(item_subruta.replace('{MODUID}', modu_id).replace('{LIPEID}', lipe_id),function(data){
			var mxca_id, mxca_horas, mxca_precio, mxca_observacion, curs_id, lipe_id, curs_codigo, curs_descripcion, html;
        	if(data){
				for (var i = 0; i <= data.length - 1; i++) {
					mxca_id 			= data[i].mxca_id;
					mxca_horas 			= data[i].mxca_horas;
					mxca_precio 		= data[i].mxca_precio;
					mxca_observacion 	= data[i].mxca_observacion;
					curs_id 			= data[i].curs_id;
					lipe_id 			= data[i].lipe_id;
					curs_codigo 		= data[i].curs_codigo;
					curs_descripcion 	= data[i].curs_descripcion;

					html 				+= '<tr>';
					html 				+= '<td class="texto-centrado">';
					html 				+= '<input type="checkbox" name="chk_registro[]" data-toggle="checkbox-x" data-three-state="false" value="0" class="chk_registro">';
					html 				+= '<input type="hidden" name="mxca_id[]" value="'+mxca_id+'">';
					html 				+= '<input type="hidden" name="curs_id[]" value="'+curs_id+'">';
					html 				+= '<input type="hidden" name="lipe_id[]" value="'+lipe_id+'">';
					html 				+= '</td>';
					html 				+= '<td class="texto-centrado">'+curs_codigo+'</td>';
					html 				+= '<td class="">'+curs_descripcion+'</td>';
					html 				+= '<td class="texto-centrado">';
					//html 				+= '<div class="input-group date timepicker">';
					html 				+= '<input name="mxca_horas[]" type="text" class="span1" placeholder="00:00:00" value="'+mxca_horas+'">';
					//html 				+= '<span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>';
					html 				+= '</div>';
					html 				+= '</td>';
					html 				+= '<td class="texto-centrado" style="display: none;"><input type="text" name="mxca_precio[]" class="span1" value="'+parseFloat(mxca_precio).toFixed(2)+'"></td>';
					html 				+= '<td class="texto-centrado"><input type="text" name="mxca_observacion[]" class="span3" value="'+mxca_observacion+'"></td>';
					html 				+= '</tr>';
					
				}//end for
        	}else{
        		html 				+= '<tr>';
        		html 				+= '<td class="texto-centrado" colspan="5">';
        		html 				+= '<span>No se encontraron registros... ¿Es la lista de precios correcta?. Pruebe agregar cursos!</span>';
        		html 				+= '</td>';
        		html 				+= '</tr>';
        	}
			$('#table_modxcurso').find('tbody').empty().html(html);
			$('.timepicker').datetimepicker({format: 'LT'});
			$('input[type=checkbox]').checkboxX('refresh');
        })
        .fail(function(){
            bootbox.alert("<span class=\"glyphicon glyphicon-exclamation-sign\"></span> No se han podido cargar los módulos...");
        });
		e.preventDefault();
	});

	$(document).on('click', '#btn_guardarcurso', function(e){

		var modu_costo 	= $('#modu_costo').val().trim();
		var modu_id 	= $('#modu_id').val();
		if(modu_costo === ''){
			bootbox.alert("<span class=\"glyphicon glyphicon-exclamation-sign\"></span> Por favor ingrese un costo para el módulo...");
			return false;
		}//end if
		$('#modu_costox').val(modu_costo);
		$('#modu_idx').val(modu_id);

        bootbox.dialog({
            title: '<h3 class="dialog-agregar"><span class=\"glyphicon glyphicon-exclamation-sign\"></span> Guardar cambios</h3>',
            message: "¿Está seguro de guardar los cambios realizados?",
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
				    	/* GUARDAR */
						if($('#table_modxcurso').find('tbody').find('.chk_registro').length === 0){
							bootbox.alert("<span class=\"glyphicon glyphicon-exclamation-sign\"></span> No tiene datos en la tabla...");
							return false;
						}
						var items_ = 0;
						$('#table_modxcurso').find('tbody').find('.chk_registro').each(function(){
							if($(this).val() === '1'){
								items_++;
							}
						});
						if(items_ === 0){
							bootbox.alert("<span class=\"glyphicon glyphicon-exclamation-sign\"></span> Debe seleccionar al menos una fila...");
							return false;
						}
						$('input[type=checkbox]').prop('checked', true);
						$('#form_modalidadxcurso_guardar').submit();
						/* FIN - GUARDAR */
				    }
	            }
            }
        });

		e.preventDefault();
	});

	
	$(document).on('click', '#btn_eliminarcurso', function(e){
		var action_url= $(this).attr('data-url');

        bootbox.dialog({
            title: '<h3 class="dialog-delete"><span class=\"btn-icon-only icon-trash\"></span> Eliminar un elemento</h3>',
            message: "¿Está seguro de eliminar los elementos seleccionados?",
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
				    	/* GUARDAR */
						if($('#table_modxcurso').find('tbody').find('.chk_registro').length === 0){
							bootbox.alert("<span class=\"glyphicon glyphicon-exclamation-sign\"></span> No tiene datos en la tabla...");
							return false;
						}
						$('input[type=checkbox]').prop('checked', true);
						$('#form_modalidadxcurso_guardar').attr('action', action_url);
						$('#form_modalidadxcurso_guardar').submit();
						/* FIN - GUARDAR */
				    }
	            }
            }
        });
		e.preventDefault();
	});

	$(document).on('change', '.check_table_all', function(e){
		$('.chk_registro').prop('checked', true);
		if($(this).val() === '1'){
			$('.chk_registro').val('1');
		}else{
			$('.chk_registro').val('0');
		}
		$('.chk_registro').checkboxX('refresh');
	});

	//$('.selectpicker-ubig').selectpicker('refresh');
});