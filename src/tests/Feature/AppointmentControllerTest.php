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
        $url = '/api/v1/appointments';
        if (isset($data['end_date'])) {
            $url .= '?end_date=' .  $data['end_date'];
        }

        $response = $this->get($url);
        $response->assertStatus($status);

        if ($status === Response::HTTP_OK) {
            $this->assertCount(
                $count, $response->json()['data']
            );
        }
    }

    public static function dataTestIndex(): array
    {
        return [
            'without-end-date' => [
                'data' => [],
                'status' => Response::HTTP_OK,
                'count' => 6,
            ],
            'today' => [
                'data' => [
                    'end_date' => Carbon::now()
                        ->format('Y-m-d')
                ],
                'status' => Response::HTTP_OK,
                'count' => 1,
            ],
        ];
    }

    public function testGet(): void
    {
        $response = $this->get('api/v1/appointments/1');
        $response->assertStatus(Response::HTTP_OK);

        $response = $this->get('api/v1/appointments/1000000');
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testDelete(): void
    {
        $response = $this->delete('api/v1/appointments/1');
        $response->assertStatus(Response::HTTP_OK);

        $response = $this->delete('api/v1/appointments/1000000');
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public static function dataTestStore(): array
    {
        return [
            // FAILED
            'already_booked_same_start_and_end_date' => [
                'data' => [
                    'title' => 'Test for Elbgoods',
                    'description' => 'Test for Elbgoods.',
                    'start_date' => Carbon::now()
                        ->format('Y-m-d'),
                    'end_date' => Carbon::now()
                        ->format('Y-m-d'),
                    'status' => 'Booked',
                ],
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY
            ],
            'already_booked_same_start_and_end_date_tentative' => [
                'data' => [
                    'title' => 'Test for Elbgoods',
                    'description' => 'Test for Elbgoods.',
                    'start_date' => Carbon::now()
                        ->format('Y-m-d'),
                    'end_date' => Carbon::now()
                        ->format('Y-m-d'),
                    'status' => 'Tentative',
                ],
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY
            ],
            'overlapping' => [
                'data' => [
                    'title' => 'Test for Elbgoods',
                    'description' => 'Test for Elbgoods.',
                    'start_date' => Carbon::now()
                        ->subDay()
                        ->format('Y-m-d'),
                    'end_date' => Carbon::now()
                        ->addDay()
                        ->format('Y-m-d'),
                    'status' => 'Booked',
                ],
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY
            ],
            'overlapping_same_end' => [
                'data' => [
                    'title' => 'Test for Elbgoods',
                    'description' => 'Test for Elbgoods.',
                    'start_date' => Carbon::now()
                        ->subDay()
                        ->format('Y-m-d'),
                    'end_date' => Carbon::now()
                        ->format('Y-m-d'),
                    'status' => 'Booked',
                ],
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY
            ],
            'overlapping_same_start' => [
                'data' => [
                    'title' => 'Test for Elbgoods',
                    'description' => 'Test for Elbgoods.',
                    'start_date' => Carbon::now()
                        ->format('Y-m-d'),
                    'end_date' => Carbon::now()
                        ->addDay()
                        ->format('Y-m-d'),
                    'status' => 'Booked',
                ],
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY
            ],
            'tentative_count_max' => [
                'data' => [
                    'title' => 'Test for Elbgoods',
                    'description' => 'Test for Elbgoods.',
                    'start_date' => Carbon::now()
                        ->addDay()
                        ->format('Y-m-d'),
                    'end_date' => Carbon::now()
                        ->addDay()
                        ->format('Y-m-d'),
                    'status' => 'Tentative',
                ],
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY
            ],
            'start_date_after_end_date' => [
                'data' => [
                    'title' => 'Test for Elbgoods',
                    'description' => 'Test for Elbgoods.',
                    'start_date' => Carbon::now()
                        ->format('Y-m-d'),
                    'end_date' => Carbon::now()
                        ->subDay()
                        ->format('Y-m-d'),
                    'status' => 'Tentative',
                ],
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY
            ],
            'appointment_in_past' => [
                'data' => [
                    'title' => 'Test for Elbgoods',
                    'description' => 'Test for Elbgoods.',
                    'start_date' => Carbon::now()
                        ->subDay()
                        ->format('Y-m-d'),
                    'end_date' => Carbon::now()
                        ->subDay()
                        ->format('Y-m-d'),
                    'status' => 'Tentative',
                ],
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY
            ],
            'overlapping_vacation_first_day' => [
                'data' => [
                    'title' => 'Test for Elbgoods',
                    'description' => 'Test for Elbgoods.',
                    'start_date' => Carbon::now()
                        ->addDays(3)
                        ->format('Y-m-d'),
                    'end_date' => Carbon::now()
                        ->addDays(4)
                        ->format('Y-m-d'),
                    'status' => 'Tentative',
                ],
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY
            ],
            'overlapping_vacation_inner' => [
                'data' => [
                    'title' => 'Test for Elbgoods',
                    'description' => 'Test for Elbgoods.',
                    'start_date' => Carbon::now()
                        ->addDays(4)
                        ->format('Y-m-d'),
                    'end_date' => Carbon::now()
                        ->addDays(4)
                        ->format('Y-m-d'),
                    'status' => 'Tentative',
                ],
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY
            ],
            'overlapping_vacation_last_day' => [
                'data' => [
                    'title' => 'Test for Elbgoods',
                    'description' => 'Test for Elbgoods.',
                    'start_date' => Carbon::now()
                        ->addDays(4)
                        ->format('Y-m-d'),
                    'end_date' => Carbon::now()
                        ->addDays(5)
                        ->format('Y-m-d'),
                    'status' => 'Tentative',
                ],
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY
            ],
            'overlapping_vacation_complete' => [
                'data' => [
                    'title' => 'Test for Elbgoods',
                    'description' => 'Test for Elbgoods.',
                    'start_date' => Carbon::now()
                        ->addDays(2)
                        ->format('Y-m-d'),
                    'end_date' => Carbon::now()
                        ->addDays(6)
                        ->format('Y-m-d'),
                    'status' => 'Tentative',
                ],
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY
            ],
            // SUCCESS
            'booked_successfully' => [
                'data' => [
                    'title' => 'Test for Elbgoods',
                    'description' => 'Test for Elbgoods.',
                    'start_date' => Carbon::now()
                        ->addMonth()
                        ->format('Y-m-d'),
                    'end_date' => Carbon::now()
                        ->addMonth()
                        ->format('Y-m-d'),
                    'status' => 'Requested',
                ],
                'status' => Response::HTTP_CREATED
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

    public static function dataTestUpdate(): array
    {
        return [
            // FAILED
            'booked_change_state' => [
                'id' => 1,
                'data' => [
                    'status' => 'Tentative',
                ],
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY
            ],
            'booked_change_title' => [
                'id' => 1,
                'data' => [
                    'title' => 'Test',
                ],
                'status' => Response::HTTP_OK
            ],
            'booked_change_start_and_end_date' => [
                'id' => 1,
                'data' => [
                    'start_date' => Carbon::now()
                        ->addDay()
                        ->format('Y-m-d'),
                    'end_date' => Carbon::now()
                        ->addDay()
                        ->format('Y-m-d'),
                    'status' => 'Tentative',
                ],
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY
            ],
            'change_start_and_end_date' => [
                'id' => 1,
                'data' => [
                    'start_date' => Carbon::now()
                        ->addDay()
                        ->format('Y-m-d'),
                    'end_date' => Carbon::now()
                        ->addDay()
                        ->format('Y-m-d'),
                ],
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY
            ],
            'booked_change_end_date' => [
                'id' => 1,
                'data' => [
                    'end_date' => Carbon::now()
                        ->addDay()
                        ->format('Y-m-d'),
                    'status' => 'Tentative',
                ],
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY
            ],
            'booked_change_start_date' => [
                'id' => 1,
                'data' => [
                    'start_date' => Carbon::now()
                        ->subDay()
                        ->format('Y-m-d'),
                    'status' => 'Tentative',
                ],
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY
            ],
            'vacation_change_start_date' => [
                'id' => 6,
                'data' => [
                    'start_date' => Carbon::now()
                        ->format('Y-m-d'),
                ],
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY
            ],
            'not_existing_appointment' => [
                'id' => 10000,
                'data' => [
                    'start_date' => Carbon::now()
                        ->format('Y-m-d'),
                ],
                'status' => Response::HTTP_NOT_FOUND
            ],
        ];
    }

    /**
     * @dataProvider dataTestUpdate
     */
    public function testUpdate($id, $data, $status): void {
        $response = $this->putJson(
            '/api/v1/appointments/' . $id,
            $data
        );
        $response->assertStatus($status);
    }
}
