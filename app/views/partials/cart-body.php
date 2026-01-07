<div class="offcanvas-header border-bottom cart-border">
    <h3 class="offcanvas-title h3-bold text-primary">Carrito (<?php echo count($cartLinesToView); ?> Productos)</h3>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
</div>

<div class="offcanvas-body d-flex flex-column">
    <?php if (empty($cartLinesToView)): ?>
        <div class="text-center mt-5 text-muted-thalassa body-text-regular">Tu carrito está vacío.</div>
    <?php else: ?>
        <div class="flex-grow-1 overflow-auto no-scrollbar">
            <?php foreach ($cartLinesToView as $item):
                $dish = $item['dish'];
            ?>
                <div class="d-flex gap-3 mb-4 align-items-start animate__animated animate__fadeIn">
                    <img src="<?php echo htmlspecialchars($dish->getImages()[0]['path']); ?>"
                        class="rounded cart-item-img">

                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-0 h5-bold text-primary"><?php echo htmlspecialchars($dish->getDishName()); ?></h5>
                            <span class="h5-bold text-tertiary">€<?php echo number_format($item['price'], 2); ?></span>
                        </div>

                        <small class="text-muted-thalassa body-text-regular d-block mb-1">Individual</small>

                        <div class="mb-2">
                            <a class="text-decoration-none small text-primary h5-bold"
                                data-bs-toggle="collapse"
                                href="#note-collapse-<?php echo $item['index']; ?>"
                                role="button"
                                aria-expanded="<?php echo empty($item['notes']) ? 'false' : 'true'; ?>">
                                <?php echo empty($item['notes']) ? 'Agrega una nota' : 'Editar nota'; ?>
                            </a>

                            <div class="collapse <?php echo !empty($item['notes']) ? 'show' : ''; ?> mt-1" id="note-collapse-<?php echo $item['index']; ?>">
                                <textarea class="form-control form-control-sm cart-note-input body-text-regular"
                                    rows="2"
                                    placeholder="Ej: Sin cebolla, carne muy hecha..."
                                    oninput="CartBridge.updateNote(<?php echo $item['index']; ?>, this.value)"><?php echo htmlspecialchars($item['notes']); ?></textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="#" class="text-danger small text-decoration-none body-text-regular"
                                onclick="CartBridge.remove(<?php echo $item['index']; ?>); return false;">
                                Eliminar
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="mt-auto pt-3 border-top cart-border">
            <a href="index.php?controller=order&action=confirm"
                class="btn btn-primary w-100 py-3 rounded-pill h5-bold text-white btn-checkout">
                Tramitar • €<?php echo number_format($totalAmount, 2); ?>
            </a>
        </div>
    <?php endif; ?>
</div>