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
	                        <li><a href="<?php echo base_url('seguridad/reportesmail') ?>"><i class="<?= $header_icon; ?>"></i>&nbsp; Reportes email</a></li>
	                        <li>&nbsp; /</li>
	                        <li class="active"><i class="<?= ICON_FORM; ?>"></i>&nbsp; Formulario</li>
	                    </ul>
	  				</div> <!-- /widget-header -->
					<div class="widget-content">
						<div id="formcontrols" class="">
							<?php
							$attributes = array(
							  'id'      	=> 'form_reportesmail',
							  'name'    	=> 'form_reportesmail',
							  'class'		=> 'form-horizontal',
							  'enctype'		=> 'multipart/form-data',
							  'tipo_vista' 	=> $tipo_vista
							  );
							$ruta = 'seguridad/reportesmail/guardar/'.((isset($data_rema))?str_encrypt($data_rema->rema_id, KEY_ENCRYPT):'');
							echo form_open($ruta, $attributes);
							?>
							<fieldset>
								<div class="control-group">
									<label for="rema_codigo" class="control-label">Código</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_rema))?$data_rema->rema_codigo:set_value('rema_codigo'); ?>" id="rema_codigo" name="rema_codigo" class="span8" maxlength="5">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->

								<div class="control-group">
									<label for="rema_titulo" class="control-label">Título</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_rema))?$data_rema->rema_titulo:set_value('rema_titulo'); ?>" id="rema_titulo" name="rema_titulo" class="span8" maxlength="40">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->

								<div class="control-group">
									<label for="rema_descripcion" class="control-label">Descripción</label>
									<div class="controls">
										<label class="span8">Tags: <?= (isset($data_rema))?$data_rema->rema_tags : ''; ?></label>
										<br>
										<div class="" >
											<textarea id="rema_descripcion" name="rema_descripcion" class="" >
												<?= (isset($data_rema))?$data_rema->rema_descripcion:set_value('rema_descripcion'); ?>
											</textarea>
										</div>
									</div> <!-- /controls -->
								</div> <!-- /control-group -->


							</fieldset>
					       	<?php
					        echo form_close();
					        ?>
							<hr>
							<br>
							<?php
							$attributes = array(
							  'id'      	=> 'form_reportexusuario',
							  'name'    	=> 'form_reportexusuario'
							  );
							$ruta = 'seguridad/reportexusuario/guardar/'.((isset($data_rema))?str_encrypt($data_rema->rema_id, KEY_ENCRYPT):'');
							echo form_open($ruta, $attributes);
							?>
							<div class="control-group">
								<label for="usua_id" class="control-label">Enviar correos a:</label>
								<div class="controls">
				                    <select class="selectpicker span4" name="usua_id" id="usua_id" data-container="body" data-live-search="true">
					                    <option value="">Seleccione</option>
					                    <?php
					                    if(isset($data_usua)){
					                    	foreach ($data_usua as $item => $usuario) {
					                   	?>
											<option value="<?= $usuario->usua_id; ?>"><?= $usuario->usua_apellido.' '.$usuario->usua_nombre; ?></option>
					                   	<?php
					                    	}
					                    }
					                    ?>
				                    </select>
				                    <button class="btn btn-default" style="vertical-align: top;">
				                    	<span class="<?= ICON_ADD; ?>"></span>
				                    </button>
								</div> <!-- /controls -->
							</div> <!-- /control-group -->
					       	<?php
					        echo form_close();
					        ?>
							<div class="table-responsive">
								<table class="table table-striped table-bordered">
					                <thead>
					                  <tr>
					                    <th class="cabecera-tabla"> Nro. </th>
					                    <th class="cabecera-tabla"> Nombre </th>
					                    <th class="cabecera-tabla"> Cargo laboral </th>
					                    <th class="cabecera-tabla"> Estado </th>
					                    <th class="cabecera-tabla td-actions"> </th>
					                  </tr>
					                </thead>
					                <tbody>
					                	<?php
					                	if(!isset($offset))
					                		$offset=0;

					                	
					                	if(isset($data_rexu)){
					                		foreach ($data_rexu as $item => $rexu) {
					               		?>
											<tr>
												<td class="texto-centrado"><?= str_pad(($item+1)+$offset, 5, '0', STR_PAD_LEFT); ?></td>
												<td class="texto-centrado"><?= $rexu->usua_apellido.' '.$rexu->usua_nombre; ?></td>
												<td class="texto-centrado"><?= $rexu->usua_cargolaboral; ?></td>
												<td class="texto-centrado"><?= ($rexu->usua_activolaboral === 'S')?'Activo':'Inactivo'; ?></td>
												<td class="texto-centrado td-actions">
							                    	<a title="Eliminar" class="btn btn-small btn-danger tr_delete" href="javascript:;" data-url="<?= base_url('seguridad/reportexusuario/eliminar/'.str_encrypt($data_rema->rema_id, KEY_ENCRYPT).'/'.str_encrypt($rexu->rexu_id, KEY_ENCRYPT)); ?>">
							                    		<i class="btn-icon-only <?= ICON_DELETE; ?>"> </i>
							                    	</a>
												</td>
											</tr>
					               		<?php
					                		}
					                	}else{
					                		?>
					                		<tr>
					                			<td colspan="5">No se encontraron registros...</td>
					                		</tr>
					                	<?php
					                	}
					                	?>
					                </tbody>
					            </table>
					            <br><br><br>
							</div>

						</div>
					</div> <!-- /widget-content -->
				</div> <!-- /widget -->
		    </div> <!-- /span8 -->
	      </div> <!-- /row -->
	    </div> <!-- /container -->
	</div> <!-- /main-inner -->
</div> <!-- /main -->

<script src="<?php echo base_url('public/assets/js/Reportesmail.js') ?>"></script>