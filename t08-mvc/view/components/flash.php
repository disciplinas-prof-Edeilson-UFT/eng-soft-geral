<?php
use Conex\MiniFramework\utils\Flash;

$flashMessages = Flash::get();
?>

<?php if (!empty($flashMessages)): ?>
    <div class="flash-container">
        <?php foreach ($flashMessages as $type => $message): ?>
            <div class="flash-message <?= $type ?>" data-flash="<?= $type ?>">
                <button class="close-btn" onclick="closeFlash(this)">&times;</button>
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<script>
function closeFlash(btn) {
    const flashMessage = btn.parentElement;
    flashMessage.classList.add('fade-out');
    setTimeout(() => {
        flashMessage.remove();
    }, 300);
}

document.addEventListener('DOMContentLoaded', function() {
    const flashMessages = document.querySelectorAll('.flash-message');
    flashMessages.forEach(flash => {
        setTimeout(() => {
            if (flash.parentElement) {
                flash.classList.add('fade-out');
                setTimeout(() => {
                    flash.remove();
                }, 300);
            }
        }, 5000);
    });
});
</script>