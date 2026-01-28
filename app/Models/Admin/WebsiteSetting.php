<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class WebsiteSetting extends Model
{
    protected $fillable = [
        'site_name',
        'site_title',
        'tagline',

        'logo',
        'favicon',
        'footer_logo',
        'og_image',

        'email',
        'support_email',
        'phone',
        'whatsapp',
        'alternate_phone',

        'address',
        'city',
        'state',
        'country',
        'pincode',
        'map_link',

        'facebook',
        'instagram',
        'linkedin',
        'twitter',
        'youtube',

        'meta_title',
        'meta_description',
        'meta_keywords',

        'google_analytics_id',
        'meta_author',

        'footer_text',
        'copyright',

        'maintenance_mode',
        'enable_registration',
        'whatsapp_number',
        'map_url',
        'telegram',

        'linkedin_company_id',
        'linkedin_share_enable',

        'og_title',
        'og_description',

        'is_active',
        'admission_form_link',

        'google_analytics_code',
        'google_tag_manager_code',
        'facebook_pixel_code',
        'linkedin_insight_code',

        'header_custom_scripts',
        'footer_custom_scripts',
    ];

    /* ----------------------------
     | Hidden (internal fields)
     |---------------------------- */
    protected $hidden = [
        'logo',
        'favicon',
        'footer_logo',
        'og_image',
        
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /* ----------------------------
     | Appended (API-friendly)
     |---------------------------- */
    protected $appends = [
        'logo_url',
        'favicon_url',
        'footer_logo_url',
        'og_image_url',
    ];

    /* ----------------------------
     | Auto-fill created_by / updated_by
     |---------------------------- */
    protected static function booted()
    {
        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_by = Auth::id();
                $model->updated_by = Auth::id();
            }
        });

        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });
    }

    /* ----------------------------
     | Image URL Accessors
     |---------------------------- */

    public function getLogoUrlAttribute()
    {
        return $this->logo
            ? asset('storage/' . $this->logo)
            : null;
    }

    public function getFaviconUrlAttribute()
    {
        return $this->favicon
            ? asset('storage/' . $this->favicon)
            : null;
    }

    public function getFooterLogoUrlAttribute()
    {
        return $this->footer_logo
            ? asset('storage/' . $this->footer_logo)
            : null;
    }

    public function getOgImageUrlAttribute()
    {
        return $this->og_image
            ? asset('storage/' . $this->og_image)
            : null;
    }
}
