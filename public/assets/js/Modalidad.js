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
				    	$('#form_modalidadxcurso').submit();
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
/*
										<tr>
											<td class="texto-centrado"><?= $mxca->curs_codigo; ?></td>
											<td class=""><?= substr($mxca->curs_descripcion, 0, LIMITSELECT); ?></td>
											<td class="texto-centrado">
								              <div class="input-group date timepicker">
								                <input name="mxca_horas[]" type="text" class="form-control" placeholder="00:00">
								                <span class='input-group-addon'><span class='glyphicon glyphicon-time'></span></span>
								              </div>
											</td>
											<td class="texto-centrado">
												<div class="controls">
													<input type="text" name="mxca_precio[]" class="span1" >
												</div> <!-- /controls -->
											</td>
											<td class="texto-centrado td-actions">
						                    	<a title="Actualizar registro" class="btn btn-small btn-invert btn_editar" 
						                    		href="<?= base_url('registros/modalidadxcarrera/guardar/'.str_encrypt($mxca->mxca_id, KEY_ENCRYPT)); ?>">
						                    		<span class="<?= ICON_SAVED; ?>"></span>
						                    	</a>
						                    	<a title="Eliminar" class="btn btn-small btn-danger tr_delete" href="javascript:;" 
						                    		data-url="<?= base_url('registros/modalidadxcarrera/eliminar/'.str_encrypt($mxca->mxca_id, KEY_ENCRYPT)); ?>">
						                    		<i class="btn-icon-only <?= ICON_DELETE; ?>"> </i>
						                    	</a>
											</td>
										</tr>
*/
        $.getJSON(item_subruta.replace('{MODUID}', modu_id).replace('{LIPEID}', lipe_id),function(data){
			var mxca_id, mxca_horas, mxca_precio, mxca_observacion, curs_codigo, curs_descripcion, html;
        	if(data){
				for (var i = 0; i <= data.length - 1; i++) {
					mxca_id 			= data[i].mxca_id;
					mxca_horas 			= data[i].mxca_horas;
					mxca_precio 		= data[i].mxca_precio;
					mxca_observacion 	= data[i].mxca_observacion;
					curs_codigo 		= data[i].curs_codigo;
					curs_descripcion 	= data[i].curs_descripcion;

					html 				+= '<tr>';
					html 				+= '<td class="texto-centrado">'+curs_codigo+'</td>';
					html 				+= '<td class="">'+curs_descripcion+'</td>';
					html 				+= '<td class="texto-centrado">';
					html 				+= '<div class="input-group date timepicker">';
					html 				+= '<input name="mxca_horas[]" type="text" class="form-control" placeholder="00:00">';
					html 				+= '<span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>';
					html 				+= '</div>';
					html 				+= '</td>';
					html 				+= '<td class="texto-centrado"><input type="text" name="mxca_precio[]" class="span1" value="'+mxca_precio+'"></td>';
					html 				+= '<td class="texto-centrado"><input type="text" name="mxca_observacion[]" class="span3" value="'+mxca_observacion+'"></td>';
					html 				+= '<td class="texto-centrado">';
					html 				+= '';
					html 				+= '</td>';
					html 				+= '</tr>';
					
				}//end for
        	}
			$('#table_modxcurso').find('tbody').empty().html(html);
        })
        .fail(function(){
            bootbox.alert("<span class=\"glyphicon glyphicon-remove\"></span> No se han podido cargar los módulos...");
        });
		e.preventDefault();
	});
});