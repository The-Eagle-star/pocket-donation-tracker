<?php

namespace App\Filament\Resources\CategoryResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Category;
use App\Models\Donation;

class CategoryOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Total donations per category
        $categories = Category::withSum('donations', 'amount')->get();
        $totalDonationsPerCategory = $categories->map(function ($category) {
            return Stat::make($category->name, number_format($category->donations_sum_amount, 2) . ' KES')
                ->description('Total donations for ' . $category->name)
                ->color('primary') // Blue for current performance
                ->icon('heroicon-o-calendar');
        });

        // Get the top category (category with the highest total donation)
        $topCategory = $categories->sortByDesc('donations_sum_amount')->first();
        $topCategoryStat = Stat::make('Top Category: ' . $topCategory->name, number_format($topCategory->donations_sum_amount, 2) . ' KES')
            ->description('Category with the highest total donations')
            ->color('success') // Green for top category
            ->icon('heroicon-o-star');

        // Donations this month (total donations for this month)
        $donationsThisMonth = Donation::whereMonth('date', now()->month)->sum('amount');
        $donationsThisMonthStat = Stat::make('Donations This Month', number_format($donationsThisMonth, 2) . ' KES')
            ->description('Total donations in ' . now()->format('F'))
            ->color('warning') // Yellow for monthly stats
            ->icon('heroicon-o-calendar');

        // Combine the stats
        return array_merge($totalDonationsPerCategory->toArray(), [
            $topCategoryStat,
            //$donationsThisMonthStat,
        ]);
    }
}
