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
	                        <li><a href="<?php echo base_url('registros/departamento') ?>"><i class="<?= $header_icon; ?>"></i>&nbsp; <?= $header_title; ?></a></li>
	                        <li>&nbsp; /</li>
	                        <li class="active"><i class="<?= ICON_FORM; ?>"></i>&nbsp; Formulario</li>
	                    </ul>
	  				</div> <!-- /widget-header -->
					<div class="widget-content">
						<div id="formcontrols" class="">
							<?php
							$attributes = array(
							  'id'      	=> 'form_departamento',
							  'name'    	=> 'form_departamento',
							  'class'		=> 'form-horizontal',
							  'tipo_vista' 	=> $tipo_vista
							  );
							$ruta = 'registros/departamento/guardar/'.((isset($data_depa))?str_encrypt($data_depa->depa_id, KEY_ENCRYPT):'');
							echo form_open($ruta, $attributes);
							?>
							<fieldset>
								<div class="control-group">
									<label for="depa_codigo" class="control-label">Código</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_depa))?$data_depa->depa_codigo:set_value('depa_codigo'); ?>" id="depa_codigo" name="depa_codigo" class="span8" maxlength="30">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->
								<div class="control-group">
									<label for="depa_descripcion" class="control-label">Departamento</label>
									<div class="controls">
										<input type="text" value="<?= (isset($data_depa))?$data_depa->depa_descripcion:set_value('depa_descripcion'); ?>" id="depa_descripcion" name="depa_descripcion" class="span8" maxlength="25">
									</div> <!-- /controls -->
								</div> <!-- /control-group -->
							</fieldset>
					       	<?php
					        echo form_close();
					        ?>
							<hr>
							<br>
							<?php
							if(isset($data_depa)){
							?>
							<div class="form-subheader">
								<h3>Provincias</h3>
								<a class="btn btn-default btn-right btn-small btn-editar" href="<?= base_url('registros/provincia/nuevo/'.(str_encrypt($data_depa->depa_id, KEY_ENCRYPT))); ?>">
									<span class="btn-icon-only <?= ICON_NEW; ?>"></span> Agregar Provincia
								</a>
								<div id="formsearch" class="" style="display: none;">
									<?php
									$attributes = array(
									  'id'      	=> 'form_listar',
									  'name'    	=> 'form_listar' 
									  );
									$ruta = 'registros/provincia/buscar/'.(str_encrypt($data_depa->depa_id, KEY_ENCRYPT));
									echo form_open($ruta, $attributes);
									?>
						            <input type="text" placeholder="Buscar" class="search-query" name="q" id="txt_buscar" value="<?= (isset($q))?$q:''; ?>">
							       	<?php
							        echo form_close();
							        ?>
						        </div>
							</div>
							<table class="table table-striped table-bordered">
				                <thead>
				                  <tr>
				                    <th class="cabecera-tabla"> Nro. </th>
				                    <th class="cabecera-tabla"> Código </th>
				                    <th class="cabecera-tabla"> Provincia </th>
				                    <th class="cabecera-tabla"> Fecha Reg. </th>
				                    <th class="cabecera-tabla td-actions"> </th>
				                  </tr>
				                </thead>
				                <tbody>
				                	<?php
				                	if(isset($data_prov)){
				                		foreach ($data_prov as $item => $provincia) {
				               		?>
										<tr>
											<td class="texto-centrado"><?= str_pad(($item+1), 5, '0', STR_PAD_LEFT); ?></td>
											<td class="texto-centrado"><?= $provincia->prov_codigo; ?></td>
											<td class=""><?= $provincia->prov_descripcion; ?></td>
											<td class="texto-centrado"><?= date('d/m/Y', strtotime($provincia->prov_fecha_registro)); ?></td>
											<td class="texto-centrado td-actions">
						                    	<a title="Ver" class="btn btn-small btn-info btn_consulta" href="<?= base_url('registros/provincia/ver/'.str_encrypt($data_depa->depa_id, KEY_ENCRYPT).'/'.str_encrypt($provincia->prov_id, KEY_ENCRYPT)); ?>">
						                    		<i class="btn-icon-only <?= ICON_VIEW; ?>"> </i>
						                    	</a>
						                    	<a title="Editar" class="btn btn-small btn-invert btn_editar" href="<?= base_url('registros/provincia/editar/'.str_encrypt($data_depa->depa_id, KEY_ENCRYPT).'/'.str_encrypt($provincia->prov_id, KEY_ENCRYPT)); ?>">
						                    		<i class="btn-icon-only <?= ICON_EDIT; ?>"> </i>
						                    	</a>
						                    	<a title="Eliminar" class="btn btn-small btn-danger tr_delete" href="javascript:;" data-url="<?= base_url('registros/provincia/eliminar/'.str_encrypt($data_depa->depa_id, KEY_ENCRYPT).'/'.str_encrypt($provincia->prov_id, KEY_ENCRYPT)); ?>">
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

<script src="<?php echo base_url('public/assets/js/Departamento.js') ?>"></script>