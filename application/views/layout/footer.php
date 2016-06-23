

    <?php if(!isset($footer_none)){ ?>
    <div class="footer">
        <div class="footer-inner footer-custom">
            <div class="container">
                <div class="row">
                    <div class="span6 footer-right">
                        <div class="form-action">
                          <a class="btn btn-action btn_nuevo" <?= (!isset($btn_nuevo))?'style="display: none;"':'href="'.base_url($btn_nuevo).'"'; ?>><i class="icon-plus"></i> <?= TEXTO_REGISTRAR; ?></a>
                          <button class="btn btn-action btn_guardar" <?= (!isset($btn_guardar))?'style="display: none;"':''; ?>><i class="icon-save"></i> <?= TEXTO_GRABAR; ?></button>
                          <a class="btn btn-action btn_cancelar" <?= (!isset($btn_cancelar))?'style="display: none;"':'href="'.base_url($btn_cancelar).'"'; ?>><i class="icon-remove"></i> <?= TEXTO_CANCELAR; ?></a>
                          <a class="btn btn-action btn_editar" <?= (!isset($btn_editar))?'style="display: none;"':'href="'.base_url($btn_editar).'"'; ?>><i class="icon-edit"></i> <?= TEXTO_EDITAR; ?></a>
                          <a class="btn btn-action btn_regresar" <?= (!isset($btn_regresar))?'style="display: none;"':'href="'.base_url($btn_regresar).'"'; ?>><i class="icon-reply"></i> <?= TEXTO_REGRESAR; ?></a>
                           <a class="btn btn-action btn_imprimir" <?= (!isset($btn_imprimir))?'style="display: none;"':'href="'.$btn_imprimir.'"'; ?>><i class="icon-print"></i> <?= TEXTO_IMPRIMIR; ?></a>
                          <a class="btn btn-action btn_exportar" <?= (!isset($btn_exportar))?'style="display: none;"':'href="'.$btn_exportar.'"'; ?>><i class="icon-download-alt"></i> <?= TEXTO_EXPORTAR; ?></a>
                        </div>
                    </div>
                    <!-- /span6 -->
                    <?php } ?>
                    <div class="span6">
                    	<div class="author"> Versi&oacuten: <?= VERSION; ?>
                      &copy; <?= date('Y'); ?> <a href="//mksystemsoft.com">MK System</a>
                      </div>
                    </div>
                    <!-- /span6 -->
                </div>
                <!-- /row -->
            </div>
            <!-- /container -->
        </div>
        <!-- /footer-inner -->
    </div>
    <!-- /footer -->

  </body>

</html>