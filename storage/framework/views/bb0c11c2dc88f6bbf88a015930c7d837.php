<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda - Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #494747;
            min-height: 100vh;
            padding: 20px 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
            color: #f7f7f7;
        }

        .container-main {
            max-width: 1200px;
            margin: 0 auto;
        }
  
        .header {
            background: white;
            padding: 25px;
            margin-bottom: 30px;
            border-radius: 8px;
            border-left: 4px solid #2c3e50;
        }

        .header h1 {
            color: #2c3e50;
            margin: 0;
            font-weight: 500;
            font-size: 28px;
        }

        .products-section {
            margin-bottom: 30px;
        }

        .product-card {
            background: white;
            border-radius: 8px;
            padding: 18px;
            margin-bottom: 15px;
            border: 1px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: border-color 0.2s ease;
        }

        .product-card:hover {
            border-color: #bbb;
        }

        .product-info h5 {
            color: #2c3e50;
            margin: 0 0 8px 0;
            font-weight: 500;
            font-size: 16px;
        }

        .product-info p {
            color: #777;
            margin: 0;
            font-size: 13px;
        }

        .btn-add {
            background: #2c3e50;
            border: 1px solid #2c3e50;
            color: white;
            padding: 8px 18px;
            border-radius: 6px;
            font-weight: 500;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.2s ease;
            white-space: nowrap;
            margin-left: 10px;
        }

        .btn-add:hover {
            background: #34495e;
            color: white;
            text-decoration: none;
        }

        .carrito-section {
            position: fixed;
            right: 30px;
            top: 20px;
            width: 360px;
            max-height: 80vh;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 12px;
            border: 1px solid #e0e0e0;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
            backdrop-filter: blur(10px);
        }

        .carrito-header {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 18px;
            border-bottom: 1px solid #e0e0e0;
            border-radius: 12px 12px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
            font-size: 16px;
        }

        .carrito-header span {
            background: #ffffff;
            color: #2c3e50;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .carrito-content {
            padding: 15px;
            max-height: 400px;
            overflow-y: auto;
        }

        .carrito-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background: #ffffff;
            border-radius: 8px;
            margin-bottom: 12px;
            border-left: 4px solid #2c3e50;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .carrito-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        }

        .item-info {
            flex: 1;
        }

        .item-name {
            color: #2c3e50;
            font-weight: 500;
            margin: 0;
            font-size: 14px;
        }

        .item-cantidad {
            color: #777;
            font-weight: 400;
            font-size: 13px;
            margin: 4px 0 0 0;
        }

        .btn-remove {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            transition: background-color 0.2s ease, transform 0.2s ease;
            margin-left: 10px;
            font-weight: 500;
        }

        .btn-remove:hover {
            background: #c0392b;
            transform: scale(1.05);
        }

        .carrito-vacio {
            text-align: center;
            padding: 30px 20px;
            color: #bbb;
            font-size: 14px;
        }

        .carrito-vacio-icon {
            font-size: 36px;
            margin-bottom: 10px;
            opacity: 0.6;
        }

        .carrito-footer {
            border-top: 1px solid #e0e0e0;
            padding: 18px;
            background: #f8f9fa;
            border-radius: 0 0 12px 12px;
        }

        .carrito-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .carrito-total span {
            color: #2c3e50;
            font-size: 16px;
            font-weight: 600;
        }

        .btn-checkout {
            width: 100%;
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s ease, transform 0.2s ease;
            margin-top: 12px;
            font-size: 14px;
            box-shadow: 0 4px 12px rgba(44, 62, 80, 0.3);
        }

        .btn-checkout:hover:not(:disabled) {
            background: linear-gradient(135deg, #34495e 0%, #2c3e50 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(44, 62, 80, 0.4);
        }

        .btn-checkout:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            background: #ccc;
            transform: none;
            box-shadow: none;
        }

        @media (max-width: 768px) {
            .carrito-section {
                position: static;
                width: 100%;
                max-height: none;
                margin-top: 30px;
            }

            .product-card {
                flex-direction: column;
                text-align: center;
            }

            .btn-add {
                margin-left: 0;
                margin-top: 10px;
                width: 100%;
            }
        }

        .badge-cart {
            display: inline-block;
            background: #ccc;
            color: #333;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container-main">
        <div class="header">
            <h1>🛍️ Catálogo de Productos</h1>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="products-section">
                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                        <div class="product-card">
                            <div class="product-info">
                                <h5><?php echo e($product->name); ?></h5>
                                <p><strong>ID:</strong> <?php echo e($product->id); ?></p>
                                <p><strong>Precio:</strong> COP $<?php echo e(number_format($product->price, 2, ',', '.')); ?></p>
                                <?php if($product->description): ?>
                                    <p><strong>Descripción:</strong> <?php echo e($product->description); ?></p>
                                <?php endif; ?>
                            </div>
                            <button class="btn-add" data-product="<?php echo json_encode($product); ?>" onclick="agregarAlcarrito(JSON.parse(this.dataset.product))">
                                ➕ Añadir al carrito
                            </button>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 
                </div>
            </div>

            <div class="col-lg-4">
                <div class="carrito-section">
                    <div class="carrito-header">
                        <div>🛒 Mi Carrito</div>
                        <span id="badge-count">0</span>
                    </div>
                    <div class="carrito-content" id="carrito">
                        <div class="carrito-vacio">
                            <div class="carrito-vacio-icon">🛒</div>
                            <p>Tu carrito está vacío</p>
                        </div>
                    </div>
                    <div class="carrito-footer">
                        <div class="carrito-total">
                            <span>Total de artículos:</span>
                            <span id="total-items">0</span>
                        </div>
                        <div class="carrito-total">
                            <span>Total precio:</span>
                            <span id="total-precio">COP $0,00</span>
                        </div>
                        <div class="carrito-total">
                            <span>Total con IVA (19%):</span>
                            <span id="total-iva">COP $0,00</span>
                        </div>
                        <button class="btn-checkout" id="btn-comprar" onclick="finalizarCompra()" disabled>
                            Finalizar Compra
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let carrito = JSON.parse(localStorage.getItem('carrito'))
        carrito = carrito ? carrito : []
        console.log("carrito:", carrito)
        
        function agregarAlcarrito(product){
            let posicion = carrito.findIndex(item => item.id === product.id)
            if(posicion !== -1){
                carrito[posicion].cantidad++
            } else {
                product.cantidad = 1
                carrito.push(product)
            }
            localStorage.setItem("carrito" , JSON.stringify(carrito))
            console.log(carrito)
            mostrarCarrito();
        }

        function eliminarDelCarrito(productId) {
            carrito = carrito.filter(item => item.id !== productId)
            mostrarCarrito();
        }

        function mostrarCarrito(){
            let divcarrito = document.getElementById('carrito')
            let buyBtn = document.getElementById('btn-comprar')
            let totalItems = document.getElementById('total-items')
            let badge = document.getElementById('badge-count')

            if(carrito.length === 0) {
                divcarrito.innerHTML = `
                    <div class="carrito-vacio">
                        <div class="carrito-vacio-icon">🛒</div>
                        <p>Tu carrito está vacío</p>
                    </div>
                `;
                buyBtn.disabled = true;
                totalItems.textContent = '0';
                badge.textContent = '0';
                document.getElementById('total-precio').textContent = 'COP $0,00';
                document.getElementById('total-iva').textContent = 'COP $0,00';
            } else {
                let items = carrito.map(item => `
                    <div class="carrito-item">
                        <div class="item-info">
                            <p class="item-name">${item.name}</p>
                            <p class="item-cantidad">Cantidad: <strong>${item.cantidad}</strong></p>
                            <p class="item-precio">Precio: <strong>COP $${item.price.toLocaleString('es-CO')}</strong></p>
                        </div>
                        <button class="btn-remove" onclick="eliminarDelCarrito(${item.id})">Eliminar</button>
                    </div>
                `).join('');

                divcarrito.innerHTML = items;
                buyBtn.disabled = false;
                
                let totalDatos = carrito.reduce((sum, item) => sum + item.cantidad, 0);
                let totalPrecio = carrito.reduce((sum, item) => sum + (item.price * item.cantidad), 0);
                let totalIVA = totalPrecio * 1.19;
                totalItems.textContent = totalDatos;
                badge.textContent = totalDatos;
                document.getElementById('total-precio').textContent = 'COP $' + totalPrecio.toLocaleString('es-CO');
                document.getElementById('total-iva').textContent = 'COP $' + totalIVA.toLocaleString('es-CO');
            }
        }

        function finalizarCompra() {
            if(carrito.length === 0) {
                alert('Tu carrito está vacío');
                return;
            }
            alert('¡Compra realizada! Productos en carrito: ' + carrito.length);
            // Aquí puedes enviar los datos del carrito al servidor
            console.log('Carrito final:', carrito);
        }
    </script>
</body>
</html><?php /**PATH C:\Users\SoporteSENA\Downloads\fiftyone-laravel12-main\resources\views\productos.blade.php ENDPATH**/ ?>