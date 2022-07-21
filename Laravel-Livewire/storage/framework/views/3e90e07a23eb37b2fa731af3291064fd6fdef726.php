<div>
    <div class="container">
        <div class="row justify-content-center">

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        Users
                    </div>
                    <div class="card-body chatbox p-0">
                        <ul class="list-group list-group-flush">
                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <?php if($user->id !== auth()->id()): ?>
                            <?php
                            $not_seen=
                            App\Models\Message::where('user_id',$user->id)->where('receiver_id',auth()->id())->where('is_seen',false)->get()
                            ?? null

                            ?>
                            <a wire:click="getUser(<?php echo e($user->id); ?>)" class="text-dark link">
                                <li class="list-group-item">
                                    <img class="img-fluid avatar"
                                        src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/12/User_icon_2.svg/800px-User_icon_2.svg.png">
                                    <?php if($user->is_online==true): ?>

                                    <i class="fa fa-circle text-success online-icon">
                                        <?php endif; ?>

                                    </i> <?php echo e($user->name); ?>

                                    <?php if(filled($not_seen)): ?>
                                    <div class="badge badge-success rounded"> <?php echo e($not_seen->count()); ?> </div>
                                    <?php endif; ?>
                                </li>
                            </a>
                            <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <i class="far fa-comments"></i> <?php if(isset($sender)): ?> &nbsp;&nbsp;<?php echo e($sender->name); ?> <?php endif; ?>
                    </div>
                    <div class="card-body message-box" wire:poll="mountdata">
                        <?php if(filled($allmessages)): ?>
                        <?php $__currentLoopData = $allmessages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mgs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="single-message <?php if($mgs->user_id == auth()->id()): ?> sent <?php else: ?> received <?php endif; ?>">
                            <p class="font-weight-bolder my-0"><?php echo e($mgs->user->name); ?></p>
                            <?php echo e($mgs->message); ?>

                            <br><small class="text-muted w-100">Sent <em><?php echo e($mgs->created_at); ?></em></small>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer">
                        <form wire:submit.prevent="SendMessage">
                            <?php if(!isset($sender)): ?>
                            <div class="row">
                                <div class="col-md-8">
                                    <input class="form-control input shadow-none w-100 d-inline-block"
                                        placeholder="Type a message receiver" disabled>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-success d-inline-block w-100" disabled>
                                        <i class="far fa-paper-plane"></i> Send</button>
                                </div>
                            </div>
                            <?php else: ?>
                            <div class="row">
                                <div class="col-md-8">
                                    <input wire:model="message"
                                        class="form-control input shadow-none w-100 d-inline-block"
                                        placeholder="Type a message sender" required>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary d-inline-block w-100">
                                        <i class="far fa-paper-plane"></i> Send</button>
                                </div>
                            </div>
                            <?php endif; ?>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH E:\laragon\www\LIVEWIRE\live-chat-main\resources\views/livewire/messages.blade.php ENDPATH**/ ?>