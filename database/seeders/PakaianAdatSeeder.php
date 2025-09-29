<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PakaianAdatSeeder extends Seeder
{
    public function run()
    {
        DB::table('pakaian_adats')->insert([
            [
                'nama' => 'Pakaian Adat Aceh',
                'jenis' => 'Pria',
                'asal' => 'Aceh',
                'deskripsi' => 'Dikenal dengan nama Ulee Balang. Pakaian pria disebut Linto Baro dan wanita Daro Baro.',
                'warna' => 'Hitam, Merah, Kuning',
                'price_per_day' => 210000,
                'image' => 'pakaian-adat/adat-aceh.png',
                'status' => 'Tersedia',
                'reduce' => 40,
            ],
            [
                'nama' => 'Kebaya Kutu Baru',
                'jenis' => 'Wanita',
                'asal' => 'Jawa',
                'deskripsi' => 'Varian kebaya dengan tambahan kain di bagian tengah yang menghubungkan sisi kiri dan kanan.',
                'warna' => 'Motif Bunga',
                'price_per_day' => 180000,
                'image' => 'pakaian-adat/kutubaru.png',
                'status' => 'Tersedia',
                'reduce' => 20,
            ],
            [
                'nama' => 'Baju Adat Toraja',
                'jenis' => 'Pria',
                'asal' => 'Sulawesi Selatan',
                'deskripsi' => 'Pakaian adat Toraja yang terbuat dari kain tenun khas Toraja dengan warna-warna alam.',
                'warna' => 'Hitam, Merah, Kuning',
                'price_per_day' => 240000,
                'image' => 'pakaian-adat/adat-toraja.png',
                'status' => 'Tersedia',
                'reduce' => 30,
            ],
            [
                'nama' => 'Baju Adat Jambi',
                'jenis' => 'Pria',
                'asal' => 'Jambi',
                'deskripsi' => 'Pakaian adat Jambi yang mewah dengan sulaman benang emas dan kain songket.',
                'warna' => 'Merah, Emas',
                'price_per_day' => 195000,
                'image' => 'pakaian-adat/adat-jambi.jpg',
                'status' => 'Tersedia',
                'reduce' => 20,
            ],
            [
                'nama' => 'Paksian',
                'jenis' => 'Wanita',
                'asal' => 'Kepulauan Bangka Belitung',
                'deskripsi' => 'Pakaian adat wanita Bangka Belitung yang terpengaruh budaya Tionghoa dan Melayu.',
                'warna' => 'Merah',
                'price_per_day' => 165000,
                'image' => 'pakaian-adat/paksian.jpg',
                'status' => 'Tersedia',
                'reduce' => 15,
            ],
            [
                'nama' => 'Beskap',
                'jenis' => 'Pria',
                'asal' => 'Jawa Barat',
                'deskripsi' => 'Pakaian adat pria Cirebon yang mirip dengan beskap namun dengan detail yang berbeda.',
                'warna' => 'Hijau, Emas',
                'price_per_day' => 135000,
                'image' => 'pakaian-adat/beskap.jpg',
                'status' => 'Tersedia',
                'reduce' => 40,
            ],
            [
                'nama' => 'Kebaya Bali',
                'jenis' => 'Wanita',
                'asal' => 'BALI',
                'deskripsi' => '- Perpaduan dengan Kain Kamen: Kebaya Bali biasanya dipadukan dengan kain kamen yang diikat di pinggang, menciptakan siluet yang anggun dan sopan. Kombinasi ini menjadi ciri khas pakaian adat Bali.
- Simbol Status Sosial: Pada masa lalu, jenis kain dan ornamen yang digunakan dalam kebaya Bali mencerminkan status sosial pemakainya. Meskipun saat ini perbedaan tersebut tidak terlalu kentara, kebaya Bali masih menunjukkan rasa hormat terhadap upacara yang dihadiri dan terhadap para leluhur.
- Makna Filosofis: Kebaya Bali bukan hanya sekadar pakaian, tetapi juga simbol identitas dan kebanggaan budaya. Setiap helai kain, hiasan, dan aksesori dalam kebaya Bali memiliki cerita dan makna tersendiri.',
                'warna' => 'Merah',
                'price_per_day' => 50000,
                'image' => 'pakaian-adat/kebaya-bali-merah.jpg',
                'status' => 'Tersedia',
                'reduce' => 10,
            ],
            [
                'nama' => 'Kebaya Bludru',
                'jenis' => 'Wanita',
                'asal' => 'JAWA TIMUR',
                'deskripsi' => 'Baju beludru kebaya adalah jenis pakaian yang memadukan bahan beludru dengan desain kebaya tradisional. Berikut beberapa karakteristik dan kelebihan baju beludru kebaya:
- Bahan Beludru: Bahan beludru dikenal dengan tekstur yang lembut dan permukaan yang halus, memberikan kesan mewah dan elegan pada pakaian.
- Desain Kebaya: Desain kebaya pada baju beludru kebaya biasanya mengikuti garis-garis tradisional kebaya, dengan potongan yang anggun dan feminin.
- Warna dan Motif: Baju beludru kebaya dapat hadir dalam berbagai warna, termasuk warna-warna cerah dan pastel, serta motif-motif yang beragam, seperti motif floral atau geometris.
- Kelebihan: Baju beludru kebaya cocok untuk acara-acara formal atau semi-formal, seperti pernikahan, resepsi, atau acara adat. Pakaian ini juga dapat memberikan kesan yang anggun dan elegan pada pemakainya.',
                'warna' => 'Merah, Maroon',
                'price_per_day' => 50000,
                'image' => 'pakaian-adat/kebaya-bludru-merah.jpg',
                'status' => 'Tersedia',
                'reduce' => 0,
            ],
            [
                'nama' => 'Baju Tari Saman',
                'jenis' => 'Pria',
                'asal' => 'ACEH',
                'deskripsi' => 'Baju Tari Saman Aceh adalah pakaian tradisional yang digunakan dalam pertunjukan Tari Saman, sebuah tarian tradisional Aceh yang terkenal dengan gerakan yang dinamis dan harmonis. Berikut beberapa karakteristik baju tari Saman Aceh:
- Warna: Baju tari Saman Aceh biasanya memiliki warna yang cerah dan mencolok, seperti merah, kuning, dan hijau, yang mencerminkan kekayaan budaya Aceh.
- Desain: Baju ini memiliki desain yang sederhana namun elegan, dengan motif-motif yang khas Aceh, seperti motif geometris dan floral.
- Bahan: Baju tari Saman Aceh biasanya terbuat dari bahan kain yang ringan dan nyaman, sehingga memungkinkan penari untuk bergerak dengan leluasa.
- Aksesoris: Baju ini sering dilengkapi dengan aksesoris seperti songket, selindung, dan asesoris lainnya yang menambah keindahan dan kemewahan pakaian.
Baju tari Saman Aceh memiliki makna yang mendalam dalam budaya Aceh, yaitu sebagai simbol kebanggaan dan identitas budaya masyarakat Aceh. Pakaian ini juga menjadi bagian penting dalam pertunjukan Tari Saman, yang merupakan salah satu warisan budaya Indonesia yang berharga.',
                'warna' => 'Oranye',
                'price_per_day' => 50000,
                'image' => 'pakaian-adat/baju-tari-saman.jpg',
                'status' => 'Tersedia',
                'reduce' => 11,
            ],
            [
                'nama' => 'Dodotan Bali',
                'jenis' => 'Anak Perempuan',
                'asal' => 'BALI',
                'deskripsi' => 'Baju Dodotan adalah pakaian tradisional Bali yang memiliki makna budaya dan estetika tinggi. Berikut deskripsi Baju Dodotan:
- Desain: Baju Dodotan memiliki desain yang sederhana namun elegan, dengan motif-motif yang khas Bali seperti motif floral dan geometris.
- Warna: Baju Dodotan sering menggunakan warna-warna cerah dan mencolok seperti putih, kuning, dan merah, yang mencerminkan kekayaan budaya Bali.
- Bahan: Baju Dodotan biasanya terbuat dari bahan kain yang nyaman dan ringan, seperti katun atau sutra.
- Mahkota: Baju Dodotan sering dilengkapi dengan mahkota atau hiasan kepala yang khas Bali, seperti mahkota bunga atau asesoris lainnya.
- Makna: Baju Dodotan memiliki makna yang mendalam dalam budaya Bali, yaitu sebagai simbol kebanggaan dan identitas budaya masyarakat Bali.
Baju Dodotan sering digunakan dalam upacara adat, pernikahan, dan pertunjukan seni di Bali. Pakaian ini menjadi bagian penting dari warisan budaya Indonesia yang kaya dan beragam.',
                'warna' => 'Merah, Emas',
                'price_per_day' => 150000,
                'image' => 'pakaian-adat/dodotan-bali.jpg',
                'status' => 'Tersedia',
                'reduce' => 12,
            ],
            [
                'nama' => 'Baju Adat Madura',
                'jenis' => 'Pria',
                'asal' => 'JAWA TIMUR',
                'deskripsi' => 'Baju adat Madura memiliki keunikan dan keindahan tersendiri. Berikut beberapa deskripsi tentang baju adat Madura:
- Baju Akat Bini (Pakaian Adat Wanita): Baju adat wanita Madura disebut "Akat Bini". Pakaian ini terdiri dari baju kurung dengan motif-motif yang elegan dan indah, serta dilengkapi dengan asesoris seperti kalung dan gelang.
- Baju Akat Aghung (Pakaian Adat Pria): Baju adat pria Madura disebut "Akat Aghung". Pakaian ini terdiri dari baju koko dengan motif-motif yang khas Madura, serta dilengkapi dengan asesoris seperti songkok atau peci.
- Motif dan Warna: Baju adat Madura memiliki motif-motif yang unik dan warna-warna yang cerah, seperti motif bunga, geometris, dan lain-lain.
- Makna: Baju adat Madura memiliki makna yang mendalam dalam budaya Madura, yaitu sebagai simbol kebanggaan dan identitas budaya masyarakat Madura.',
                'warna' => 'putih',
                'price_per_day' => 50000,
                'image' => 'pakaian-adat/baju-adat-madura.jpg',
                'status' => 'Tersedia',
                'reduce' => 13,
            ],
            [
                'nama' => 'Baju Adat Jawa Timur',
                'jenis' => 'Wanita',
                'asal' => 'JAWA TIMUR',
                'deskripsi' => 'Baju adat Jawa Timur memiliki keunikan dan keindahan tersendiri. Berikut beberapa deskripsi tentang baju adat Jawa Timur:
- Beskap: Baju adat pria Jawa Timur disebut "Beskap". Pakaian ini terdiri dari baju koko dengan motif-motif yang elegan dan indah.
- Kebaya: Baju adat wanita Jawa Timur disebut "Kebaya". Pakaian ini terdiri dari baju kurung dengan motif-motif yang khas Jawa Timur, seperti motif batik dan bordir.
- Motif dan Warna: Baju adat Jawa Timur memiliki motif-motif yang unik dan warna-warna yang cerah, seperti motif batik, geometris, dan lain-lain.
- Asesoris: Baju adat Jawa Timur sering dilengkapi dengan asesoris seperti keris, gelang, dan kalung.
- Makna: Baju adat Jawa Timur memiliki makna yang mendalam dalam budaya Jawa Timur, yaitu sebagai simbol kebanggaan dan identitas budaya masyarakat Jawa Timur.',
                'warna' => 'Hitam',
                'price_per_day' => 50000,
                'image' => 'pakaian-adat/baju-adat-jawatimur.jpg',
                'status' => 'Tersedia',
                'reduce' => 10,
            ],
            [
                'nama' => 'Kebaya Wisuda',
                'jenis' => 'Wanita',
                'asal' => 'JAWA BARAT',
                'deskripsi' => 'Set Kebaya PINK',
                'warna' => 'Merah Muda',
                'price_per_day' => 100000,
                'image' => 'pakaian-adat/kebaya-wisuda.jpg',
                'status' => 'Tersedia',
                'reduce' => 30,
            ],
            [
                'nama' => 'Baju Lurik',
                'jenis' => 'Pria',
                'asal' => 'JAWA TIMUR',
                'deskripsi' => 'Set Baju Lurik Jawa Timur',
                'warna' => 'Coklat, Hitam',
                'price_per_day' => 50000,
                'image' => 'pakaian-adat/lurik.jpg',
                'status' => 'Tersedia',
                'reduce' => 20,
            ],
            [
                'nama' => 'Arabian Look',
                'jenis' => 'Wanita',
                'asal' => 'ACEH',
                'deskripsi' => 'Set Baju Arabian Look',
                'warna' => 'Emas',
                'price_per_day' => 750000,
                'image' => 'pakaian-adat/arabian-look.jpg',
                'status' => 'Tersedia',
                'reduce' => 12,
            ],
        ]);
    }
}
