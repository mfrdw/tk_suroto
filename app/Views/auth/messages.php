<?php
$errors = session()->getFlashdata('auth_errors') ?? [];
if (!is_array($errors)) $errors = [$errors];
?>
<?php if ($errors): ?>
<div class="auth-alert auth-alert-error" role="alert"><ul><?php foreach ($errors as $error): ?><li><?= esc($error) ?></li><?php endforeach; ?></ul></div>
<?php endif; ?>
<?php if ($message = session()->getFlashdata('error')): ?><div class="auth-alert auth-alert-error" role="alert"><?= esc($message) ?></div><?php endif; ?>
<?php if ($message = session()->getFlashdata('success')): ?><div class="auth-alert auth-alert-success" role="status"><?= esc($message) ?></div><?php endif; ?>
