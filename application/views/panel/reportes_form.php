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

                        <div id="formcontrols" class="">

                            <?php

                            $attributes = array(

                              'id'      => 'form_reporte',

                              'name'    => 'form_reporte',

                              'class'   => 'form-horizontal',

                              'target'  => '_blank',

                              'method'  => 'post'

                              );

                            //$ruta = 'panel/reportes/formato1/';

                            //echo validation_errors();

                            echo form_open($ruta, $attributes);

                            ?>

                            <fieldset>

                                <div class="control-group form-group">

                                    <label for="oficina" class="control-label">Unidad Organica</label>
                                    <input type="hidden" id="extension" name="extension" value="">
                                    <div class="controls">
                                        <select class="selectpicker span8" name="ogru_id" id="ogru_id" data-container="body" data-subruta="<?= base_url('seguridad/oficinagrupo/getOficina_ajax/{OGRU_ID}'); ?>" multiple title="Todas">
                                        <?php
                                        if(isset($data_ogru)){
                                            foreach ($data_ogru as $item => $oficinagrupo) {
                                        ?>
                                            <option value="<?=$oficinagrupo->ogru_id; ?>" ><?= $oficinagrupo->ogru_nombre; ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                        </select>
                                    </div> <!-- /controls -->
                                </div> <!-- /control-group -->

                                <div class="control-group">

                                    <label for="item" class="control-label ">Agencia Zonal</label>

                                    <div class="controls">
                                    <select class="selectpicker span8" name="ofic_id" id="ofic_id" data-container="body" data-live-search="true" multiple title="Todas">

                                              </select>
                                        

                                    </div> <!-- /controls -->

                                </div> <!-- /control-group -->

                                <div class="control-group">

                                    <label for="periodo" class="control-label">Periodo (*)</label>

                                    <div class="controls">
                                    <select class="selectpicker span8" name="peri_id" id="peri_id" data-container="body" title="Todos">

                                    
                                        <?php
                                        if(isset($data_peri)){
                                            foreach ($data_peri as $item => $periodo) {
                                        ?>
                                            <option value="<?= $periodo->peri_id; ?>" ><?= $periodo->peri_nombre; ?></option>
                                        <?php
                                            }
                                        }
                                        ?>

                                        </select>

                                    </select>
                                        
                                    </div> <!-- /controls -->

                                </div> <!-- /control-group -->

                                 <div class="control-group">

                                    <label for="periodo" class="control-label">A la Fecha </label>

                                    <div class="controls">
                                    
                                        <div class="input-append date datepicker" data-date-format="dd/mm/yyyy">

                                                <input class="span2" size="16" type="text" value="" id="fecha_reporte" name="fecha_reporte" required value="<?=date('d/m/Y')?>">

                                                <span class="add-on"><i class="<?= ICONO_CALENDARIO; ?>"></i></span>

                                            </div>
                                    </div> <!-- /controls -->

                                </div> <!-- /control-group -->

                                 <div class="control-group">
                                    <label for="poli_id" class="control-label" style="">Política</label>
                                    <div class="controls">
                                        <select class="selectpicker span8" name="poli_id" id="poli_id" data-live-search="true" data-subruta="<?= base_url('poa/catactividad/progpresupxpol_listar_ajax/{SELECTED}'); ?>" data-container="body" multiple title="Todas">
                                            
                                        <?php
                                        if(isset($data_poli)){
                                            foreach ($data_poli as $item => $politica) {
                                        ?>
                                            <option value="<?= $politica->poli_id; ?>" <?= (isset($data_poa) && $data_poa->poli_id === $politica->poli_id)?'selected':set_select('poli_id', $politica->poli_id); ?>><?= substr($politica->poli_nombre, 0, LIMITSELECT); ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                        </select>
                                    </div> <!-- /controls -->
                                </div> <!-- /control-group -->

                                <div class="control-group">
                                    <label for="ppre_id" class="control-label" style="">Programa Presupuestal</label>
                                    <div class="controls">
                                        <select class="selectpicker span8 selectpicker-poli" name="ppre_id" id="ppre_id" data-live-search="true" data-subruta="<?= base_url('poa/catactividad/productoxppre_listar_ajax/{POLI_ID}/{SELECTED}'); ?>" data-container="body" multiple title="Todos">
                                            
                                        <?php
                                        if(isset($data_ppre)){
                                            foreach ($data_ppre as $item => $progpresup) {
                                        ?>
                                            <option value="<?= $progpresup->ppre_id; ?>" ><?= substr($progpresup->ppre_codref.'-'.$progpresup->ppre_nombre, 0, LIMITSELECT); ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                        </select>
                                    </div> <!-- /controls -->
                                </div> <!-- /control-group -->

                                <div class="control-group">
                                    <label for="prod_id" class="control-label" style="">Producto</label>
                                    <div class="controls">
                                        <select class="selectpicker span8 selectpicker-ppre" name="prod_id" id="prod_id" data-live-search="true" data-subruta="<?= base_url('poa/catactividad/actividadesxprod_listar_ajax/{POLI_ID}/{PPRE_ID}/{SELECTED}'); ?>" data-container="body" multiple title="Todos">
                                            
                                        <?php
                                        if(isset($data_prod)){
                                            foreach ($data_prod as $item => $producto) {
                                        ?>
                                            <option value="<?= $producto->prod_id; ?>" ><?= substr($producto->prod_codref.'-'.$producto->prod_nombre, 0, LIMITSELECT); ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                        </select>
                                    </div> <!-- /controls -->
                                </div> <!-- /control-group -->

                                <div class="control-group">
                                    <label for="acti_id" class="control-label" style="">Actividad</label>
                                    <div class="controls">
                                        <select class="selectpicker span8" name="acti_id" id="acti_id" data-live-search="true" data-subruta="<?= base_url('poa/subactividad/subactividadesxcact_listar_ajax/{POLI_ID}/{PPRE_ID}/{PROD_ID}/{SELECTED}'); ?>" data-container="body" multiple title="Todas">
                                            
                                        <?php
                                        if(isset($data_acti)){
                                            foreach ($data_acti as $item => $actividad) {
                                        ?>
                                            <option value="<?= $actividad->acti_id; ?>" ><?= substr($actividad->acti_codref.'-'.$actividad->acti_nombre, 0, LIMITSELECT); ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                        </select>
                                    </div> <!-- /controls -->
                                </div> <!-- /control-group -->

                                <div class="control-group">
                                    <label for="sact_id" class="control-label" style="">Sub Actividad</label>
                                    <div class="controls">
                                        <select class="selectpicker span8" name="sact_id" id="sact_id" data-live-search="true" data-container="body" multiple title="Todas">
                                            
                                        <?php
                                        if(isset($data_sact)){
                                            foreach ($data_sact as $item => $subactividad) {
                                        ?>
                                            <option value="<?= $subactividad->sact_id; ?>" ><?= substr($subactividad->sact_nombre, 0, LIMITSELECT); ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                        </select>
                                        
                                    </div> <!-- /controls -->
                                </div> <!-- /control-group -->

                                <div class="control-group">
                                    <label for="estr_id" class="control-label" style="">Estructura</label>
                                    <div class="controls">
                                        <select class="selectpicker span8" name="estr_id" id="estr_id" data-live-search="true" data-container="body" multiple title="Todas">
                                            
                                        <?php
                                        if(isset($data_estr)){
                                            foreach ($data_estr as $item => $estructura) {
                                        ?>
                                            <option value="<?= $estructura->estr_id; ?>" ><?= substr($estructura->estr_nombre, 0, LIMITSELECT); ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                        </select>
                                        
                                    </div> <!-- /controls -->
                                </div> <!-- /control-group -->


                            </fieldset>

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





<script src="<?php echo base_url('public/assets/js/Reporte.js') ?>"></script>


