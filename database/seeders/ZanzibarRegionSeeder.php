<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Zanzibar regions (ids 33–37) and their districts (ids 477–487).
 */
class ZanzibarRegionSeeder extends Seeder
{
    /**
     * Each region lists its own districts, so a district always gets its parent region's id.
     */
    private const REGIONS = [
        ['id' => 33, 'name' => 'Mjini Magharibi', 'code' => 'MM', 'districts' => [
            ['id' => 477, 'name' => 'Mjini'],
            ['id' => 478, 'name' => 'Magharibi A'],
            ['id' => 479, 'name' => 'Magharibi B'],
        ]],
        ['id' => 34, 'name' => 'Kaskazini Unguja', 'code' => 'KU', 'districts' => [
            ['id' => 480, 'name' => 'Kaskazini A'],
            ['id' => 481, 'name' => 'Kaskazini B'],
        ]],
        ['id' => 35, 'name' => 'Kusini Unguja', 'code' => 'SU', 'districts' => [
            ['id' => 482, 'name' => 'Kati'],
            ['id' => 483, 'name' => 'Kusini'],
        ]],
        ['id' => 36, 'name' => 'Kaskazini Pemba', 'code' => 'KP', 'districts' => [
            ['id' => 484, 'name' => 'Wete'],
            ['id' => 485, 'name' => 'Micheweni'],
        ]],
        ['id' => 37, 'name' => 'Kusini Pemba', 'code' => 'SP', 'districts' => [
            ['id' => 486, 'name' => 'Chake Chake'],
            ['id' => 487, 'name' => 'Mkoani'],
        ]],
    ];

