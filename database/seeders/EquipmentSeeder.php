<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Equipment;
use App\Models\Category;

class EquipmentSeeder extends Seeder
{
    public function run(): void
    {
        $equipmentData = [
            'Rekayasa Perangkat Lunak (RPL)' => [
                ['INV-RPL-2026-001', 'PC Workstation Core i7', 'PC Lab High Spec untuk Android Studio & Programming', 36, 'Lab RPL 1', 15000000, 'PT Komputer Sejahtera', 2024],
                ['INV-RPL-2026-002', 'Mikrotik RB1100AHx4', 'Router Gateway Utama Lab Jaringan', 5, 'Lab Jaringan', 12000000, 'CV Network Prima', 2023],
                ['INV-RPL-2026-003', 'Crimping Tool RJ45', 'Tang Krimping RJ45 Networking', 20, 'Lab Jaringan', 250000, 'Toko Elektronik Jaya', 2025],
                ['INV-RPL-2026-004', 'Server Dell PowerEdge', 'Server Ujian Lokal (CBT) & Database', 2, 'Ruang Server', 45000000, 'Dell Indonesia', 2023],
                ['INV-RPL-2026-005', 'Switch Gigabit 24 Port', 'Switch managed untuk lab networking', 8, 'Lab Jaringan', 5500000, 'CV Network Prima', 2024],
            ],
            'Teknik Pemesinan (TP)' => [
                ['INV-TP-2026-001', 'Mesin Bubut CNC', 'Mesin bubut computer numerical control', 2, 'Bengkel TP', 85000000, 'PT Mesin Industri Nusantara', 2022],
                ['INV-TP-2026-002', 'Jangka Sorong Digital', 'Alat ukur presisi 150mm Mitutoyo', 30, 'Ruang Alat', 450000, 'Toko Alat Teknik Cemerlang', 2025],
                ['INV-TP-2026-003', 'Mesin Frais Universal', 'Milling Machine standard industri', 4, 'Bengkel TP', 65000000, 'PT Mesin Industri Nusantara', 2021],
                ['INV-TP-2026-004', 'Mikrometer Luar', 'Alat ukur presisi 0-25mm', 25, 'Ruang Alat', 350000, 'Toko Alat Teknik Cemerlang', 2024],
            ],
            'Teknik Pengelasan (TPL)' => [
                ['INV-TPL-2026-001', 'Mesin Las TIG/Argon', 'Mesin las tungsten inert gas', 5, 'Bengkel Las', 8500000, 'PT Las Karya Mandiri', 2023],
                ['INV-TPL-2026-002', 'Helm Las Auto Darkening', 'Helm pelindung mata sensor otomatis', 25, 'Ruang APD', 850000, 'CV Safety Equipment', 2025],
                ['INV-TPL-2026-003', 'Trafo Las Inverter 900W', 'Trafo las portable', 10, 'Bengkel Las', 3200000, 'PT Las Karya Mandiri', 2024],
                ['INV-TPL-2026-004', 'Gerinda Tangan 4 Inch', 'Mesin gerinda potong & poles', 15, 'Bengkel Las', 750000, 'Toko Bangunan Sumber Rejeki', 2025],
            ],
            'Teknik Otomasi Industri (TOI)' => [
                ['INV-TOI-2026-001', 'PLC Omron CP1E', 'Programmable Logic Controller Trainer', 10, 'Lab Otomasi', 6500000, 'PT Automation Technology', 2023],
                ['INV-TOI-2026-002', 'Pneumatic Cylinder Kit', 'Set aktuator pneumatik pembelajaran', 8, 'Lab Otomasi', 3500000, 'PT Automation Technology', 2024],
                ['INV-TOI-2026-003', 'HMI Touchscreen 7"', 'Human Machine Interface Panel', 5, 'Lab Otomasi', 4200000, 'PT Automation Technology', 2024],
                ['INV-TOI-2026-004', 'Sensor Proximity', 'Sensor induktif 12-24VDC', 30, 'Lab Sensor', 320000, 'Toko Elektronik Jaya', 2025],
            ],
            'Teknik Pendingin & Tata Udara (TPTU)' => [
                ['INV-TPTU-2026-001', 'Manifold Gauge Set', 'Alat ukur tekanan Freon R32/R410', 15, 'Lab TPTU', 1250000, 'CV Refrigerasi Maju', 2024],
                ['INV-TPTU-2026-002', 'Pompa Vakum 1HP', 'Vacuum pump untuk instalasi AC', 6, 'Lab TPTU', 4500000, 'CV Refrigerasi Maju', 2023],
                ['INV-TPTU-2026-003', 'Tang Ampere Digital', 'Clamp meter untuk ukur arus kompresor', 12, 'Lab TPTU', 950000, 'Toko Elektronik Jaya', 2025],
                ['INV-TPTU-2026-004', 'Thermometer Infrared', 'Pengukur suhu non-contact', 10, 'Lab TPTU', 650000, 'Toko Elektronik Jaya', 2025],
            ],
            'Desain Pemodelan & Informasi Bangunan (DPIB)' => [
                ['INV-DPIB-2026-001', 'Total Station', 'Alat ukur survei pemetaan digital', 3, 'Lab DPIB', 75000000, 'PT Surveyor Indonesia', 2022],
                ['INV-DPIB-2026-002', 'Theodolite Digital', 'Alat ukur sudut tanah presisi', 5, 'Lab DPIB', 18000000, 'PT Surveyor Indonesia', 2023],
                ['INV-DPIB-2026-003', 'Drone Pemetaan DJI', 'Drone Phantom untuk topografi', 1, 'Ruang Alat', 35000000, 'DJI Indonesia', 2024],
                ['INV-DPIB-2026-004', 'Waterpass Digital', 'Level digital untuk survey', 8, 'Lab DPIB', 2500000, 'Toko Alat Teknik Cemerlang', 2025],
            ],
            'Bisnis Konstruksi & Properti (BKP)' => [
                ['INV-BKP-2026-001', 'Bor Impact Drill', 'Bor beton heavy duty Bosch', 10, 'Bengkel BKP', 2100000, 'Toko Bangunan Sumber Rejeki', 2024],
                ['INV-BKP-2026-002', 'Gergaji Circular Saw', 'Mesin gergaji potong kayu listrik', 5, 'Bengkel BKP', 3500000, 'Toko Bangunan Sumber Rejeki', 2024],
                ['INV-BKP-2026-003', 'Waterpass Alumunium', 'Alat ukur kedataran 60cm', 20, 'Ruang Alat', 180000, 'Toko Bangunan Sumber Rejeki', 2025],
                ['INV-BKP-2026-004', 'Meteran Laser Digital', 'Pengukur jarak laser 50m', 12, 'Ruang Alat', 850000, 'Toko Elektronik Jaya', 2025],
            ],
            'Teknik Elektronika Industri (TEI)' => [
                ['INV-TEI-2026-001', 'Oscilloscope Digital', 'Alat ukur gelombang sinyal 100MHz', 8, 'Lab TEI', 12500000, 'PT Elektronik Instrument', 2023],
                ['INV-TEI-2026-002', 'Solder Station Analog', 'Solder dengan pengaturan suhu', 30, 'Lab TEI', 450000, 'Toko Elektronik Jaya', 2024],
                ['INV-TEI-2026-003', 'Power Supply Variable', 'Catu daya DC 0-30V 5A', 15, 'Lab TEI', 2200000, 'PT Elektronik Instrument', 2024],
                ['INV-TEI-2026-004', 'Multimeter Digital', 'AVO meter Fluke', 25, 'Lab TEI', 1350000, 'Toko Elektronik Jaya', 2025],
            ],
            'Teknik & Bisnis Sepeda Motor (TBSM)' => [
                ['INV-TBSM-2026-001', 'Bike Lift Hydraulic', 'Lift hidrolik servis motor', 4, 'Bengkel TBSM', 5500000, 'CV Otomotif Pratama', 2023],
                ['INV-TBSM-2026-002', 'Impact Wrench Angin', 'Kunci pembuka baut tenaga angin', 6, 'Bengkel TBSM', 1850000, 'CV Otomotif Pratama', 2024],
                ['INV-TBSM-2026-003', 'Scanner Injeksi Universal', 'Alat scan ECU motor injeksi', 3, 'Bengkel TBSM', 8500000, 'PT Diagnostic Tools', 2024],
                ['INV-TBSM-2026-004', 'Kompresor Angin 2HP', 'Kompresor udara bengkel', 2, 'Bengkel TBSM', 4200000, 'Toko Bangunan Sumber Rejeki', 2023],
            ],
        ];

        foreach ($equipmentData as $categoryName => $items) {
            $category = Category::where('name', $categoryName)->first();

            if ($category) {
                foreach ($items as $item) {
                    Equipment::create([
                        'code' => $item[0],
                        'name' => $item[1],
                        'category_id' => $category->id,
                        'description' => $item[2],
                        'stock' => $item[3],
                        'location' => $item[4],
                        'condition' => 'baik',
                        'price' => $item[5],
                        'vendor' => $item[6],
                        'purchase_year' => $item[7],
                    ]);
                }
            }
        }
    }
}
