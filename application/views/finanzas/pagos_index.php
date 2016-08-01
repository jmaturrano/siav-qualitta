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
							$ruta = 'servicios/matricula/buscar';
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
				                    <th class="cabecera-tabla"> Carrera </th>
				                    <th class="cabecera-tabla"> Matrícula </th>
				                    <th class="cabecera-tabla"> Alumno </th>
				                    <th class="cabecera-tabla"> Fecha </th>
				                    <th class="cabecera-tabla"> Estado </th>
				                    <th class="cabecera-tabla"> Pagado </th>
				                    <th class="cabecera-tabla"> Pendiente </th>
				                    <th class="cabecera-tabla td-actions"> </th>
				                  </tr>
				                </thead>
				                <tbody>
				                	<?php
				                	if(!isset($offset))
				                		$offset=0;
				                	if(isset($data_pagos)){
				                		foreach ($data_pagos as $item => $pagos) {
				               		?>
										<tr>
											<td class="texto-centrado"><?= str_pad(($item+1)+$offset, 5, '0', STR_PAD_LEFT); ?></td>
											<td class="texto-centrado"><?= $pagos['carr_codigo']; ?></td>
											<td class="texto-centrado"><?= $pagos['matr_codigo']; ?></td>
											<td class=""><?= $pagos['alum_apellido'].' '.$pagos['alum_nombre']; ?></td>
											<td class="texto-centrado"><?= fecha_latino($pagos['matr_fecha_proceso']); ?></td>
											<td class="texto-centrado"><?= $pagos['emat_descripcion']; ?></td>
											<td class="texto-derecha"><?= number_format($pagos['monto_pagado'], 2); ?></td>
											<td class="texto-derecha"><?= number_format($pagos['monto_pendiente'], 2); ?></td>

											<td class="texto-centrado td-actions">
						                    	<a title="Ver" class="btn btn-small btn-info btn_consulta" href="<?= base_url('finanzas/pagos/ver/'.str_encrypt($pagos['matr_id'], KEY_ENCRYPT)); ?>">
						                    		<i class="btn-icon-only <?= ICON_VIEW; ?>"> </i>
						                    	</a>
						                    	<a title="Editar" class="btn btn-small btn-invert btn_editar" href="<?= base_url('finanzas/pagos/editar/'.str_encrypt($pagos['matr_id'], KEY_ENCRYPT)); ?>">
						                    		<i class="btn-icon-only <?= ICON_EDIT; ?>"> </i>
						                    	</a>
						                    	<a title="Eliminar" class="btn btn-small btn-danger tr_delete" href="javascript:;" data-url="<?= base_url('finanzas/pagos/eliminar/'.str_encrypt($pagos['matr_id'], KEY_ENCRYPT)); ?>">
						                    		<i class="btn-icon-only <?= ICON_DELETE; ?>"> </i>
						                    	</a>
											</td>
										</tr>
				               		<?php
				                		}//end foreach
				                	}else{
				                	?>
				                		<tr>
				                			<td class="texto-centrado" colspan="9">
				                				<span>No se encontraron registros...</span>
				                			</td>
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

<script src="<?php echo base_url('public/assets/js/Pagos.js') ?>"></script>