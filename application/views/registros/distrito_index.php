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
							$ruta = 'registros/distrito/buscar/'.(str_encrypt($prov_id, KEY_ENCRYPT));
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
						                    	<a title="Ver" class="btn btn-small btn-info btn_consulta" href="<?= base_url('poa/disitrito/ver/'.str_encrypt($distrito->dist_id, KEY_ENCRYPT)); ?>">
						                    		<i class="btn-icon-only <?= ICON_VIEW; ?>"> </i>
						                    	</a>
						                    	<a title="Editar" class="btn btn-small btn-invert btn_editar" href="<?= base_url('registros/distrito/editar/'.str_encrypt($distrito->dist_id, KEY_ENCRYPT)); ?>">
						                    		<i class="btn-icon-only <?= ICON_EDIT; ?>"> </i>
						                    	</a>
						                    	<a title="Eliminar" class="btn btn-small btn-danger tr_delete" href="javascript:;" data-url="<?= base_url('registros/distrito/eliminar/'.str_encrypt($distrito->dist_id, KEY_ENCRYPT)); ?>">
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

<script src="<?php echo base_url('public/assets/js/Departamento.js') ?>"></script>