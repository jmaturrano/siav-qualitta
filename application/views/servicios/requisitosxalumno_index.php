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
							$ruta = 'servicios/requisitosxalumno/buscar';
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
				                    <th class="cabecera-tabla"> Documento </th>
				                    <th class="cabecera-tabla"> Obligatorio </th>
				                    <th class="cabecera-tabla"> Presentado </th>
				                    <th class="cabecera-tabla"> Observacion </th>
				                    <th class="cabecera-tabla td-actions"> </th>
				                  </tr>
				                </thead>
				                <tbody>
				                	<?php
				                	if(!isset($offset))
				                		$offset=0;
				                	if(isset($data_rxal)){
				                		foreach ($data_rxal as $item => $requisitos) {
				               		?>
										<tr>
											<td class="texto-centrado"><?= str_pad(($item+1)+$offset, 5, '0', STR_PAD_LEFT); ?></td>
											<td class="texto-centrado"><?= $requisitos->rcar_descripcion; ?></td>
											<td class="texto-centrado"><?= interpretar_booleanchar($requisitos->rxca_obligatorio); ?></td>
											<td class="texto-centrado"><?= interpretar_booleanchar($requisitos->rxal_cumplido); ?></td>
											<td class="texto-centrado"><?= $requisitos->rxal_observacion; ?></td>
											<td class="texto-centrado td-actions">
					                            <button class="btn btn-small btn-invert" data-toggle="modal" data-target="#requisito_editar<?= $item; ?>" type="button">
					                                <span class="glyphicon glyphicon-open"></span> 
					                            </button>

												<?php
												$attributes = array(
												  'class'		=> 'form-horizontal',
												  'enctype'		=> 'multipart/form-data',
												  'style'		=> 'margin: 0;'
												  );
												$ruta = 'servicios/requisitosxalumno/guardar/'.str_encrypt($data_matr->matr_id, KEY_ENCRYPT).'/'.str_encrypt($requisitos->rxal_id, KEY_ENCRYPT);
												echo form_open($ruta, $attributes);
												?>

					                            <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="requisito_editar<?= $item; ?>">
					                              <div class="modal-dialog modal-sm">
					                                <div class="modal-content">
					                                    <div class="modal-header form-subheader">
					                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					                                            <span aria-hidden="true">&times;</span>
					                                        </button>
					                                        <h4 class="modal-title">Documentos requisito:</h4>
					                                    </div>
					                                    <div class="modal-body">
					                                        <div class="control-group">
					                                            <label for="" class="span-1" style="">Cumplido?</label>
					                                            <div class="controls" style="float: left; margin-left: 45px;">
					                                                <input type="checkbox" class="span3" name="rxal_cumplido" checked="" value="<?= ($requisitos->rxal_cumplido === 'S')?'1':'0'; ?>" data-three-state="false" data-toggle="checkbox-x" >
					                                            </div> <!-- /controls -->
					                                        </div> <!-- /control-group -->

															<div class="control-group">
																<label for="rxal_observacion" class="span-1">Observaciones</label>
																<div class="controls">
																	<input type="text" name="rxal_observacion" class="span3" maxlength="100" value="<?= (isset($requisitos))?$requisitos->rxal_observacion:set_value('rxal_observacion'); ?>" >
																</div> <!-- /controls -->
															</div> <!-- /control-group -->

															<div class="control-group">
																<label for="rxal_ruta_imagen" class="span-1">Imagen</label>
																<div class="controls">
																	<input type="file" name="rxal_ruta_imagen" class="span4">
																	<?php 
																	if($requisitos->rxal_ruta_imagen != '' && $requisitos->rxal_ruta_imagen != NULL){
																	$ruta_imagen = base_url(IMG_PATH.'requisitosxalumno/'.$requisitos->rxal_ruta_imagen);
																		if($ruta_imagen != ''){
																	?>
																	<img src="<?= $ruta_imagen; ?>" alt="<?= $requisitos->rxal_ruta_imagen; ?>" class="img-thumbnail span2 float_rigth">
																	<?php
																		}//end if
																	}//end if
																	?>
																</div> <!-- /controls -->
															</div> <!-- /control-group -->

					                                    <div class="modal-footer">
					                                      <button class="btn btn-primary btn-small" data-dismiss="modal" type="button">
					                                        <span class="btn-icon-only <?= ICON_BACK; ?>"></span> Cancelar
					                                      </button>
					                                      <button class="btn btn-default btn-right btn-small" type="submit">
					                                        <span class="glyphicon glyphicon-save"></span> Guardar
					                                      </button>
					                                    </div>
					                                </div>
					                              </div>
					                            </div>
										       	<?php
										        echo form_close();
										        ?>

											</td>
										</tr>
				               		<?php
				                		}
				                	}else{
				                	?>
				                		<tr>
				                			<td class="texto-centrado" colspan="6">
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

<script src="<?php echo base_url('public/assets/js/requisitos.js') ?>"></script>