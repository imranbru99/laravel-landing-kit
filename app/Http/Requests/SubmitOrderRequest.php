<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Blocklist;
use Illuminate\Foundation\Http\FormRequest;

class SubmitOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'phone' => ['required', 'string', 'max:25'],
            'name' => ['required', 'string', 'max:150'],
            'address' => ['required', 'string', 'max:500'],
            'district_id' => ['required', 'integer', 'exists:bd_districts,id'],
            'thana_id' => ['nullable', 'integer', 'exists:bd_thanas,id'],
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'product_variant_id' => ['nullable', 'integer', 'exists:product_variants,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:100'],
            'delivery_zone_id' => ['nullable', 'integer', 'exists:delivery_zones,id'],
            'payment_method' => ['nullable', 'string', 'in:cod,bkash,nagad,rocket'],
            'payment_trx_id' => ['nullable', 'string', 'max:64'],
            'payment_sender_number' => ['nullable', 'string', 'max:25'],
            'customer_notes' => ['nullable', 'string', 'max:500'],
            'idempotency_key' => ['nullable', 'string', 'max:64'],
            
            // Honeypot fields
            '_hp_name' => ['nullable', 'string', 'max:100'],
            '_hp_time' => ['nullable', 'integer'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Honeypot check
            if (!empty($this->input('_hp_name'))) {
                $validator->errors()->add('phone', 'Spam detection triggered.');
                return;
            }

            if ($this->has('_hp_time')) {
                $submissionTime = time() - (int) $this->input('_hp_time');
                if ($submissionTime < 1) { // Submitted suspiciously fast (<1 sec)
                    $validator->errors()->add('phone', 'Please take a moment before submitting.');
                    return;
                }
            }

            // BD phone validation
            $phone = (string) $this->input('phone');
            if (!llk_is_valid_bd_phone($phone)) {
                $validator->errors()->add('phone', 'দয়া করে একটি সঠিক ১১ ডিজিটের বাংলাদেশী মোবাইল নম্বর দিন (যেমন: 017XXXXXXXX)।');
                return;
            }

            // Blocklist check
            $ip = $this->ip();
            if (Blocklist::isBlocked($phone, $ip)) {
                $validator->errors()->add('phone', 'এই নম্বর বা আইপি থেকে অর্ডার সাময়িকভাবে স্থগিত আছে। বিস্তারিত জানতে সাপোর্টে যোগাযোগ করুন।');
            }
        });
    }

    public function messages(): array
    {
        return [
            'name.required' => 'আপনার নাম লিখুন।',
            'phone.required' => 'আপনার মোবাইল নম্বর লিখুন।',
            'address.required' => 'আপনার সম্পূর্ণ ডেলিভারি ঠিকানা লিখুন।',
            'district_id.required' => 'আপনার জেলা নির্বাচন করুন।',
            'district_id.exists' => 'নির্বাচিত জেলা সঠিক নয়।',
            'quantity.required' => 'পণ্যের পরিমাণ উল্লেখ করুন।',
            'quantity.min' => 'পণ্যের পরিমাণ কমপক্ষে ১ হতে হবে।',
        ];
    }
}
