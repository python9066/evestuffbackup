<?php

use App\Models\Character;
use App\Models\Dscan;
use App\Models\DscanLocal;
use App\Models\Structure;
use App\Models\System;
use GuzzleHttp\Client;
use GuzzleHttp\Utils;

if (!function_exists('getDscanInfo')) {
    function getDscanInfo($link)
    {
        $dscan = Dscan::whereLink($link)
            ->with([
                'system:id,region_id,constellation_id,system_name',
                'system.region',
                'system.constellation',
                'updatedBy:id,name',
                'madeby:id,name',
                'items.item.group',
                'totals',
                'locals.corp.alliance'
            ])
            ->first();


        $allLocals = $dscan->locals;
        // return $allLocals;
        $corpsTotal = [];
        $allianceTotal = [];

        foreach ($allLocals as $local) {
            if ($local->corp) {
                $corpsTotal[$local->corp->name]['details'] = $local->corp;
                $corpsTotal[$local->corp->name]['total'] = 0;
                $corpsTotal[$local->corp->name]['new'] = 0;
                $corpsTotal[$local->corp->name]['same'] = 0;
                $corpsTotal[$local->corp->name]['left'] = 0;
                $corpsTotal[$local->corp->name]['totalInSystem'] = 0;
            }
            if ($local->corp && $local->corp->alliance) {
                $allianceTotal[$local->corp->alliance->name]['details'] = $local->corp->alliance;
                $allianceTotal[$local->corp->alliance->name]['total'] = 0;
                $allianceTotal[$local->corp->alliance->name]['new'] = 0;
                $allianceTotal[$local->corp->alliance->name]['same'] = 0;
                $allianceTotal[$local->corp->alliance->name]['left'] = 0;
                $allianceTotal[$local->corp->alliance->name]['totalInSystem'] = 0;
            }
            if (!$local->corp) {
                $corpsTotal['unknown']['details']['standings'] = 0;
                $corpsTotal['unknown']['details']['name'] = 'Unknown';
                $corpsTotal['unknown']['details']['ticker'] = '???';
                $corpsTotal['unknown']['details']['url'] = 'https://images.evetech.net/Corporation/1_64.png';
                $corpsTotal['unknown']['total'] = 0;
                $corpsTotal['unknown']['new'] = 0;
                $corpsTotal['unknown']['same'] = 0;
                $corpsTotal['unknown']['left'] = 0;
                $corpsTotal['unknown']['totalInSystem'] = 0;
            }
        }

        foreach ($allLocals as $local) {
            if ($local->corp) {
                $corpsTotal[$local->corp->name]['total'] = $corpsTotal[$local->corp->name]['total'] + 1;
            }
            if ($local->corp && $local->corp->alliance) {
                $allianceTotal[$local->corp->alliance->name]['total'] = $allianceTotal[$local->corp->alliance->name]['total'] + 1;
            }
            if (!$local->corp) {
                $corpsTotal['unknown']['total'] = $corpsTotal['unknown']['total'] + 1;
            }

            if ($local->pivot->new) {
                if ($local->corp) {
                    $corpsTotal[$local->corp->name]['new'] = $corpsTotal[$local->corp->name]['new'] + 1;
                    $corpsTotal[$local->corp->name]['totalInSystem'] = $corpsTotal[$local->corp->name]['totalInSystem'] + 1;
                }
                if ($local->corp && $local->corp->alliance) {
                    $allianceTotal[$local->corp->alliance->name]['new'] = $allianceTotal[$local->corp->alliance->name]['new'] + 1;
                    $allianceTotal[$local->corp->alliance->name]['totalInSystem'] = $allianceTotal[$local->corp->alliance->name]['totalInSystem'] + 1;
                }
                if (!$local->corp) {
                    $corpsTotal['unknown']['new'] = $corpsTotal['unknown']['new'] + 1;
                    $corpsTotal['unknown']['totalInSystem'] = $corpsTotal['unknown']['totalInSystem'] + 1;
                }
            }

            if ($local->pivot->same) {
                if ($local->corp) {
                    $corpsTotal[$local->corp->name]['same'] = $corpsTotal[$local->corp->name]['same'] + 1;
                    $corpsTotal[$local->corp->name]['totalInSystem'] = $corpsTotal[$local->corp->name]['totalInSystem'] + 1;
                }
                if ($local->corp && $local->corp->alliance) {
                    $allianceTotal[$local->corp->alliance->name]['same'] = $allianceTotal[$local->corp->alliance->name]['same'] + 1;
                    $allianceTotal[$local->corp->alliance->name]['totalInSystem'] = $allianceTotal[$local->corp->alliance->name]['totalInSystem'] + 1;
                }
                if (!$local->corp) {
                    $corpsTotal['unknown']['same'] = $corpsTotal['unknown']['same'] + 1;
                    $corpsTotal['unknown']['totalInSystem'] = $corpsTotal['unknown']['totalInSystem'] + 1;
                }
            }

            if ($local->pivot->left) {
                if ($local->corp) {
                    $corpsTotal[$local->corp->name]['left'] = $corpsTotal[$local->corp->name]['left'] + 1;
                }
                if ($local->corp && $local->corp->alliance) {
                    $allianceTotal[$local->corp->alliance->name]['left'] = $allianceTotal[$local->corp->alliance->name]['left'] + 1;
                }
                if (!$local->corp) {
                    $corpsTotal['unknown']['left'] = $corpsTotal['unknown']['left'] + 1;
                }
            }
        }

        foreach ($corpsTotal as $key => $corp) {

            $same =   $corpsTotal[$key]['same'];
            $new =   $corpsTotal[$key]['new'];
            $left =   $corpsTotal[$key]['left'];

            if ($new == 0 && $left == 0) {
                $diff = 0;
            } else {

                $diff = $new - $left;
            }
            $corpsTotal[$key]['diff'] = $diff;
        }

        foreach ($allianceTotal as $key => $alliance) {
            $same =   $allianceTotal[$key]['same'];
            $new =   $allianceTotal[$key]['new'];
            $left =   $allianceTotal[$key]['left'];

            if ($new == 0 && $left == 0) {
                $diff = 0;
            } else {

                $diff = $new - $left;
            }
            $allianceTotal[$key]['diff'] = $diff;
        }


        $corpsTotal = collect($corpsTotal)->sortByDesc('totalInSystem')->values()->all();
        $allianceTotal = collect($allianceTotal)->sortByDesc('totalInSystem')->values()->all();

        return [
            'dscan' => $dscan,
            'corpsTotal' => $corpsTotal,
            'allianceTotal' => $allianceTotal,
        ];
    }
}

