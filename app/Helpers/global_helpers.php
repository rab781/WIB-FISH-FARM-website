<?php

use App\Helpers\FinancialHelper;

/**
 * Get appropriate color for expense category
 *
 * @param string $category
 * @return string
 */
if (!function_exists('getCategoryColor')) {
    function getCategoryColor($category)
    {
        return FinancialHelper::getCategoryColor($category);
    }
}
