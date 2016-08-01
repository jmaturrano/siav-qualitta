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
	                        <li><a href="<?php echo base_url('registros/curso') ?>"><i class="<?= $header_icon; ?>"></i>&nbsp; <?= $header_title; ?></a></li>
	                        <li>&nbsp; /</li>
	                        <li class="active"><i class="<?= ICON_FORM; ?>"></i>&nbsp; Formulario</li>
	                    </ul>
	  				</div> <!-- /widget-header -->
					<div class="widget-content">
						<div id="formcontrols" class="">
							<?php
							$attributes = array(
							  'id'      	=> 'form_modulosxcarrera',
							  'name'    	=> 'form_modulosxcarrera',
							  'class'		=> 'form-horizontal',
							  'tipo_vista' 	=> $tipo_vista
							  );
							$ruta = 'registros/modulosxcarrera/guardar/'.((isset($data_carr))?str_encrypt($data_carr->carr_id, KEY_ENCRYPT):'').'/'.((isset($data_modu))?str_encrypt($data_modu->modu_id, KEY_ENCRYPT):'');
							echo form_open($ruta, $attributes);
							?>
							<fieldset>
								<div class="control-group">
									<label for="carr_descripcion" class="control-label">Carrera</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_carr))?'('.$data_carr->carr_codigo.') '.$data_carr->carr_descripcion:set_value('carr_descripcion'); ?>" id="carr_descripcion" name="carr_descripcion" class="span8" maxlength="100" disabled>
									</div> <!-- /controls -->
								</div> <!-- /control-group -->



								<div class="control-group">
									<label for="modu_codigo" class="control-label">Código módulo</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_modu))?$data_modu->modu_codigo:set_value('modu_codigo'); ?>" id="modu_codigo" name="modu_codigo" class="span8" maxlength="10">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->
								<div class="control-group">
									<label for="modu_descripcion" class="control-label">Descripción</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_modu))?$data_modu->modu_descripcion:set_value('modu_descripcion'); ?>" id="modu_descripcion" name="modu_descripcion" class="span8" maxlength="100">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->
							</fieldset>
					       	<?php
					        echo form_close();
					        ?>



							<hr>
							<br>
							<?php
							if(isset($data_modu)){
							?>
							<div class="form-subheader">
								<h3>Cursos</h3>
								<a class="btn btn-default btn-right btn-small btn_nuevo" href="<?= base_url('registros/curso/nuevo/'.str_encrypt($data_carr->carr_id, KEY_ENCRYPT).'/'.(str_encrypt($data_modu->modu_id, KEY_ENCRYPT))); ?>">
									<span class="btn-icon-only <?= ICON_NEW; ?>"></span> Agregar Curso
								</a>
							</div>
							<table class="table table-striped table-bordered">
				                <thead>
				                  <tr>
				                    <th class="cabecera-tabla"> Nro. </th>
				                    <th class="cabecera-tabla"> Código </th>
				                    <th class="cabecera-tabla"> Curso </th>
				                    <th class="cabecera-tabla"> Nivel Aprendizaje </th>
				                    <th class="cabecera-tabla"> Fecha registro </th>
				                    <th class="cabecera-tabla td-actions"> </th>
				                  </tr>
				                </thead>
				                <tbody>
				                	<?php
				                	if(isset($data_curs)){
				                		foreach ($data_curs as $item => $curso) {
				               		?>
										<tr>
											<td class="texto-centrado"><?= str_pad(($item+1), 5, '0', STR_PAD_LEFT); ?></td>
											<td class="texto-centrado"><?= $curso->curs_codigo; ?></td>
											<td class=""><?= $curso->curs_descripcion; ?></td>
											<td class="texto-centrado"><?= $curso->niap_descripcion; ?></td>
											<td class="texto-centrado"><?= fecha_latino($curso->curs_fecha_registro); ?></td>
											<td class="texto-centrado td-actions">
						                    	<a title="Ver" class="btn btn-small btn-info btn_consulta" href="<?= base_url('registros/curso/ver/'.str_encrypt($data_carr->carr_id, KEY_ENCRYPT).'/'.str_encrypt($data_modu->modu_id, KEY_ENCRYPT).'/'.str_encrypt($curso->curs_id, KEY_ENCRYPT)); ?>">
						                    		<i class="btn-icon-only <?= ICON_VIEW; ?>"> </i>
						                    	</a>
						                    	<a title="Editar" class="btn btn-small btn-invert btn_editar" href="<?= base_url('registros/curso/editar/'.str_encrypt($data_carr->carr_id, KEY_ENCRYPT).'/'.str_encrypt($data_modu->modu_id, KEY_ENCRYPT).'/'.str_encrypt($curso->curs_id, KEY_ENCRYPT)); ?>">
						                    		<i class="btn-icon-only <?= ICON_EDIT; ?>"> </i>
						                    	</a>
						                    	<a title="Eliminar" class="btn btn-small btn-danger tr_delete" href="javascript:;" data-url="<?= base_url('registros/curso/eliminar/'.str_encrypt($data_carr->carr_id, KEY_ENCRYPT).'/'.str_encrypt($data_modu->modu_id, KEY_ENCRYPT).'/'.str_encrypt($curso->curs_id, KEY_ENCRYPT)); ?>">
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
							}//end if
							?>

						</div>
					</div> <!-- /widget-content -->
				</div> <!-- /widget -->
		    </div> <!-- /span8 -->
	      </div> <!-- /row -->
	    </div> <!-- /container -->
	</div> <!-- /main-inner -->
</div> <!-- /main -->


<script src="<?php echo base_url('public/assets/js/Modulosxcarrera.js') ?>"></script>