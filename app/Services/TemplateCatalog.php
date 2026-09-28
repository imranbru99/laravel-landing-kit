<?php

declare(strict_types=1);

namespace App\Services;

class TemplateCatalog
{
    /**
     * Get all 107 ready-made Bangladesh shop templates definitions.
     *
     * @return array<array>
     */
    public static function getTemplates(): array
    {
        $templates = [];

        $definitions = [
            // 1. Fashion (10)
            ['slug' => 'fashion-panjabi-eid', 'name' => 'ঈদ স্পেশাল লাক্সারি পাঞ্জাবি', 'category' => 'fashion', 'primary' => '#059669', 'accent' => '#f59e0b', 'tags' => ['panjabi', 'eid', 'men', 'festive']],
            ['slug' => 'fashion-saree-jamdani', 'name' => 'ঐতিহ্যবাহী ঢাকাই জামদানি শাড়ি', 'category' => 'fashion', 'primary' => '#be123c', 'accent' => '#fbbf24', 'tags' => ['saree', 'jamdani', 'women', 'traditional']],
            ['slug' => 'fashion-three-piece', 'name' => 'আনস্টিচড প্রিমিয়াম থ্রি-পিস', 'category' => 'fashion', 'primary' => '#7c3aed', 'accent' => '#f43f5e', 'tags' => ['three-piece', 'salwar', 'cotton']],
            ['slug' => 'fashion-kurti-casual', 'name' => 'ডিজাইনার ক্যাজুয়াল কুর্তি', 'category' => 'fashion', 'primary' => '#db2777', 'accent' => '#06b6d4', 'tags' => ['kurti', 'tunic', 'casual']],
            ['slug' => 'fashion-polo-tshirt', 'name' => '১০০% সুতি পিকে পোলো টি-শার্ট', 'category' => 'fashion', 'primary' => '#1e40af', 'accent' => '#f97316', 'tags' => ['polo', 'tshirt', 'men']],
            ['slug' => 'fashion-denim-jeans', 'name' => 'স্ট্রেচেবল স্লিম ফিট ডেনিম জিন্স', 'category' => 'fashion', 'primary' => '#0f172a', 'accent' => '#3b82f6', 'tags' => ['jeans', 'denim', 'pants']],
            ['slug' => 'fashion-kids-eid', 'name' => 'বাচ্চাদের রঙিন উৎসব পোশাক', 'category' => 'fashion', 'primary' => '#ea580c', 'accent' => '#10b981', 'tags' => ['kids', 'baby', 'eid']],
            ['slug' => 'fashion-borka-hijab', 'name' => 'দুবাই চেরি কাপড়ের প্রিমিয়াম আবায়া', 'category' => 'fashion', 'primary' => '#111827', 'accent' => '#d97706', 'tags' => ['abaya', 'borka', 'hijab']],
            ['slug' => 'fashion-winter-jacket', 'name' => 'উইন্টার ওয়াটারপ্রুফ উইন্ডব্রেকার জ্যাকেট', 'category' => 'fashion', 'primary' => '#374151', 'accent' => '#ef4444', 'tags' => ['winter', 'jacket', 'warm']],
            ['slug' => 'fashion-lingerie-modest', 'name' => 'মেটারনিটি ও নার্সিং ইনারওয়্যার', 'category' => 'fashion', 'primary' => '#9d174d', 'accent' => '#f472b6', 'tags' => ['innerwear', 'women', 'maternity']],

            // 2. Footwear & Bags (8)
            ['slug' => 'footwear-leather-sandals', 'name' => 'খাঁটি চামড়ার ক্যাজুয়াল স্যান্ডেল', 'category' => 'footwear', 'primary' => '#78350f', 'accent' => '#d97706', 'tags' => ['sandals', 'leather', 'men']],
            ['slug' => 'footwear-sneakers-sports', 'name' => 'ব্রেথেবল স্পোর্টস রানিং স্নিকার্স', 'category' => 'footwear', 'primary' => '#1e3a8a', 'accent' => '#22c55e', 'tags' => ['sneakers', 'sports', 'shoes']],
            ['slug' => 'footwear-formal-oxford', 'name' => 'এক্সিকিউটিভ প্রিমিয়াম লেদার জুতো', 'category' => 'footwear', 'primary' => '#18181b', 'accent' => '#b45309', 'tags' => ['formal', 'oxford', 'shoes']],
            ['slug' => 'bags-ladies-handbag', 'name' => 'ফ্যাশনেবল লেডিস শোল্ডার হ্যান্ডব্যাগ', 'category' => 'footwear', 'primary' => '#991b1b', 'accent' => '#f59e0b', 'tags' => ['handbag', 'ladies', 'bags']],
            ['slug' => 'bags-travel-backpack', 'name' => 'ওয়াটারপ্রুফ ল্যাপটপ ট্রাভেল ব্যাকপ্যাক', 'category' => 'footwear', 'primary' => '#1f2937', 'accent' => '#0284c7', 'tags' => ['backpack', 'laptop', 'travel']],
            ['slug' => 'bags-leather-wallet', 'name' => 'জেনুইন লেদার স্লিম মানিব্যাগ', 'category' => 'footwear', 'primary' => '#451a03', 'accent' => '#d97706', 'tags' => ['wallet', 'leather', 'men']],
            ['slug' => 'footwear-leather-belt', 'name' => 'অটোমেটিক বাকল লেদার বেল্ট', 'category' => 'footwear', 'primary' => '#27272a', 'accent' => '#eab308', 'tags' => ['belt', 'leather', 'accessories']],
            ['slug' => 'footwear-kids-shoes', 'name' => 'বাচ্চাদের আরামদায়ক লাইটিং জুতো', 'category' => 'footwear', 'primary' => '#0284c7', 'accent' => '#ec4899', 'tags' => ['kids', 'shoes', 'footwear']],

            // 3. Beauty & Personal Care (8)
            ['slug' => 'beauty-kumkumadi-serum', 'name' => 'কুঙ্কুমাদি স্কিন গ্লো সিরাম', 'category' => 'beauty', 'primary' => '#d97706', 'accent' => '#10b981', 'tags' => ['serum', 'glow', 'skincare']],
            ['slug' => 'beauty-hair-growth-oil', 'name' => 'ভেষজ চুল পড়া রোধক তেল', 'category' => 'beauty', 'primary' => '#166534', 'accent' => '#eab308', 'tags' => ['hair-oil', 'herbal', 'care']],
            ['slug' => 'beauty-sunscreen-spf50', 'name' => 'আল্ট্রা লাইটওয়েট এসপিএফ ৫০ সানস্ক্রিন', 'category' => 'beauty', 'primary' => '#0284c7', 'accent' => '#f59e0b', 'tags' => ['sunscreen', 'skincare']],
            ['slug' => 'beauty-organic-facepack', 'name' => 'নিম ও চন্দন অর্গানিক ফেসপ্যাক', 'category' => 'beauty', 'primary' => '#15803d', 'accent' => '#f97316', 'tags' => ['facepack', 'organic', 'acne']],
            ['slug' => 'beauty-perfume-attar', 'name' => 'অ্যালকোহলমুক্ত এরাবিয়ান উদ আতর', 'category' => 'beauty', 'primary' => '#581c87', 'accent' => '#d97706', 'tags' => ['attar', 'perfume', 'fragrance']],
            ['slug' => 'beauty-beard-growth-kit', 'name' => 'পুরুষদের দাড়ি বৃদ্ধি ও গ্রুমিং কিট', 'category' => 'beauty', 'primary' => '#292524', 'accent' => '#f59e0b', 'tags' => ['beard', 'grooming', 'men']],
            ['slug' => 'beauty-handmade-soap', 'name' => 'জাফরান ও মধু হ্যান্ডমেড সাবান', 'category' => 'beauty', 'primary' => '#b45309', 'accent' => '#059669', 'tags' => ['soap', 'handmade', 'bath']],
            ['slug' => 'beauty-lip-care-scrub', 'name' => 'প্রাকৃতিক বিটরুট লিপ ব্রাইটেনিং কম্বো', 'category' => 'beauty', 'primary' => '#be123c', 'accent' => '#fb7185', 'tags' => ['lip-care', 'scrub', 'balm']],

            // 4. Health & Wellness (9)
            ['slug' => 'health-sundarban-honey', 'name' => 'খাঁটি সুন্দরবনের প্রাকৃতিক চাকের মধু', 'category' => 'health', 'primary' => '#b45309', 'accent' => '#15803d', 'tags' => ['honey', 'sundarban', 'organic']],
            ['slug' => 'health-black-seed-oil', 'name' => 'কোল্ড প্রেসড ১০০% খাঁটি কালোজিরা তেল', 'category' => 'health', 'primary' => '#1c1917', 'accent' => '#10b981', 'tags' => ['black-seed', 'kalojira', 'immunity']],
            ['slug' => 'health-medjool-dates', 'name' => 'প্রিমিয়াম কোয়ালিটি মেদজুল খেজুর', 'category' => 'health', 'primary' => '#78350f', 'accent' => '#eab308', 'tags' => ['dates', 'khejur', 'energy']],
            ['slug' => 'health-pure-cow-ghee', 'name' => 'পাবনার খাঁটি দানাদার গাওয়া ঘি', 'category' => 'health', 'primary' => '#ca8a04', 'accent' => '#15803d', 'tags' => ['ghee', 'dairy', 'pure']],
            ['slug' => 'health-moringa-powder', 'name' => 'সুপারফুড সাজনা পাতা গুঁড়ো (Moringa)', 'category' => 'health', 'primary' => '#166534', 'accent' => '#84cc16', 'tags' => ['moringa', 'superfood', 'health']],
            ['slug' => 'health-acupressure-massager', 'name' => 'পেইন রিলিফ ইলেকট্রিক বডি ম্যাসাজার', 'category' => 'health', 'primary' => '#1e3a8a', 'accent' => '#f97316', 'tags' => ['massager', 'pain-relief', 'device']],
            ['slug' => 'health-blood-pressure-monitor', 'name' => 'ডিজিটাল অটোমেটিক বিপি মনিটর', 'category' => 'health', 'primary' => '#0369a1', 'accent' => '#22c55e', 'tags' => ['bp-monitor', 'medical', 'health']],
            ['slug' => 'health-prayer-mat-orthopedic', 'name' => 'অর্থোপেডিক মেমোরি ফোম জায়নামাজ', 'category' => 'health', 'primary' => '#134e4a', 'accent' => '#d97706', 'tags' => ['prayer-mat', 'orthopedic', 'islamic']],
            ['slug' => 'health-chia-seeds-combo', 'name' => 'অর্গানিক চিয়া সিড ও ইসপগুল কম্বো', 'category' => 'health', 'primary' => '#047857', 'accent' => '#f59e0b', 'tags' => ['chia-seed', 'weight-loss', 'diet']],

            // 5. Grocery & Food (9)
            ['slug' => 'grocery-mustard-oil', 'name' => 'ঘানি ভাঙা খাঁটি সরিষার তেল', 'category' => 'grocery', 'primary' => '#a16207', 'accent' => '#15803d', 'tags' => ['mustard-oil', 'cooking', 'pure']],
            ['slug' => 'grocery-chinigura-rice', 'name' => 'সুগন্ধি দিনাজপুরের চিনিগুড়া চাল', 'category' => 'grocery', 'primary' => '#15803d', 'accent' => '#f59e0b', 'tags' => ['rice', 'aromatic', 'polao']],
            ['slug' => 'grocery-rajshahi-mango', 'name' => 'রাজশাহীর কেমিক্যালমুক্ত হিমসাগর আম', 'category' => 'grocery', 'primary' => '#eab308', 'accent' => '#16a34a', 'tags' => ['mango', 'rajshahi', 'fresh']],
            ['slug' => 'grocery-dry-fruits-mix', 'name' => '১০ রকমের প্রিমিয়াম ড্রাই ফ্রুটস মিক্স', 'category' => 'grocery', 'primary' => '#854d0e', 'accent' => '#ea580c', 'tags' => ['dry-fruits', 'nuts', 'healthy']],
            ['slug' => 'grocery-spicy-pickles', 'name' => 'ঝাল-মিষ্টি বোম্বাই মরিচ ও আমের আচার', 'category' => 'grocery', 'primary' => '#991b1b', 'accent' => '#eab308', 'tags' => ['pickle', 'achar', 'spicy']],
            ['slug' => 'grocery-sylhet-tea', 'name' => 'শ্রীমঙ্গলের প্রিমিয়াম ব্ল্যাক টি', 'category' => 'grocery', 'primary' => '#365314', 'accent' => '#d97706', 'tags' => ['tea', 'sylhet', 'beverage']],
            ['slug' => 'grocery-homemade-sweets', 'name' => 'ঐতিহ্যবাহী রসমালাই ও ছানার মিষ্টি', 'category' => 'grocery', 'primary' => '#c2410c', 'accent' => '#10b981', 'tags' => ['sweets', 'dessert', 'traditional']],
            ['slug' => 'grocery-hilsha-fish', 'name' => 'পদ্মার টাটকা রূপালী ইলিশ মাছ', 'category' => 'grocery', 'primary' => '#0369a1', 'accent' => '#f59e0b', 'tags' => ['fish', 'hilsha', 'padma']],
            ['slug' => 'grocery-pure-spices', 'name' => 'খাঁটি হলুদ, মরিচ ও ধনিয়া গুঁড়ার কম্বো', 'category' => 'grocery', 'primary' => '#b91c1c', 'accent' => '#ca8a04', 'tags' => ['spices', 'masala', 'cooking']],

            // 6. Electronics & Gadgets (9)
            ['slug' => 'gadgets-smartwatch-amoled', 'name' => 'অ্যামোলেড ডিসপ্লে কলিং স্মার্টওয়াচ', 'category' => 'electronics', 'primary' => '#0f172a', 'accent' => '#06b6d4', 'tags' => ['smartwatch', 'amoled', 'calling']],
            ['slug' => 'gadgets-anc-earbuds', 'name' => 'অ্যাক্টিভ নয়েজ ক্যান্সেলেশন এয়ারবাডস', 'category' => 'electronics', 'primary' => '#1e1b4b', 'accent' => '#8b5cf6', 'tags' => ['earbuds', 'anc', 'audio']],
            ['slug' => 'gadgets-powerbank-20000mah', 'name' => '২২.৫ ওয়াট ফাস্ট চার্জিং পাওয়ার ব্যাংক', 'category' => 'electronics', 'primary' => '#1e293b', 'accent' => '#10b981', 'tags' => ['powerbank', 'fast-charge', 'mobile']],
            ['slug' => 'gadgets-hair-trimmer', 'name' => 'রিচার্জেবল প্রো হেয়ার ও দাড়ি ট্রিমার', 'category' => 'electronics', 'primary' => '#334155', 'accent' => '#f59e0b', 'tags' => ['trimmer', 'grooming', 'shaving']],
            ['slug' => 'gadgets-cctv-wifi-camera', 'name' => 'স্মার্ট ৩৬০° ওয়াইফাই সিকিউরিটি ক্যামেরা', 'category' => 'electronics', 'primary' => '#0c4a6e', 'accent' => '#10b981', 'tags' => ['cctv', 'wifi-camera', 'security']],
            ['slug' => 'gadgets-smart-led-strip', 'name' => 'মিউজিক সিন্ক আরজিবি স্মার্ট এলইডি স্ট্রিপ', 'category' => 'electronics', 'primary' => '#581c87', 'accent' => '#06b6d4', 'tags' => ['led', 'rgb', 'lighting']],
            ['slug' => 'gadgets-electric-kettle', 'name' => 'স্টেইনলেস স্টিল অটো শাট-অফ কেটলি', 'category' => 'electronics', 'primary' => '#475569', 'accent' => '#ea580c', 'tags' => ['kettle', 'appliances', 'kitchen']],
            ['slug' => 'gadgets-ringlight-tripod', 'name' => 'ইউটিউব ও রিল ভিডিও মেকিং রিং লাইট', 'category' => 'electronics', 'primary' => '#18181b', 'accent' => '#ec4899', 'tags' => ['ringlight', 'tripod', 'creator']],
            ['slug' => 'gadgets-car-charger-fm', 'name' => 'ব্লুটুথ এফএম ট্রান্সমিটার ও কার চার্জার', 'category' => 'electronics', 'primary' => '#1e293b', 'accent' => '#3b82f6', 'tags' => ['car-charger', 'bluetooth', 'auto']],

            // 7. Home & Living (8)
            ['slug' => 'home-cotton-bedsheet', 'name' => '১০০% সুতি কিং সাইজ বেডশিট কম্বো', 'category' => 'home', 'primary' => '#065f46', 'accent' => '#f59e0b', 'tags' => ['bedsheet', 'cotton', 'bedroom']],
            ['slug' => 'home-kitchen-chopper', 'name' => 'ম্যানুয়াল মাল্টিফাংশনাল ফুড চপার', 'category' => 'home', 'primary' => '#15803d', 'accent' => '#f97316', 'tags' => ['chopper', 'kitchen', 'tools']],
            ['slug' => 'home-granite-cookware', 'name' => 'নন-স্টিক গ্রানাইট মার্বেল কুকওয়্যার সেট', 'category' => 'home', 'primary' => '#374151', 'accent' => '#dc2626', 'tags' => ['cookware', 'non-stick', 'pan']],
            ['slug' => 'home-wardrobe-organizer', 'name' => 'ফোল্ডেবল ক্লথস স্টোরেজ বক্স সেট', 'category' => 'home', 'primary' => '#475569', 'accent' => '#0284c7', 'tags' => ['organizer', 'wardrobe', 'storage']],
            ['slug' => 'home-microfiber-mop', 'name' => '৩৬০° রোটেটিং স্পিন মপ ও বাকেট', 'category' => 'home', 'primary' => '#0369a1', 'accent' => '#10b981', 'tags' => ['mop', 'cleaning', 'household']],
            ['slug' => 'home-gardening-tools', 'name' => 'ছাদ ও বারান্দা বাগান হ্যান্ড টুলস সেট', 'category' => 'home', 'primary' => '#166534', 'accent' => '#84cc16', 'tags' => ['gardening', 'tools', 'plants']],
            ['slug' => 'home-aromatherapy-diffuser', 'name' => 'এসেনশিয়াল অয়েল অ্যারোমা হিউমিডিফায়ার', 'category' => 'home', 'primary' => '#581c87', 'accent' => '#f472b6', 'tags' => ['diffuser', 'humidifier', 'aroma']],
            ['slug' => 'home-orthopedic-pillow', 'name' => 'স্লিপ ওয়েল অর্থোপেডিক নেক পিলো', 'category' => 'home', 'primary' => '#1e3a8a', 'accent' => '#06b6d4', 'tags' => ['pillow', 'orthopedic', 'sleep']],

            // 8. Kids & Baby (8)
            ['slug' => 'kids-educational-tablet', 'name' => 'বাচ্চাদের বাংলা লার্নিং স্মার্ট প্যাড', 'category' => 'kids', 'primary' => '#ea580c', 'accent' => '#0284c7', 'tags' => ['tablet', 'learning', 'kids']],
            ['slug' => 'kids-montessori-wooden-toy', 'name' => 'মন্টেসরি কাঠের তৈরি ইন্টেলিজেন্ট পাজল', 'category' => 'kids', 'primary' => '#b45309', 'accent' => '#10b981', 'tags' => ['wooden-toy', 'puzzle', 'montessori']],
            ['slug' => 'kids-soft-feeding-set', 'name' => 'বিপিএ-মুক্ত সিলিকন বেবি ফিডিং সেট', 'category' => 'kids', 'primary' => '#0284c7', 'accent' => '#f43f5e', 'tags' => ['baby', 'feeding', 'silicone']],
            ['slug' => 'kids-school-backpack', 'name' => 'অর্থোপেডিক ওয়াটারপ্রুফ স্কুল ব্যাগ', 'category' => 'kids', 'primary' => '#1d4ed8', 'accent' => '#eab308', 'tags' => ['school-bag', 'kids', 'backpack']],
            ['slug' => 'kids-magic-water-book', 'name' => 'রঙ ছাড়া রি-ইউজেবল ম্যাজিক ওয়াটার বুক', 'category' => 'kids', 'primary' => '#7c3aed', 'accent' => '#ec4899', 'tags' => ['magic-book', 'drawing', 'kids']],
            ['slug' => 'kids-baby-carrier', 'name' => '৪-ইন-১ এরগোনোমিক ব্রেথেবল বেবি ক্যারিয়ার', 'category' => 'kids', 'primary' => '#334155', 'accent' => '#06b6d4', 'tags' => ['carrier', 'baby', 'travel']],
            ['slug' => 'kids-remote-control-car', 'name' => 'রিচার্জেবল ৪x৪ মনস্টার আরসি কার', 'category' => 'kids', 'primary' => '#dc2626', 'accent' => '#eab308', 'tags' => ['rc-car', 'toy', 'remote']],
            ['slug' => 'kids-diaper-bag', 'name' => 'মাল্টি-পকেট ওয়াটারপ্রুফ ডায়াপার ব্যাকপ্যাক', 'category' => 'kids', 'primary' => '#475569', 'accent' => '#10b981', 'tags' => ['diaper-bag', 'mother', 'baby']],

            // 9. Books & Education (8)
            ['slug' => 'books-bestseller-novel', 'name' => 'বেস্টসেলার বাংলা উপন্যাস ও সাহিত্য', 'category' => 'books', 'primary' => '#1c1917', 'accent' => '#d97706', 'tags' => ['novel', 'literature', 'bangla']],
            ['slug' => 'books-self-development', 'name' => 'আত্মউন্নয়ন ও পার্সোনাল ডেভেলপমেন্ট বুক সেট', 'category' => 'books', 'primary' => '#1e3a8a', 'accent' => '#10b981', 'tags' => ['self-help', 'success', 'books']],
            ['slug' => 'books-islamic-lifestyle', 'name' => 'আদর্শ মুসলিম পরিবার ও সুন্নতি জীবনযাপন', 'category' => 'books', 'primary' => '#14532d', 'accent' => '#eab308', 'tags' => ['islamic', 'sunnah', 'family']],
            ['slug' => 'books-ielts-mastery', 'name' => 'আইইএলটিএস ব্যান্ড ৮.০ কমপ্লিট গাইড', 'category' => 'books', 'primary' => '#b91c1c', 'accent' => '#0284c7', 'tags' => ['ielts', 'english', 'study']],
            ['slug' => 'books-parenting-guide', 'name' => 'সন্তান প্রতিপালন ও সফল প্যারেন্টিং', 'category' => 'books', 'primary' => '#4338ca', 'accent' => '#f43f5e', 'tags' => ['parenting', 'child', 'guide']],
            ['slug' => 'books-freelancing-course', 'name' => 'ঘরে বসে ফ্রিল্যান্সিং ক্যারিয়ার ব্লুপ্রিন্ট', 'category' => 'books', 'primary' => '#0f172a', 'accent' => '#06b6d4', 'tags' => ['freelancing', 'career', 'digital']],
            ['slug' => 'books-holy-quran-tafseer', 'name' => 'সহজ বাংলা অনুবাদ ও তাফসিরুল কুরআন', 'category' => 'books', 'primary' => '#064e3b', 'accent' => '#d97706', 'tags' => ['quran', 'tafseer', 'islamic']],
            ['slug' => 'books-handwriting-kit', 'name' => 'শিশুদের সুন্দর হাতের লেখা প্র্যাকটিস সেট', 'category' => 'books', 'primary' => '#c2410c', 'accent' => '#22c55e', 'tags' => ['handwriting', 'learning', 'kids']],

            // 10. Automotive (8)
            ['slug' => 'auto-dot-certified-helmet', 'name' => 'ডট সার্টিফাইড ফুল ফেস বাইকার হেলমেট', 'category' => 'automotive', 'primary' => '#09090b', 'accent' => '#ef4444', 'tags' => ['helmet', 'bike', 'safety']],
            ['slug' => 'auto-riding-gloves', 'name' => 'ব্রিদেবল টাচস্ক্রিন বাইক রাইডিং গ্লাভস', 'category' => 'automotive', 'primary' => '#18181b', 'accent' => '#f97316', 'tags' => ['gloves', 'rider', 'motorcycle']],
            ['slug' => 'auto-anti-theft-alarm', 'name' => 'স্মার্ট জিপিএস ট্র্যাকার ও অ্যান্টি-থেফ্ট লক', 'category' => 'automotive', 'primary' => '#1e293b', 'accent' => '#10b981', 'tags' => ['gps', 'security', 'anti-theft']],
            ['slug' => 'auto-car-dashcam', 'name' => 'ডুয়েল লেন্স নাইট ভিশন কার ড্যাশ ক্যাম', 'category' => 'automotive', 'primary' => '#0f172a', 'accent' => '#0284c7', 'tags' => ['dashcam', 'camera', 'car']],
            ['slug' => 'auto-car-vacuum-cleaner', 'name' => 'হাই পাওয়ার রিচার্জেবল কার ভ্যাকুয়াম', 'category' => 'automotive', 'primary' => '#334155', 'accent' => '#eab308', 'tags' => ['vacuum', 'cleaning', 'car']],
            ['slug' => 'auto-tyre-inflator', 'name' => 'ডিজিটাল পোর্টেবল কার ও বাইক টায়ার ইনফ্লেটর', 'category' => 'automotive', 'primary' => '#27272a', 'accent' => '#22c55e', 'tags' => ['inflator', 'pump', 'auto']],
            ['slug' => 'auto-waterproof-cover', 'name' => 'অল-ওয়েদার হেভি ডিউটি বাইক কভার', 'category' => 'automotive', 'primary' => '#374151', 'accent' => '#3b82f6', 'tags' => ['cover', 'waterproof', 'bike']],
            ['slug' => 'auto-seat-cushion', 'name' => 'মেমোরি ফোম কার সিট ব্যাক সাপোর্ট কুশন', 'category' => 'automotive', 'primary' => '#1e1b4b', 'accent' => '#06b6d4', 'tags' => ['seat-cushion', 'comfort', 'car']],

            // 11. Gifts & Occasions (8)
            ['slug' => 'gifts-eid-special-box', 'name' => 'ঈদ মোবারক প্রিমিয়াম গিফট হ্যাম্পার', 'category' => 'gifts', 'primary' => '#047857', 'accent' => '#f59e0b', 'tags' => ['eid', 'gift', 'hamper']],
            ['slug' => 'gifts-couples-combo', 'name' => 'কাপল স্পেশাল ঘড়ি ও পারফিউম বক্স', 'category' => 'gifts', 'primary' => '#831843', 'accent' => '#f472b6', 'tags' => ['couple', 'watch', 'combo']],
            ['slug' => 'gifts-wedding-package', 'name' => 'ব্রাইডাল স্পেশাল ওয়েডিং গিফট সেট', 'category' => 'gifts', 'primary' => '#991b1b', 'accent' => '#eab308', 'tags' => ['wedding', 'bridal', 'gift']],
            ['slug' => 'gifts-personalized-wallet', 'name' => 'নাম খোদাই করা কাস্টমাইজড লেদার ওয়ালেট', 'category' => 'gifts', 'primary' => '#451a03', 'accent' => '#d97706', 'tags' => ['custom', 'name-engraved', 'wallet']],
            ['slug' => 'gifts-ramadan-iftar', 'name' => 'রামাদান বরকতময় খেজুর ও আতর গিফট বক্স', 'category' => 'gifts', 'primary' => '#14532d', 'accent' => '#ca8a04', 'tags' => ['ramadan', 'iftar', 'islamic']],
            ['slug' => 'gifts-pohela-boishakh', 'name' => 'পহেলা বৈশাখ লাল-সাদা কাপল উৎসব কম্বো', 'category' => 'gifts', 'primary' => '#b91c1c', 'accent' => '#ffffff', 'tags' => ['boishakh', 'bengali', 'festive']],
            ['slug' => 'gifts-anniversary-hamper', 'name' => 'বিবাহ বার্ষিকী স্পেশাল সারপ্রাইজ হ্যাম্পার', 'category' => 'gifts', 'primary' => '#701a75', 'accent' => '#f472b6', 'tags' => ['anniversary', 'love', 'hamper']],
            ['slug' => 'gifts-birthday-surprise', 'name' => 'বার্থডে সেলিব্রেশন উইশ বক্স ও গিফট কম্বো', 'category' => 'gifts', 'primary' => '#4338ca', 'accent' => '#f59e0b', 'tags' => ['birthday', 'celebration', 'gift']],

            // 12. Handicraft & Local (8)
            ['slug' => 'craft-nakshi-kantha', 'name' => 'ঐতিহ্যবাহী ময়মনসিংহের সুতি নকশিকাঁথা', 'category' => 'craft', 'primary' => '#9a3412', 'accent' => '#ca8a04', 'tags' => ['nakshi-kantha', 'handicraft', 'heritage']],
            ['slug' => 'craft-clay-pottery', 'name' => 'মাটির তৈরি শৌখিন হস্তশিল্প ও ফুলদানি', 'category' => 'craft', 'primary' => '#78350f', 'accent' => '#15803d', 'tags' => ['pottery', 'clay', 'decor']],
            ['slug' => 'craft-jute-rug-carpet', 'name' => 'সোনালী আঁশের ইকো-ফ্রেন্ডলি পাটের কার্পেট', 'category' => 'craft', 'primary' => '#854d0e', 'accent' => '#166534', 'tags' => ['jute', 'carpet', 'eco-friendly']],
            ['slug' => 'craft-leather-satchel', 'name' => 'হাতে তৈরি হাজারীবাগের লেদার মেসেঞ্জার ব্যাগ', 'category' => 'craft', 'primary' => '#431407', 'accent' => '#d97706', 'tags' => ['leather', 'handcrafted', 'bag']],
            ['slug' => 'craft-bamboo-lamp', 'name' => 'বাঁশ ও বেতের তৈরি নান্দনিক ল্যাম্প শেড', 'category' => 'craft', 'primary' => '#713f12', 'accent' => '#f59e0b', 'tags' => ['bamboo', 'lamp', 'homedecor']],
            ['slug' => 'craft-brass-decor', 'name' => 'ধামরাইয়ের কাঁসা ও পিতলের ঐতিহ্যবাহী শো-পিস', 'category' => 'craft', 'primary' => '#a16207', 'accent' => '#ca8a04', 'tags' => ['brass', 'kasha', 'dharmrai']],
            ['slug' => 'craft-block-print-bedsheet', 'name' => 'বগুড়ার সুতি হ্যান্ড ব্লক প্রিন্ট চাদর', 'category' => 'craft', 'primary' => '#166534', 'accent' => '#ea580c', 'tags' => ['block-print', 'bedsheet', 'cotton']],
            ['slug' => 'craft-handloom-scarf', 'name' => 'তাঁতের বোনা প্রিমিয়াম কটন ওড়না ও স্কার্ফ', 'category' => 'craft', 'primary' => '#0c4a6e', 'accent' => '#f43f5e', 'tags' => ['handloom', 'scarf', 'cotton']],

            // 13. Universal Templates (5)
            ['slug' => 'universal-flash-deal', 'name' => 'ইউনিভার্সাল ফ্ল্যাশ ডিল ও ডিসকাউন্ট ড্রাইভ', 'category' => 'universal', 'primary' => '#dc2626', 'accent' => '#f59e0b', 'tags' => ['flash-deal', 'discount', 'high-converting']],
            ['slug' => 'universal-single-product', 'name' => 'ওয়ান-প্রোডাক্ট মিনিমালিস্ট হাই-কনভার্সন পেজ', 'category' => 'universal', 'primary' => '#059669', 'accent' => '#d97706', 'tags' => ['one-product', 'minimal', 'clean']],
            ['slug' => 'universal-video-first', 'name' => 'ভিডিও ফার্স্ট ডিরেক্ট সেলস ল্যান্ডিং পেজ', 'category' => 'universal', 'primary' => '#1d4ed8', 'accent' => '#f97316', 'tags' => ['video-first', 'demo', 'sales']],
            ['slug' => 'universal-lead-generation', 'name' => 'প্রি-অর্ডার ও কোয়ালিটি লিড কালেকশন ফানেল', 'category' => 'universal', 'primary' => '#4338ca', 'accent' => '#10b981', 'tags' => ['pre-order', 'leads', 'exclusive']],
            ['slug' => 'universal-urgent-clearance', 'name' => 'স্টক ক্লিয়ারেন্স বাম্পার ডিসকাউন্ট অফার', 'category' => 'universal', 'primary' => '#991b1b', 'accent' => '#fbbf24', 'tags' => ['clearance', 'stock-out', 'urgent']],
        ];

        foreach ($definitions as $def) {
            $templates[] = [
                'slug' => $def['slug'],
                'name' => $def['name'],
                'category' => $def['category'],
                'description' => "সম্পূর্ণ প্রস্তুত {$def['name']} ল্যান্ডিং পেজ টেমপ্লেট। ক্যাশ অন ডেলিভারি, কাস্টমার রিভিউ ও ডিসকাউন্ট অফার সহ সাজানো।",
                'tags' => $def['tags'],
                'palette' => [
                    'primary_color' => $def['primary'],
                    'accent_color' => $def['accent'],
                    'font_family' => 'Hind Siliguri',
                    'radius' => 'rounded-2xl',
                ],
                'sections' => [
                    [
                        'section_type' => 'hero_image_right',
                        'position' => 0,
                        'content' => [
                            'badge' => 'সীমিত সময়ের অফার',
                            'title' => "১০০% অরিজিনাল ও প্রিমিয়াম {$def['name']}",
                            'subtitle' => 'সারা বাংলাদেশে দ্রুততম হোম ডেলিভারি এবং পণ্য হাতে পেয়ে মূল্য পরিশোধের সুবিধা।',
                            'cta_text' => 'এখনই অর্ডার করুন',
                        ],
                    ],
                    [
                        'section_type' => 'features_grid_3col',
                        'position' => 1,
                        'content' => [
                            'title' => 'আমাদের পণ্যটি কেন সবার চেয়ে সেরা?',
                            'items' => [
                                ['title' => '১০০% অরিজিনাল কোয়ালিটি', 'desc' => 'মানের ব্যাপারে কোনো আপস নেই।'],
                                ['title' => 'দ্রুততম হোম ডেলিভারি', 'desc' => '২৪ থেকে ৭২ ঘণ্টার মধ্যে সারাদেশে ডেলিভারি।'],
                                ['title' => 'ক্যাশ অন ডেলিভারি', 'desc' => 'পণ্য হাতে পেয়ে চেক করে মূল্য পরিশোধের সুবিধা।'],
                            ],
                        ],
                    ],
                    [
                        'section_type' => 'proof_testimonial_cards',
                        'position' => 2,
                        'content' => [
                            'title' => 'গ্রাহকরা আমাদের সম্পর্কে যা বলছেন',
                        ],
                    ],
                    [
                        'section_type' => 'guarantee_money_back',
                        'position' => 3,
                        'content' => [
                            'title' => '১০০% মানিব্যাক গ্যারান্টি ও সহজ রিটার্ন',
                        ],
                    ],
                    [
                        'section_type' => 'order_form_classic',
                        'position' => 4,
                        'content' => [
                            'title' => 'অর্ডার করতে আপনার সঠিক তথ্য দিন',
                            'subtitle' => 'ক্যাশ অন ডেলিভারিতে পণ্য পৌঁছাবে আপনার দরজায়।',
                        ],
                    ],
                    [
                        'section_type' => 'footer_minimal',
                        'position' => 5,
                        'content' => [
                            'copyright' => 'সর্বস্বত্ব সংরক্ষিত।',
                        ],
                    ],
                ],
            ];
        }

        return $templates;
    }
}