if (!function_exists('getDscanLocalInfo')) {
    function getDscanLocalInfo($link)
    {
        $dscan = Dscan::whereLink($link)->first();


        $allLocals = $dscan->locals;
        $corpsTotal = [];
        $allianceTotal = [];

        foreach ($allLocals as $local) {
            if ($local->corp) {
                $corpsTotal[$local->corp->name]['details'] = $local->corp;
                $corpsTotal[$local->corp->name]['total'] = 0;
                $corpsTotal[$local->corp->name]['new'] = 0;
                $corpsTotal[$local->corp->name]['same'] = 0;
                $corpsTotal[$local->corp->name]['left'] = 0;
                $corpsTotal[$local->corp->name]['totalInSystem'] = 0;
            }
            if ($local->corp && $local->corp->alliance) {
                $allianceTotal[$local->corp->alliance->name]['details'] = $local->corp->alliance;
                $allianceTotal[$local->corp->alliance->name]['total'] = 0;
                $allianceTotal[$local->corp->alliance->name]['new'] = 0;
                $allianceTotal[$local->corp->alliance->name]['same'] = 0;
                $allianceTotal[$local->corp->alliance->name]['left'] = 0;
                $allianceTotal[$local->corp->alliance->name]['totalInSystem'] = 0;
            }
            if (!$local->corp) {
                $corpsTotal['unknown']['details']['standings'] = 0;
                $corpsTotal['unknown']['details']['name'] = 'Unknown';
                $corpsTotal['unknown']['details']['ticker'] = '???';
                $corpsTotal['unknown']['details']['url'] = 'https://images.evetech.net/Corporation/1_64.png';
                $corpsTotal['unknown']['total'] = 0;
                $corpsTotal['unknown']['new'] = 0;
                $corpsTotal['unknown']['same'] = 0;
                $corpsTotal['unknown']['left'] = 0;
                $corpsTotal['unknown']['totalInSystem'] = 0;
            }
        }

        foreach ($allLocals as $local) {
            if ($local->corp) {
                $corpsTotal[$local->corp->name]['total'] = $corpsTotal[$local->corp->name]['total'] + 1;
            }
            if ($local->corp && $local->corp->alliance) {
                $allianceTotal[$local->corp->alliance->name]['total'] = $allianceTotal[$local->corp->alliance->name]['total'] + 1;
            }
            if (!$local->corp) {
                $corpsTotal['unknown']['total'] = $corpsTotal['unknown']['total'] + 1;
            }

            if ($local->pivot->new) {
                if ($local->corp) {
                    $corpsTotal[$local->corp->name]['new'] = $corpsTotal[$local->corp->name]['new'] + 1;
                    $corpsTotal[$local->corp->name]['totalInSystem'] = $corpsTotal[$local->corp->name]['totalInSystem'] + 1;
                }
                if ($local->corp && $local->corp->alliance) {
                    $allianceTotal[$local->corp->alliance->name]['new'] = $allianceTotal[$local->corp->alliance->name]['new'] + 1;
                    $allianceTotal[$local->corp->alliance->name]['totalInSystem'] = $allianceTotal[$local->corp->alliance->name]['totalInSystem'] + 1;
                }
                if (!$local->corp) {
                    $corpsTotal['unknown']['new'] = $corpsTotal['unknown']['new'] + 1;
                    $corpsTotal['unknown']['totalInSystem'] = $corpsTotal['unknown']['totalInSystem'] + 1;
                }
            }

            if ($local->pivot->same) {
                if ($local->corp) {
                    $corpsTotal[$local->corp->name]['same'] = $corpsTotal[$local->corp->name]['same'] + 1;
                    $corpsTotal[$local->corp->name]['totalInSystem'] = $corpsTotal[$local->corp->name]['totalInSystem'] + 1;
                }
                if ($local->corp && $local->corp->alliance) {
                    $allianceTotal[$local->corp->alliance->name]['same'] = $allianceTotal[$local->corp->alliance->name]['same'] + 1;
                    $allianceTotal[$local->corp->alliance->name]['totalInSystem'] = $allianceTotal[$local->corp->alliance->name]['totalInSystem'] + 1;
                }
                if (!$local->corp) {
                    $corpsTotal['unknown']['same'] = $corpsTotal['unknown']['same'] + 1;
                    $corpsTotal['unknown']['totalInSystem'] = $corpsTotal['unknown']['totalInSystem'] + 1;
                }
            }

            if ($local->pivot->left) {
                if ($local->corp) {
                    $corpsTotal[$local->corp->name]['left'] = $corpsTotal[$local->corp->name]['left'] + 1;
                }
                if ($local->corp && $local->corp->alliance) {
                    $allianceTotal[$local->corp->alliance->name]['left'] = $allianceTotal[$local->corp->alliance->name]['left'] + 1;
                }
                if (!$local->corp) {
                    $corpsTotal['unknown']['left'] = $corpsTotal['unknown']['left'] + 1;
                }
            }
        }

        foreach ($corpsTotal as $key => $corp) {

            $same =   $corpsTotal[$key]['same'];
            $new =   $corpsTotal[$key]['new'];
            $left =   $corpsTotal[$key]['left'];

            if ($new == 0 && $left == 0) {
                $diff = 0;
            } else {

                $diff = $new - $left;
            }
            $corpsTotal[$key]['diff'] = $diff;
        }

        foreach ($allianceTotal as $key => $alliance) {
            $same =   $allianceTotal[$key]['same'];
            $new =   $allianceTotal[$key]['new'];
            $left =   $allianceTotal[$key]['left'];

            if ($new == 0 && $left == 0) {
                $diff = 0;
            } else {

                $diff = $new - $left;
            }
            $allianceTotal[$key]['diff'] = $diff;
        }


        $corpsTotal = collect($corpsTotal)->sortByDesc('totalInSystem')->values()->all();
        $allianceTotal = collect($allianceTotal)->sortByDesc('totalInSystem')->values()->all();

        return [
            'corpsTotal' => $corpsTotal,
            'allianceTotal' => $allianceTotal,
        ];
    }
}
