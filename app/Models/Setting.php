<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
class Setting extends Model
{
    use Sortable;
    //
    public $sortable = ['id', 'site_title', 'tag_line', 'company_name', 'contact_number', 'contact_email', 'address', 'facebook_link', 'twitter_link', 'google_link', 'instagram_link', 'linkedin_link', 'pinterest_link', 'youtube_link', 'vimeo_link', 'home_logo', 'logo', 'favicon', 'created_at', 'updated_at'];

    
}
