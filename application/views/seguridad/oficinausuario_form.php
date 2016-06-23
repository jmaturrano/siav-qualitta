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
							  'tipo_vista' 	=> $tipo_vista
							  );
							$ruta = 'seguridad/usuario/guardar/'.((isset($data_usua))?str_encrypt($data_usua->usua_id, KEY_ENCRYPT):'');
							echo validation_errors();
							echo form_open($ruta, $attributes);
							?>
							<fieldset>
								<div class="control-group">
									<label for="usua_nombre" class="control-label">Nombre</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_usua))?$data_usua->usua_nombre:set_value('usua_nombre'); ?>" id="usua_nombre" name="usua_nombre" class="span8" maxlength="40">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->
								<div class="control-group">
									<label for="dide_id" class="control-label">Tipo Documento</label>
									<div class="controls">
					                    <select class="selectpicker span8" name="dide_id" id="dide_id">
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
									<div class="controls checkbox input-group">
										<label class="input-group-addon" style="display: none;">
											<input type="checkbox" class="chkbx_x">
										</label>
										<input type="password" value="" id="usua_clave" name="usua_clave" class="span8 form-control" maxlength="50">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->
								<div class="control-group">
									<label for="usua_fecha_expiracion" class="control-label">Fecha de expiración</label>
									<div class="controls">
								    <div class="input-append date datepicker" data-date="<?= (isset($data_usua))?date('d/m/Y', strtotime($data_usua->usua_fecha_expiracion)):date('d/m/Y'); ?>" data-date-format="dd/mm/yyyy">
								      <input class="span2" size="16" type="text" value="<?= (isset($data_usua))?date('d/m/Y', strtotime($data_usua->usua_fecha_expiracion)):date('d/m/Y'); ?>" id="usua_fecha_expiracion" name="usua_fecha_expiracion">
								      <span class="add-on"><i class="icon-th"></i></span>
								    </div>
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
							</div>
							<table class="table table-striped table-bordered">
				                <thead>
				                  <tr>
				                    <th> Oficinas Grupo </th>
				                    <th> Oficinas </th>
				                    <th> Asignadas </th>
				                  </tr>
				                </thead>
								<tr>
									<td>
										<div class="list-group">
										<?php
										if(isset($data_ofig)){
											foreach ($data_ofig as $item => $oficinagrupo) {
										?>
											<a href="#" class="list-group-item" data-id="<?= $oficinagrupo->ogru_id; ?>"><?= $oficinagrupo->ogru_nombre; ?></a>
										<?php
											}
										}
										?>
										</div>
									</td>
									<td>
										<div class="list-group">
										<?php
										if(isset($data_ofig)){
											foreach ($data_ofig as $item => $oficinagrupo) {
										?>
											<a href="#" class="list-group-item" data-id="<?= $oficinagrupo->ogru_id; ?>"><?= $oficinagrupo->ogru_nombre; ?></a>
										<?php
											}
										}
										?>
										</div>
									</td>
									<td>
										<div class="list-group">
										<?php
										if(isset($data_ofig)){
											foreach ($data_ofig as $item => $oficinagrupo) {
										?>
											<a href="#" class="list-group-item" data-id="<?= $oficinagrupo->ogru_id; ?>"><?= $oficinagrupo->ogru_nombre; ?></a>
										<?php
											}
										}
										?>
										</div>
									</td>
								</tr>
							</table>


							<?php
							if(isset($data_ofxu)){
							?>
							<table class="table table-striped table-bordered">
				                <thead>
				                  <tr>
				                    <th> Nro. </th>
				                    <th> Oficina </th>
				                    <th> Por defecto </th>
				                    <th> Email </th>
				                    <th class="td-actions"> </th>
				                  </tr>
				                </thead>
				                <tbody>
				                	<?php
				                	foreach ($data_ofxu as $item => $oficinaxusuario) {
				               		?>
										<tr>
											<td><?= str_pad(($item+1), 5, '0', STR_PAD_LEFT); ?></td>
											<td><?= $oficinaxusuario->ofic_nombre; ?></td>
											<td><?= $oficinaxusuario->ofic_abreviatura; ?></td>
											<td><?= $oficinaxusuario->ofic_email; ?></td>
											<td class="td-actions">
						                    	<a class="btn btn-small btn-danger" href="javascript:;" data-url="<?= base_url('seguridad/oficina/eliminar/'.str_encrypt($data_ofig->ogru_id, KEY_ENCRYPT).'/'.str_encrypt($oficina->ofic_id, KEY_ENCRYPT)); ?>">
						                    		<i class="btn-icon-only <?= ICON_DELETE; ?>"> </i>
						                    	</a>
											</td>
										</tr>
				               		<?php
				                	}
				                	?>
				                </tbody>
				            </table>
							<?php
							}else{
							?>
								<table class="table table-striped table-bordered">
									<tr>
										<td>No tiene oficinas asignadas</td>
									</tr>
								</table>
							<?php
							}
							?>
							<hr>
							<br>

							<div class="form-subheader">
								<h3>Roles Asignados</h3>
							</div>
							<?php
							if(isset($data_rxus)){
							?>
							<table class="table table-striped table-bordered">
				                <thead>
				                  <tr>
				                    <th> Nro. </th>
				                    <th> Rol </th>
				                    <th> Oficina </th>
				                    <th> Fecha registro </th>
				                    <th class="td-actions"> </th>
				                  </tr>
				                </thead>
				                <tbody>
				                	<?php
				                	foreach ($data_rxus as $item => $rolxusuario) {
				               		?>
										<tr>
											<td><?= str_pad(($item+1), 5, '0', STR_PAD_LEFT); ?></td>
											<td><?= $rolxusuario->ofic_nombre; ?></td>
											<td><?= $rolxusuario->ofic_abreviatura; ?></td>
											<td><?= $rolxusuario->ofic_email; ?></td>
											<td class="td-actions">
						                    	<a class="btn btn-small btn-info" href="<?= base_url('seguridad/usuario/ver/'.str_encrypt($usuario->usua_id, KEY_ENCRYPT)); ?>">
						                    		<i class="btn-icon-only <?= ICON_VIEW; ?>"> </i>
						                    	</a>
						                    	<a class="btn btn-small btn-invert" href="<?= base_url('seguridad/usuario/editar/'.str_encrypt($usuario->usua_id, KEY_ENCRYPT)); ?>">
						                    		<i class="btn-icon-only <?= ICON_EDIT; ?>"> </i>
						                    	</a>
						                    	<a class="btn btn-small btn-danger" href="javascript:;" data-url="<?= base_url('seguridad/oficina/eliminar/'.str_encrypt($data_ofig->ogru_id, KEY_ENCRYPT).'/'.str_encrypt($oficina->ofic_id, KEY_ENCRYPT)); ?>">
						                    		<i class="btn-icon-only <?= ICON_DELETE; ?>"> </i>
						                    	</a>
											</td>
										</tr>
				               		<?php
				                	}
				                	?>
				                </tbody>
				            </table>
							<?php
							}else{
							?>
								<table class="table table-striped table-bordered">
									<tr>
										<td>No tiene roles asignados</td>
									</tr>
								</table>
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


<script src="<?php echo base_url('public/assets/js/Usuario.js') ?>"></script>