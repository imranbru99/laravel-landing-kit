<?php

declare(strict_types=1);

namespace App\Enums;

enum OrderStatus: string
{
    case Incomplete = 'incomplete';
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case OnHold = 'on_hold';
    case Processing = 'processing';
    case ReadyToShip = 'ready_to_ship';
    case Shipped = 'shipped';
    case InTransit = 'in_transit';
    case OutForDelivery = 'out_for_delivery';
    case Delivered = 'delivered';
    case PartiallyDelivered = 'partially_delivered';
    case PaymentPending = 'payment_pending';
    case Paid = 'paid';
    case FailedDelivery = 'failed_delivery';
    case Rescheduled = 'rescheduled';
    case Cancelled = 'cancelled';
    case Returned = 'returned';
    case ReturnReceived = 'return_received';
    case Refunded = 'refunded';
    case PartiallyRefunded = 'partially_refunded';
    case Exchange = 'exchange';
    case FakeSpam = 'fake_spam';

    public function labelEn(): string
    {
        return match ($this) {
            self::Incomplete => 'Incomplete (Lead)',
            self::Pending => 'Pending',
            self::Confirmed => 'Confirmed',
            self::OnHold => 'On Hold',
            self::Processing => 'Processing',
            self::ReadyToShip => 'Ready to Ship',
            self::Shipped => 'Shipped / In Courier',
            self::InTransit => 'In Transit',
            self::OutForDelivery => 'Out for Delivery',
            self::Delivered => 'Delivered',
            self::PartiallyDelivered => 'Partially Delivered',
            self::PaymentPending => 'Payment Pending',
            self::Paid => 'Paid',
            self::FailedDelivery => 'Failed Delivery',
            self::Rescheduled => 'Rescheduled',
            self::Cancelled => 'Cancelled',
            self::Returned => 'Returned',
            self::ReturnReceived => 'Return Received',
            self::Refunded => 'Refunded',
            self::PartiallyRefunded => 'Partially Refunded',
            self::Exchange => 'Exchange',
            self::FakeSpam => 'Fake / Spam',
        };
    }

    public function labelBn(): string
    {
        return match ($this) {
            self::Incomplete => 'অসম্পূর্ণ (লিড)',
            self::Pending => 'অপেক্ষমান (পেন্ডিং)',
            self::Confirmed => 'নিশ্চিত করা হয়েছে',
            self::OnHold => 'স্থগিত (অন হোল্ড)',
            self::Processing => 'প্রক্রিয়াধীন',
            self::ReadyToShip => 'পাঠানোর জন্য প্রস্তুত',
            self::Shipped => 'কুরিয়ারে পাঠানো হয়েছে',
            self::InTransit => 'পথে রয়েছে (ইন ট্রানজিট)',
            self::OutForDelivery => 'ডেলিভারির জন্য বের হয়েছে',
            self::Delivered => 'ডেলিভারি সম্পন্ন',
            self::PartiallyDelivered => 'আংশিক ডেলিভারি',
            self::PaymentPending => 'পেমেন্ট বাকি',
            self::Paid => 'পরিশোধিত',
            self::FailedDelivery => 'ডেলিভারি ব্যর্থ',
            self::Rescheduled => 'পুনঃনির্ধারিত',
            self::Cancelled => 'বাতিল',
            self::Returned => 'ফেরত এসেছে (রিটার্ন)',
            self::ReturnReceived => 'রিটার্ন গ্রহণ করা হয়েছে',
            self::Refunded => 'রিফান্ড সম্পন্ন',
            self::PartiallyRefunded => 'আংশিক রিফান্ড',
            self::Exchange => 'এক্সচেঞ্জ / পরিবর্তন',
            self::FakeSpam => 'ফেক / স্প্যাম',
        };
    }

    public function label(): string
    {
        $lang = (string) setting('default_language', 'bn');

        return $lang === 'bn' ? $this->labelBn() : $this->labelEn();
    }

    public function color(): string
    {
        return match ($this) {
            self::Pending, self::PaymentPending => 'warning',
            self::Confirmed, self::Processing => 'info',
            self::ReadyToShip, self::Shipped, self::InTransit, self::OutForDelivery => 'primary',
            self::Delivered, self::Paid => 'success',
            self::Cancelled, self::FailedDelivery, self::FakeSpam => 'danger',
            self::Returned, self::ReturnReceived, self::Refunded, self::PartiallyRefunded => 'rose',
            self::OnHold, self::Rescheduled, self::PartiallyDelivered, self::Exchange => 'amber',
            self::Incomplete => 'gray',
        };
    }

    public function countsAsRevenue(): bool
    {
        return in_array($this, [self::Delivered, self::Paid], true);
    }

    public function decrementsStock(): bool
    {
        return in_array($this, [
            self::Confirmed, self::Processing, self::ReadyToShip, self::Shipped, self::InTransit, self::OutForDelivery, self::Delivered, self::Paid,
        ], true);
    }

    public function restoresStock(): bool
    {
        return in_array($this, [
            self::Cancelled, self::Returned, self::ReturnReceived, self::Refunded, self::FakeSpam,
        ], true);
    }

    public function allowedTransitions(): array
    {
        return match ($this) {
            self::Incomplete => [self::Pending, self::Cancelled, self::FakeSpam],
            self::Pending => [self::Confirmed, self::OnHold, self::Cancelled, self::FakeSpam],
            self::Confirmed => [self::Processing, self::ReadyToShip, self::OnHold, self::Cancelled],
            self::OnHold => [self::Confirmed, self::Cancelled, self::FakeSpam],
            self::Processing => [self::ReadyToShip, self::OnHold, self::Cancelled],
            self::ReadyToShip => [self::Shipped, self::Cancelled],
            self::Shipped => [self::InTransit, self::OutForDelivery, self::Delivered, self::FailedDelivery, self::Returned],
            self::InTransit => [self::OutForDelivery, self::Delivered, self::FailedDelivery, self::Returned],
            self::OutForDelivery => [self::Delivered, self::FailedDelivery, self::Rescheduled, self::Returned],
            self::FailedDelivery => [self::Rescheduled, self::Returned, self::Cancelled],
            self::Rescheduled => [self::OutForDelivery, self::Delivered, self::FailedDelivery, self::Returned],
            self::Delivered => [self::Paid, self::PartiallyDelivered, self::Returned, self::Refunded, self::Exchange],
            self::PartiallyDelivered => [self::Paid, self::Returned, self::Refunded],
            self::PaymentPending => [self::Paid, self::Cancelled],
            self::Paid => [self::Refunded, self::PartiallyRefunded, self::Exchange],
            self::Returned => [self::ReturnReceived, self::Refunded, self::Exchange],
            self::ReturnReceived => [self::Refunded, self::Exchange],
            self::Cancelled, self::Refunded, self::PartiallyRefunded, self::Exchange, self::FakeSpam => [],
        };
    }
}
