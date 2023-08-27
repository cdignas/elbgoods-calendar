<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AppointmentControllerTest  extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        DB::beginTransaction();
    }

    public function tearDown(): void
    {
        DB::rollback();
        parent::tearDown();
    }

    /**
     * @dataProvider dataTestIndex
     */
    public function testIndex(array $data, int $status, int $count): void
    {
        $response = $this->get('/api/v1/appointments');
        $response->assertStatus($status);

        if ($status === Response::HTTP_OK) {
            $this->assertCount(
                5, $response->json()['data']
            );
        }
    }

    public static function dataTestIndex(): array
    {
        return [
            'without-end-date' => [
                'data' => [],
                'status' => Response::HTTP_OK,
                'count' => 5,
            ],
            'today' => [
                'data' => [
                    'end_date' => Carbon::now('Europe/Berlin')
                        ->setTime(23, 0, 0)
                        ->setTimezone('UTC')
                        ->format('Y-m-d H:i:s')
                ],
                'status' => Response::HTTP_OK,
                'count' => 1,
            ]
        ];
    }

    public static function dataTestStore(): array
    {
        return [
            // FAILED
            'allready_booked_same_start_and_end_date' => [
                'data' => [
                    'title' => 'Test for Elbgoods',
                    'description' => 'Test for Elbgoods.',
                    'start_date' => Carbon::now('Europe/Berlin')
                        ->setTime(8, 0, 0)
                        ->setTimezone('UTC')
                        ->format('Y-m-d H:i:s'),
                    'end_date' => Carbon::now('Europe/Berlin')
                        ->setTime(18, 0, 0)
                        ->setTimezone('UTC')
                        ->format('Y-m-d H:i:s'),
                    'status' => 'Booked',
                ],
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY
            ],
            'overlapping' => [
                'data' => [
                    'title' => 'Test for Elbgoods',
                    'description' => 'Test for Elbgoods.',
                    'start_date' => Carbon::now('Europe/Berlin')
                        ->subDay()
                        ->setTime(8, 0, 0)
                        ->setTimezone('UTC')
                        ->format('Y-m-d H:i:s'),
                    'end_date' => Carbon::now('Europe/Berlin')
                        ->addDay()
                        ->setTime(18, 0, 0)
                        ->setTimezone('UTC')
                        ->format('Y-m-d H:i:s'),
                    'status' => 'Booked',
                ],
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY
            ],
            'overlapping_same_end' => [
                'data' => [
                    'title' => 'Test for Elbgoods',
                    'description' => 'Test for Elbgoods.',
                    'start_date' => Carbon::now('Europe/Berlin')
                        ->subDay()
                        ->setTime(8, 0, 0)
                        ->setTimezone('UTC')
                        ->format('Y-m-d H:i:s'),
                    'end_date' => Carbon::now('Europe/Berlin')
                        ->setTime(18, 0, 0)
                        ->setTimezone('UTC')
                        ->format('Y-m-d H:i:s'),
                    'status' => 'Booked',
                ],
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY
            ],
            'overlapping_same_start' => [
                'data' => [
                    'title' => 'Test for Elbgoods',
                    'description' => 'Test for Elbgoods.',
                    'start_date' => Carbon::now('Europe/Berlin')
                        ->setTime(8, 0, 0)
                        ->setTimezone('UTC')
                        ->format('Y-m-d H:i:s'),
                    'end_date' => Carbon::now('Europe/Berlin')
                        ->addDay()
                        ->setTime(18, 0, 0)
                        ->setTimezone('UTC')
                        ->format('Y-m-d H:i:s'),
                    'status' => 'Booked',
                ],
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY
            ],
            'tentative_count_max' => [
                'data' => [
                    'title' => 'Test for Elbgoods',
                    'description' => 'Test for Elbgoods.',
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
                ],
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY
            ],
            'start_date_after_end_date' => [
                'data' => [
                    'title' => 'Test for Elbgoods',
                    'description' => 'Test for Elbgoods.',
                    'start_date' => Carbon::now('Europe/Berlin')
                        ->setTime(18, 0, 0)
                        ->setTimezone('UTC')
                        ->format('Y-m-d H:i:s'),
                    'end_date' => Carbon::now('Europe/Berlin')
                        ->setTime(8, 0, 0)
                        ->setTimezone('UTC')
                        ->format('Y-m-d H:i:s'),
                    'status' => 'Tentative',
                ],
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY
            ],
            'appointment_in_past' => [
                'data' => [
                    'title' => 'Test for Elbgoods',
                    'description' => 'Test for Elbgoods.',
                    'start_date' => Carbon::now('Europe/Berlin')
                        ->subDay()
                        ->setTime(8, 0, 0)
                        ->setTimezone('UTC')
                        ->format('Y-m-d H:i:s'),
                    'end_date' => Carbon::now('Europe/Berlin')
                        ->subDay()
                        ->setTime(18, 0, 0)
                        ->setTimezone('UTC')
                        ->format('Y-m-d H:i:s'),
                    'status' => 'Tentative',
                ],
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY
            ],
            // SUCCESS
            'booked_succesfully' => [
                'data' => [
                    'title' => 'Test for Elbgoods',
                    'description' => 'Test for Elbgoods.',
                    'start_date' => Carbon::now('Europe/Berlin')
                        ->addDays(5)
                        ->setTime(8, 0, 0)
                        ->setTimezone('UTC')
                        ->format('Y-m-d H:i:s'),
                    'end_date' => Carbon::now('Europe/Berlin')
                        ->addDays(5)
                        ->setTime(18, 0, 0)
                        ->setTimezone('UTC')
                        ->format('Y-m-d H:i:s'),
                    'status' => 'Requested',
                ],
                'status' => Response::HTTP_OK
            ],
        ];
    }

    /**
     * @dataProvider dataTestStore
     */
    public function testStore($data, $status): void {
        $response = $this->postJson(
            '/api/v1/appointments',
            $data
        );
        $response->assertStatus($status);
    }
}
