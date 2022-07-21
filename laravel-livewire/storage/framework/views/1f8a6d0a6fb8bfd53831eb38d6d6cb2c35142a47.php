
<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="card">
        <div class="card-header">Chats</div>
        <div class="card-body">
            <chat-messages :messages="messages"></chat-messages>
        </div>
        <div class="card-footer">
            <chat-form v-on:messagesent="addMessage" :user="<?php echo e(Auth::user()); ?>"></chat-form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\ALEGRA-TRAINING\REAL TIME CHATTING\laravel-chat\resources\views/chat.blade.php ENDPATH**/ ?>