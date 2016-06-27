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
	                        <li><a href="<?php echo base_url('registros/carrera') ?>"><i class="<?= $header_icon; ?>"></i>&nbsp; <?= $header_title; ?></a></li>
	                        <li>&nbsp; /</li>
	                        <li class="active"><i class="<?= ICON_FORM; ?>"></i>&nbsp; Formulario</li>
	                    </ul>
	  				</div> <!-- /widget-header -->
					<div class="widget-content">
						<div id="formcontrols" class="">
							<?php
							$attributes = array(
							  'id'      	=> 'form_carrera',
							  'name'    	=> 'form_carrera',
							  'class'		=> 'form-horizontal',
							  'tipo_vista' 	=> $tipo_vista
							  );
							$ruta = 'registros/carrera/guardar/'.((isset($data_carr))?str_encrypt($data_carr->carr_id, KEY_ENCRYPT):'');
							echo form_open($ruta, $attributes);
							?>
							<fieldset>
								<div class="control-group">
									<label for="carr_codigo" class="control-label">Código</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_carr))?$data_carr->carr_codigo:set_value('carr_codigo'); ?>" id="carr_codigo" name="carr_codigo" class="span8" maxlength="10">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->
								<div class="control-group">
									<label for="carr_descripcion" class="control-label">Carrera</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_carr))?$data_carr->carr_descripcion:set_value('carr_descripcion'); ?>" id="carr_descripcion" name="carr_descripcion" class="span8" maxlength="100">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->
							</fieldset>
					       	<?php
					        echo form_close();
					        ?>

							<hr>
							<br>
							<?php
							if(isset($data_carr)){
							?>
							<div class="form-subheader">
								<h3>Módulos por carrera</h3>
								<a class="btn btn-default btn-right btn-small btn_nuevo" href="<?= base_url('registros/modulosxcarrera/nuevo/'.(str_encrypt($data_carr->carr_id, KEY_ENCRYPT))); ?>">
									<span class="btn-icon-only <?= ICON_NEW; ?>"></span> Agregar Módulo
								</a>
							</div>
							<table class="table table-striped table-bordered">
				                <thead>
				                  <tr>
				                    <th class="cabecera-tabla"> Nro. </th>
				                    <th class="cabecera-tabla"> Código </th>
				                    <th class="cabecera-tabla"> Módulo </th>
				                    <th class="cabecera-tabla"> Cod. Nivel Aprend. </th>
				                    <th class="cabecera-tabla td-actions"> </th>
				                  </tr>
				                </thead>
				                <tbody>
				                	<?php
				                	if(isset($data_modu)){
				                		foreach ($data_modu as $item => $modulo) {
				               		?>
										<tr>
											<td class="texto-centrado"><?= str_pad(($item+1), 5, '0', STR_PAD_LEFT); ?></td>
											<td class="texto-centrado"><?= $modulo->modu_codigo; ?></td>
											<td class=""><?= $modulo->modu_descripcion; ?></td>
											<td class="texto-centrado"><?= $modulo->niap_codigo; ?></td>
											<td class="texto-centrado td-actions">
						                    	<a title="Ver" class="btn btn-small btn-info btn_consulta" href="<?= base_url('registros/modulosxcarrera/ver/'.str_encrypt($data_carr->carr_id, KEY_ENCRYPT).'/'.str_encrypt($modulo->modu_id, KEY_ENCRYPT)); ?>">
						                    		<i class="btn-icon-only <?= ICON_VIEW; ?>"> </i>
						                    	</a>
						                    	<a title="Editar" class="btn btn-small btn-invert btn_editar" href="<?= base_url('registros/modulosxcarrera/editar/'.str_encrypt($data_carr->carr_id, KEY_ENCRYPT).'/'.str_encrypt($modulo->modu_id, KEY_ENCRYPT)); ?>">
						                    		<i class="btn-icon-only <?= ICON_EDIT; ?>"> </i>
						                    	</a>
						                    	<a title="Eliminar" class="btn btn-small btn-danger tr_delete" href="javascript:;" data-url="<?= base_url('registros/modulosxcarrera/eliminar/'.str_encrypt($data_carr->carr_id, KEY_ENCRYPT).'/'.str_encrypt($modulo->modu_id, KEY_ENCRYPT)); ?>">
						                    		<i class="btn-icon-only <?= ICON_DELETE; ?>"> </i>
						                    	</a>
											</td>
										</tr>
				               		<?php
				                		}//end foreach
				                	}else{
		                			?>
		                				<tr>
		                					<td class="texto-centrado" colspan="5">
		                						<span>No se encontraron registros</span>
		                					</td>
		                				</tr>
		                			<?php
				                	}
				                	?>
				                </tbody>
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


<script src="<?php echo base_url('public/assets/js/Carrera.js') ?>"></script>