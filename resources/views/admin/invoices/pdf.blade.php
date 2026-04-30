<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura {{ $invoice->invoice_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            font-size: 12px; 
            line-height: 1.5;
            color: #333;
            padding: 20px;
        }
        .container { max-width: 800px; margin: 0 auto; }
        
        /* Header */
        .header { 
            display: flex; 
            justify-content: space-between; 
            align-items: flex-start;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #4F46E5;
        }
        .company-info h1 { 
            font-size: 28px; 
            font-weight: bold; 
            color: #1F2937;
            margin-bottom: 5px;
        }
        .company-info p { 
            font-size: 11px; 
            color: #6B7280;
        }
        .invoice-info { text-align: right; }
        .invoice-badge {
            background: #4F46E5;
            color: white;
            padding: 5px 15px;
            border-radius: 5px;
            font-size: 10px;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 10px;
        }
        .invoice-number {
            font-size: 24px;
            font-weight: bold;
            color: #1F2937;
            margin-bottom: 5px;
        }
        .invoice-date {
            font-size: 11px;
            color: #6B7280;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: bold;
            margin-top: 8px;
        }
        .status-active {
            background: #D1FAE5;
            color: #065F46;
        }
        .status-cancelled {
            background: #FEE2E2;
            color: #991B1B;
        }

        /* Customer info */
        .customer-section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 10px;
            font-weight: bold;
            color: #6B7280;
            text-transform: uppercase;
            margin-bottom: 10px;
            letter-spacing: 0.5px;
        }
        .customer-box {
            background: #F9FAFB;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #E5E7EB;
        }
        .customer-name {
            font-weight: bold;
            font-size: 13px;
            color: #1F2937;
            margin-bottom: 5px;
        }
        .customer-detail {
            font-size: 11px;
            color: #4B5563;
            margin-bottom: 3px;
        }

        /* Cancellation notice */
        .cancellation-notice {
            background: #FEE2E2;
            border-left: 4px solid #DC2626;
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 5px;
        }
        .cancellation-notice h3 {
            color: #991B1B;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .cancellation-notice p {
            color: #7F1D1D;
            font-size: 11px;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        thead {
            background: #F3F4F6;
        }
        th {
            padding: 10px 8px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
            color: #374151;
            text-transform: uppercase;
            border-bottom: 2px solid #D1D5DB;
        }
        th.text-center { text-align: center; }
        th.text-right { text-align: right; }
        td {
            padding: 10px 8px;
            font-size: 11px;
            color: #4B5563;
            border-bottom: 1px solid #E5E7EB;
        }
        td.text-center { text-align: center; }
        td.text-right { text-align: right; }
        .product-name {
            font-weight: 600;
            color: #1F2937;
            margin-bottom: 2px;
        }
        .product-color {
            font-size: 10px;
            color: #6B7280;
        }
        .total-row {
            font-weight: bold;
            color: #1F2937;
        }

        /* Summary */
        .summary-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 25px;
        }
        .summary-box {
            width: 300px;
            background: #F9FAFB;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #E5E7EB;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 11px;
        }
        .summary-label {
            color: #6B7280;
        }
        .summary-value {
            font-weight: 600;
            color: #1F2937;
        }
        .summary-discount {
            color: #DC2626;
        }
        .summary-total {
            border-top: 2px solid #D1D5DB;
            padding-top: 12px;
            margin-top: 5px;
        }
        .summary-total .summary-label {
            font-weight: bold;
            color: #1F2937;
            font-size: 12px;
        }
        .summary-total .summary-value {
            font-size: 20px;
            font-weight: bold;
            color: #4F46E5;
        }

        /* Footer */
        .footer {
            border-top: 2px solid #E5E7EB;
            padding-top: 20px;
            margin-top: 30px;
        }
        .thank-you {
            background: #EFF6FF;
            border-left: 4px solid #3B82F6;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .thank-you h3 {
            color: #1E40AF;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 3px;
        }
        .thank-you p {
            color: #1E3A8A;
            font-size: 10px;
        }
        .footer-info {
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            color: #6B7280;
        }
        .footer-column h4 {
            font-weight: bold;
            color: #374151;
            margin-bottom: 5px;
        }
        .footer-column p {
            margin-bottom: 2px;
        }

        /* Print styles */
        @media print {
            body { padding: 0; }
            .container { max-width: 100%; }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="company-info">
                <h1>FIFTYONE</h1>
                <p>Ropa Oversize Colombia</p>
            </div>
            <div class="invoice-info">
                <div class="invoice-badge">FACTURA DE VENTA</div>
                <div class="invoice-number">{{ $invoice->invoice_number }}</div>
                <div class="invoice-date">{{ $invoice->created_at->format('d/m/Y H:i') }}</div>
                @if($invoice->status === 'cancelled')
                    <div class="status-badge status-cancelled">ANULADA</div>
                @else
                    <div class="status-badge status-active">ACTIVA</div>
                @endif
            </div>
        </div>

        <!-- Customer info -->
        <div class="customer-section">
            <div class="section-title">Facturado a:</div>
            <div class="customer-box">
                <div class="customer-name">{{ $invoice->customer_name }}</div>
                <div class="customer-detail">{{ $invoice->customer_email }}</div>
                @if($invoice->customer_address)
                    <div class="customer-detail">{{ $invoice->customer_address }}</div>
                @endif
                @if($invoice->customer_document)
                    <div class="customer-detail">CC/NIT: {{ $invoice->customer_document }}</div>
                @endif
            </div>
        </div>

        <!-- Cancellation notice -->
        @if($invoice->status === 'cancelled')
        <div class="cancellation-notice">
            <h3>Factura Anulada</h3>
            <p>{{ $invoice->cancellation_reason }}</p>
            <p style="margin-top: 5px;">Anulada el {{ $invoice->cancelled_at->format('d/m/Y H:i') }}</p>
        </div>
        @endif

        <!-- Products table -->
        <div class="section-title">Detalle de productos:</div>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th class="text-center">Talla</th>
                    <th class="text-right">Precio</th>
                    <th class="text-center">Cant.</th>
                    <th class="text-right">Desc.</th>
                    <th class="text-right">Subtotal</th>
                    <th class="text-right">IVA ({{ $invoice->iva_percentage ?? 19 }}%)</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                <tr>
                    <td>
                        <div class="product-name">{{ $item['name'] }}</div>
                        @if(isset($item['color']) && $item['color'])
                            <div class="product-color">Color: {{ $item['color'] }}</div>
                        @endif
                    </td>
                    <td class="text-center">{{ $item['size'] ?? '-' }}</td>
                    <td class="text-right">${{ number_format($item['price'], 0, ',', '.') }}</td>
                    <td class="text-center">{{ $item['quantity'] }}</td>
                    <td class="text-right">{{ $item['discount'] ?? 0 }}%</td>
                    <td class="text-right">${{ number_format($item['base_gravable'], 0, ',', '.') }}</td>
                    <td class="text-right">${{ number_format($item['iva'], 0, ',', '.') }}</td>
                    <td class="text-right total-row">${{ number_format($item['total'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary -->
        <div class="summary-section">
            <div class="summary-box">
                <div class="summary-row">
                    <span class="summary-label">Subtotal (antes de IVA):</span>
                    <span class="summary-value">${{ number_format($invoice->subtotal, 0, ',', '.') }}</span>
                </div>
                @if($invoice->total_discounts > 0)
                <div class="summary-row">
                    <span class="summary-label">Descuentos aplicados:</span>
                    <span class="summary-value summary-discount">-${{ number_format($invoice->total_discounts, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="summary-row">
                    <span class="summary-label">IVA ({{ $invoice->iva_percentage ?? 19 }}%):</span>
                    <span class="summary-value">${{ number_format($invoice->total_iva, 0, ',', '.') }}</span>
                </div>
                <div class="summary-row summary-total">
                    <span class="summary-label">TOTAL:</span>
                    <span class="summary-value">${{ number_format($invoice->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="thank-you">
                <h3>¡Gracias por tu compra!</h3>
                <p>Apreciamos tu confianza en FiftyOne. Esperamos verte pronto.</p>
            </div>
            
            <div class="footer-info">
                <div class="footer-column">
                    <h4>Política de devoluciones:</h4>
                    <p>Tienes 30 días para devolver productos sin usar</p>
                    <p>con etiquetas originales.</p>
                </div>
                <div class="footer-column">
                    <h4>Contacto:</h4>
                    <p>Email: contacto@fiftyone.com</p>
                    <p>WhatsApp: +57 300 123 4567</p>
                    <p>Web: www.fiftyone.com</p>
                </div>
            </div>
        </div>

        <div style="text-align: center; margin-top: 30px; font-size: 10px; color: #9CA3AF;">
            Este documento es una factura electrónica válida en Colombia
        </div>
    </div>
</body>
</html>
