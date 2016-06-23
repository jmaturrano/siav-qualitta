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
	                        <li><a href="<?php echo base_url('seguridad/rol') ?>"><i class="<?= $header_icon; ?>"></i>&nbsp; Roles</a></li>
	                        <li>&nbsp; /</li>
	                        <li class="active"><i class="<?= ICON_FORM; ?>"></i>&nbsp; Formulario</li>
	                    </ul>
	  				</div> <!-- /widget-header -->
					<div class="widget-content">
						<div id="formcontrols" class="">
							<?php
							$attributes = array(
							  'id'      	=> 'form_rol',
							  'name'    	=> 'form_rol',
							  'class'		=> 'form-horizontal',
							  'tipo_vista' 	=> $tipo_vista
							  );
							$ruta = 'seguridad/rol/guardar/'.((isset($data_rol))?str_encrypt($data_rol->rol_id, KEY_ENCRYPT):'');
							//echo validation_errors();
							echo form_open($ruta, $attributes);
							?>
							<fieldset>
								<div class="control-group">
									<label for="rol_nombre" class="control-label">Nombre rol</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_rol))?$data_rol->rol_nombre:set_value('rol_nombre'); ?>" id="rol_nombre" name="rol_nombre" class="span8" maxlength="100">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->
							</fieldset>

							<hr>
							<br>

							<div class="form-subheader">
								<h3>Opciones de menú por rol</h3>
								<?php $ruta_formajax = 'seguridad/rol/buscaritem/'.((isset($data_rol))?str_encrypt($data_rol->rol_id, KEY_ENCRYPT):''); ?>
								<div id="formsearch" class="" data-formajax="<?= base_url($ruta_formajax); ?>">
									<div style="margin-bottom: 18px;">
						            <input type="text" placeholder="Buscar" class="search-query" name="q" id="txt_buscar" value="<?= (isset($q))?$q:''; ?>">
						            </div>
						        </div>
							</div>
							<table class="table table-striped table-bordered">
				                <thead>
				                  <tr>
				                    <th class="cabecera-tabla"> Item </th>
				                    <th class="cabecera-tabla"> Opción de menú </th>
				                    <th class="cabecera-tabla"> Asignado </th>
				                    <th class="cabecera-tabla"> Permisos </th>
				                  </tr>
				                </thead>
				                <tbody>
				                	<?php
				                	if(isset($data_mxro)){
				                		foreach ($data_mxro as $item => $menuxrol) {
				               		?>
										<tr>
											<td class="texto-centrado">
												<?= ($item+1); ?>
												<input type="hidden" name="menu_id[]" id="menu_id" class="menu_id" value="<?= $menuxrol->menu_id; ?>">
												<input type="hidden" name="mxro_id[]" id="mxro_id" class="mxro_id" value="<?= $menuxrol->mxro_id; ?>">
											</td>
											<td><?= ($menuxrol->menu_idpadre === '0')?'<span class="color_tema">'.$menuxrol->menu_descripcion.'</span>':$menuxrol->menu_descripcion; ?></td>
											<td class="td-control texto-centrado">
												<div class="checkbox">
													<input type="checkbox" name="mxro_accesa[]" data-toggle="checkbox-x" data-three-state="false" class="mxro_accesa" value="<?= ($menuxrol->mxro_accesa==='')?'0':$menuxrol->mxro_accesa; ?>" <?= ($menuxrol->mxro_accesa === '1')?'checked':''; ?> id="mxro_accesa<?= $item; ?>">
													<label class="cbx-label" for="mxro_accesa<?= $item; ?>">Accesa</label>
												</div>
											</td>
						                    <?php if($menuxrol->menu_nivel != 1 && $menuxrol->menu_formulario != null && $menuxrol->menu_formulario != ''){ ?>
						                    <td class="td-actions td-visible texto-centrado">

												<div class="checkbox">
													<input type="checkbox" name="mxro_ingresa[]" data-toggle="checkbox-x" data-three-state="false" class="mxro_ingresa row_permisos" value="<?= ($menuxrol->mxro_ingresa==='')?'0':$menuxrol->mxro_ingresa; ?>" <?= ($menuxrol->mxro_ingresa === '1')?'checked':''; ?> id="mxro_ingresa<?= $item; ?>">
													<label class="cbx-label" for="mxro_ingresa<?= $item; ?>">Ingresa</label>
												</div>

												<div class="checkbox">
													<input type="checkbox" name="mxro_modifica[]" data-toggle="checkbox-x" data-three-state="false" class="mxro_modifica row_permisos" value="<?= ($menuxrol->mxro_modifica==='')?'0':$menuxrol->mxro_modifica; ?>" <?= ($menuxrol->mxro_modifica === '1')?'checked':''; ?> id="mxro_modifica<?= $item; ?>">
													<label class="cbx-label" for="mxro_modifica<?= $item; ?>">Modifica</label>
												</div>

												<div class="checkbox">
													<input type="checkbox" name="mxro_consulta[]" data-toggle="checkbox-x" data-three-state="false" class="mxro_consulta row_permisos" value="<?= ($menuxrol->mxro_consulta==='')?'0':$menuxrol->mxro_consulta; ?>" <?= ($menuxrol->mxro_consulta === '1')?'checked':''; ?> id="mxro_consulta<?= $item; ?>">
													<label class="cbx-label" for="mxro_consulta<?= $item; ?>">Consulta</label>
												</div>

												<div class="checkbox">
													<input type="checkbox" name="mxro_elimina[]" data-toggle="checkbox-x" data-three-state="false" class="mxro_elimina row_permisos" value="<?= ($menuxrol->mxro_elimina==='')?'0':$menuxrol->mxro_elimina; ?>" <?= ($menuxrol->mxro_elimina === '1')?'checked':''; ?> id="mxro_elimina<?= $item; ?>">
													<label class="cbx-label" for="mxro_elimina<?= $item; ?>">Elimina</label>
												</div>

												<div class="checkbox">
													<input type="checkbox" name="mxro_imprime[]" data-toggle="checkbox-x" data-three-state="false" class="mxro_imprime row_permisos" value="<?= ($menuxrol->mxro_imprime==='')?'0':$menuxrol->mxro_imprime; ?>" <?= ($menuxrol->mxro_imprime === '1')?'checked':''; ?> id="mxro_imprime<?= $item; ?>">
													<label class="cbx-label" for="mxro_imprime<?= $item; ?>">Imprime</label>
												</div>

												<div class="checkbox">
													<input type="checkbox" name="mxro_exporta[]" data-toggle="checkbox-x" data-three-state="false" class="mxro_exporta row_permisos" value="<?= ($menuxrol->mxro_exporta==='')?'0':$menuxrol->mxro_exporta; ?>" <?= ($menuxrol->mxro_exporta === '1')?'checked':''; ?> id="mxro_exporta<?= $item; ?>">
													<label class="cbx-label" for="mxro_exporta<?= $item; ?>">Exporta</label>
												</div>

											</td>
						                    <?php } else { ?>
						                    <td class="td-actions td-unvisible texto-centrado">
						                    		<input type="checkbox" name="mxro_ingresa[]" data-toggle="checkbox-x" data-three-state="false" class="mxro_ingresa row_permisos" value="<?= ($menuxrol->mxro_ingresa==='')?'0':$menuxrol->mxro_ingresa; ?>" <?= ($menuxrol->mxro_ingresa === '1')?'checked':''; ?> id="mxro_ingresa<?= $item; ?>">
						                    		<input type="checkbox" name="mxro_modifica[]" data-toggle="checkbox-x" data-three-state="false" class="mxro_modifica row_permisos" value="<?= ($menuxrol->mxro_modifica==='')?'0':$menuxrol->mxro_modifica; ?>" <?= ($menuxrol->mxro_modifica === '1')?'checked':''; ?> id="mxro_modifica<?= $item; ?>">
						                    		<input type="checkbox" name="mxro_consulta[]" data-toggle="checkbox-x" data-three-state="false" class="mxro_consulta row_permisos" value="<?= ($menuxrol->mxro_consulta==='')?'0':$menuxrol->mxro_consulta; ?>" <?= ($menuxrol->mxro_consulta === '1')?'checked':''; ?> id="mxro_consulta<?= $item; ?>">
						                    		<input type="checkbox" name="mxro_elimina[]" data-toggle="checkbox-x" data-three-state="false" class="mxro_elimina row_permisos" value="<?= ($menuxrol->mxro_elimina==='')?'0':$menuxrol->mxro_elimina; ?>" <?= ($menuxrol->mxro_elimina === '1')?'checked':''; ?> id="mxro_elimina<?= $item; ?>">
						                    		<input type="checkbox" name="mxro_imprime[]" data-toggle="checkbox-x" data-three-state="false" class="mxro_imprime row_permisos" value="<?= ($menuxrol->mxro_imprime==='')?'0':$menuxrol->mxro_imprime; ?>" <?= ($menuxrol->mxro_imprime === '1')?'checked':''; ?> id="mxro_imprime<?= $item; ?>">
						                    		<input type="checkbox" name="mxro_exporta[]" data-toggle="checkbox-x" data-three-state="false" class="mxro_exporta row_permisos" value="<?= ($menuxrol->mxro_exporta==='')?'0':$menuxrol->mxro_exporta; ?>" <?= ($menuxrol->mxro_exporta === '1')?'checked':''; ?> id="mxro_exporta<?= $item; ?>">
											</td>
						                    <?php } ?>
										</tr>
				               		<?php
				                		}
				                	}
				                	?>
				                </tbody>
				            </table>
							<?php
							echo form_close();
							?>
						</div>
					</div> <!-- /widget-content -->



				</div> <!-- /widget -->



		    </div> <!-- /span8 -->



	      </div> <!-- /row -->



	    </div> <!-- /container -->



	</div> <!-- /main-inner -->



</div> <!-- /main -->





<script src="<?php echo base_url('public/assets/js/Rol.js') ?>"></script>