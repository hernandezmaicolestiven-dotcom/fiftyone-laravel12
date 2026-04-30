<?php if($paginator->hasPages()): ?>
<?php
    $currentPage = $paginator->currentPage();
    $lastPage    = $paginator->lastPage();
    $from        = $paginator->firstItem();
    $to          = $paginator->lastItem();
    $total       = $paginator->total();
?>
<div class="flex flex-col sm:flex-row items-center justify-between gap-3 text-sm">

    
    <p class="text-xs text-gray-400">
        Mostrando <span class="font-semibold text-gray-600"><?php echo e($from); ?></span>–<span class="font-semibold text-gray-600"><?php echo e($to); ?></span>
        de <span class="font-semibold text-gray-600"><?php echo e($total); ?></span> resultados
    </p>

    
    <div class="flex items-center gap-1">

        
        <?php if($paginator->onFirstPage()): ?>
            <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl border border-gray-200 text-gray-300 cursor-not-allowed select-none">
                <i class="fa-solid fa-chevron-left text-xs"></i>
            </span>
        <?php else: ?>
            <a href="<?php echo e($paginator->previousPageUrl()); ?>"
               class="inline-flex items-center justify-center w-9 h-9 rounded-xl border border-gray-200 text-gray-500 hover:border-indigo-300 hover:text-indigo-600 hover:bg-indigo-50 transition">
                <i class="fa-solid fa-chevron-left text-xs"></i>
            </a>
        <?php endif; ?>

        
        <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(is_string($element)): ?>
                <span class="inline-flex items-center justify-center w-9 h-9 text-gray-400 select-none">…</span>
            <?php endif; ?>
            <?php if(is_array($element)): ?>
                <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($page == $currentPage): ?>
                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl text-white text-xs font-bold shadow-sm"
                              style="background: linear-gradient(90deg, #3B59FF, #7B2FBE)">
                            <?php echo e($page); ?>

                        </span>
                    <?php else: ?>
                        <a href="<?php echo e($url); ?>"
                           class="inline-flex items-center justify-center w-9 h-9 rounded-xl border border-gray-200 text-gray-600 text-xs font-medium hover:border-indigo-300 hover:text-indigo-600 hover:bg-indigo-50 transition">
                            <?php echo e($page); ?>

                        </a>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        
        <?php if($paginator->hasMorePages()): ?>
            <a href="<?php echo e($paginator->nextPageUrl()); ?>"
               class="inline-flex items-center justify-center w-9 h-9 rounded-xl border border-gray-200 text-gray-500 hover:border-indigo-300 hover:text-indigo-600 hover:bg-indigo-50 transition">
                <i class="fa-solid fa-chevron-right text-xs"></i>
            </a>
        <?php else: ?>
            <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl border border-gray-200 text-gray-300 cursor-not-allowed select-none">
                <i class="fa-solid fa-chevron-right text-xs"></i>
            </span>
        <?php endif; ?>

    </div>
</div>
<?php endif; ?>
<?php /**PATH C:\MAI\fiftyone-laravel12-main\resources\views/vendor/pagination/tailwind.blade.php ENDPATH**/ ?>