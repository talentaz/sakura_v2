<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PageSetting extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'page_setting';

    protected $fillable = [
        'parent_id',
        'title',
        'description',
        'keywords',
        'page_type',
        'slug',
        'url',
        'position',
        'on_menu',
        'on_menu_order',
        'on_footer',
        'footer_section',
        'editor_content',
        'plain_content',
        'is_active'
    ];

    protected $casts = [
        'on_menu' => 'boolean',
        'on_footer' => 'boolean',
        'is_active' => 'boolean',
        'position' => 'integer',
        'on_menu_order' => 'integer',
        'parent_id' => 'integer'
    ];

    protected $attributes = [
        'page_type' => 'inner_page',
        'position' => 0,
        'on_menu' => true,
        'on_menu_order' => 0,
        'on_footer' => false,
        'is_active' => true
    ];

    // Relationships
    public function parent()
    {
        return $this->belongsTo(PageSetting::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(PageSetting::class, 'parent_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOnMenu($query)
    {
        return $query->where('on_menu', true);
    }

    public function scopeOnFooter($query)
    {
        return $query->where('on_footer', true);
    }

    public function scopeByPageType($query, $type)
    {
        return $query->where('page_type', $type);
    }

    // Accessors
    public function getFullUrlAttribute()
    {
        return $this->url ?: url($this->slug);
    }
}
