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
	                        <li><a href="<?php echo base_url('seguridad/usuario') ?>"><i class="<?= $header_icon; ?>"></i>&nbsp; Usuarios</a></li>
	                        <li>&nbsp; /</li>
	                        <li class="active"><i class="<?= ICON_FORM; ?>"></i>&nbsp; Formulario</li>
	                    </ul>
	  				</div> <!-- /widget-header -->
					<div class="widget-content">
						<div id="formcontrols" class="">
							<?php
							$attributes = array(
							  'id'      	=> 'form_usuario',
							  'name'    	=> 'form_usuario',
							  'class'		=> 'form-horizontal',
							  'enctype'		=> 'multipart/form-data',
							  'tipo_vista' 	=> $tipo_vista
							  );
							$ruta = 'seguridad/usuario/guardar/'.((isset($data_usua))?str_encrypt($data_usua->usua_id, KEY_ENCRYPT):'');
							echo form_open($ruta, $attributes);
							?>
							<fieldset>
								<div class="control-group">
									<label for="usua_codigo" class="control-label">Código</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_usua))?$data_usua->usua_codigo:set_value('usua_codigo'); ?>" id="usua_codigo" name="usua_codigo" class="span8" maxlength="5">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->

								<div class="control-group">
									<label for="usua_nombre" class="control-label">Nombre</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_usua))?$data_usua->usua_nombre:set_value('usua_nombre'); ?>" id="usua_nombre" name="usua_nombre" class="span8" maxlength="80">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->

								<div class="control-group">
									<label for="usua_apellido" class="control-label">Apellido</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_usua))?$data_usua->usua_apellido:set_value('usua_apellido'); ?>" id="usua_apellido" name="usua_apellido" class="span8" maxlength="80">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->

								<div class="control-group">
									<label for="dide_id" class="control-label">Tipo Documento</label>
									<div class="controls">
					                    <select class="selectpicker span8" name="dide_id" id="dide_id" data-container="body">
					                    <option value="">Seleccione</option>
					                    <?php
					                    if(isset($data_doid)){
					                    	foreach ($data_doid as $item => $docidentidad) {
					                   	?>
											<option value="<?= $docidentidad->dide_id; ?>" data-length="<?= $docidentidad->dide_caracteres; ?>" <?= (isset($data_usua) && $data_usua->dide_id === $docidentidad->dide_id)?'selected': set_select('dide_id', $docidentidad->dide_id); ?>><?= $docidentidad->dide_descripcion; ?></option>
					                   	<?php
					                    	}
					                    }
					                    ?>
					                    </select>
									</div> <!-- /controls -->
								</div> <!-- /control-group -->

								<div class="control-group">
									<label for="usua_numero_documento" class="control-label">Doc. Identidad <span id="dideid_maxlength">(8 dig.)</span></label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_usua))?$data_usua->usua_numero_documento:set_value('usua_numero_documento'); ?>" id="usua_numero_documento" name="usua_numero_documento" class="span8" maxlength="8">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->
								<div class="control-group">
									<label for="usua_clave" class="control-label">Clave</label>
									<div class="controls">
										<input type="password" value="" id="usua_clave" name="usua_clave" class="span3 form-control" maxlength="50" <?= isset($data_usua)?'disabled':''?> >
										<?php
										if(isset($data_usua)){
										?>
										<input type="checkbox" id="cambiar_clave" checked="" value="0" class="cambiar_clave" data-three-state="false" data-toggle="checkbox-x"> 
										<label for="cambiar_clave" style="display: inline-block;">Cambiar Clave?</label>
										<?php
										}//end if
										?>
									</div> <!-- /controls -->
								</div> <!-- /control-group -->

								<div class="control-group">
									<label for="usua_ruta_imagen" class="control-label">Foto</label>
									<div class="controls">
										<input type="file" id="usua_ruta_imagen" name="usua_ruta_imagen" class="span4">
										<?php 
										if(isset($data_usua)){
										$ruta_imagen = ($data_usua->usua_ruta_imagen != '' && $data_usua->usua_ruta_imagen != NULL) ? base_url(IMG_PATH.'usuarios/'.$data_usua->usua_ruta_imagen) : '';
											if($ruta_imagen != ''){
										?>
										<img src="<?= $ruta_imagen; ?>" alt="<?= $data_usua->usua_nombre.' '.$data_usua->usua_apellido; ?>" class="img-thumbnail span2 float_rigth">
										<?php
											}//end if
										}//end if
										?>
									</div> <!-- /controls -->
								</div> <!-- /control-group -->

								<div class="control-group">
									<label for="usua_email" class="control-label">E-mail</label>
									<div class="controls">
										<input type="email" id="usua_email" name="usua_email" class="span8 form-control" maxlength="100" value="<?= (isset($data_usua))?$data_usua->usua_email:set_value('usua_email'); ?>">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->

								<div class="control-group">
									<label for="usua_email_personal" class="control-label">E-mail Personal</label>
									<div class="controls">
										<input type="email" id="usua_email_personal" name="usua_email_personal" class="span8 form-control" maxlength="100" value="<?= (isset($data_usua))?$data_usua->usua_email_personal:set_value('usua_email_personal'); ?>">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->

								<div class="control-group">
									<label for="usua_fecha_nacimiento" class="control-label">Fecha de nacimiento</label>
									<div class="controls">
									    <div class="input-append date datepicker" data-date="<?= (isset($data_usua))?fecha_latino($data_usua->usua_fecha_nacimiento):date('d/m/Y'); ?>" data-date-format="dd/mm/yyyy">
									      <input class="span2" size="16" type="text" value="<?= (isset($data_usua))?fecha_latino($data_usua->usua_fecha_nacimiento):date('d/m/Y'); ?>" id="usua_fecha_nacimiento" name="usua_fecha_nacimiento">
									      <span class="add-on"><i class="icon-th"></i></span>
									    </div>
									</div> <!-- /controls -->
								</div> <!-- /control-group -->

								<div class="control-group">
									<label for="usua_direccion" class="control-label">Dirección</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_usua))?$data_usua->usua_direccion:set_value('usua_direccion'); ?>" id="usua_direccion" name="usua_direccion" class="span8" maxlength="200">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->

								<div class="control-group">
									<label for="usua_cargolaboral" class="control-label">Cargo Laboral</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_usua))?$data_usua->usua_cargolaboral:set_value('usua_cargolaboral'); ?>" id="usua_cargolaboral" name="usua_cargolaboral" class="span8" maxlength="100">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->

								<div class="control-group">
									<label for="usua_activolaboral" class="control-label">Estado</label>
									<div class="controls">
					                    <select class="selectpicker span8" name="usua_activolaboral" id="usua_activolaboral">
					                    <option value="S"> Activo </option>
					                    <option value="N"> Inactivo </option>
					                    </select>
									</div> <!-- /controls -->
								</div> <!-- /control-group -->

							</fieldset>
					       	<?php
					        echo form_close();
					        ?>
							<hr>
							<br>
							<div class="form-subheader">
								<h3>Oficinas Asignadas</h3>
								<?php
								if(isset($data_usua)){
								?>
								<button class="btn btn-default btn-right btn-small btn-editar" data-toggle="modal" data-target="#asignar_oficina" disabled="disabled" id="btn_asignaroficina">
									<span class="btn-icon-only <?= ICON_NEW; ?>"></span> Asignar Oficina
								</button>
								<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="asignar_oficina">
								  <div class="modal-dialog modal-sm">
								    <div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span>
											</button>
											<h4 class="modal-title">Oficinas por usuario</h4>
										</div>
								        <div class="modal-body">
											<?php
											$attributes = array(
											  'id'      	=> 'form_oficinausuario',
											  'name'    	=> 'form_oficinausuario',
											  'class'		=> 'form-horizontal'
											  );
											$ruta = 'seguridad/oficinausuario/asignaroficina/'.((isset($data_usua))?str_encrypt($data_usua->usua_id, KEY_ENCRYPT):'');
											echo form_open($ruta, $attributes);
											?>
								            <div class="form-group">
								              <label for="ofic_id" class="form-control-label span0">Oficina</label>
							                  <select class="selectpicker span4" name="ofic_id" id="ofic_id" disabled="" data-container="body">
							                  </select>
								            </div>
								            <div class="form-group">
								              <label for="uxof_estadodefecto" class="form-control-label span0">Oficina por defecto?</label>
							                  <select class="selectpicker span4" name="uxof_estadodefecto" id="uxof_estadodefecto" data-container="body">
							                  	<option value="N">No</option>
							                  	<option value="S">S&iacute;</option>
							                  </select>
								            </div>
									       	<?php
									        echo form_close();
									        ?>
								        </div>
								        <div class="modal-footer">
										  <button class="btn btn-primary btn-small" data-dismiss="modal" type="button">
											<span class="btn-icon-only <?= ICON_BACK; ?>"></span> Cancelar
										  </button>
										  <button class="btn btn-default btn-right btn-small" id="btn_asignar_oficina">
											<span class="btn-icon-only <?= ICON_SAVE; ?>"></span> Asignar
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
				                    <th class="cabecera-tabla"> Oficinas </th>
				                    <th class="cabecera-tabla"> Asignadas </th>
				                  </tr>
				                </thead>
								<tr>
									<td class="texto-centrado table-three-colum">
										<div class="container-list" id="oficina-grupo" data-url="">
											<ul class="list-group">
											  <li class="list-group-item">
												<?php
												if(isset($data_ofic)){
													$count_ofic = 0;
													foreach ($data_ofic as $item => $oficina) {
														if($oficina->uxof_id == ''){
															$ruta = 'seguridad/oficinausuario/asignaroficina/'.(isset($data_usua)?str_encrypt($data_usua->usua_id, KEY_ENCRYPT):'');
															$count_ofic++;
													?>
														<a href="javascript:;" class="list-group-item oficina-grupo" data-oficid="<?= $oficina->ofic_id; ?>" data-url="<?= base_url($ruta); ?>">
															<span class="glyphicon glyphicon-ok-sign check-hidden"></span>
															<?= $oficina->ofic_nombre; ?>
														</a>
													<?php
														}//end if
													}//end foreach
													if($count_ofic == 0){
													?>
														<span>No tiene más oficinas disponibles</span>
													<?php
													}//end if
												}else{
												?>
													<span>No tiene oficinas disponibles</span>
												<?php
												}//end else
												?>
											  </li>
											</ul>
										</div>
									</td>
									<td class="texto-centrado table-three-colum">
										<div class="container-list" id="oficinas_asignadas" data-url="">
											<?php
											if(isset($data_uxof)){
											?>
											<ul class="list-group">
												<li class="list-group-item">
											<?php
												foreach ($data_uxof as $item => $oficinausuario) {
													$ruta = 'seguridad/oficinausuario/asignaroficina/'.str_encrypt($data_usua->usua_id, KEY_ENCRYPT).'/'.str_encrypt($oficinausuario->uxof_id, KEY_ENCRYPT);
											?>
												<a href="javascript:;" class="list-group-item item-asignado" 
													data-url="<?= base_url('seguridad/oficinausuario/eliminar/'.str_encrypt($data_usua->usua_id, KEY_ENCRYPT).'/'.str_encrypt($oficinausuario->uxof_id, KEY_ENCRYPT)); ?>" 
													data-uxofid="<?= $oficinausuario->uxof_id; ?>" 
													data-oficid="<?= $oficinausuario->ofic_id; ?>" 
													data-oficname="<?= $oficinausuario->ofic_nombre; ?>" 
													data-uxofdefecto="<?= $oficinausuario->uxof_estadodefecto; ?>" 
													data-urledit="<?= base_url($ruta); ?>"
													>
													<?= $oficinausuario->ofic_nombre; ?> 
													<?= ($oficinausuario->uxof_estadodefecto === 'S')?' - Principal':''; ?>
													<i class="glyphicon glyphicon-trash oficina-delete btn_delete"></i>
													<i class="glyphicon glyphicon-pencil oficina-edit"></i>
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
											    <span>No tiene oficinas asignadas</span>
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
								<h3>Roles Asignados</h3>
								<?php
								if(isset($data_usua)){
								?>
								<button class="btn btn-default btn-right btn-small" data-toggle="modal" data-target="#asignar_rol" disabled="disabled" id="btn_asignarrol">
									<span class="btn-icon-only <?= ICON_NEW; ?>"></span> Asignar Rol
								</button>
								<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="asignar_rol">
								  <div class="modal-dialog modal-sm">
								    <div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span>
											</button>
											<h4 class="modal-title">Asignar Rol por usuario</h4>
										</div>
								        <div class="modal-body">
											<?php
											$attributes = array(
											  'id'      	=> 'form_rolusuario',
											  'name'    	=> 'form_rolusuario',
											  'class'		=> 'form-horizontal'
											  );
											$ruta = 'seguridad/rolusuario/asignarrol/'.((isset($data_usua))?str_encrypt($data_usua->usua_id, KEY_ENCRYPT):'');
											echo form_open($ruta, $attributes);
											?>
								            <div class="form-group">
								              <label for="uxof_id" class="form-control-label span0">Oficina</label>
							                  <select class="selectpicker span4" name="uxof_id" id="uxof_id" disabled="" data-container="body">

												<?php
												if(isset($data_uxof)){
													foreach ($data_uxof as $item => $oficinausuario) {
												?>
													<option value="<?= $oficinausuario->uxof_id; ?>">
														<?= $oficinausuario->ofic_nombre; ?>
														<?= ($oficinausuario->uxof_estadodefecto === 'S')?' - Principal':''; ?>
													</option>
												<?php
													}//end foreach
												}//end if
												?>

							                  </select>
								            </div>
								            <div class="form-group">
								              <label for="rol_id" class="form-control-label span0">Rol</label>
							                  <select class="selectpicker span4" name="rol_id" id="rol_id" disabled="" data-container="body">

												<?php
												if(isset($data_rol)){
													foreach ($data_rol as $item => $rol) {
												?>
													<option value="<?= $rol->rol_id; ?>">
														<?= $rol->rol_nombre; ?>
													</option>
												<?php
													}//end foreach
												}//end if
												?>

							                  </select>
								            </div>
									       	<?php
									        echo form_close();
									        ?>
								        </div>
								        <div class="modal-footer">
										  <button class="btn btn-primary btn-small" data-dismiss="modal" type="button">
											<span class="btn-icon-only <?= ICON_BACK; ?>"></span> Cancelar
										  </button>
										  <button class="btn btn-default btn-right btn-small" id="btn_asignar_rol">
											<span class="btn-icon-only <?= ICON_SAVE; ?>"></span> Asignar
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
				                    <th class="cabecera-tabla"> Oficinas asignadas </th>
				                    <th class="cabecera-tabla"> Roles </th>
				                    <th class="cabecera-tabla"> Asignados </th>
				                  </tr>
				                </thead>
								<tr>
									<td class="texto-centrado table-three-colum">
										<div class="container-list" id="oficina_x_usuario">
											<?php
											if(isset($data_uxofx)){
											?>
											<div class="list-group-item">
											<?php
												$count_uxof = 0;
												foreach ($data_uxofx as $item => $oficinausuario) {
													if($oficinausuario->rxus_id == ''){
														$count_uxof++;
														$ruta = 'seguridad/rolusuario/asignarrol/'.((isset($data_usua))?str_encrypt($data_usua->usua_id, KEY_ENCRYPT):'');
											?>
												<a href="javascript:;" class="list-group-item oficina_x_usuario" 
													data-uxofid="<?= $oficinausuario->uxof_id; ?>" 
													data-oficid="<?= $oficinausuario->ofic_id; ?>" 
													data-url="<?= base_url($ruta); ?>" 
													>
													<span class="glyphicon glyphicon-ok-sign check-hidden"></span>
													<?= $oficinausuario->ofic_nombre; ?>
													<?= ($oficinausuario->uxof_estadodefecto === 'S')?' - Principal':''; ?>
												</a>
											<?php
													}//end if
												}//end foreach

												if($count_uxof == 0){
												?>
													<span>No tiene más oficinas disponibles</span>
												<?php
												}//end if
											?>
											</div>
											<?php
											}else{
											?>
											<ul class="list-group">
											  <li class="list-group-item">
											    <span>No tiene oficinas asignadas</span>
											  </li>
											</ul>
											<?php
											}
											?>
										</div>
									</td>
									<td class="texto-centrado table-three-colum">
										<div class="container-list" id="roles_item">
											<div class="list-group-item">
											<?php
											if(isset($data_rol)){
												foreach ($data_rol as $item => $rol) {
													$ruta = 'seguridad/rolusuario/asignarrol/'.((isset($data_usua))?str_encrypt($data_usua->usua_id, KEY_ENCRYPT):'');
											?>
												<a href="javascript:;" class="list-group-item roles_item" 
													data-rolid="<?= $rol->rol_id; ?>" 
													data-url="<?= base_url($ruta); ?>" 
													>
													<span class="glyphicon glyphicon-ok-sign check-hidden"></span>
													<?= $rol->rol_nombre; ?>
												</a>
											<?php
												}//end foreach
											}
											?>
											</div>
										</div>
									</td>
									<td class="texto-centrado table-three-colum">
										<div class="container-list" id="rol_x_usuario">
											<?php
											if(isset($data_rxus)){
											?>
											<div class="list-group-item">
											<?php
												foreach ($data_rxus as $item => $rolusuario) {
													$ruta = 'seguridad/rolusuario/asignarrol/'.str_encrypt($data_usua->usua_id, KEY_ENCRYPT).'/'.str_encrypt($rolusuario->rxus_id, KEY_ENCRYPT);
											?>
												<a href="javascript:;" class="list-group-item item-asignado" 
													data-url="<?= base_url('seguridad/rolusuario/eliminar/'.str_encrypt($data_usua->usua_id, KEY_ENCRYPT).'/'.str_encrypt($rolusuario->rxus_id, KEY_ENCRYPT)); ?>" 
													data-rxusid="<?= $rolusuario->rxus_id; ?>" 
													data-uxofid="<?= $rolusuario->uxof_id; ?>" 
													data-rolid="<?= $rolusuario->rol_id; ?>" 
													data-urledit = "<?= base_url($ruta); ?>" 
													>
													<?= $rolusuario->ofic_nombre.' - '.$rolusuario->rol_nombre; ?>
													<i class="glyphicon glyphicon-trash rol-delete btn_delete"></i>
													<i class="glyphicon glyphicon-pencil rol-edit"></i>
												</a>
											<?php
												}//end foreach
											?>
											</div>
											<?php
											}else{
											?>
											<ul class="list-group">
											  <li class="list-group-item">
											    <span>No tiene roles asignados</span>
											  </li>
											</ul>
											<?php
											}
											?>
										</div>
									</td>
								</tr>
							</table>
						</div>
					</div> <!-- /widget-content -->
				</div> <!-- /widget -->
		    </div> <!-- /span8 -->
	      </div> <!-- /row -->
	    </div> <!-- /container -->
	</div> <!-- /main-inner -->
</div> <!-- /main -->

<script src="<?php echo base_url('public/assets/js/Usuario.js') ?>"></script>