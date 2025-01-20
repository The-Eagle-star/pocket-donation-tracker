<?php

namespace App\Filament\Resources\DonationResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Donation;

use App\Models\Charity;

class DonationsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // General stats
        $totalDonations = Donation::sum('amount');
        $donationsThisMonth = Donation::whereMonth('date', now()->month)->sum('amount');

        // Get the latest donation made
        $latestDonation = Donation::latest()->first();

        if ($latestDonation) {
            // Retrieve the charity associated with the latest donation
            $charity = $latestDonation->charity;

            // Calculate total donations made for this charity
            $totalDonationsMade = $charity->donations()->sum('amount');

            // Calculate remaining balance for the charity
            $remainingBalance = max(0, $charity->total_donations - $totalDonationsMade);

            return [
                Stat::make($charity->title, number_format($totalDonationsMade, 2) . ' KES')
                
                    ->description("Goal: " . number_format($charity->total_donations, 2) . ' KES, Remaining: ' . number_format($remainingBalance, 2) . ' KES')
                    ->color($remainingBalance > 0 ? 'info' : 'success') // Info for ongoing goals, success for completed goals
                    ->icon($remainingBalance > 0 ? 'heroicon-o-clock' : 'heroicon-o-check-circle'), // Icon based on goal status
                    

                Stat::make('Total Donations', number_format($totalDonations, 2) . ' KES')
                    ->description('Overall amount received')
                    ->color('success') // Green for positive financial stats
                    ->icon('heroicon-o-currency-dollar'),

                Stat::make('Donations This Month', number_format($donationsThisMonth, 2) . ' KES')
                    ->description('Amount donated in ' . now()->format('F'))
                    ->color('primary') // Blue for current performance
                    ->icon('heroicon-o-calendar'),
            ];
        }

        // Return empty if no donation exists
        return [];
    }
}
