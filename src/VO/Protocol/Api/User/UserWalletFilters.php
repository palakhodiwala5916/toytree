<?php

namespace App\VO\Protocol\Api\User;

use App\VO\Protocol\BaseFilters;

final class UserWalletFilters extends BaseFilters
{
    public ?string $user = null;
}
