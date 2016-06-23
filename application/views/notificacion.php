
<script type="text/javascript">
    tipo = "<?php echo $this->session->flashdata('mensaje_tipo') ?>";
    texto = "<?php echo $this->session->flashdata('mensaje') ?>";
    notificacion(tipo, texto);
</script>
