<?php

namespace App\Models;

use App\Models\BaseModel;

class MembershipModel extends BaseModel
{

    protected static $source = 'Membership';

    protected $primaryKey = 'membership_id';

    protected $dates = ['source_created_at', 'source_modified_at'];

    protected $fakeIdsStartAt = 99900000;

    protected function fillIdsFrom($source)
    {

        $this->membership_id = $source->id;

        return $this;

    }

}
