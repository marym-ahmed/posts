<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Tag extends Model
{
    use HasFactory;
    use Translatable;

    public $translatedAttributes = ['title'];
    protected $fillable = ['title'];

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    public static function firstOrCreateTag(array $attributes)
    {
        $locale = app()->getLocale();
        $title = $attributes['title'][$locale] ?? null;

        if ($title) {
            // ابحث عن العلامة بناءً على الترجمة الحالية
            $tag = static::whereTranslation('title', $title)->first();

            if (!$tag) {
                // إنشاء العلامة بدون الترجمة أولاً
                $tag = new static;
                $tag->save();

                // إضافة الترجمة بشكل منفصل
                foreach ($attributes['title'] as $locale => $localeTitle) {
                    // تأكد من أن $localeTitle هو نص وليس مصفوفة
                    if (is_array($localeTitle)) {
                        $localeTitle = implode(',', $localeTitle);
                    }
                    $tag->translateOrNew($locale)->title = $localeTitle;
                }
                $tag->save();
            }

            return $tag;
        }

        throw new \Exception('Title for the current locale is missing.');
    }
    public function getTagIdByTitle($title, $locale)
    {
        return Tag::whereRaw('JSON_EXTRACT(title, ?) = ?', ["$.$locale", $title])->value('id');
    }
}
