<?php

namespace App\Filament\Resources\CategoryResource\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Category;

class CategoryChart extends ChartWidget
{
    protected static ?string $heading = 'Category Donations';

    protected function getData(): array
    {
        // Query total donations per category
        $categoryDonations = \App\Models\Category::selectRaw('categories.name as category_name, SUM(donations.amount) as total_donations')
            ->join('donations', 'donations.category_id', '=', 'categories.id')
            ->groupBy('categories.name')
            ->get();

        // Prepare the data for the chart
        $labels = [];
        $data = [];
        $colors = [
            'rgba(255, 99, 132, 0.2)', // Red
            'rgba(54, 162, 235, 0.2)', // Blue
            'rgba(255, 206, 86, 0.2)', // Yellow
            'rgba(75, 192, 192, 0.2)', // Green
            'rgba(153, 102, 255, 0.2)', // Purple
            'rgba(255, 159, 64, 0.2)', // Orange
        ];

        // Loop through the results and prepare labels, data, and colors for the chart
        foreach ($categoryDonations as $index => $categoryDonation) {
            $labels[] = $categoryDonation->category_name;
            $data[] = (float) $categoryDonation->total_donations;
        }

        return [
            'labels' => $labels, // Category names
            'datasets' => [
                [
                    'label' => 'Total Donations by Category (KES)',
                    'data' => $data,  // Donation amounts per category
                    'backgroundColor' => $colors,  // Different colors for each slice
                    'borderWidth' => 1,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'pie'; // Pie chart for category donation data
    }
    public function getColumnSpan(): int
    {
        return 12; // Spans the entire width of the row (Filament's grid system uses a 12-column layout)
    }
    public function getHeight(): int
    {
        return 100; // Adjust the height as needed
    }

}
