<div class="checkout-summary-panel bg-thalassa-blue text-white h-100 d-flex justify-content-center pt-5">

    <div class="w-100 mx-5">

        <div class="d-flex flex-column gap-3 my-4 checkout-products-scroll no-scrollbar">
            <?php foreach ($orderLinesObjects as $item):
                /** @var OrderLine $line */
                $line = $item['lineObj'];
                /** @var Dish $dish */
                $dish = $item['dishObj'];

                $images = $dish->getImages();
                $imgPath = isset($images[0]['path']) ? $images[0]['path'] : 'assets/img/placeholder.jpg';
            ?>
                <div class="d-flex align-items-center gap-3 my-3">

                    <div class="position-relative">
                        <div class="bg-white rounded border border-secondary-subtle overflow-hidden d-flex justify-content-center align-items-center checkout-img-wrapper">
                            <img src="<?= htmlspecialchars($imgPath) ?>" class="w-100 h-100 object-fit-contain">
                        </div>
                        <span class="position-absolute top-0 start-100 badge rounded-pill bg-secondary text-white fs-xs badge-quantity">
                            1
                        </span>
                    </div>

                    <div class="flex-grow-1">
                        <h6 class="mb-0 fw-normal fs-sm-plus"><?= htmlspecialchars($dish->getDishName()) ?></h6>
                        <small class="text-white-50"><?= htmlspecialchars($dish->getCategory()) ?></small>
                        <?php if (!empty($line->getNotes())): ?>
                            <small class="d-block text-white-50 fst-italic">"<?= htmlspecialchars($line->getNotes()) ?>"</small>
                        <?php endif; ?>
                    </div>

                    <div class="fw-bold">
                        <?= number_format($line->getUnitPrice(), 2, ',', '.') ?> €
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <hr class="border-white-20">

        <div class="d-flex gap-2 mb-4">
            <input type="text"
                id="discount-input"
                class="form-control"
                placeholder="Código de descuento"
                value="<?= $appliedDiscount ? htmlspecialchars($appliedDiscount->getDiscountCode()) : '' ?>"
                <?= $appliedDiscount ? 'disabled' : '' ?>>

            <button class="btn btn-light text-primary fw-bold px-3"
                onclick="CartBridge.applyDiscount()"
                <?= $appliedDiscount ? 'disabled' : '' ?>>
                Aplicar
            </button>
        </div>

        <?php if ($discountError): ?>
            <div class="alert alert-danger py-2 small mb-3">
                <i class="bi bi-exclamation-circle"></i> <?= $discountError ?>
            </div>
        <?php endif; ?>

        <?php if ($appliedDiscount): ?>
            <div class="d-flex justify-content-between mb-2 text-white small">
                <span class="text-white fw-bold">
                    <i class="bi bi-tag-fill"></i> Descuento (<?= $appliedDiscount->getPercent() ?>%)
                </span>
                <span class="text-white fw-bold">-<?= number_format($discountAmount, 2, ',', '.') ?> €</span>
            </div>
        <?php endif; ?>

 <hr class="border-white-20 my-4">

        <div class="d-flex flex-column gap-2 text-white">
            
            <div class="d-flex justify-content-between">
                <span>Subtotal · <?= count($orderLinesObjects) ?> artículos</span>
                <span><?= number_format($subtotal, 2, ',', '.') ?> €</span>
            </div>

            <?php if ($appliedDiscount): ?>
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex flex-column">
                    <span>Descuento aplicado</span>
                    <span class="fs-sm-plus">
                        <i class="bi bi-tag-fill me-1"></i> <?= $appliedDiscount->getDiscountCode() ?>
                    </span>
                </div>
                <span>-<?= number_format($discountAmount, 2, ',', '.') ?> €</span>
            </div>
            <?php endif; ?>

            <div class="d-flex justify-content-between align-items-center mt-2">
                <span>Envío <i class="bi bi-question-circle ms-1 fs-xs"></i></span>
                <span class="text-white-50 text-end small">Introducir la dirección de envío</span>
            </div>

        </div>

        <hr class="border-white-20 my-4">

        <div class="d-flex justify-content-between align-items-baseline">
            <span class="fs-4">Total</span>
            <div class="text-end">
                <small class="text-white-50 me-2 fs-xs">EUR</small>
                <span class="fs-2 fw-bold"><?= number_format($finalTotal, 2, ',', '.') ?> €</span>
            </div>
        </div>
        
        <?php 
            // Fórmula: Precio / 1.10 = Base Imponible. Precio - Base = IVA.
            $taxAmount = $finalTotal - ($finalTotal / 1.10); 
        ?>
        <div class="small text-white-50 mb-3">
            Incluye <?= number_format($taxAmount, 2, ',', '.') ?> € de impuestos
        </div>

        <?php if ($appliedDiscount): ?>
            <div class="d-flex align-items-center gap-2 mt-2">
                <i class="bi bi-tag-fill fs-5"></i>
                <span class="fw-bold fs-5">AHORRO TOTAL <?= number_format($discountAmount, 2, ',', '.') ?> €</span>
            </div>
        <?php endif; ?>

        <input type="hidden" id="hidden-discount-id" value="<?= $appliedDiscount ? $appliedDiscount->getDiscountId() : '' ?>">

    </div>
</div>