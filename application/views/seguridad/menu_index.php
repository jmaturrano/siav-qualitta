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
							$ruta = 'seguridad/menu/buscar';
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
									<th class="cabecera-tabla"> Orden </th>
									<th class="cabecera-tabla"> Código </th>
				                    <th class="cabecera-tabla"> Menú </th>
				                    <th class="cabecera-tabla"> Nivel </th>
				                    <th class="cabecera-tabla td-actions"> </th>
				                  </tr>
				                </thead>
				                <tbody>
				                	<?php
				                	if(isset($data_menu)){
				                		foreach ($data_menu as $item => $menu) {
				               		?>
										<tr>
											<td class="texto-centrado"><?= str_pad(($item+1), 5, '0', STR_PAD_LEFT); ?></td>
											<td class="texto-centrado"><?= str_pad($menu->menu_orden, 5, '0', STR_PAD_LEFT); ?></td>
											<td class="texto-centrado"><?= $menu->menu_codigo; ?></td>
											<td class=""><?= ($menu->menu_nivel==='1')?'<span class="color_tema">'.$menu->menu_descripcion.'</span>':$menu->menu_descripcion; ?></td>
											<td class="texto-centrado"><?= describe_menu_nivel($menu->menu_nivel); ?></td>
											<td class="texto-centrado td-actions">
						                    	<a class="btn btn-small btn-info btn-consulta" href="<?= base_url('seguridad/menu/ver/'.str_encrypt($menu->menu_id, KEY_ENCRYPT)); ?>">
						                    		<i class="btn-icon-only <?= ICON_VIEW; ?>"> </i>
						                    	</a>
						                    	<a class="btn btn-small btn-invert btn-editar" href="<?= base_url('seguridad/menu/editar/'.str_encrypt($menu->menu_id, KEY_ENCRYPT)); ?>">
						                    		<i class="btn-icon-only <?= ICON_EDIT; ?>"> </i>
						                    	</a>
						                    	<a class="btn btn-small btn-danger" href="javascript:;" data-url="<?= base_url('seguridad/menu/eliminar/'.str_encrypt($menu->menu_id, KEY_ENCRYPT)); ?>">
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
						</div>
					</div> <!-- /widget-content -->
				</div> <!-- /widget -->
		    </div> <!-- /span8 -->
	      </div> <!-- /row -->
	    </div> <!-- /container -->
	</div> <!-- /main-inner -->
</div> <!-- /main -->
<script src="<?php echo base_url('public/assets/js/Menu.js') ?>"></script>