<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\DeliveryZone;
use App\Models\District;
use App\Models\Thana;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BangladeshLocationSeeder extends Seeder
{
    /**
     * Seed all 64 districts and primary thanas of Bangladesh.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Thana::truncate();
        District::truncate();
        DeliveryZone::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Delivery Zones
        DeliveryZone::create(['name' => 'Inside Dhaka', 'code' => 'inside_dhaka', 'charge' => 60, 'estimated_days' => '24-48 Hours', 'is_active' => true]);
        DeliveryZone::create(['name' => 'Dhaka Sub-Urbs (Gazipur, Narayanganj, Savar)', 'code' => 'sub_dhaka', 'charge' => 100, 'estimated_days' => '2-3 Days', 'is_active' => true]);
        DeliveryZone::create(['name' => 'Outside Dhaka (All BD)', 'code' => 'outside_dhaka', 'charge' => 120, 'estimated_days' => '3-5 Days', 'is_active' => true]);

        $districts = [
            // Dhaka Division
            ['name_en' => 'Dhaka', 'name_bn' => 'ঢাকা', 'division' => 'Dhaka', 'is_inside' => true, 'is_sub' => false, 'thanas' => [
                ['en' => 'Mirpur', 'bn' => 'মিরপুর', 'post' => '1216'],
                ['en' => 'Dhanmondi', 'bn' => 'ধানমন্ডি', 'post' => '1209'],
                ['en' => 'Gulshan', 'bn' => 'গুলশান', 'post' => '1212'],
                ['en' => 'Banani', 'bn' => 'বনানী', 'post' => '1213'],
                ['en' => 'Uttara', 'bn' => 'উত্তরা', 'post' => '1230'],
                ['en' => 'Mohammadpur', 'bn' => 'মোহাম্মদপুর', 'post' => '1207'],
                ['en' => 'Motijheel', 'bn' => 'মতিঝিল', 'post' => '1000'],
                ['en' => 'Badda', 'bn' => 'বাড্ডা', 'post' => '1212'],
                ['en' => 'Khilkhet', 'bn' => 'খিলক্ষেত', 'post' => '1229'],
                ['en' => 'Jatrabari', 'bn' => 'যাত্রাবাড়ী', 'post' => '1204'],
                ['en' => 'Demra', 'bn' => 'ডেমরা', 'post' => '1360'],
                ['en' => 'Lalbagh', 'bn' => 'লালবাগ', 'post' => '1211'],
                ['en' => 'Tejgaon', 'bn' => 'তেজগাঁও', 'post' => '1215'],
                ['en' => 'Rampura', 'bn' => 'রামপুরা', 'post' => '1219'],
                ['en' => 'Shahbagh', 'bn' => 'শাহবাগ', 'post' => '1000'],
                ['en' => 'Paltan', 'bn' => 'পল্টন', 'post' => '1000'],
                ['en' => 'Savar', 'bn' => 'সাভার', 'post' => '1340'],
                ['en' => 'Keraniganj', 'bn' => 'কেরানীগঞ্জ', 'post' => '1310'],
                ['en' => 'Dhamrai', 'bn' => 'ধামরাই', 'post' => '1350'],
                ['en' => 'Nawabganj', 'bn' => 'নবাবগঞ্জ', 'post' => '1320'],
                ['en' => 'Dohar', 'bn' => 'দোহার', 'post' => '1330'],
            ]],
            ['name_en' => 'Gazipur', 'name_bn' => 'গাজীপুর', 'division' => 'Dhaka', 'is_inside' => false, 'is_sub' => true, 'thanas' => [
                ['en' => 'Gazipur Sadar', 'bn' => 'গাজীপুর সদর', 'post' => '1700'],
                ['en' => 'Tongi', 'bn' => 'টঙ্গী', 'post' => '1710'],
                ['en' => 'Kaliakair', 'bn' => 'কালিয়াকৈর', 'post' => '1750'],
                ['en' => 'Kapasia', 'bn' => 'কাপাসিয়া', 'post' => '1730'],
                ['en' => 'Sreepur', 'bn' => 'শ্রীপুর', 'post' => '1740'],
                ['en' => 'Kaliganj', 'bn' => 'কালীগঞ্জ', 'post' => '1720'],
            ]],
            ['name_en' => 'Narayanganj', 'name_bn' => 'নারায়ণগঞ্জ', 'division' => 'Dhaka', 'is_inside' => false, 'is_sub' => true, 'thanas' => [
                ['en' => 'Narayanganj Sadar', 'bn' => 'নারায়ণগঞ্জ সদর', 'post' => '1400'],
                ['en' => 'Bandar', 'bn' => 'বন্দর', 'post' => '1410'],
                ['en' => 'Fatullah', 'bn' => 'ফতুল্লা', 'post' => '1420'],
                ['en' => 'Siddhirganj', 'bn' => 'সিদ্ধিরগঞ্জ', 'post' => '1430'],
                ['en' => 'Rupganj', 'bn' => 'রূপগঞ্জ', 'post' => '1460'],
                ['en' => 'Sonargaon', 'bn' => 'সোনারগাঁও', 'post' => '1440'],
                ['en' => 'Araihazar', 'bn' => 'আড়াইহাজার', 'post' => '1450'],
            ]],
            ['name_en' => 'Munshiganj', 'name_bn' => 'মুন্সিগঞ্জ', 'division' => 'Dhaka', 'is_inside' => false, 'is_sub' => true, 'thanas' => [
                ['en' => 'Munshiganj Sadar', 'bn' => 'মুন্সিগঞ্জ সদর', 'post' => '1500'],
                ['en' => 'Srinagar', 'bn' => 'শ্রীনগর', 'post' => '1550'],
                ['en' => 'Sirajdikhan', 'bn' => 'সিরাজদিখান', 'post' => '1530'],
                ['en' => 'Louhajang', 'bn' => 'লৌহজং', 'post' => '1530'],
                ['en' => 'Tongibari', 'bn' => 'টঙ্গীবাড়ী', 'post' => '1520'],
                ['en' => 'Gazaria', 'bn' => 'গজারিয়া', 'post' => '1510'],
            ]],
            ['name_en' => 'Manikganj', 'name_bn' => 'মানিকগঞ্জ', 'division' => 'Dhaka', 'is_inside' => false, 'is_sub' => true, 'thanas' => [
                ['en' => 'Manikganj Sadar', 'bn' => 'মানিকগঞ্জ সদর', 'post' => '1800'],
                ['en' => 'Singair', 'bn' => 'সিংগাইর', 'post' => '1820'],
                ['en' => 'Saturia', 'bn' => 'সাটুরিয়া', 'post' => '1810'],
                ['en' => 'Shibalaya', 'bn' => 'শিবালয়', 'post' => '1850'],
            ]],
            ['name_en' => 'Narsingdi', 'name_bn' => 'নরসিংদী', 'division' => 'Dhaka', 'is_inside' => false, 'is_sub' => true, 'thanas' => [
                ['en' => 'Narsingdi Sadar', 'bn' => 'নরসিংদী সদর', 'post' => '1600'],
                ['en' => 'Palash', 'bn' => 'পলাশ', 'post' => '1610'],
                ['en' => 'Shibpur', 'bn' => 'শিবপুর', 'post' => '1620'],
                ['en' => 'Raipura', 'bn' => 'রায়পুরা', 'post' => '1630'],
                ['en' => 'Belabo', 'bn' => 'বেলাবো', 'post' => '1640'],
                ['en' => 'Monohardi', 'bn' => 'মনোহরদী', 'post' => '1650'],
            ]],
            ['name_en' => 'Faridpur', 'name_bn' => 'ফরিদপুর', 'division' => 'Dhaka', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Faridpur Sadar', 'bn' => 'ফরিদপুর সদর', 'post' => '7800'],
                ['en' => 'Boalmari', 'bn' => 'বোয়ালমারী', 'post' => '7860'],
                ['en' => 'Bhanga', 'bn' => 'ভাঙ্গা', 'post' => '7830'],
            ]],
            ['name_en' => 'Madaripur', 'name_bn' => 'মাদারীপুর', 'division' => 'Dhaka', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Madaripur Sadar', 'bn' => 'মাদারীপুর সদর', 'post' => '7900'],
                ['en' => 'Shibchar', 'bn' => 'শিবচর', 'post' => '7930'],
            ]],
            ['name_en' => 'Gopalganj', 'name_bn' => 'গোপালগঞ্জ', 'division' => 'Dhaka', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Gopalganj Sadar', 'bn' => 'গোপালগঞ্জ সদর', 'post' => '8100'],
                ['en' => 'Kashiani', 'bn' => 'কাশিয়ানী', 'post' => '8120'],
                ['en' => 'Tungipara', 'bn' => 'টুঙ্গিপাড়া', 'post' => '8120'],
            ]],
            ['name_en' => 'Rajbari', 'name_bn' => 'রাজবাড়ী', 'division' => 'Dhaka', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Rajbari Sadar', 'bn' => 'রাজবাড়ী সদর', 'post' => '7700'],
                ['en' => 'Pangsha', 'bn' => 'পাংশা', 'post' => '7720'],
            ]],
            ['name_en' => 'Shariatpur', 'name_bn' => 'শরীয়তপুর', 'division' => 'Dhaka', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Shariatpur Sadar', 'bn' => 'শরীয়তপুর সদর', 'post' => '8000'],
                ['en' => 'Naria', 'bn' => 'নড়িয়া', 'post' => '8010'],
                ['en' => 'Zajira', 'bn' => 'জাজিরা', 'post' => '8020'],
            ]],
            ['name_en' => 'Kishoreganj', 'name_bn' => 'কিশোরগঞ্জ', 'division' => 'Dhaka', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Kishoreganj Sadar', 'bn' => 'কিশোরগঞ্জ সদর', 'post' => '2300'],
                ['en' => 'Bhairab', 'bn' => 'ভৈরব', 'post' => '2350'],
                ['en' => 'Bajitpur', 'bn' => 'বাজিতপুর', 'post' => '2336'],
            ]],
            ['name_en' => 'Tangail', 'name_bn' => 'টাঙ্গাইল', 'division' => 'Dhaka', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Tangail Sadar', 'bn' => 'টাঙ্গাইল সদর', 'post' => '1900'],
                ['en' => 'Mirzapur', 'bn' => 'মির্জাপুর', 'post' => '1940'],
                ['en' => 'Ghatail', 'bn' => 'ঘাটাইল', 'post' => '1980'],
                ['en' => 'Sakhipur', 'bn' => 'সখিপুর', 'post' => '1950'],
            ]],

            // Chattogram Division
            ['name_en' => 'Chattogram', 'name_bn' => 'চট্টগ্রাম', 'division' => 'Chattogram', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Kotwali', 'bn' => 'কোতোয়ালী', 'post' => '4000'],
                ['en' => 'Panchlaish', 'bn' => 'পাঁচলাইশ', 'post' => '4203'],
                ['en' => 'Halishahar', 'bn' => 'হালিশহর', 'post' => '4216'],
                ['en' => 'Agrabad', 'bn' => 'আগ্রাবাদ', 'post' => '4100'],
                ['en' => 'Double Mooring', 'bn' => 'ডবলমুরিং', 'post' => '4100'],
                ['en' => 'Khulshi', 'bn' => 'খুলশী', 'post' => '4225'],
                ['en' => 'Pahartali', 'bn' => 'পাহাড়তলী', 'post' => '4202'],
                ['en' => 'Hathazari', 'bn' => 'হাটহাজারী', 'post' => '4330'],
                ['en' => 'Sitakunda', 'bn' => 'সীতাকুণ্ড', 'post' => '4310'],
                ['en' => 'Mirsharai', 'bn' => 'মীরসরাই', 'post' => '4320'],
                ['en' => 'Patiya', 'bn' => 'পটিয়া', 'post' => '4370'],
                ['en' => 'Boalkhali', 'bn' => 'বোয়ালখালী', 'post' => '4360'],
                ['en' => 'Raozan', 'bn' => 'রাউজান', 'post' => '4340'],
            ]],
            ['name_en' => 'Cox\'s Bazar', 'name_bn' => 'কক্সবাজার', 'division' => 'Chattogram', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Cox\'s Bazar Sadar', 'bn' => 'কক্সবাজার সদর', 'post' => '4700'],
                ['en' => 'Ramu', 'bn' => 'রামু', 'post' => '4730'],
                ['en' => 'Teknaf', 'bn' => 'টেকনাফ', 'post' => '4760'],
                ['en' => 'Chakaria', 'bn' => 'চকোরিয়া', 'post' => '4740'],
            ]],
            ['name_en' => 'Cumilla', 'name_bn' => 'কুমিল্লা', 'division' => 'Chattogram', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Cumilla Adarsha Sadar', 'bn' => 'কুমিল্লা আদর্শ সদর', 'post' => '3500'],
                ['en' => 'Laksam', 'bn' => 'লাকসাম', 'post' => '3570'],
                ['en' => 'Daudkandi', 'bn' => 'দাউদকান্দি', 'post' => '3516'],
                ['en' => 'Debidwar', 'bn' => 'দেবিদ্বার', 'post' => '3530'],
            ]],
            ['name_en' => 'Feni', 'name_bn' => 'ফেনী', 'division' => 'Chattogram', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Feni Sadar', 'bn' => 'ফেনী সদর', 'post' => '3900'],
                ['en' => 'Daganbhuiyan', 'bn' => 'দাগনভূঞা', 'post' => '3920'],
                ['en' => 'Chhagalnaiya', 'bn' => 'ছাগলনাইয়া', 'post' => '3910'],
            ]],
            ['name_en' => 'Brahmanbaria', 'name_bn' => 'ব্রাহ্মণবাড়িয়া', 'division' => 'Chattogram', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Brahmanbaria Sadar', 'bn' => 'ব্রাহ্মণবাড়িয়া সদর', 'post' => '3400'],
                ['en' => 'Ashuganj', 'bn' => 'আশুগঞ্জ', 'post' => '3402'],
                ['en' => 'Kasba', 'bn' => 'কসবা', 'post' => '3460'],
                ['en' => 'Nabinagar', 'bn' => 'নবীনগর', 'post' => '3410'],
            ]],
            ['name_en' => 'Chandpur', 'name_bn' => 'চাঁদপুর', 'division' => 'Chattogram', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Chandpur Sadar', 'bn' => 'চাঁদপুর সদর', 'post' => '3600'],
                ['en' => 'Hajiganj', 'bn' => 'হাজীগঞ্জ', 'post' => '3610'],
                ['en' => 'Matlab', 'bn' => 'মতলব', 'post' => '3640'],
            ]],
            ['name_en' => 'Noakhali', 'name_bn' => 'নোয়াখালী', 'division' => 'Chattogram', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Noakhali Sadar', 'bn' => 'নোয়াখালী সদর', 'post' => '3800'],
                ['en' => 'Begumganj', 'bn' => 'বেগমগঞ্জ', 'post' => '3820'],
                ['en' => 'Chowmuhani', 'bn' => 'চৌমুহনী', 'post' => '3821'],
            ]],
            ['name_en' => 'Lakshmipur', 'name_bn' => 'লক্ষ্মীপুর', 'division' => 'Chattogram', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Lakshmipur Sadar', 'bn' => 'লক্ষ্মীপুর সদর', 'post' => '3700'],
                ['en' => 'Raipur', 'bn' => 'রায়পুর', 'post' => '3710'],
                ['en' => 'Ramganj', 'bn' => 'রামগঞ্জ', 'post' => '3720'],
            ]],
            ['name_en' => 'Rangamati', 'name_bn' => 'রাঙ্গামাটি', 'division' => 'Chattogram', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Rangamati Sadar', 'bn' => 'রাঙ্গামাটি সদর', 'post' => '4500'],
                ['en' => 'Kaptai', 'bn' => 'কাপ্তাই', 'post' => '4530'],
            ]],
            ['name_en' => 'Bandarban', 'name_bn' => 'বান্দরবান', 'division' => 'Chattogram', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Bandarban Sadar', 'bn' => 'বান্দরবান সদর', 'post' => '4600'],
                ['en' => 'Ruma', 'bn' => 'রুমা', 'post' => '4620'],
            ]],
            ['name_en' => 'Khagrachhari', 'name_bn' => 'খাগড়াছড়ি', 'division' => 'Chattogram', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Khagrachhari Sadar', 'bn' => 'খাগড়াছড়ি সদর', 'post' => '4400'],
                ['en' => 'Dighinala', 'bn' => 'দীঘিনালা', 'post' => '4420'],
            ]],

            // Rajshahi Division
            ['name_en' => 'Rajshahi', 'name_bn' => 'রাজশাহী', 'division' => 'Rajshahi', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Boalia', 'bn' => 'বোয়ালিয়া', 'post' => '6000'],
                ['en' => 'Motihar', 'bn' => 'মতিহার', 'post' => '6204'],
                ['en' => 'Rajpara', 'bn' => 'রাজপাড়া', 'post' => '6000'],
                ['en' => 'Godagari', 'bn' => 'গোদাগাড়ী', 'post' => '6290'],
                ['en' => 'Paba', 'bn' => 'পবা', 'post' => '6201'],
                ['en' => 'Bagha', 'bn' => 'বাঘা', 'post' => '6280'],
            ]],
            ['name_en' => 'Bogura', 'name_bn' => 'বগুড়া', 'division' => 'Rajshahi', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Bogura Sadar', 'bn' => 'বগুড়া সদর', 'post' => '5800'],
                ['en' => 'Sherpur', 'bn' => 'শেরপুর', 'post' => '5840'],
                ['en' => 'Shibganj', 'bn' => 'শিবগঞ্জ', 'post' => '5810'],
                ['en' => 'Gabtali', 'bn' => 'গাবতলী', 'post' => '5820'],
            ]],
            ['name_en' => 'Pabna', 'name_bn' => 'পাবনা', 'division' => 'Rajshahi', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Pabna Sadar', 'bn' => 'পাবনা সদর', 'post' => '6600'],
                ['en' => 'Ishwardi', 'bn' => 'ঈশ্বরদী', 'post' => '6620'],
                ['en' => 'Chatmohar', 'bn' => 'চাটমোহর', 'post' => '6630'],
            ]],
            ['name_en' => 'Sirajganj', 'name_bn' => 'সিরাজগঞ্জ', 'division' => 'Rajshahi', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Sirajganj Sadar', 'bn' => 'সিরাজগঞ্জ সদর', 'post' => '6700'],
                ['en' => 'Shahjadpur', 'bn' => 'শাহজাদপুর', 'post' => '6770'],
                ['en' => 'Ullapara', 'bn' => 'উল্লাপাড়া', 'post' => '6740'],
            ]],
            ['name_en' => 'Naogaon', 'name_bn' => 'নওগাঁ', 'division' => 'Rajshahi', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Naogaon Sadar', 'bn' => 'নওগাঁ সদর', 'post' => '6500'],
                ['en' => 'Patnitala', 'bn' => 'পত্নীতলা', 'post' => '6540'],
                ['en' => 'Manda', 'bn' => 'মান্দা', 'post' => '6520'],
            ]],
            ['name_en' => 'Natore', 'name_bn' => 'নাটোর', 'division' => 'Rajshahi', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Natore Sadar', 'bn' => 'নাটোর সদর', 'post' => '6400'],
                ['en' => 'Baraigram', 'bn' => 'বড়াইগ্রাম', 'post' => '6430'],
                ['en' => 'Singra', 'bn' => 'সিংড়া', 'post' => '6450'],
            ]],
            ['name_en' => 'Chapai Nawabganj', 'name_bn' => 'চাঁপাইনবাবগঞ্জ', 'division' => 'Rajshahi', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Chapai Sadar', 'bn' => 'সদর', 'post' => '6300'],
                ['en' => 'Shibganj', 'bn' => 'শিবগঞ্জ', 'post' => '6320'],
            ]],
            ['name_en' => 'Joypurhat', 'name_bn' => 'জয়পুরহাট', 'division' => 'Rajshahi', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Joypurhat Sadar', 'bn' => 'জয়পুরহাট সদর', 'post' => '5900'],
                ['en' => 'Panchbibi', 'bn' => 'পাঁচবিবি', 'post' => '5910'],
            ]],

            // Khulna Division
            ['name_en' => 'Khulna', 'name_bn' => 'খুলনা', 'division' => 'Khulna', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Kotwali', 'bn' => 'কোতোয়ালী', 'post' => '9100'],
                ['en' => 'Sonadanga', 'bn' => 'সোনাডাঙ্গা', 'post' => '9000'],
                ['en' => 'Khalishpur', 'bn' => 'খালিশপুর', 'post' => '9000'],
                ['en' => 'Daulatpur', 'bn' => 'দৌলতপুর', 'post' => '9202'],
                ['en' => 'Dumuria', 'bn' => 'ডুমুরিয়া', 'post' => '9250'],
                ['en' => 'Rupsha', 'bn' => 'রূপসা', 'post' => '9240'],
            ]],
            ['name_en' => 'Jashore', 'name_bn' => 'যশোর', 'division' => 'Khulna', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Jashore Sadar', 'bn' => 'যশোর সদর', 'post' => '7400'],
                ['en' => 'Benapole', 'bn' => 'বেনাপোল', 'post' => '7431'],
                ['en' => 'Jhikargachha', 'bn' => 'ঝিকরগাছা', 'post' => '7420'],
            ]],
            ['name_en' => 'Kushtia', 'name_bn' => 'কুষ্টিয়া', 'division' => 'Khulna', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Kushtia Sadar', 'bn' => 'কুষ্টিয়া সদর', 'post' => '7000'],
                ['en' => 'Kumarkhali', 'bn' => 'কুমারখালী', 'post' => '7010'],
                ['en' => 'Bheramara', 'bn' => 'ভেড়ামারা', 'post' => '7040'],
            ]],
            ['name_en' => 'Satkhira', 'name_bn' => 'সাতক্ষীরা', 'division' => 'Khulna', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Satkhira Sadar', 'bn' => 'সাতক্ষীরা সদর', 'post' => '9400'],
                ['en' => 'Kalaroa', 'bn' => 'কলারোয়া', 'post' => '9410'],
                ['en' => 'Shyamnagar', 'bn' => 'শ্যামনগর', 'post' => '9450'],
            ]],
            ['name_en' => 'Bagerhat', 'name_bn' => 'বাগেরহাট', 'division' => 'Khulna', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Bagerhat Sadar', 'bn' => 'বাগেরহাট সদর', 'post' => '9300'],
                ['en' => 'Mongla', 'bn' => 'মোংলা', 'post' => '9350'],
            ]],
            ['name_en' => 'Jhenaidah', 'name_bn' => 'ঝিনাইদহ', 'division' => 'Khulna', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Jhenaidah Sadar', 'bn' => 'ঝিনাইদহ সদর', 'post' => '7300'],
                ['en' => 'Kaliganj', 'bn' => 'কালীগঞ্জ', 'post' => '7320'],
            ]],
            ['name_en' => 'Chuadanga', 'name_bn' => 'চুয়াডাঙ্গা', 'division' => 'Khulna', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Chuadanga Sadar', 'bn' => 'চুয়াডাঙ্গা সদর', 'post' => '7200'],
                ['en' => 'Alamdanga', 'bn' => 'আলমডাঙ্গা', 'post' => '7210'],
            ]],
            ['name_en' => 'Meherpur', 'name_bn' => 'মেহেরপুর', 'division' => 'Khulna', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Meherpur Sadar', 'bn' => 'মেহেরপুর সদর', 'post' => '7100'],
                ['en' => 'Gangni', 'bn' => 'গাংনী', 'post' => '7110'],
            ]],
            ['name_en' => 'Narail', 'name_bn' => 'নড়াইল', 'division' => 'Khulna', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Narail Sadar', 'bn' => 'নড়াইল সদর', 'post' => '7500'],
                ['en' => 'Lohagara', 'bn' => 'লোহাগড়া', 'post' => '7510'],
            ]],
            ['name_en' => 'Magura', 'name_bn' => 'মাগুরা', 'division' => 'Khulna', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Magura Sadar', 'bn' => 'মাগুরা সদর', 'post' => '7600'],
                ['en' => 'Sreepur', 'bn' => 'শ্রীপুর', 'post' => '7610'],
            ]],

            // Barishal Division
            ['name_en' => 'Barishal', 'name_bn' => 'বরিশাল', 'division' => 'Barishal', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Kotwali', 'bn' => 'কোতোয়ালী', 'post' => '8200'],
                ['en' => 'Airport', 'bn' => 'এয়ারপোর্ট', 'post' => '8205'],
                ['en' => 'Bakerganj', 'bn' => 'বাকেরগঞ্জ', 'post' => '8280'],
                ['en' => 'Babuganj', 'bn' => 'বাবুগঞ্জ', 'post' => '8210'],
            ]],
            ['name_en' => 'Patuakhali', 'name_bn' => 'পটুয়াখালী', 'division' => 'Barishal', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Patuakhali Sadar', 'bn' => 'পটুয়াখালী সদর', 'post' => '8600'],
                ['en' => 'Kuakata', 'bn' => 'কুয়াকাটা', 'post' => '8650'],
                ['en' => 'Galachipa', 'bn' => 'গলাচিপা', 'post' => '8640'],
            ]],
            ['name_en' => 'Bhola', 'name_bn' => 'ভোলা', 'division' => 'Barishal', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Bhola Sadar', 'bn' => 'ভোলা সদর', 'post' => '8300'],
                ['en' => 'Char Fasson', 'bn' => 'চরফ্যাশন', 'post' => '8340'],
            ]],
            ['name_en' => 'Pirojpur', 'name_bn' => 'পিরোজপুর', 'division' => 'Barishal', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Pirojpur Sadar', 'bn' => 'পিরোজপুর সদর', 'post' => '8500'],
                ['en' => 'Nesarabad (Swarupkathi)', 'bn' => 'নেছারাবাদ', 'post' => '8520'],
            ]],
            ['name_en' => 'Barguna', 'name_bn' => 'বরগুনা', 'division' => 'Barishal', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Barguna Sadar', 'bn' => 'বরগুনা সদর', 'post' => '8700'],
                ['en' => 'Amtali', 'bn' => 'আমতলী', 'post' => '8710'],
            ]],
            ['name_en' => 'Jhalokati', 'name_bn' => 'ঝালকাঠি', 'division' => 'Barishal', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Jhalokati Sadar', 'bn' => 'ঝালকাঠি সদর', 'post' => '8400'],
                ['en' => 'Rajapur', 'bn' => 'রাজাপুর', 'post' => '8420'],
            ]],

            // Sylhet Division
            ['name_en' => 'Sylhet', 'name_bn' => 'সিলেট', 'division' => 'Sylhet', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Kotwali', 'bn' => 'কোতোয়ালী', 'post' => '3100'],
                ['en' => 'Shah Paran', 'bn' => 'শাহপরাণ', 'post' => '3104'],
                ['en' => 'South Surma', 'bn' => 'দক্ষিণ সুরমা', 'post' => '3100'],
                ['en' => 'Beanibazar', 'bn' => 'বিয়ানীবাজার', 'post' => '3170'],
                ['en' => 'Golapganj', 'bn' => 'গোলাপগঞ্জ', 'post' => '3160'],
                ['en' => 'Osmani Nagar', 'bn' => 'ওসমানী নগর', 'post' => '3123'],
            ]],
            ['name_en' => 'Moulvibazar', 'name_bn' => 'মৌলভীবাজার', 'division' => 'Sylhet', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Moulvibazar Sadar', 'bn' => 'মৌলভীবাজার সদর', 'post' => '3200'],
                ['en' => 'Sreemangal', 'bn' => 'শ্রীমঙ্গল', 'post' => '3210'],
                ['en' => 'Kulaura', 'bn' => 'কুলাউড়া', 'post' => '3230'],
            ]],
            ['name_en' => 'Habiganj', 'name_bn' => 'হবিগঞ্জ', 'division' => 'Sylhet', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Habiganj Sadar', 'bn' => 'হবিগঞ্জ সদর', 'post' => '3300'],
                ['en' => 'Madhabpur', 'bn' => 'মাধবপুর', 'post' => '3330'],
                ['en' => 'Nabiganj', 'bn' => 'নবীগঞ্জ', 'post' => '3310'],
            ]],
            ['name_en' => 'Sunamganj', 'name_bn' => 'সুনামগঞ্জ', 'division' => 'Sylhet', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Sunamganj Sadar', 'bn' => 'সুনামগঞ্জ সদর', 'post' => '3000'],
                ['en' => 'Chhatak', 'bn' => 'ছাতক', 'post' => '3080'],
                ['en' => 'Jagannathpur', 'bn' => 'জগন্নাথপুর', 'post' => '3060'],
            ]],

            // Rangpur Division
            ['name_en' => 'Rangpur', 'name_bn' => 'রংপুর', 'division' => 'Rangpur', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Kotwali', 'bn' => 'কোতোয়ালী', 'post' => '5400'],
                ['en' => 'Tajhat', 'bn' => 'তাজহাট', 'post' => '5402'],
                ['en' => 'Badarganj', 'bn' => 'বদরগঞ্জ', 'post' => '5420'],
                ['en' => 'Pirganj', 'bn' => 'পীরগঞ্জ', 'post' => '5470'],
                ['en' => 'Mithapukur', 'bn' => 'মিঠাপুকুর', 'post' => '5460'],
            ]],
            ['name_en' => 'Dinajpur', 'name_bn' => 'দিনাজপুর', 'division' => 'Rangpur', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Dinajpur Sadar', 'bn' => 'দিনাজপুর সদর', 'post' => '5200'],
                ['en' => 'Birganj', 'bn' => 'বীরগঞ্জ', 'post' => '5220'],
                ['en' => 'Phulbari', 'bn' => 'ফুলবাড়ী', 'post' => '5260'],
            ]],
            ['name_en' => 'Gaibandha', 'name_bn' => 'গাইবান্ধা', 'division' => 'Rangpur', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Gaibandha Sadar', 'bn' => 'গাইবান্ধা সদর', 'post' => '5700'],
                ['en' => 'Gobindaganj', 'bn' => 'গোবিন্দগঞ্জ', 'post' => '5740'],
            ]],
            ['name_en' => 'Kurigram', 'name_bn' => 'কুড়িগ্রাম', 'division' => 'Rangpur', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Kurigram Sadar', 'bn' => 'কুড়িগ্রাম সদর', 'post' => '5600'],
                ['en' => 'Nageshwari', 'bn' => 'নাগেশ্বরী', 'post' => '5660'],
            ]],
            ['name_en' => 'Lalmonirhat', 'name_bn' => 'লালমনিরহাট', 'division' => 'Rangpur', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Lalmonirhat Sadar', 'bn' => 'লালমনিরহাট সদর', 'post' => '5500'],
                ['en' => 'Patgram', 'bn' => 'পাটগ্রাম', 'post' => '5540'],
            ]],
            ['name_en' => 'Nilphamari', 'name_bn' => 'নীলফামারী', 'division' => 'Rangpur', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Nilphamari Sadar', 'bn' => 'নীলফামারী সদর', 'post' => '5300'],
                ['en' => 'Saidpur', 'bn' => 'সৈয়দপুর', 'post' => '5310'],
            ]],
            ['name_en' => 'Panchagarh', 'name_bn' => 'পঞ্চগড়', 'division' => 'Rangpur', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Panchagarh Sadar', 'bn' => 'পঞ্চগড় সদর', 'post' => '5000'],
                ['en' => 'Tetulia', 'bn' => 'তেঁতুলিয়া', 'post' => '5030'],
            ]],
            ['name_en' => 'Thakurgaon', 'name_bn' => 'ঠাকুরগাঁও', 'division' => 'Rangpur', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Thakurgaon Sadar', 'bn' => 'ঠাকুরগাঁও সদর', 'post' => '5100'],
                ['en' => 'Pirganj', 'bn' => 'পীরগঞ্জ', 'post' => '5110'],
            ]],

            // Mymensingh Division
            ['name_en' => 'Mymensingh', 'name_bn' => 'ময়মনসিংহ', 'division' => 'Mymensingh', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Kotwali', 'bn' => 'কোতোয়ালী', 'post' => '2200'],
                ['en' => 'Muktagachha', 'bn' => 'মুক্তাগাছা', 'post' => '2210'],
                ['en' => 'Bhaluka', 'bn' => 'ভালুকা', 'post' => '2240'],
                ['en' => 'Trishal', 'bn' => 'ত্রিশাল', 'post' => '2220'],
            ]],
            ['name_en' => 'Jamalpur', 'name_bn' => 'জামালপুর', 'division' => 'Mymensingh', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Jamalpur Sadar', 'bn' => 'জামালপুর সদর', 'post' => '2000'],
                ['en' => 'Sarishabari', 'bn' => 'সরিষাবাড়ী', 'post' => '2050'],
            ]],
            ['name_en' => 'Netrokona', 'name_bn' => 'নেত্রকোণা', 'division' => 'Mymensingh', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Netrokona Sadar', 'bn' => 'নেত্রকোণা সদর', 'post' => '2400'],
                ['en' => 'Kendua', 'bn' => 'কেন্দুয়া', 'post' => '2480'],
            ]],
            ['name_en' => 'Sherpur', 'name_bn' => 'শেরপুর', 'division' => 'Mymensingh', 'is_inside' => false, 'is_sub' => false, 'thanas' => [
                ['en' => 'Sherpur Sadar', 'bn' => 'শেরপুর সদর', 'post' => '2100'],
                ['en' => 'Nalitabari', 'bn' => 'নালিতাবাড়ী', 'post' => '2110'],
            ]],
        ];

        foreach ($districts as $d) {
            $district = District::create([
                'name_en' => $d['name_en'],
                'name_bn' => $d['name_bn'],
                'division' => $d['division'],
                'is_inside_dhaka' => $d['is_inside'],
                'is_sub_dhaka' => $d['is_sub'],
            ]);

            foreach ($d['thanas'] as $t) {
                Thana::create([
                    'district_id' => $district->id,
                    'name_en' => $t['en'],
                    'name_bn' => $t['bn'],
                    'postcode' => $t['post'],
                ]);
            }
        }
    }
}
