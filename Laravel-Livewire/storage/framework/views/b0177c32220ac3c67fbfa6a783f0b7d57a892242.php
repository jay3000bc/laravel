<?php $__env->startSection('content'); ?>
<div>
	<?php
if (! isset($_instance)) {
    $html = \Livewire\Livewire::mount('messages')->html();
} elseif ($_instance->childHasBeenRendered('oONtdVg')) {
    $componentId = $_instance->getRenderedChildComponentId('oONtdVg');
    $componentTag = $_instance->getRenderedChildComponentTagName('oONtdVg');
    $html = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('oONtdVg');
} else {
    $response = \Livewire\Livewire::mount('messages');
    $html = $response->html();
    $_instance->logRenderedChild('oONtdVg', $response->id(), \Livewire\Livewire::getRootElementTagName($html));
}
echo $html;
?>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\laragon\www\LIVEWIRE\live-chat-main\resources\views/users/messages.blade.php ENDPATH**/ ?>