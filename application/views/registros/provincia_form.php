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
	                        <li><a href="<?php echo base_url('registros/departamento') ?>"><i class="<?= $header_icon; ?>"></i>&nbsp; Departamentos</a></li>
	                        <li>&nbsp; /</li>
	                        <li><a href="<?= (isset($data_depa)?base_url('registros/departamento/ver/'.str_encrypt($data_depa->depa_id, KEY_ENCRYPT)):'javascript:;') ?>"><i class="<?= ICON_FORM; ?>"></i>&nbsp; Formulario</a></li>
	                        <li>&nbsp; /</li>
	                        <li class="active"><i class="<?= $header_icon; ?>"></i>&nbsp; <?= $header_title; ?></li>
	                    </ul>
	  				</div> <!-- /widget-header -->
					<div class="widget-content">
						<div id="formcontrols" class="">
							<?php
							$attributes = array(
							  'id'      	=> 'form_provincia',
							  'name'    	=> 'form_provincia',
							  'class'		=> 'form-horizontal',
							  'tipo_vista' 	=> $tipo_vista
							  );

							$ruta = 'registros/provincia/guardar/'.((isset($data_depa))?str_encrypt($data_depa->depa_id, KEY_ENCRYPT):'').'/'.((isset($data_prov))?str_encrypt($data_prov->prov_id, KEY_ENCRYPT):'');
							echo form_open($ruta, $attributes);
							?>
							<fieldset>
								<div class="control-group">
									<label for="depa_descripcion" class="control-label">Departamento</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_depa))?'('.$data_depa->depa_codigo.') '.$data_depa->depa_descripcion:set_value('depa_descripcion'); ?>" id="depa_descripcion" name="depa_descripcion" class="span8" maxlength="25" disabled>
									</div> <!-- /controls -->
								</div> <!-- /control-group -->
								<div class="control-group">
									<label for="prov_codigo" class="control-label">Código</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_prov))?$data_prov->prov_codigo:set_value('prov_codigo'); ?>" id="prov_codigo" name="prov_codigo" class="span8" maxlength="10">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->
								<div class="control-group">
									<label for="prov_descripcion" class="control-label">Provincia</label>
									<div class="controls">
									     <input type="hidden" name="depa_id" id="depa_id" value="<?=((isset($data_depa))?$data_depa->depa_id:'')?>" />
										<input type="text" value="<?= (isset($data_prov))?$data_prov->prov_descripcion:set_value('prov_descripcion'); ?>" id="prov_descripcion" name="prov_descripcion" class="span8" maxlength="25" >
									</div> <!-- /controls -->
								</div> <!-- /control-group -->
							</fieldset>
					       	<?php
					        echo form_close();
					        ?>
							<hr>
							<br>
							<?php if(isset($data_prov)) { ?>
							<div class="form-subheader">
								<h3>Distritos</h3>
								<a class="btn btn-default btn-right btn-small" href="<?= base_url('registros/distrito/nuevo/'.((isset($data_depa))?str_encrypt($data_depa->depa_id, KEY_ENCRYPT):'').'/'.((isset($data_prov))?str_encrypt($data_prov->prov_id, KEY_ENCRYPT):'')); ?>">
									<span class="btn-icon-only <?= ICON_NEW; ?>"></span> Agregar Distrito
								</a>
								<div id="formsearch" class="" style="display: none;">
									<?php
									$attributes = array(
									  'id'      	=> 'form_listar',
									  'name'    	=> 'form_listar' 
									  );
									$ruta = 'registros/distrito/buscar/'.((isset($data_prov))?str_encrypt($data_prov->prov_id, KEY_ENCRYPT):'');
									echo form_open($ruta, $attributes);
									?>
						            <input type="text" placeholder="Buscar" class="search-query" name="q" id="txt_buscar" value="<?= (isset($q))?$q:''; ?>">
							       	<?php
							        echo form_close();
							        ?>
						        </div>
							</div>
							<?php } ?>
							<?php
							if(isset($data_dist)){
							?>
							<table class="table table-striped table-bordered">
				                <thead>
				                  <tr>
				                    <th class="cabecera-tabla"> Nro. </th>
				                    <th class="cabecera-tabla"> Código </th>
				                    <th class="cabecera-tabla"> Distrito </th>
				                    <th class="cabecera-tabla"> Fecha Reg. </th>
				                    <th class="cabecera-tabla td-actions"> </th>
				                  </tr>
				                </thead>
				                <tbody>
				                	<?php
				                	if(isset($data_dist)){
				                		foreach ($data_dist as $item => $distrito) {
				               		?>
										<tr>
											<td class="texto-centrado"><?= str_pad(($item+1), 5, '0', STR_PAD_LEFT); ?></td>
											<td class="texto-centrado"><?= $distrito->dist_codigo; ?></td>
											<td class=""><?= $distrito->dist_descripcion; ?></td>
											<td class="texto-centrado"><?= date('d/m/Y', strtotime($distrito->dist_fecha_registro)); ?></td>
											<td class="texto-centrado td-actions">
						                    	<a title="Ver" class="btn btn-small btn-info btn_consulta" href="<?= base_url('registros/distrito/ver/'.str_encrypt($data_prov->prov_id, KEY_ENCRYPT).'/'.str_encrypt($distrito->dist_id, KEY_ENCRYPT)); ?>">
						                    		<i class="btn-icon-only <?= ICON_VIEW; ?>"> </i>
						                    	</a>
						                    	<a title="Editar" class="btn btn-small btn-invert btn_editar" href="<?= base_url('registros/distrito/editar/'.str_encrypt($data_prov->prov_id, KEY_ENCRYPT).'/'.str_encrypt($distrito->dist_id, KEY_ENCRYPT)); ?>">
						                    		<i class="btn-icon-only <?= ICON_EDIT; ?>"> </i>
						                    	</a>
						                    	<a title="Eliminar" class="btn btn-small btn-danger tr_delete" href="javascript:;" data-url="<?= base_url('registros/distrito/eliminar/'.str_encrypt($data_prov->prov_id, KEY_ENCRYPT).'/'.str_encrypt($distrito->dist_id, KEY_ENCRYPT)); ?>">
						                    		<i class="btn-icon-only <?= ICON_DELETE; ?>"> </i>
						                    	</a>
											</td>
										</tr>
				               		<?php
				                		}
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

<script src="<?php echo base_url('public/assets/js/Provincia.js') ?>"></script>