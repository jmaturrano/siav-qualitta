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
	                        <li class="active"><i class="<?= $header_icon; ?>"></i>&nbsp; <?= $header_title; ?></li>
	                    </ul>
	  				</div> <!-- /widget-header -->
					<div class="widget-content">
						<div id="formsearch" class="">
							<?php
							$attributes = array(
							  'id'      	=> 'form_listar',
							  'name'    	=> 'form_listar'
							  );
							$ruta = 'registros/alumno/buscar';
							echo form_open($ruta, $attributes);
							?>
				            <input type="text" placeholder="Buscar" class="search-query" name="q" id="txt_buscar" value="<?= (isset($q))?$q:''; ?>">
					       	<?php
					        echo form_close();
					        ?>
				  		</div>
						<div id="formcontrols" class="table-responsive">
							<table class="table table-striped table-bordered">
				                <thead>
				                  <tr>
				                    <th class="cabecera-tabla"> Nro. </th>
				                    <th class="cabecera-tabla"> Nombre </th>
				                    <th class="cabecera-tabla"> Correo </th>
				                    <th class="cabecera-tabla"> Teléfono </th>
				                    <th class="cabecera-tabla"> Atendido por </th>
				                    <th class="cabecera-tabla"> </th>
				                    <th class="cabecera-tabla td-actions"> </th>
				                  </tr>
				                </thead>
				                <tbody>
				                	<?php
				                	if(!isset($offset))
				                		$offset=0;
				                	
				                	if(isset($data_alum)){
				                		foreach ($data_alum as $item => $alumno) {
				               		?>
										<tr>
											<td class="texto-centrado"><?= str_pad(($item+1)+$offset, 5, '0', STR_PAD_LEFT); ?></td>
											<td class=""><?= $alumno->alum_nombre.' '.$alumno->alum_apellido; ?></td>
											<td class="texto-centrado"><a href="mailto:<?= $alumno->alum_email; ?>"><?= $alumno->alum_email; ?></a></td>
											<td class="texto-centrado"><?= $alumno->txal_numero; ?></td>
											<td class="texto-centrado"><?= $alumno->usua_nombre.' '.$alumno->usua_apellido; ?></td>
											<td class="texto-centrado">
												<a href="javascript:;" class="label-badge-info" title="<?= $alumno->alum_observaciones; ?>"><span class="badge"><i>i</i></span></a>
											</td>

											<td class="texto-centrado td-actions">
						                    	<a title="Ver" class="btn btn-small btn-info btn_consulta" href="<?= base_url('registros/alumno/ver/'.str_encrypt($alumno->alum_id, KEY_ENCRYPT)); ?>">
						                    		<i class="btn-icon-only <?= ICON_VIEW; ?>"> </i>
						                    	</a>
						                    	<a title="Editar" class="btn btn-small btn-invert btn_editar" href="<?= base_url('registros/alumno/editar/'.str_encrypt($alumno->alum_id, KEY_ENCRYPT)); ?>">
						                    		<i class="btn-icon-only <?= ICON_EDIT; ?>"> </i>
						                    	</a>
						                    	<a title="Eliminar" class="btn btn-small btn-danger tr_delete" href="javascript:;" data-url="<?= base_url('registros/alumno/eliminar/'.str_encrypt($alumno->alum_id, KEY_ENCRYPT)); ?>">
						                    		<i class="btn-icon-only <?= ICON_DELETE; ?>"> </i>
						                    	</a>
											</td>
										</tr>
				               		<?php
				                		}//end foreach
				                	}else{
				                	?>
				                		<tr>
				                			<td class="texto-centrado" colspan="7">No se encontraron registros...</td>
				                		</tr>
				                	<?php
				                	}
				                	?>
				                </tbody>
				            </table>
	                        <div class="pull-right">
	                            <?php echo $this->pagination->create_links() ?>
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