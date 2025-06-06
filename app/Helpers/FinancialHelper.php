<?php

namespace App\Helpers;

class FinancialHelper
{
    /**
     * Get appropriate color for expense category
     *
     * @param string $category
     * @return string
     */
    public static function getCategoryColor($category)
    {
        $colorMap = [
            'Operational' => 'info',
            'Utilities' => 'warning',
            'Salaries' => 'primary',
            'Rent' => 'secondary',
            'Marketing' => 'success',
            'Inventory' => 'danger',
            'Other' => 'dark'
        ];

        return $colorMap[$category] ?? 'light';
    }
}
