<?php

namespace Database\Seeders;

use App\Models\Cabinet;
use App\Models\User;
use App\Models\Event;
use App\Models\Stage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Data\DefaultStagesData;  // Импортируем класс с этапами

class TestCabinetsSeeder extends Seeder
{
    /**
     * Создание этапов по умолчанию для мероприятия
     */
    protected function createDefaultStages(Event $event)
    {
        // Берём данные из отдельного файла DefaultStagesData
        $structure = DefaultStagesData::getData();
        $sortOrder = 0;

        foreach ($structure as $parentData) {
            // Создаём родительский этап
            $parent = Stage::create([
                'event_id'   => $event->id,
                'name'       => $parentData['name'],
                'status'     => 'planned',
                'sort_order' => ++$sortOrder,
            ]);

            $childSort = 0;
            foreach ($parentData['children'] as $childName) {
                Stage::create([
                    'event_id'   => $event->id,
                    'parent_id'  => $parent->id,
                    'name'       => $childName,
                    'status'     => 'planned',
                    'sort_order' => ++$childSort,
                ]);
            }
        }
    }

    public function run()
    {
        // Суперадмин
        User::firstOrCreate(
            ['email' => 'super@admin.com'],
            [
                'name'     => 'Super Admin',
                'password' => Hash::make('11111111'),
                'role'     => 'super_admin',
                'email_verified_at' => now(),
            ]
        );

        $cabinetsData = [
            [
                'name' => 'Тестовый кабинет 1',
                'admin' => [
                    'name'  => 'Администратор 1',
                    'email' => 'admin1@test.com',
                ],
            ],
            [
                'name' => 'Тестовый кабинет 2',
                'admin' => [
                    'name'  => 'Администратор 2',
                    'email' => 'admin2@test.com',
                ],
            ],
        ];

        foreach ($cabinetsData as $data) {
            $cabinet = Cabinet::firstOrCreate(
                ['name' => $data['name']],
                ['name' => $data['name']]
            );

            $user = User::firstOrCreate(
                ['email' => $data['admin']['email']],
                [
                    'name'     => $data['admin']['name'],
                    'password' => Hash::make('11111111'),
                    'role'     => 'user',
                    'email_verified_at' => now(),
                ]
            );

            if (!$cabinet->users()->where('user_id', $user->id)->exists()) {
                $cabinet->users()->attach($user->id, ['role' => 'admin']);
            }

            // Создаём мероприятия для кабинета
            $eventsData = [
                [
                    'title'       => 'Корпоратив "Новый год 2025"',
                    'description' => 'Организация новогоднего корпоратива для 150 человек, кейтеринг, ведущий, фотограф.',
                    'start_date'  => Carbon::now()->addMonths(2)->startOfMonth(),
                    'end_date'    => Carbon::now()->addMonths(2)->startOfMonth()->addDays(1),
                ],
                [
                    'title'       => 'Конференция "Digital 2025"',
                    'description' => 'Двухдневная конференция по цифровому маркетингу с участием экспертов.',
                    'start_date'  => Carbon::now()->addMonths(1)->addDays(10),
                    'end_date'    => Carbon::now()->addMonths(1)->addDays(11),
                ],
                [
                    'title'       => 'Семинар "Управление проектами"',
                    'description' => 'Однодневный семинар для руководителей, аренда зала, оборудование, кофе-брейки.',
                    'start_date'  => Carbon::now()->addDays(20),
                    'end_date'    => null,
                ],
            ];

            if ($cabinet->name === 'Тестовый кабинет 2') {
                $eventsData = [
                    [
                        'title'       => 'Выставка',
                        'description' => 'Участие в выставке: стенд, печатная продукция, персонал.',
                        'start_date'  => Carbon::now()->subMonths(1)->startOfMonth(),
                        'end_date'    => Carbon::now()->subMonths(1)->startOfMonth()->addDays(2),
                    ],
                    [
                        'title'       => 'Тимбилдинг "Спортивный квест"',
                        'description' => 'Выездной тимбилдинг, квест, транспорт, обед.',
                        'start_date'  => Carbon::now()->subDays(10),
                        'end_date'    => Carbon::now()->subDays(9),
                    ],
                    [
                        'title'       => 'Вебинар "Эффективные продажи"',
                        'description' => 'Вебинар для отдела продаж, платформа Zoom, раздаточные материалы.',
                        'start_date'  => Carbon::now()->addDays(5),
                        'end_date'    => null,
                    ],
                ];
            }

            foreach ($eventsData as $eventData) {
                $event = Event::firstOrCreate(
                    [
                        'cabinet_id' => $cabinet->id,
                        'title'      => $eventData['title'],
                    ],
                    [
                        'description' => $eventData['description'],
                        'start_date'  => $eventData['start_date'],
                        'end_date'    => $eventData['end_date'],
                        'status'      => $eventData['start_date'] <= Carbon::now() ? 'past' : 'future',
                    ]
                );

                // Создаём этапы для мероприятия, если их ещё нет
                if ($event->stages()->count() === 0) {
                    $this->createDefaultStages($event);
                }
            }
        }
    }
}
