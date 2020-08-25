<?php

namespace Hellotreedigital\Cms\Models;

use Illuminate\Database\Eloquent\Model;

class AdminRolePermission extends Model
{
    public function page()
    {
        return $this->belongsTo('Hellotreedigital\Cms\Models\CmsPage', 'cms_page_id');
    }
}
