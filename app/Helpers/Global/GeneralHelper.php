<?php

use App\Models\Attribute;
use App\Models\Product;
use Carbon\Carbon;
use App\Models\Transaction;

if (! function_exists('appName')) {
    /**
     * Helper to grab the application name.
     *
     * @return mixed
     */
    function appName()
    {
        return config('app.name', 'Laravel Boilerplate');
    }
}

if (!function_exists('escape_like')) {
    /**
     * escape_like
     *
     * @param $string
     * @return mixed
     */
    function escape_like($string)
    {
        $search = array('%', '_', '&', '*', ',', '|');
        $replace = array('\%', '\_', '\&', '\*', '\,', '\|');
        return str_replace($search, $replace, $string);
    }
}

if (!function_exists('dataAttributeValue')) {
    function dataAttributeValue(Attribute $at, Product $product)
    {
        foreach ($product->productAttributeValue as $atv) {
            if ($atv->attribute_id == $at->id) {
                echo $atv->value;
            }
        }
    }
}
if (!function_exists('checkDataAttributeValue')) {
    function checkDataAttributeValue(Attribute $at, Product $product, $value)
    {
        foreach ($product->productAttributeValue as $atv) {
            if ($atv->attribute_id == $at->id) {
                if ($atv->value == $value) {
                    return true;
                }
            }
        }
        return false;
    }
}

if (!function_exists('chart')) {
    function chart()
    {
        $days = [];
        $totals = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->format('Y-m-d');
            $days[] = Carbon::today()->subDays($i)->format('d-m');
            $totals[] = Transaction::where(function ($query) use ($date) {
                $query->whereDate('created_at', $date)
                    ->whereStatus(2);
            })->sum('total_amount');
        }

        $total_price_seven_days_edit = implode(',', $totals);
        $time_chart = implode(',', array_slice($days, 0, 6)) . ',Hôm nay';

        return [
            'total_price_seven_days_edit' => $total_price_seven_days_edit,
            'time_chart' => $time_chart,
        ];
    }
}

if (!function_exists('checkExtensionImage')) {
    function checkExtensionImage($image)
    {
        $extension = $image->getClientOriginalExtension();
        if (!in_array(strtolower($extension), ['jpg', 'png', 'jpeg'])) {
            return false;
        }
        return true;
    }
}
