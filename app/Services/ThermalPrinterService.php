<?php

namespace App\Services;

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\EscposImage;

class ThermalPrinterService
{
    protected $printer;

    public function __construct()
    {
        // Kalau pakai Windows Shared Printer
        $connector = new WindowsPrintConnector("XP-80");
        // $connector = new WindowsPrintConnector("USB004");


        // Kalau printer via LAN
        // $connector = new NetworkPrintConnector("192.168.1.100", 9100);

        $this->printer = new Printer($connector);
    }

    public function printReceipt(array $data)
    {
        // --- Logo ---
        try {
            $logo = EscposImage::load(public_path("images/struck-logo-sm.png"), false);
            $this->printer->setJustification(Printer::JUSTIFY_CENTER);
            // $this->printer->graphics($logo, Printer::IMG_DEFAULT);

            $this->printer->bitImage($logo);
        } catch (\Exception $e) {
            // kalau logo tidak ditemukan, skip
        }

        // --- Header Toko ---
        $this->printer->setJustification(Printer::JUSTIFY_CENTER);
        $this->printer->text("Jalan Raya Kebonsari 540 Tumpang\n");
        $this->printer->text("081233330252\n");
        $this->printer->text(str_repeat("-", 32) . "\n");

        // --- Info Transaksi ---
        $this->printer->setJustification(Printer::JUSTIFY_LEFT);
        $this->printer->text("Invoice : {$data['invoice']}\n");
        $this->printer->text("Tanggal : {$data['date']}\n");
        $this->printer->text("Kasir   : {$data['cashier']}\n");
        $this->printer->text("Metode  : {$data['payment_method']}\n");
        $this->printer->text(str_repeat("-", 32) . "\n");

        // --- Item List ---
        foreach ($data['items'] as $item) {
            $this->printer->text($item['name'] . "\n");

            $line = sprintf(
                "%3s x %-8s %10s",
                $item['quantity'],
                $item['price'],
                $item['subtotal']
            );
            $this->printer->text($line . "\n");
        }

        $this->printer->text(str_repeat("-", 32) . "\n");

        // --- Summary ---
        $this->printer->setJustification(Printer::JUSTIFY_RIGHT);
        $this->printer->text("Subtotal : {$data['total']}\n");
        if (!empty($data['discount']) && $data['discount'] !== '0') {
            $this->printer->text("Diskon   : -{$data['discount']}\n");
        }
        $this->printer->text("Bayar    : {$data['paid']}\n");
        $this->printer->text("Kembali  : {$data['change']}\n");
        $this->printer->text(str_repeat("-", 32) . "\n");

        // --- Footer ---
        $this->printer->setJustification(Printer::JUSTIFY_CENTER);
        $this->printer->text("Terima kasih sudah berbelanja\n");
        $this->printer->text("di Chana Frozen!\n\n\n");

        $this->printer->feed(3);
        $this->printer->cut();

        $this->printer->close();
    }
}
