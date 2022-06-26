<?php

use App\Models\OperationInfo;

if (!function_exists('operationInfoAll')) {
    function operationInfoAll()
    {
        return OperationInfo::with(['status'])->select('id', 'name', 'start', 'status')->get();
    }
}
