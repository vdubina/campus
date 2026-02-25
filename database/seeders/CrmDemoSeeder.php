<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Activity;
use App\Models\Contact;
use App\Models\Lead;
use App\Models\Opportunity;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CrmDemoSeeder extends Seeder
{
    public function run(): void
    {
        mt_srand(2027);

        $owners = User::query()->pluck('id')->values();

        if ($owners->isEmpty()) {
            return;
        }

        $accountNames = [
            'ТОВ "Аеронавт Системи"',
            'ГО "КіберЩит Громада"',
            'Центр підготовки "Північ"',
            'ТОВ "ДронФлот"',
            'ТОВ "Карта і Навігація"',
            'ФОП Гринюк О.О.',
            'ТОВ "Сигнал Лаб"',
            'БФ "Зв\'язок Майбутнього"',
            'ТОВ "OSINT Хаб"',
            'Навчальний центр "Компас"',
            'ТОВ "Ротор Тех"',
            'ТОВ "Крило Інжиніринг"',
            'ТОВ "ЕфПіВі Академія"',
            'ТОВ "Супутник Нет"',
            'ТОВ "Радіо Лінк"',
            'ТОВ "Тактичний Зв\'язок"',
            'КП "Безпечне Місто"',
            'ТОВ "ГеоРішення"',
            'ГО "Цифрова Оборона"',
            'ТОВ "БПЛА Платформа"',
        ];

        $accounts = collect();

        foreach ($accountNames as $index => $name) {
            $account = Account::query()->updateOrCreate([
                'name' => $name,
            ], [
                'owner_id' => $owners[$index % $owners->count()],
                'industry' => 'Освіта та безпека',
                'website' => 'https://example' . ($index + 1) . '.ua',
                'email' => 'office' . ($index + 1) . '@example' . ($index + 1) . '.ua',
                'phone' => '+38044' . str_pad((string) (200000 + $index), 6, '0', STR_PAD_LEFT),
                'billing_address' => 'м. Київ',
                'shipping_address' => 'м. Київ',
                'notes' => 'Тестовий CRM-акаунт (UA)',
            ]);

            $accounts->push($account);
        }

        $firstNames = ['Василь', 'Оксана', 'Павло', 'Ілона', 'Тарас', 'Вікторія', 'Богдан', 'Світлана', 'Євген', 'Людмила'];
        $lastNames = ['Іваненко', 'Мартинюк', 'Литвин', 'Чумак', 'Яременко', 'Федорчук', 'Руденко', 'Гаврилюк', 'Захаренко', 'Клименко'];

        $contacts = collect();

        for ($i = 1; $i <= 45; $i++) {
            $firstName = $firstNames[($i - 1) % count($firstNames)];
            $lastName = $lastNames[(($i - 1) * 2) % count($lastNames)];
            $account = $accounts[($i - 1) % $accounts->count()];
            $email = 'contact' . str_pad((string) $i, 2, '0', STR_PAD_LEFT) . '@crm.local';

            $contact = Contact::query()->updateOrCreate([
                'email' => $email,
            ], [
                'account_id' => $account->id,
                'owner_id' => $owners[$i % $owners->count()],
                'first_name' => $firstName,
                'last_name' => $lastName,
                'title' => ['Координатор', 'Менеджер', 'Інструктор', 'Керівник проєкту'][$i % 4],
                'phone' => '+38067' . str_pad((string) (300000 + $i), 6, '0', STR_PAD_LEFT),
                'mobile' => '+38096' . str_pad((string) (300000 + $i), 6, '0', STR_PAD_LEFT),
                'address' => 'Україна',
                'is_primary' => $i % 9 === 0,
                'notes' => 'Тестовий контакт',
            ]);

            $contacts->push($contact);
        }

        $leadStatuses = ['new', 'contacted', 'qualified', 'proposal', 'unqualified', 'converted'];
        $leadRatings = ['cold', 'warm', 'hot'];

        $leads = collect();

        for ($i = 1; $i <= 35; $i++) {
            $firstName = $firstNames[$i % count($firstNames)];
            $lastName = $lastNames[$i % count($lastNames)];
            $account = $accounts[$i % $accounts->count()];
            $email = 'lead' . str_pad((string) $i, 2, '0', STR_PAD_LEFT) . '@crm.local';

            $lead = Lead::query()->updateOrCreate([
                'email' => $email,
            ], [
                'account_id' => $account->id,
                'owner_id' => $owners[$i % $owners->count()],
                'first_name' => $firstName,
                'last_name' => $lastName,
                'company' => $account->name,
                'phone' => '+38093' . str_pad((string) (400000 + $i), 6, '0', STR_PAD_LEFT),
                'source' => ['Вебсайт', 'Реферал', 'Подія', 'Соцмережі'][$i % 4],
                'status' => $leadStatuses[$i % count($leadStatuses)],
                'rating' => $leadRatings[$i % count($leadRatings)],
                'estimated_value' => 20000 + ($i * 1500),
                'next_follow_up_at' => Carbon::now()->addDays(($i % 14) + 1),
                'notes' => 'Тестовий лід',
            ]);

            $leads->push($lead);
        }

        $stages = ['qualification', 'needs_analysis', 'proposal', 'negotiation', 'closed_won', 'closed_lost'];
        $statuses = ['open', 'won', 'lost'];

        $opportunities = collect();

        for ($i = 1; $i <= 25; $i++) {
            $account = $accounts[$i % $accounts->count()];
            $contact = $contacts[$i % $contacts->count()];

            $opportunity = Opportunity::query()->updateOrCreate([
                'name' => 'Угода #' . str_pad((string) $i, 3, '0', STR_PAD_LEFT),
            ], [
                'account_id' => $account->id,
                'contact_id' => $contact->id,
                'owner_id' => $owners[$i % $owners->count()],
                'stage' => $stages[$i % count($stages)],
                'status' => $statuses[$i % count($statuses)],
                'amount' => 50000 + ($i * 5000),
                'probability' => min(100, 20 + ($i * 3)),
                'close_date' => Carbon::now()->addDays(($i % 45) + 7)->toDateString(),
                'description' => 'Тестова можливість продажу.',
            ]);

            $opportunities->push($opportunity);
        }

        $activityTypes = ['task', 'call', 'email', 'meeting'];
        $activityStatuses = ['planned', 'in_progress', 'completed', 'cancelled'];
        $priorities = ['low', 'normal', 'high', 'urgent'];

        for ($i = 1; $i <= 80; $i++) {
            $account = $accounts[$i % $accounts->count()];
            $contact = $contacts[$i % $contacts->count()];
            $lead = $leads[$i % $leads->count()];
            $opportunity = $opportunities[$i % $opportunities->count()];

            Activity::query()->updateOrCreate([
                'subject' => 'Активність #' . str_pad((string) $i, 3, '0', STR_PAD_LEFT),
            ], [
                'account_id' => $account->id,
                'contact_id' => $contact->id,
                'lead_id' => $lead->id,
                'opportunity_id' => $opportunity->id,
                'owner_id' => $owners[$i % $owners->count()],
                'type' => $activityTypes[$i % count($activityTypes)],
                'status' => $activityStatuses[$i % count($activityStatuses)],
                'priority' => $priorities[$i % count($priorities)],
                'due_at' => Carbon::now()->addDays(($i % 20) + 1),
                'completed_at' => $i % 4 === 0 ? Carbon::now()->subDays($i % 10) : null,
                'description' => 'Тестова CRM-активність українською.',
            ]);
        }
    }
}
