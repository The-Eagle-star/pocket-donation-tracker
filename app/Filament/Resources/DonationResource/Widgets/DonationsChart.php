<?php

namespace App\Filament\Resources\DonationResource\Widgets;

use Filament\Widgets\ChartWidget;

class DonationsChart extends ChartWidget
{
    protected static ?string $heading = 'Monthly Donations';

    protected function getData(): array
    {
        // Query total donations per month for the last 6 months
        $donations = \App\Models\Donation::selectRaw('SUM(amount) as total, MONTH(date) as month')
            ->where('date', '>=', now()->subMonths(6))  // Last 6 months
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // Prepare the data for the chart
        $labels = [];
        $data = [];

        foreach ($donations as $donation) {
            // Add month names to the labels
            $labels[] = \Carbon\Carbon::create()->month($donation->month)->format('F');
            // Add the total donation amounts to the data array
            $data[] = (float) $donation->total;
        }

        return [
            'labels' => $labels, // Month names
            'datasets' => [
                [
                    'label' => 'Total Donations (KES)',
                    'data' => $data,  // Donation amounts per month
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',  // Light green for the bar color
                    'borderColor' => 'rgba(75, 192, 192, 1)',  // Green for the bar border
                    'borderWidth' => 1,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // Bar chart for donation data
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
