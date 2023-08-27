<?php

namespace Database\Seeders;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppointmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Appointment::create(
            [
                'title' => 'App Presentation with Elbgoods',
                'description' => 'App Presentation with Elbgoods.',
                'start_date' => Carbon::now('Europe/Berlin')
                    ->setTime(8, 0, 0)
                    ->setTimezone('UTC')
                    ->format('Y-m-d H:i:s'),
                'end_date' => Carbon::now('Europe/Berlin')
                    ->setTime(18, 0, 0)
                    ->setTimezone('UTC')
                    ->format('Y-m-d H:i:s'),
                'status' => 'Booked',
            ]
        );

        Appointment::create(
            [
                'title' => 'Interview with Elbgoods - Peter',
                'description' => 'Interview with Elbgoods - Peter.',
                'start_date' => Carbon::now('Europe/Berlin')
                    ->addDay()
                    ->setTime(8, 0, 0)
                    ->setTimezone('UTC')
                    ->format('Y-m-d H:i:s'),
                'end_date' => Carbon::now('Europe/Berlin')
                    ->addDay()
                    ->setTime(18, 0, 0)
                    ->setTimezone('UTC')
                    ->format('Y-m-d H:i:s'),
                'status' => 'Tentative',
            ]
        );

        Appointment::create(
            [
                'title' => 'Interview with Elbgoods - Hans',
                'description' => 'Interview with Elbgoods - Hans.',
                'start_date' => Carbon::now('Europe/Berlin')
                    ->addDay()
                    ->setTime(8, 0, 0)
                    ->setTimezone('UTC')
                    ->format('Y-m-d H:i:s'),
                'end_date' => Carbon::now('Europe/Berlin')
                    ->addDay()
                    ->setTime(18, 0, 0)
                    ->setTimezone('UTC')
                    ->format('Y-m-d H:i:s'),
                'status' => 'Tentative',
            ]
        );

        Appointment::create(
            [
                'title' => 'Interview with Elbgoods - Steven',
                'description' => 'Interview with Elbgoods - Steven.',
                'start_date' => Carbon::now('Europe/Berlin')
                    ->addDay()
                    ->setTime(8, 0, 0)
                    ->setTimezone('UTC')
                    ->format('Y-m-d H:i:s'),
                'end_date' => Carbon::now('Europe/Berlin')
                    ->addDay()
                    ->setTime(18, 0, 0)
                    ->setTimezone('UTC')
                    ->format('Y-m-d H:i:s'),
                'status' => 'Tentative',
            ]
        );

        Appointment::create(
            [
                'title' => 'Interview with Elbgoods - Frank',
                'description' => 'Interview with Elbgoods - Frank.',
                'start_date' => Carbon::now('Europe/Berlin')
                    ->addDay()
                    ->setTime(8, 0, 0)
                    ->setTimezone('UTC')
                    ->format('Y-m-d H:i:s'),
                'end_date' => Carbon::now('Europe/Berlin')
                    ->addDay()
                    ->setTime(18, 0, 0)
                    ->setTimezone('UTC')
                    ->format('Y-m-d H:i:s'),
                'status' => 'Tentative',
            ]
        );
    }
}
