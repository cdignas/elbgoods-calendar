<?php

namespace Database\Seeders;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AppointmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Booked Appointment today
        Appointment::create(
            [
                'title' => 'App Presentation with Elbgoods',
                'description' => 'App Presentation with Elbgoods.',
                'start_date' => Carbon::now()->format('Y-m-d'),
                'end_date' => Carbon::now()->format('Y-m-d'),
                'status' => 'Booked',
            ]
        );

        // 2 Tentative and 2 Requested Appointments tomorrow
        Appointment::create(
            [
                'title' => 'Interview with Elbgoods - Peter',
                'description' => 'Interview with Elbgoods - Peter.',
                'start_date' => Carbon::now()->addDay()->format('Y-m-d'),
                'end_date' => Carbon::now()->addDay()->format('Y-m-d'),
                'status' => 'Tentative',
            ]
        );
        Appointment::create(
            [
                'title' => 'Interview with Elbgoods - Hans',
                'description' => 'Interview with Elbgoods - Hans.',
                'start_date' => Carbon::now()->addDay()->format('Y-m-d'),
                'end_date' => Carbon::now()->addDay()->format('Y-m-d'),
                'status' => 'Requested',
            ]
        );
        Appointment::create(
            [
                'title' => 'Interview with Elbgoods - Steven',
                'description' => 'Interview with Elbgoods - Steven.',
                'start_date' => Carbon::now()->addDay()->format('Y-m-d'),
                'end_date' => Carbon::now()->addDay()->format('Y-m-d'),
                'status' => 'Requested',
            ]
        );
        Appointment::create(
            [
                'title' => 'Interview with Elbgoods - Frank',
                'description' => 'Interview with Elbgoods - Frank.',
                'start_date' => Carbon::now()->addDay()->format('Y-m-d'),
                'end_date' => Carbon::now()->addDay()->format('Y-m-d'),
                'status' => 'Tentative',
            ]
        );

        // Appointment over 2 days in 3 days future
        Appointment::create(
            [
                'title' => 'Vacation with Elbgoods Team',
                'description' => 'Vacation with Elbgoods Team.',
                'start_date' => Carbon::now()->addDays(3)->format('Y-m-d'),
                'end_date' => Carbon::now()->addDays(5)->format('Y-m-d'),
                'status' => 'Booked',
            ]
        );
    }
}
