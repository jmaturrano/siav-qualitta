<div class="main">
	<div class="main-inner">
	    <div class="container">
	      <div class="row">
	      	<div class="span12">
	      		<div class="widget">
	      			<div class="widget-header">
	      				<i class="<?= $header_icon; ?>"></i>
	      				<h3><?= $header_title; ?></h3>
	                    <ul class="breadcrumb custom-breadcrumb">
	                        <li><a href="<?php echo base_url('panel/principal') ?>"><i class="<?php echo ICONO_HOME ?>"></i>&nbsp; Inicio</a></li>
	                        <li>&nbsp; /</li>
	                        <li><a href="<?php echo base_url('registros/matricula') ?>"><i class="<?= $header_icon; ?>"></i>&nbsp; <?= $header_title; ?></a></li>
	                        <li>&nbsp; /</li>
	                        <li class="active"><i class="<?= ICON_FORM; ?>"></i>&nbsp; Formulario</li>
	                    </ul>
	  				</div> <!-- /widget-header -->
					<div class="widget-content">
						<div id="formcontrols" class="">
							<?php
							$attributes = array(
							  'id'      	=> 'form_matricula',
							  'name'    	=> 'form_matricula',
							  'class'		=> 'form-horizontal',
							  'tipo_vista' 	=> $tipo_vista
							  );
							$ruta = 'registros/matricula/guardar/'.((isset($data_matr))?str_encrypt($data_matr->matr_id, KEY_ENCRYPT):'');
							echo form_open($ruta, $attributes);
							?>
							<fieldset>
								<div class="control-group">
									<label for="moda_descripcion" class="control-label">Descripción</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_matr))?$data_matr->moda_descripcion:set_value('moda_descripcion'); ?>" id="moda_descripcion" name="moda_descripcion" class="span8" maxlength="100">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->
							</fieldset>
					       	<?php
					        echo form_close();
					        ?>

							<hr>
							<br>
							<?php
							if(isset($data_matr)){
							?>
							<div class="form-subheader">
								<h3>Cursos por matricula</h3>
							</div>
							<?php
							$attributes = array(
							  'id'      	=> 'form_matriculaxcurso_buscar',
							  'name'    	=> 'form_matriculaxcurso_buscar',
							  'class'		=> 'form-horizontal',
							  'tipo_vista' 	=> $tipo_vista
							  );
							$ruta = 'registros/matriculaxcurso/guardar/'.((isset($data_matr))?str_encrypt($data_matr->matr_id, KEY_ENCRYPT):'');
							echo form_open($ruta, $attributes);
							?>
							<fieldset>
                                <div class="control-group">
                                    <label for="carr_id" class="control-label" style="">Carrera</label>
                                    <div class="controls">
                                        <select class="selectpicker span8 selectpicker-carrera" name="carr_id" id="carr_id" data-live-search="true" data-subruta="<?= base_url('registros/modulosxcarrera/getModulosxcarrera_ajax/{SELECTED}'); ?>" data-container="body">
                                            <option value="">Seleccione</option>
                                        <?php
                                        if(isset($data_carr)){
                                            foreach ($data_carr as $item => $carrera) {
                                        ?>
                                            <option value="<?= $carrera->carr_id; ?>" <?= (isset($data_mxca) && $data_mxca->carr_id === $carrera->carr_id)?'selected':set_select('carr_id', $carrera->carr_id); ?>><?= $carrera->carr_codigo.' - '.substr($carrera->carr_descripcion, 0, LIMITSELECT); ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                        </select>
                                    </div> <!-- /controls -->
                                </div> <!-- /control-group -->

                                <div class="control-group">
                                    <label for="modu_id" class="control-label" style="">Módulo</label>
                                    <div class="controls">
                                        <select class="selectpicker span8 selectpicker-carrera" name="modu_id" id="modu_id" data-live-search="true" 
                                        data-subruta="<?= base_url('registros/curso/getCursosxmodulo_ajax/{SELECTED}'); ?>"  
                                        data-container="body">
                                            <option value="">Seleccione</option>
                                        </select>
                                    </div> <!-- /controls -->
                                </div> <!-- /control-group -->

                                <div class="control-group">
                                    <label for="curs_id" class="control-label" style="">Curso</label>
                                    <div class="controls">
                                        <select class="selectpicker span8 selectpicker-carrera" name="curs_id" id="curs_id" data-live-search="true" data-subruta="" data-container="body">
                                            <option value="">Todos</option>
                                        </select>
                                    </div> <!-- /controls -->
                                </div> <!-- /control-group -->

                                <div class="control-group">
                                    <label for="lipe_id" class="control-label" style="">Lista Precio</label>
                                    <div class="controls">
                                        <select class="selectpicker span8 selectpicker-carrera" name="lipe_id" id="lipe_id" data-live-search="true" data-subruta="" data-container="body">
                                            <option value="">Seleccione</option>
	                                        <?php
	                                        if(isset($data_lipe)){
	                                            foreach ($data_lipe as $item => $lipe) {
	                                            	$checked = ($lipe->lipe_indvigente === 'S' ? 'selected' : '');
	                                        ?>
	                                            <option <?= $checked; ?> value="<?= $lipe->lipe_id; ?>"><?= $lipe->lipe_descripcion.($checked==='selected'?' - Principal' : ''); ?></option>
	                                        <?php
	                                            }//end foreach
	                                        }
	                                        ?>
                                        </select>
                                    </div> <!-- /controls -->
                                </div> <!-- /control-group -->

                                <div class="control-group">
                                    <label class="control-label" style=""></label>
                                    <div class="controls">
										<button class="btn btn-warning btn-small btn_consulta" type="button" data-subruta="<?= base_url('registros/matriculaxcurso/getmatriculaxcurso_ajax/'.$data_matr->matr_id.'/{MODUID}/{LIPEID}'); ?>" id="btn_buscarcurso">
											<span class="<?= ICON_SEARCH; ?>"></span> Buscar cursos
										</button>
										<button class="btn btn-default btn-small btn_nuevo" type="button" id="btn_agregarcurso">
											<span class="<?= ICON_ADD; ?>"></span> Agregar curso (s)
										</button>
										<input type="hidden" name="agregar_curso_todo" id="agregar_curso_todo" value="0">
										<button class="btn btn-primary btn-small btn_edit" type="button" id="btn_guardarcurso">
											<span class="<?= ICON_SAVED; ?>"></span> Guardar cambios
										</button>
										<button class="btn btn-danger btn-small btn_delete" type="button" id="btn_eliminarcurso" data-url="<?= base_url('registros/matriculaxcurso/eliminar/'.((isset($data_matr))?str_encrypt($data_matr->matr_id, KEY_ENCRYPT):'')); ?>">
											<span class="<?= ICON_DELETE; ?>"></span> Eliminar seleccionados
										</button>
                                    </div> <!-- /controls -->
                                </div> <!-- /control-group -->

							</fieldset>
					       	<?php
					        echo form_close();
					        ?>

							<?php
							$attributes = array(
							  'id'      	=> 'form_matriculaxcurso_guardar',
							  'name'    	=> 'form_matriculaxcurso_guardar',
							  'class'		=> 'form-horizontal',
							  'tipo_vista' 	=> $tipo_vista
							  );
							$ruta = 'registros/matriculaxcurso/actualizar/'.((isset($data_matr))?str_encrypt($data_matr->matr_id, KEY_ENCRYPT):'');
							echo form_open($ruta, $attributes);
							?>

							<table class="table table-striped table-bordered" id="table_modxcurso">
				                <thead>
				                  <tr>
				                  	<th class="cabecera-tabla"> </th>
				                    <th class="cabecera-tabla"> Código </th>
				                    <th class="cabecera-tabla"> Curso </th>
				                    <th class="cabecera-tabla"> Horas </th>
				                    <th class="cabecera-tabla"> Precio </th>
				                    <th class="cabecera-tabla"> Observaciones </th>
				                  </tr>
				                </thead>
				                <tbody>
				                	<?php
				                	if(isset($data_mxca)){
				                		foreach ($data_mxca as $item => $mxca) {
				               		?>
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
						                    		href="<?= base_url('registros/matriculaxcarrera/guardar/'.str_encrypt($mxca->mxca_id, KEY_ENCRYPT)); ?>">
						                    		<span class="<?= ICON_SAVED; ?>"></span>
						                    	</a>
						                    	<a title="Eliminar" class="btn btn-small btn-danger tr_delete" href="javascript:;" 
						                    		data-url="<?= base_url('registros/matriculaxcarrera/eliminar/'.str_encrypt($mxca->mxca_id, KEY_ENCRYPT)); ?>">
						                    		<i class="btn-icon-only <?= ICON_DELETE; ?>"> </i>
						                    	</a>
											</td>
										</tr>
				               		<?php
				                		}//end foreach
				                	}else{
				                	?>
				                	<tr>
				                		<td colspan="6" class="texto-centrado">
				                			<span>No se encontraron registros...</span>
				                		</td>
				                	</tr>
				                	<?php
				                	}
				                	?>
				                </tbody>
				            </table>

					       	<?php
					        echo form_close();
					        ?>

							<?php
							}
							?>

						</div>
					</div> <!-- /widget-content -->
				</div> <!-- /widget -->
		    </div> <!-- /span8 -->
	      </div> <!-- /row -->
	    </div> <!-- /container -->
	</div> <!-- /main-inner -->
</div> <!-- /main -->







<script src="<?php echo base_url('public/assets/js/Matricula.js') ?>"></script>