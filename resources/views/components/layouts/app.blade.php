<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        @vite(['resources/css/app.css','resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/qz-tray@2.1.0/qz-tray.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <title>{{ $title ?? 'POS - App' }}</title>
    </head>
    <body>
        <div class="min-h-screen flex bg-gray-100">
            <!-- Sidebar -->
            <x-sidebar />

            <!-- Main Content -->
            <main class="flex-1 p-8">
                {{ $slot ?? '' }}
            </main>
        </div>

        @livewireScripts

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Livewire.on('print-struk', function (sale) {
                if (!qz.websocket.isActive()) {
                    qz.websocket.connect().then(() => sendToPrinter(sale));
                } else {
                    sendToPrinter(sale);
                }
            });

            function sendToPrinter(sale) {
                const config = qz.configs.create("POS-PRINTER");

                const items = sale.items.map(item =>
                    `${item.name} x${item.quantity}  @${item.price} = ${item.subtotal}\n`
                ).join('');

                const content = [
                    '\x1B\x40',
                    'CHANA FROZEN\n',
                    'Jl. Kebonsari No.1\n',
                    '-----------------------------\n',
                    `Invoice: ${sale.invoice}\n`,
                    `Tanggal: ${sale.date}\n\n`,
                    items,
                    '-----------------------------\n',
                    `Total   : ${sale.total}\n`,
                    `Bayar   : ${sale.paid}\n`,
                    `Kembali : ${sale.change}\n`,
                    '\n\n\n\n',
                    '\x1D\x56\x00'
                ];

                qz.print(config, content).catch(e => alert("Gagal cetak: " + e));
            }

            Livewire.on('print-preview', function (...sale) {
                const data = sale[0][0]

                const items = data.items
                if (!items || items.length === 0) {
                    alert("Keranjang kosong, tidak ada yang bisa dipreview.");
                    
                    return;
                }

                const itemRows = items.map(item => `
                    <tr>
                        <td>${item.name}</td>
                        <td style="text-align:right;">x${item.quantity}</td>
                        <td style="text-align:right;">@${item.price}</td>
                        <td style="text-align:right;">${item.subtotal}</td>
                    </tr>
                `).join('');

                const html = `
                    <html>
                    <head>
                        <title>Struk Penjualan</title>
                        <style>
                            body { font-family: monospace; padding: 10px; }
                            table { width: 100%; border-collapse: collapse; }
                            td { padding: 2px; }
                            .right { text-align: right; }
                            .center { text-align: center; }
                            .bold { font-weight: bold; }
                        </style>
                    </head>
                    <body>
                        <div class="center bold">CHANA FROZEN</div>
                        <div class="center">Jl. Kebonsari No.1</div>
                        <hr>
                        <div>Invoice: ${data.invoice}</div>
                        <div>Tanggal: ${data.date}</div>
                        <div>Kasir: ${data.cashier}</div>
                        <hr>
                        <table>${itemRows}</table>
                        <hr>
                        <table>
                            <tr><td colspan="3" class="right">Total</td><td class="right">${data.total}</td></tr>
                            <tr><td colspan="3" class="right">Bayar</td><td class="right">${data.paid}</td></tr>
                            <tr><td colspan="3" class="right">Kembali</td><td class="right">${data.change}</td></tr>
                        </table>
                        <hr>
                        <div class="center">Terima kasih sudah berbelanja di Chana Frozen!</div>
                        <script>window.print();<\/script>
                    </body>
                    </html>
                `;

                const win = window.open('', 'Struk', 'width=400,height=600');
                win.document.write(html);
                win.document.close();
            });
        });

        window.addEventListener('swal', event => {
            const msg = event.detail[0];

            Swal.fire({
                title: msg.title,
                text: msg.text,
                icon: msg.icon,
                timer: 2000,
                showConfirmButton: false,
            });
        });
    </script>

    </body>
</html>