    /**
     * Shehia per district: district_id => [ward_id => name].
     */
    private const WARDS = [
        // Mjini
        477 => [
            6478 => 'Malindi',
            6479 => 'Mkunazini',
            6480 => 'Shangani',
            6481 => 'Kiponda',
            6482 => 'Vikokotoni',
            6483 => 'Mwembeladu',
            6484 => 'Kikwajuni Juu',
            6485 => 'Kikwajuni Bondeni',
            6486 => 'Miembeni',
            6487 => 'Rahaleo',
            6488 => 'Kwahani',
            6489 => 'Jang\'ombe',
            6490 => 'Kilimani',
            6491 => 'Mwembemakumbi',
            6492 => 'Chumbuni',
            6493 => 'Amani',
            6494 => 'Mpendae',
            6495 => 'Kidongochekundu',
            6496 => 'Sebleni',
            6497 => 'Magomeni',
            6498 => 'Mikunguni',
            6499 => 'Karakana',
            6500 => 'Mitiulaya',
            6501 => 'Kisiwandui',
            6502 => 'Mlandege',
            6503 => 'Gulioni',
            6504 => 'Kwamtipura',
            6505 => 'Nyerere',
            6506 => 'Shaurimoyo',
            6507 => 'Kilimahewa',
        ],
        // Magharibi A
        478 => [
            6508 => 'Bububu',
            6509 => 'Mtoni',
            6510 => 'Kibweni',
            6511 => 'Mwanyanya',
            6512 => 'Kizimbani',
            6513 => 'Dole',
            6514 => 'Mfenesini',
            6515 => 'Chuini',
            6516 => 'Mwakaje',
            6517 => 'Bumbwisudi',
            6518 => 'Kama',
            6519 => 'Kianga',
            6520 => 'Welezo',
            6521 => 'Mtofaani',
        ],
        // Magharibi B
        479 => [
            6522 => 'Fuoni',
            6523 => 'Kiembesamaki',
            6524 => 'Chukwani',
            6525 => 'Mombasa',
            6526 => 'Kwerekwe',
            6527 => 'Mwanakwerekwe',
            6528 => 'Tomondo',
            6529 => 'Magogoni',
            6530 => 'Kinuni',
            6531 => 'Pangawe',
            6532 => 'Melinne',
            6533 => 'Kijitoupele',
            6534 => 'Shakani',
            6535 => 'Kisauni',
            6536 => 'Maungani',
            6537 => 'Dimani',
            6538 => 'Fumba',
            6539 => 'Bweleo',
            6540 => 'Mbweni',
        ],
        // Kaskazini A
        480 => [
            6541 => 'Nungwi',
            6542 => 'Kendwa',
            6543 => 'Tazari',
            6544 => 'Kidoti',
            6545 => 'Kijini',
            6546 => 'Matemwe',
            6547 => 'Pwani Mchangani',
            6548 => 'Gamba',
            6549 => 'Moga',
            6550 => 'Chaani',
            6551 => 'Kinyasini',
            6552 => 'Mkokotoni',
            6553 => 'Tumbatu Gomani',
            6554 => 'Tumbatu Jongowe',
            6555 => 'Fukuchani',
            6556 => 'Mkwajuni',
            6557 => 'Kivunge',
            6558 => 'Potoa',
        ],
        // Kaskazini B
        481 => [
            6559 => 'Mahonda',
            6560 => 'Donge',
            6561 => 'Kitope',
            6562 => 'Fujoni',
            6563 => 'Kiomba Mvua',
            6564 => 'Mangapwani',
            6565 => 'Bumbwini',
            6566 => 'Upenja',
            6567 => 'Pangeni',
            6568 => 'Zingwezingwe',
            6569 => 'Kinduni',
            6570 => 'Mafufuni',
            6571 => 'Makoba',
        ],
        // Kati
        482 => [
            6572 => 'Dunga',
            6573 => 'Koani',
            6574 => 'Bambi',
            6575 => 'Uzini',
            6576 => 'Umbuji',
            6577 => 'Machui',
            6578 => 'Pongwe',
            6579 => 'Uroa',
            6580 => 'Chwaka',
            6581 => 'Marumbi',
            6582 => 'Charawe',
            6583 => 'Ukongoroni',
            6584 => 'Jumbi',
            6585 => 'Tunguu',
            6586 => 'Cheju',
            6587 => 'Mwera',
            6588 => 'Kiboje',
            6589 => 'Binguni',
            6590 => 'Ghana',
            6591 => 'Ndijani',
        ],
        // Kusini
        483 => [
            6592 => 'Jambiani',
            6593 => 'Paje',
            6594 => 'Bwejuu',
            6595 => 'Michamvi',
            6596 => 'Kizimkazi Dimbani',
            6597 => 'Kizimkazi Mkunguni',
            6598 => 'Muyuni',
            6599 => 'Kitogani',
            6600 => 'Pete',
            6601 => 'Mtende',
            6602 => 'Kibuteni',
            6603 => 'Kajengwa',
            6604 => 'Nganani',
            6605 => 'Mzuri',
            6606 => 'Tasani',
        ],
        // Wete
        484 => [
            6607 => 'Bopwe',
            6608 => 'Utaani',
            6609 => 'Mtemani',
            6610 => 'Kipangani',
            6611 => 'Jadida',
            6612 => 'Pandani',
            6613 => 'Gando',
            6614 => 'Kiuyu',
            6615 => 'Kojani',
            6616 => 'Fundo',
            6617 => 'Mtambwe Kaskazini',
            6618 => 'Mtambwe Kusini',
            6619 => 'Pembeni',
            6620 => 'Kangagani',
            6621 => 'Junguni',
            6622 => 'Shengejuu',
            6623 => 'Ukunjwi',
            6624 => 'Kinyikani',
            6625 => 'Mjananza',
            6626 => 'Selem',
            6627 => 'Limbani',
            6628 => 'Mchangamdogo',
            6629 => 'Kisiwani',
            6630 => 'Piki',
        ],
        // Micheweni
        485 => [
            6631 => 'Micheweni',
            6632 => 'Konde',
            6633 => 'Tumbe Mashariki',
            6634 => 'Tumbe Magharibi',
            6635 => 'Chimba',
            6636 => 'Msuka Mashariki',
            6637 => 'Msuka Magharibi',
            6638 => 'Makangale',
            6639 => 'Shumba Viamboni',
            6640 => 'Shumba Mjini',
            6641 => 'Kinowe',
            6642 => 'Sizini',
            6643 => 'Mgogoni',
            6644 => 'Majenzi',
            6645 => 'Kiuyu Mbuyuni',
            6646 => 'Maziwa Ng\'ombe',
        ],
        // Chake Chake
        486 => [
            6647 => 'Chachani',
            6648 => 'Madungu',
            6649 => 'Tibirinzi',
            6650 => 'Wawi',
            6651 => 'Vitongoji',
            6652 => 'Ziwani',
            6653 => 'Wara',
            6654 => 'Ole',
            6655 => 'Pujini',
            6656 => 'Kilindi',
            6657 => 'Shungi',
            6658 => 'Ndagoni',
            6659 => 'Uwandani',
            6660 => 'Mkoroshoni',
            6661 => 'Mfikiwa',
            6662 => 'Matale',
            6663 => 'Gombani',
            6664 => 'Chanjaani',
            6665 => 'Chonga',
            6666 => 'Pondeani',
            6667 => 'Wesha',
            6668 => 'Vikunguni',
            6669 => 'Kwale',
            6670 => 'Dodo',
        ],
        // Mkoani
        487 => [
            6671 => 'Mkoani',
            6672 => 'Mbuyuni',
            6673 => 'Makombeni',
            6674 => 'Chokocho',
            6675 => 'Kengeja',
            6676 => 'Mwambe',
            6677 => 'Kangani',
            6678 => 'Kiwani',
            6679 => 'Mtambile',
            6680 => 'Wambaa',
            6681 => 'Chumbageni',
            6682 => 'Mizingani',
            6683 => 'Ngwachani',
            6684 => 'Chambani',
            6685 => 'Uweleni',
            6686 => 'Kisiwapanza',
            6687 => 'Makoongwe',
            6688 => 'Shidi',
            6689 => 'Michenzani',
            6690 => 'Jombwe',
            6691 => 'Mgagadu',
            6692 => 'Ukutini',
            6693 => 'Mjimbini',
            6694 => 'Changaweni',
            6695 => 'Mkanyageni',
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            foreach (self::REGIONS as $region) {
                DB::table('regions')->updateOrInsert(
                    ['id' => $region['id']],
                    ['name' => $region['name'], 'code' => $region['code']]
                );

                foreach ($region['districts'] as $district) {
                    DB::table('districts')->updateOrInsert(
                        ['id' => $district['id']],
                        ['name' => $district['name'], 'region_id' => $region['id']]
                    );
                }
            }

            foreach (self::WARDS as $districtId => $wards) {
                foreach ($wards as $wardId => $name) {
                    DB::table('wards')->updateOrInsert(
                        ['id' => $wardId],
                        ['name' => $name, 'district_id' => $districtId]
                    );
                }
            }
        });
    }
}
