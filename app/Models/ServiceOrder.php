<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceOrder extends Model
{
    protected $fillable = [
        'tracking_id',
        'user_id',
        'service_id',
        'service_title',
        'customer_name',
        'customer_phone',
        'customer_email',
        'customer_address',
        'application_data',
        'customer_notes',
        'service_fee',
        'govt_fee',
        'total_fee',
        'payment_method',
        'payment_number',
        'transaction_id',
        'payment_status',
        'order_status',
        'admin_notes',
        'completed_file_path',
        'completed_at',
    ];

    protected $casts = [
        'application_data' => 'array',
        'service_fee' => 'decimal:2',
        'govt_fee' => 'decimal:2',
        'total_fee' => 'decimal:2',
        'completed_at' => 'datetime',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(OrderAttachment::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->order_status) {
            'pending' => 'অপেক্ষমাণ (Pending)',
            'processing' => 'প্রক্রিয়াধীন (Processing)',
            'completed' => 'সম্পন্ন (Completed)',
            'delivered' => 'ডেলিভারি সম্পন্ন (Delivered)',
            'cancelled' => 'বাতিল (Cancelled)',
            default => ucfirst($this->order_status),
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->order_status) {
            'pending' => 'bg-warning text-dark',
            'processing' => 'bg-primary text-white',
            'completed' => 'bg-success text-white',
            'delivered' => 'bg-info text-dark',
            'cancelled' => 'bg-danger text-white',
            default => 'bg-secondary text-white',
        };
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return match ($this->payment_status) {
            'verified' => 'যাচাইকৃত (Verified)',
            'rejected' => 'বাতিল (Rejected)',
            default => 'অপেক্ষমাণ (Pending)',
        };
    }

    public function getPaymentStatusBadgeAttribute(): string
    {
        return match ($this->payment_status) {
            'verified' => 'bg-success text-white',
            'rejected' => 'bg-danger text-white',
            default => 'bg-warning text-dark',
        };
    }

    public function getPaymentMethodNameAttribute(): string
    {
        return match (strtolower($this->payment_method)) {
            'bkash' => 'বিকাশ (bKash)',
            'nagad' => 'নগদ (Nagad)',
            'rocket' => 'রকেট (Rocket)',
            'shop_cash' => 'দোকানে নগদ প্রদান (Cash at Shop)',
            default => ucfirst($this->payment_method),
        };
    }

    public function getApplicantPhotoUrlAttribute(): ?string
    {
        if (!empty($this->application_data['applicant_photo'])) {
            return asset('storage/' . $this->application_data['applicant_photo']);
        }

        if ($this->relationLoaded('attachments')) {
            $photoAttachment = $this->attachments->first(function ($att) {
                return $att->file_type === 'photo' || in_array(strtolower(pathinfo($att->file_name, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp']);
            });

            if ($photoAttachment) {
                return asset('storage/' . $photoAttachment->file_path);
            }
        }

        return null;
    }

    public static function getFieldLabel(string $key): string
    {
        $labels = [
            'applicant_name_bn' => 'আবেদনকারীর নাম (বাংলা)',
            'applicant_name_en' => 'আবেদনকারীর নাম (ইংরেজি)',
            'applicant_name' => 'আবেদনকারীর নাম',
            'father_name_bn' => 'পিতার নাম (বাংলা)',
            'father_name_en' => 'পিতার নাম (ইংরেজি)',
            'father_name' => 'পিতার নাম',
            'mother_name_bn' => 'মাতার নাম (বাংলা)',
            'mother_name_en' => 'মাতার নাম (ইংরেজি)',
            'mother_name' => 'মাতার নাম',
            'spouse_name_bn' => 'স্বামী/স্ত্রীর নাম (বাংলা)',
            'spouse_name_en' => 'স্বামী/স্ত্রীর নাম (ইংরেজি)',
            'applicant_photo' => 'আবেদনকারীর ছবি',
            'dob' => 'জন্ম তারিখ',
            'nid_birth_reg_no' => 'জাতীয় পরিচয়পত্র / জন্মনিবন্ধন নম্বর',
            'job_post_or_circular' => 'পদের নাম / সার্কুলার / প্রতিষ্ঠান',
            'exam_roll_reg' => 'শিক্ষাগত যোগ্যতা / রোল ও রেজিস্ট্রেশন',
            'choice_list' => 'পছন্দক্রম বা বিশেষ নির্দেশনা',
            'extra_details' => 'অতিরিক্ত বিবরণ',
        ];

        return $labels[$key] ?? ucfirst(str_replace('_', ' ', $key));
    }
}

