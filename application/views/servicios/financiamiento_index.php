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
							$costo_matricula = 0;
							if(isset($data_cxma)){
								foreach ($data_cxma as $cxma) {
									$costo_matricula += (float)$cxma->cxma_costofinal;
								}//end foreach
							}//end if
							?>
							<span><strong> Total matrícula: <?= number_format($costo_matricula, 2); ?> </strong></span>
				  		</div>
						<div id="formcontrols" class="table-responsive">
							<table class="table table-striped table-bordered">
				                <thead>
				                  <tr>
				                    <th class="cabecera-tabla"> Nro. </th>
				                    <th class="cabecera-tabla"> Código Matrícula </th>
				                    <th class="cabecera-tabla"> Fecha programada </th>
				                    <th class="cabecera-tabla"> Monto </th>
				                    <th class="cabecera-tabla"> Pagado </th>
				                    <th class="cabecera-tabla"> Comprobante </th>
				                    <th class="cabecera-tabla"> Fecha proceso </th>
				                    <th class="cabecera-tabla td-actions"> </th>
				                  </tr>
				                </thead>
				                <tbody>
				                	<?php
				                	if(!isset($offset))
				                		$offset=0;
				                	if(isset($data_fima)){
				                		$total_monto = 0;
				                		foreach ($data_fima as $item => $fima) {
				                			$total_monto+= (float)$fima->fima_monto;
				               		?>
										<tr>
											<td class="texto-centrado"><?= str_pad(($item+1)+$offset, 5, '0', STR_PAD_LEFT); ?></td>
											<td class="texto-centrado"><?= $data_matr->matr_codigo; ?></td>
											<td class="texto-centrado"><?= fecha_latino($fima->fima_fecha_programada); ?></td>
											<td class="texto-derecha"><?= number_format($fima->fima_monto, 2); ?></td>
											<td class="texto-centrado"><?= interpretar_booleanchar($fima->fima_pagado); ?></td>
											<td class="texto-centrado"><?= $fima->fima_comprobante; ?></td>
											<td class="texto-centrado"><?= fecha_latino($fima->fima_fecha_proceso); ?></td>
											<td class="texto-centrado td-actions">
						                    	<a title="Ver" class="btn btn-small btn-info btn_consulta" href="<?= base_url('servicios/financiamiento/ver/'.str_encrypt($data_matr->matr_id, KEY_ENCRYPT).'/'.str_encrypt($fima->fima_id, KEY_ENCRYPT)); ?>">
						                    		<i class="btn-icon-only <?= ICON_VIEW; ?>"> </i>
						                    	</a>
						                    	<a title="Editar" class="btn btn-small btn-invert btn_editar" href="<?= base_url('servicios/financiamiento/editar/'.str_encrypt($data_matr->matr_id, KEY_ENCRYPT).'/'.str_encrypt($fima->fima_id, KEY_ENCRYPT)); ?>">
						                    		<i class="btn-icon-only <?= ICON_EDIT; ?>"> </i>
						                    	</a>
						                    	<a title="Eliminar" class="btn btn-small btn-danger tr_delete" href="javascript:;" data-url="<?= base_url('servicios/financiamiento/eliminar/'.str_encrypt($data_matr->matr_id, KEY_ENCRYPT).'/'.str_encrypt($fima->fima_id, KEY_ENCRYPT)); ?>">
						                    		<i class="btn-icon-only <?= ICON_DELETE; ?>"> </i>
						                    	</a>
											</td>
										</tr>
				               		<?php
				                		}
				                	}else{
				                	?>
				                		<tr>
				                			<td class="texto-centrado" colspan="7">
				                				<span>No se encontraron registros...</span>
				                			</td>
				                		</tr>
				                	<?php
				                	}
									?>
				                </tbody>
				                <?php
			                	if(isset($data_fima)){
			                	?>
				                <tfoot>
				                	<tr>
				                		<td class="texto-centrado" colspan="3">Total</td>
				                		<td class="texto-derecha"><?= number_format($total_monto, 2); ?></td>
				                		<td class="texto-centrado" colspan="3">Monto pendiente: &nbsp;&nbsp;&nbsp;<strong><?= number_format($costo_matricula-$total_monto, 2); ?></strong></td>
				                		<td ></td>
				                	</tr>
				                </tfoot>
			                	<?php
			                	}
			                	?>
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

<!--<script src="<?php echo base_url('public/assets/js/Financiamiento.js') ?>"></script>-->