<script>
  // flash messages from CI4 (safe)
  window.__flash = {
    success: <?= json_encode(session()->getFlashdata('success') ?? '') ?>,
    error:   <?= json_encode(session()->getFlashdata('error') ?? '') ?>,
    warning: <?= json_encode(session()->getFlashdata('warning') ?? '') ?>,
    info:    <?= json_encode(session()->getFlashdata('info') ?? '') ?>
  };
</script>

<script src="<?= base_url('js/ajax-toast.js') ?>"></script>
