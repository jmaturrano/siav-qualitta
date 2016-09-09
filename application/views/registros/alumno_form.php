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
	                        <li><a href="<?php echo base_url('registros/alumno') ?>"><i class="<?= $header_icon; ?>"></i>&nbsp; <?= $header_title; ?></a></li>
	                        <li>&nbsp; /</li>
	                        <li class="active"><i class="<?= ICON_FORM; ?>"></i>&nbsp; Formulario</li>
	                    </ul>
	  				</div> <!-- /widget-header -->
					<div class="widget-content">
						<div id="formcontrols" class="">
							<?php
							$attributes = array(
							  'id'      	=> 'form_alumno',
							  'name'    	=> 'form_alumno',
							  'class'		=> 'form-horizontal',
							  'enctype'		=> 'multipart/form-data',
							  'tipo_vista' 	=> $tipo_vista,
							  'reporte_mail'=> (isset($reporte_mail))?$reporte_mail:false,
							  'mail_confirm'=> base_url('registros/alumno/enviarreporte/'.((isset($data_alum))?str_encrypt($data_alum->alum_id, KEY_ENCRYPT):'')),
							  'mail_cancel'	=> base_url('registros/alumno/ver/'.((isset($data_alum))?str_encrypt($data_alum->alum_id, KEY_ENCRYPT):''))
							  );
							$ruta = 'registros/alumno/guardar/'.((isset($data_alum))?str_encrypt($data_alum->alum_id, KEY_ENCRYPT):'');
							echo form_open($ruta, $attributes);
							?>
							<fieldset>
								<div class="control-group" style="display: none;">
									<label for="alum_codigo" class="control-label">Código</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_alum))?$data_alum->alum_codigo:set_value('alum_codigo'); ?>" id="alum_codigo" name="alum_codigo" class="span8" maxlength="5">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->

								<div class="control-group" style="max-width: 650px;">
									<label for="alum_nombre" class="control-label">Nombre</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_alum))?$data_alum->alum_nombre:set_value('alum_nombre'); ?>" id="alum_nombre" name="alum_nombre" class="span4" maxlength="80">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->

								<div class="span2" style="position: absolute; top: 5%; right: 25%;">
									<?php 
									if(isset($data_alum)){
									$ruta_imagen = ($data_alum->alum_ruta_imagen != '' && $data_alum->alum_ruta_imagen != NULL) ? base_url(IMG_PATH.'alumnos/'.$data_alum->alum_ruta_imagen) : '';
										if($ruta_imagen != ''){
									?>
									<img src="<?= $ruta_imagen; ?>" alt="<?= $data_alum->alum_nombre.' '.$data_alum->alum_apellido; ?>" class="img-thumbnail span2 float_rigth">
									<?php
										}//end if
									}//end if
									?>
								</div>

								<div class="control-group" style="max-width: 650px;">
									<label for="alum_apellido" class="control-label">Apellido</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_alum))?$data_alum->alum_apellido:set_value('alum_apellido'); ?>" id="alum_apellido" name="alum_apellido" class="span4" maxlength="80">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->

								<div class="control-group" style="max-width: 650px;">
									<label for="dide_id" class="control-label">Tipo Documento</label>
									<div class="controls">
					                    <select class="selectpicker span4" name="dide_id" id="dide_id" data-container="body">
					                    <option value="">Seleccione</option>
					                    <?php
					                    if(isset($data_doid)){
					                    	foreach ($data_doid as $item => $docidentidad) {
					                   	?>
											<option value="<?= $docidentidad->dide_id; ?>" data-length="<?= $docidentidad->dide_caracteres; ?>" <?= (isset($data_alum) && $data_alum->dide_id === $docidentidad->dide_id)?'selected': set_select('dide_id', $docidentidad->dide_id); ?>><?= $docidentidad->dide_descripcion; ?></option>
					                   	<?php
					                    	}
					                    }
					                    ?>
					                    </select>
									</div> <!-- /controls -->
								</div> <!-- /control-group -->

								<div class="control-group" style="max-width: 650px;">
									<label for="alum_numero_documento" class="control-label">Doc. Identidad <span id="dideid_maxlength">(8 dig.)</span></label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_alum))?$data_alum->alum_numero_documento:set_value('alum_numero_documento'); ?>" id="alum_numero_documento" name="alum_numero_documento" class="span4" maxlength="8">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->

								<div class="control-group">
									<label for="alum_ruta_imagen" class="control-label">Foto</label>
									<div class="controls">
										<input type="file" id="alum_ruta_imagen" name="alum_ruta_imagen" class="span4">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->

								<div class="control-group">
									<label for="alum_email" class="control-label">E-mail</label>
									<div class="controls">
										<input type="email" id="alum_email" name="alum_email" class="span8 form-control" maxlength="100" value="<?= (isset($data_alum))?$data_alum->alum_email:set_value('alum_email'); ?>">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->

								<div class="control-group">
									<label for="alum_fecha_nacimiento" class="control-label">Fecha de nacimiento</label>
									<div class="controls">
									    <div class="datepickerx" data-date="<?= (isset($data_alum))?fecha_latino($data_alum->alum_fecha_nacimiento):date('d/m/Y'); ?>" data-date-format="dd/mm/yyyy">
									      <input class="span2" size="16" type="text" value="<?= (isset($data_alum))?fecha_latino($data_alum->alum_fecha_nacimiento):set_value('alum_fecha_nacimiento'); ?>" id="alum_fecha_nacimiento" name="alum_fecha_nacimiento">
									    </div>
									</div> <!-- /controls -->
								</div> <!-- /control-group -->

								<div class="control-group">
									<label for="alum_lugar_nacimiento" class="control-label">Lugar de nacimiento</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_alum))?$data_alum->alum_lugar_nacimiento:set_value('alum_lugar_nacimiento'); ?>" id="alum_lugar_nacimiento" name="alum_lugar_nacimiento" class="span8" maxlength="100">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->

								<div class="control-group">
									<label for="alum_direccion" class="control-label">Dirección</label>
									<div class="controls">

					                    <select class="selectpicker span1" name="tdir_id" id="tdir_id" data-container="body">
					                    <option value="0">Sel.</option>
					                    <?php
					                    if(isset($data_tdir)){
					                    	foreach ($data_tdir as $item => $tipodireccion) {
					                   	?>
											<option value="<?= $tipodireccion->tdir_id; ?>" <?= (isset($data_alum) && $data_alum->tdir_id === $tipodireccion->tdir_id)?'selected': set_select('tdir_id', $tipodireccion->tdir_id); ?>><?= $tipodireccion->tdir_abreviatura; ?></option>
					                   	<?php
					                    	}
					                    }
					                    ?>
					                    </select>

										<input type="text" value="<?= (isset($data_alum))?$data_alum->alum_direccion:set_value('alum_direccion'); ?>" id="alum_direccion" name="alum_direccion" class="span7" maxlength="200" style="vertical-align: top;">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->

                                <div class="control-group">
                                    <label for="depa_id" class="control-label" style="">Departamento</label>
                                    <div class="controls">
                                        <select class="selectpicker span8 selectpicker-ubig" name="depa_id" id="depa_id" data-live-search="true" data-subruta="<?= base_url('registros/provincia/getProvincia_ajax/{SELECTED}'); ?>" data-container="body">
                                            <option value="">Seleccione</option>
                                        <?php
                                        if(isset($data_depa)){
                                            foreach ($data_depa as $item => $departamento) {
                                        ?>
                                            <option value="<?= $departamento->depa_id; ?>" <?= (isset($data_alum) && $data_alum->depa_id === $departamento->depa_id)?'selected':set_select('depa_id', $departamento->depa_id); ?>><?= substr($departamento->depa_descripcion, 0, LIMITSELECT); ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                        </select>
                                    </div> <!-- /controls -->
                                </div> <!-- /control-group -->

                                <div class="control-group">
                                    <label for="prov_id" class="control-label" style="">Provincia</label>
                                    <div class="controls">
                                        <select class="selectpicker span8 selectpicker-ubig" name="prov_id" id="prov_id" data-live-search="true" data-subruta="<?= base_url('registros/distrito/getDistrito_ajax/{SELECTED}'); ?>" data-container="body">
                                            <option value="">Seleccione</option>
                                        <?php
                                        if(isset($data_prov)){
                                            foreach ($data_prov as $item => $provincia) {
                                        ?>
                                            <option value="<?= $provincia->prov_id; ?>" <?= (isset($data_alum) && $data_alum->prov_id === $provincia->prov_id)?'selected':set_select('prov_id', $provincia->prov_id); ?>><?= substr($provincia->prov_descripcion, 0, LIMITSELECT); ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                        </select>
                                    </div> <!-- /controls -->
                                </div> <!-- /control-group -->

                                <div class="control-group">
                                    <label for="dist_id" class="control-label" style="">Distrito</label>
                                    <div class="controls">
                                        <select class="selectpicker span8 selectpicker-ubig" name="dist_id" id="dist_id" data-live-search="true" data-subruta="" data-container="body">
                                            <option value="">Seleccione</option>
                                        <?php
                                        if(isset($data_dist)){
                                            foreach ($data_dist as $item => $distrito) {
                                        ?>
                                            <option value="<?= $distrito->dist_id; ?>" <?= (isset($data_alum) && $data_alum->dist_id === $distrito->dist_id)?'selected':set_select('dist_id', $distrito->dist_id); ?>><?= substr($distrito->dist_descripcion, 0, LIMITSELECT); ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                        </select>
                                    </div> <!-- /controls -->
                                </div> <!-- /control-group -->


								<div class="control-group">
									<label for="alum_seguro" class="control-label">Seguro</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_alum))?$data_alum->alum_seguro:set_value('alum_seguro'); ?>" id="alum_seguro" name="alum_seguro" class="span8" maxlength="100">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->

								<div class="control-group">
									<label for="alum_observaciones" class="control-label">Observaciones</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_alum))?$data_alum->alum_observaciones:set_value('alum_observaciones'); ?>" id="alum_observaciones" name="alum_observaciones" class="span8" maxlength="100">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->

							</fieldset>
					       	<?php
					        echo form_close();
					        ?>
							<hr>
							<br>
							<div class="form-subheader">
								<h3>Datos Adicionales</h3>
								<?php
								if(isset($data_alum)){
									$ruta = 'registros/telefonoxalumno/agregartelefono/'.(str_encrypt($data_alum->alum_id, KEY_ENCRYPT));
								?>
								<button class="btn btn-default btn-right btn-small btn-editar" data-toggle="modal" data-target="#agregar_telefono" id="btn_agregartelefono" data-url="<?= base_url($ruta); ?>">
									<span class="<?= ICON_PHONE; ?>"></span> Agregar Teléfono
								</button>
								<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="agregar_telefono">
								  <div class="modal-dialog modal-sm">
								    <div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span>
											</button>
											<h4 class="modal-title">Teléfonos por Alumno</h4>
										</div>
								        <div class="modal-body">
											<?php
											$attributes = array(
											  'id'      	=> 'form_telefonoxalumno',
											  'name'    	=> 'form_telefonoxalumno',
											  'class'		=> 'form-horizontal'
											  );
											echo form_open($ruta, $attributes);
											?>
								            <div class="form-group">
								              <label for="opte_id" class="form-control-label span0">Operador Telefónico</label>
							                  <select class="selectpicker span3" name="opte_id" id="opte_id" data-container="body">
							                  	<option value="">Seleccione</option>
							                  	<?php
							                  		if(isset($data_opte)){
							                  			foreach ($data_opte as $opte) {
							                  				?>
							                  				<option value="<?= $opte->opte_id; ?>"><?= $opte->opte_descripcion; ?></option>
							                  				<?php
							                  			}//end foreach
							                  		}//end if
							                  	?>
							                  </select>
								            </div>

											<div class="form-group">
												<label class="form-control-label span0" for="txal_principal">Marcar Principal</label>
												<div style="padding-bottom: 6px;">
													<input type="checkbox" name="txal_principal" data-toggle="checkbox-x" data-three-state="false" class="mxro_consulta row_permisos" value="0" id="txal_principal">
												</div>
											</div>

								            <div class="form-group">
												<label for="txal_numero" class="form-control-label span0">Número Telef.</label>
												<input type="text" value="" id="txal_numero" name="txal_numero" class="span2" maxlength="45">
								            </div>
									       	<?php
									        echo form_close();
									        ?>
								        </div>
								        <div class="modal-footer">
										  <button class="btn btn-primary btn-small" data-dismiss="modal" type="button">
											<span class="btn-icon-only <?= ICON_BACK; ?>"></span> Cancelar
										  </button>
										  <button class="btn btn-default btn-right btn-small" id="btn_agregar_telefono">
											<span class="btn-icon-only <?= ICON_SAVE; ?>"></span> Agregar
										  </button>
								        </div>
								    </div>
								  </div>
								</div>
								<?php
								}
								?>
							</div>
							<table class="table table-bordered">
				                <thead>
				                  <tr>
				                    <th class="cabecera-tabla"> Línea Académica </th>
				                    <th class="cabecera-tabla"> Teléfonos </th>
				                  </tr>
				                </thead>
								<tr>
									<td class="texto-centrado table-three-colum">
										<div class="container-list" id="estado_x_alumno" data-url="">
											<ul class="list-group">
											  <li class="list-group-item">
												<?php
												if(isset($data_exal)){
													$count_exal = 0;
													foreach ($data_exal as $item => $estadoxalumno) {
														$count_exal++;
													?>
														<a href="javascript:;" class="list-group-item" data-exalid="<?= $estadoxalumno->exal_id; ?>">
															<?= $estadoxalumno->esal_descripcion.' - '.fecha_latino($estadoxalumno->exal_fecha_movimiento); ?>
														</a>
													<?php
													}//end foreach
													if($count_exal == 0){
													?>
														<span>No tiene registros guardados</span>
													<?php
													}//end if
												}else{
												?>
													<span>No tiene registros guardados</span>
												<?php
												}//end else
												?>
											  </li>
											</ul>
										</div>
									</td>
									<td class="texto-centrado table-three-colum">
										<div class="container-list" id="telefono_x_alumno" data-url="">
											<?php
											if(isset($data_txal)){
											?>
											<ul class="list-group">
												<li class="list-group-item">
											<?php
												foreach ($data_txal as $item => $telefonoxalumno) {
													$ruta_update 	= 'registros/telefonoxalumno/agregartelefono/'.str_encrypt($telefonoxalumno->alum_id, KEY_ENCRYPT).'/'.str_encrypt($telefonoxalumno->txal_id, KEY_ENCRYPT);
													$ruta_eliminar 	= 'registros/telefonoxalumno/eliminar/'.str_encrypt($telefonoxalumno->alum_id, KEY_ENCRYPT).'/'.str_encrypt($telefonoxalumno->txal_id, KEY_ENCRYPT);
											?>
												<a href="javascript:;" class="list-group-item item-asignado" 
													data-url="<?= base_url($ruta_eliminar); ?>" data-urledit="<?= base_url($ruta_update); ?>" 
													data-opteid="<?= $telefonoxalumno->opte_id; ?>" 
													data-txalnumero="<?= $telefonoxalumno->txal_numero; ?>" 
													data-txalprincipal="<?= $telefonoxalumno->txal_principal; ?>" 
													>
													<?= $telefonoxalumno->opte_descripcion; ?> 
													 - 
													<?= $telefonoxalumno->txal_numero; ?> 
													<i class="glyphicon glyphicon-trash btn_delete item-delete"></i>
													<i class="glyphicon glyphicon-pencil btn_editar item-edit"></i>
												</a>
											<?php
												}
											?>
												</li>
											</ul>
											<?php
											}else{
											?>
											<ul class="list-group">
											  <li class="list-group-item">
											    <span>No tiene teléfonos guardados</span>
											  </li>
											</ul>
											<?php
											}
											?>
										</div>
									</td>
								</tr>
							</table>
							<hr>
							<br>
							<div class="form-subheader">
								<h3>Datos de Apoderado(s)</h3>
								<?php
								if(isset($data_alum)){
									$ruta = 'registros/apoderadoxalumno/agregarapoderado/'.(str_encrypt($data_alum->alum_id, KEY_ENCRYPT));
								?>
								<button class="btn btn-default btn-right btn-small" data-toggle="modal" data-target="#agregar_apoderado" id="btn_agregarapoderado" data-url="<?= base_url($ruta); ?>">
									<span class="<?= ICON_USER; ?>"></span> Agregar Apoderado
								</button>
								<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="agregar_apoderado">
								  <div class="modal-dialog modal-sm">
								    <div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span>
											</button>
											<h4 class="modal-title">Apoderado(s) por Alumno</h4>
										</div>
								        <div class="modal-body">
											<?php
											$attributes = array(
											  'id'      	=> 'form_apoderadousuario',
											  'name'    	=> 'form_apoderadousuario',
											  'class'		=> 'form-horizontal'
											  );
											echo form_open($ruta, $attributes);
											?>
								            <div class="form-group">
												<label for="apoa_nombre" class="form-control-label span0">Nombres(*)</label>
												<input type="text" value="" id="apoa_nombre" name="apoa_nombre" class="span3" maxlength="80">
								            </div>
								            <div class="form-group">
												<label for="apoa_apellido" class="form-control-label span0">Apellidos(*)</label>
												<input type="text" value="" id="apoa_apellido" name="apoa_apellido" class="span3" maxlength="80">
								            </div>
								            <div class="form-group">
												<label for="apoa_direccion" class="form-control-label span0">Dirección</label>
												<input type="text" value="" id="apoa_direccion" name="apoa_direccion" class="span3" maxlength="200">
								            </div>
								            <div class="form-group">
												<label for="apoa_telefijo" class="form-control-label span0">Telef. Fijo</label>
												<input type="text" value="" id="apoa_telefijo" name="apoa_telefijo" class="span3" maxlength="20">
								            </div>
								            <div class="form-group">
												<label for="apoa_telemovil" class="form-control-label span0">Telef. Móvil</label>
												<input type="text" value="" id="apoa_telemovil" name="apoa_telemovil" class="span3" maxlength="20">
								            </div>
								            <div class="form-group">
												<label for="apoa_email" class="form-control-label span0">Correo</label>
												<input type="text" value="" id="apoa_email" name="apoa_email" class="span3" maxlength="100">
								            </div>
											<div class="form-group">
												<label class="form-control-label span0" for="apoa_principal">Llamar en caso de emergencia?</label>
												<div style="padding-bottom: 6px;">
													<input type="checkbox" name="apoa_principal" data-toggle="checkbox-x" data-three-state="false" class="" value="0" id="apoa_principal">
												</div>
											</div>
									       	<?php
									        echo form_close();
									        ?>
								        </div>
								        <div class="modal-footer">
										  <button class="btn btn-primary btn-small" data-dismiss="modal" type="button">
											<span class="btn-icon-only <?= ICON_BACK; ?>"></span> Cancelar
										  </button>
										  <button class="btn btn-default btn-right btn-small" id="btn_agregar_apoderado">
											<span class="btn-icon-only <?= ICON_SAVE; ?>"></span> Agregar
										  </button>
								        </div>
								    </div>
								  </div>
								</div>
								<?php
								}//end if
								?>
							</div>

							<table class="table table-striped table-bordered">
				                <thead>
				                  <tr>
				                    <th class="cabecera-tabla"> Nro. </th>
				                    <th class="cabecera-tabla"> Nombres </th>
				                    <th class="cabecera-tabla"> Dirección </th>
				                    <th class="cabecera-tabla"> Correo </th>
				                    <th class="cabecera-tabla"> Teléfonos </th>
				                    <th class="cabecera-tabla"> Llamar en EMERG. </th>
				                    <th class="cabecera-tabla td-actions"> </th>
				                  </tr>
				                </thead>
				                <tbody>
				                	<?php
				                	if(isset($data_apoa)){
				                		foreach ($data_apoa as $item => $apoderado) {
				                			$ruta = 'registros/apoderadoxalumno/agregarapoderado/'.(str_encrypt($data_alum->alum_id, KEY_ENCRYPT)).'/'.(str_encrypt($apoderado->apoa_id, KEY_ENCRYPT));
				               		?>
										<tr>
											<td class="texto-centrado"><?= str_pad(($item+1), 5, '0', STR_PAD_LEFT); ?></td>
											<td class="texto-centrado"><?= $apoderado->apoa_nombre.' '.$apoderado->apoa_apellido; ?></td>
											<td class=""><?= $apoderado->apoa_direccion; ?></td>
											<td class=""><?= $apoderado->apoa_email; ?></td>
											<td class="texto-centrado"><?= (($apoderado->apoa_telefijo=='')?'':'Fijo: '.$apoderado->apoa_telefijo.' /').(($apoderado->apoa_telemovil=='')?'':' Móvil: '.$apoderado->apoa_telemovil); ?></td>
											<td class="texto-centrado"><?= interpretar_booleanchar($apoderado->apoa_principal); ?></td>
											<td class="texto-centrado td-actions">

						                    	<a title="Editar" class="btn btn-small btn-invert btn_editar apoderado-edit" href="javascript:;" 
						                    		data-urledit="<?= base_url($ruta); ?>" 
						                    		data-apoanombre="<?= $apoderado->apoa_nombre; ?>" 
						                    		data-apoaapellido="<?= $apoderado->apoa_apellido; ?>" 
						                    		data-apoadireccion="<?= $apoderado->apoa_direccion; ?>" 
						                    		data-apoatelefijo="<?= $apoderado->apoa_telefijo; ?>" 
						                    		data-apoatelemovil="<?= $apoderado->apoa_telemovil; ?>" 
						                    		data-apoaemail="<?= $apoderado->apoa_email; ?>" 
						                    		data-apoaprincipal="<?= $apoderado->apoa_principal; ?>" 
						                    		>
						                    		<i class="btn-icon-only <?= ICON_EDIT; ?>"> </i>
						                    	</a>
						                    	<a title="Eliminar" class="btn btn-small btn-danger tr_delete" href="javascript:;" data-url="<?= base_url('registros/apoderadoxalumno/eliminar/'.str_encrypt($data_alum->alum_id, KEY_ENCRYPT).'/'.str_encrypt($apoderado->apoa_id, KEY_ENCRYPT)); ?>">
						                    		<i class="btn-icon-only <?= ICON_DELETE; ?>"> </i>
						                    	</a>
											</td>
										</tr>
				               		<?php
				                		}//end foreach
				                	}else{
				                	?>
				                		<tr>
				                			<td class="texto-centrado" colspan="7">No tiene registros guardados</td>
				                		</tr>
				                	<?php
				                	}//end else
				                	?>
				                </tbody>
				            </table>
							
							<div class="clearfix">
								<br><br>
								<br><br>
							</div>
						</div>
					</div> <!-- /widget-content -->
				</div> <!-- /widget -->
		    </div> <!-- /span8 -->
	      </div> <!-- /row -->
	    </div> <!-- /container -->
	</div> <!-- /main-inner -->
</div> <!-- /main -->

<script src="<?php echo base_url('public/assets/js/Alumno.js') ?>"></script>