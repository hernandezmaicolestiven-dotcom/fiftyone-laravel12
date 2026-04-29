<?php $__env->startSection('title', 'Chat interno'); ?>
<?php $__env->startSection('page-title', 'Chat interno'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden flex flex-col" style="height: calc(100vh - 160px)">

        
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3"
             style="background: linear-gradient(135deg, #0d0d1a, #0a0e2e)">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                 style="background: linear-gradient(135deg, #3B59FF, #7B2FBE)">
                <i class="fa-solid fa-comments text-white text-sm"></i>
            </div>
            <div>
                <h2 class="text-base font-bold text-white">Chat del equipo</h2>
                <p class="text-xs text-gray-400">Mensajes internos entre administradores</p>
            </div>
        </div>

        
        <div id="chat-messages" class="flex-1 overflow-y-auto px-6 py-4 space-y-3 bg-gray-50">
            <?php $__empty_1 = true; $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $msg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php $mine = $msg->user_id === auth()->id(); ?>
            <div class="flex <?php echo e($mine ? 'justify-end' : 'justify-start'); ?> gap-2">
                <?php if(!$mine): ?>
                <div class="w-8 h-8 rounded-xl flex items-center justify-center text-white text-xs font-bold flex-shrink-0 mt-1"
                     style="background: linear-gradient(135deg, #3B59FF, #7B2FBE)">
                    <?php echo e(strtoupper(substr($msg->user->name, 0, 1))); ?>

                </div>
                <?php endif; ?>
                <div class="max-w-xs lg:max-w-md">
                    <?php if(!$mine): ?>
                    <p class="text-xs text-gray-400 mb-1 ml-1"><?php echo e($msg->user->name); ?></p>
                    <?php endif; ?>
                    <div class="px-4 py-2.5 rounded-2xl text-sm <?php echo e($mine
                        ? 'text-white rounded-tr-sm'
                        : 'bg-white text-gray-800 rounded-tl-sm shadow-sm border border-gray-100'); ?>"
                         <?php if($mine): ?> style="background: linear-gradient(135deg, #3B59FF, #7B2FBE)" <?php endif; ?>>
                        <?php echo e($msg->body); ?>

                    </div>
                    <p class="text-[10px] text-gray-400 mt-1 <?php echo e($mine ? 'text-right mr-1' : 'ml-1'); ?>">
                        <?php echo e($msg->created_at->format('H:i')); ?>

                    </p>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="flex flex-col items-center justify-center h-full py-20 text-center">
                <div class="w-16 h-16 rounded-2xl bg-indigo-100 mx-auto mb-4 flex items-center justify-center">
                    <i class="fa-solid fa-comments text-2xl text-indigo-500"></i>
                </div>
                <p class="text-gray-500 font-medium">Sin mensajes aún</p>
                <p class="text-gray-400 text-sm mt-1">Sé el primero en escribir algo.</p>
            </div>
            <?php endif; ?>
        </div>

        
        <div class="px-6 py-4 border-t border-gray-100 bg-white">
            <form id="chat-form" class="flex gap-3">
                <?php echo csrf_field(); ?>
                <input type="text" id="chat-input" name="body"
                       placeholder="Escribe un mensaje..."
                       autocomplete="off"
                       class="flex-1 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 bg-gray-50 focus:bg-white transition">
                <button type="submit"
                        class="px-5 py-2.5 rounded-xl text-white text-sm font-semibold transition hover:opacity-90"
                        style="background: linear-gradient(90deg, #3B59FF, #7B2FBE)">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
(function () {
    const container = document.getElementById('chat-messages');
    const form      = document.getElementById('chat-form');
    const input     = document.getElementById('chat-input');
    const csrf      = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    let lastId      = <?php echo e($messages->last()?->id ?? 0); ?>;

    // Scroll al fondo
    const scrollBottom = () => container.scrollTop = container.scrollHeight;
    scrollBottom();

    // Renderiza un mensaje en el DOM
    function renderMessage(msg) {
        const wrap = document.createElement('div');
        wrap.className = `flex ${msg.mine ? 'justify-end' : 'justify-start'} gap-2`;
        wrap.innerHTML = `
            ${!msg.mine ? `<div class="w-8 h-8 rounded-xl flex items-center justify-center text-white text-xs font-bold flex-shrink-0 mt-1" style="background:linear-gradient(135deg,#3B59FF,#7B2FBE)">${msg.avatar}</div>` : ''}
            <div class="max-w-xs lg:max-w-md">
                ${!msg.mine ? `<p class="text-xs text-gray-400 mb-1 ml-1">${msg.user}</p>` : ''}
                <div class="px-4 py-2.5 rounded-2xl text-sm ${msg.mine ? 'text-white rounded-tr-sm' : 'bg-white text-gray-800 rounded-tl-sm shadow-sm border border-gray-100'}"
                     ${msg.mine ? 'style="background:linear-gradient(135deg,#3B59FF,#7B2FBE)"' : ''}>
                    ${msg.body.replace(/</g,'&lt;').replace(/>/g,'&gt;')}
                </div>
                <p class="text-[10px] text-gray-400 mt-1 ${msg.mine ? 'text-right mr-1' : 'ml-1'}">${msg.created_at}</p>
            </div>`;
        container.appendChild(wrap);
        scrollBottom();
    }

    // Enviar mensaje
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const body = input.value.trim();
        if (!body) return;
        input.value = '';

        const res  = await fetch('<?php echo e(route("admin.messages.store")); ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            body: JSON.stringify({ body }),
        });
        const msg = await res.json();
        if (msg.id) { renderMessage(msg); lastId = msg.id; }
    });

    // Polling cada 3 segundos para mensajes nuevos
    setInterval(async () => {
        const res  = await fetch(`<?php echo e(route("admin.messages.poll")); ?>?since=${lastId}`, {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
        });
        const msgs = await res.json();
        msgs.forEach(msg => { if (!msg.mine) { renderMessage(msg); lastId = msg.id; } });
    }, 3000);
})();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\SoporteSENA\Downloads\fiftyone-laravel12-main\resources\views\admin\messages\index.blade.php ENDPATH**/ ?>