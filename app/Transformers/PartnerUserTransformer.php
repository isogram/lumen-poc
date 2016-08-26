<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\PartnerUser;

class PartnerUserTransformer extends TransformerAbstract
{

    /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform(PartnerUser $partnerUser)
    {
        return [
            'id'            => (int) $partnerUser->id,
            'username'      => $partnerUser->username
        ];
    }

}