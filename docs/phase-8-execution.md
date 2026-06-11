# Phase 8 Execution Guide — Notification Center

## 0. Identitas Phase

Dokumen ini adalah instruksi eksekusi untuk AI coding agent di code editor.

Project:

```text
HafizPlus School Platform
```

Produk pertama:

```text
Tahfizh Monitoring App
```

Framework:

```text
Laravel 12
```

Database:

```text
MySQL
```

Project folder:

```text
C:\xampp\htdocs\hafizplus-school-platform
```

Database local:

```text
hafizplus_school_platform
```

Status:

```text
Proyek mandiri, bukan bagian dari HafizPlus 2.0 atau HafizPlus 3.0.
```

---

# 1. Tujuan Phase 8

Phase 8 bertujuan membuat **Notification Center** berbasis database.

Fokus Phase 8:

1. Membuat tabel `notifications`.
2. Membuat halaman daftar notifikasi user.
3. Membuat detail notifikasi.
4. Membuat fitur tandai sudah dibaca.
5. Membuat fitur tandai semua sudah dibaca.
6. Membuat fitur hapus notifikasi pribadi.
7. Membuat pengiriman pengumuman dari admin.
8. Membuat notification class untuk:

   * pengumuman umum
   * setoran hafalan baru
   * hutang hafalan / santri tertinggal
9. Membuat service pengiriman notifikasi.
10. Menampilkan badge jumlah notifikasi belum dibaca.
11. Dokumentasi Phase 8.

Phase 8 hanya memakai **in-app database notification**.

---

# 2. Batasan Phase 8

AI agent tidak boleh membuat fitur berikut pada Phase 8:

1. WhatsApp gateway.
2. Push notification.
3. Firebase Cloud Messaging.
4. Pusher.
5. Laravel Reverb.
6. Websocket.
7. SMS.
8. Email otomatis.
9. Queue worker production.
10. Native Android.
11. Native iOS.
12. Chat parent-guru.
13. Export PDF.
14. Export Excel.
15. Attendance.
16. Mutabaah.
17. Tahsin.
18. Finance.
19. Cashless.
20. Payment gateway.
21. Multi-tenant kompleks.

Phase 8 cukup memakai:

1. Laravel Notification.
2. Database notification channel.
3. Blade.
4. Controller.
5. Form Request.
6. Service class.
7. Role-based access.
8. Ownership-based notification access.

---

# 3. Prinsip Notification Center

## 3.1 Kanal Awal

Kanal awal:

```text
database
```

Belum memakai:

```text
mail
broadcast
sms
whatsapp
push
```

Alasan:

1. Lebih stabil.
2. Tidak butuh biaya eksternal.
3. Tidak tergantung API pihak ketiga.
4. Cocok untuk MVP.
5. Bisa diuji lokal.
6. Mudah ditingkatkan ke email/push nanti.

---

## 3.2 Jenis Notifikasi Phase 8

| Jenis              | Penerima                   | Trigger                            |
| ------------------ | -------------------------- | ---------------------------------- |
| Pengumuman sekolah | Role tertentu / semua user | Admin mengirim manual              |
| Setoran baru       | Orang tua dan santri       | Setelah setoran dibuat             |
| Santri tertinggal  | Orang tua, guru, admin     | Manual dari service / controller   |
| Reminder input     | Guru                       | Manual pengumuman / nanti otomatis |
| Laporan tersedia   | Orang tua / santri         | Manual pengumuman / nanti otomatis |

---

## 3.3 Tidak Semua Notifikasi Harus Otomatis

Pada Phase 8, jangan memaksakan semua trigger otomatis.

Yang wajib:

1. Sistem bisa menyimpan notifikasi.
2. User bisa membaca notifikasi.
3. Admin bisa mengirim pengumuman.
4. Service siap dipakai oleh phase berikutnya.

Trigger otomatis dari observer/event boleh dibuat minimal untuk **setoran baru**, tetapi jangan membuat scheduler kompleks.

---

# 4. Target Output Phase 8

Setelah Phase 8 selesai, aplikasi harus punya:

1. Tabel `notifications`.
2. Route notification center.
3. Controller:

   * `NotificationCenterController`
   * `AnnouncementController`
4. Request:

   * `StoreAnnouncementRequest`
5. Notification class:

   * `AnnouncementNotification`
   * `HafalanRecordCreatedNotification`
   * `TahfizhDebtBehindNotification`
6. Service:

   * `NotificationRecipientResolver`
   * `NotificationDispatchService`
7. View:

   * notification index
   * notification show
   * announcement create
8. Badge unread notification di navbar.
9. Role access:

   * Semua role login bisa melihat notifikasi masing-masing.
   * Super Admin dan Admin Sekolah bisa membuat pengumuman.
   * User tidak bisa melihat notifikasi milik user lain.
10. Dokumentasi Phase 8.

---

# 5. Validasi Awal Sebelum Eksekusi

Jalankan:

```powershell
cd C:\xampp\htdocs\hafizplus-school-platform
php artisan --version
php artisan migrate:status
php artisan route:list
git status
```

Target:

1. Laravel 12 berjalan.
2. Phase 1 selesai.
3. Phase 2 selesai.
4. Phase 3 selesai.
5. Phase 4 selesai.
6. Phase 5 selesai.
7. Phase 6 selesai.
8. Phase 7 selesai.
9. Tabel berikut sudah ada:

   * `users`
   * `roles`
   * `schools`
   * `students`
   * `parent_profiles`
   * `parent_student`
   * `hafalan_records`
   * `tahfizh_debts`
10. Working tree bersih atau semua perubahan sudah diketahui.

Jika Phase 7 belum selesai, hentikan eksekusi.

---

# 6. Buat Branch Git Phase 8

Jalankan:

```powershell
git checkout -b phase-8-notification-center
```

Jika branch sudah ada:

```powershell
git checkout phase-8-notification-center
```

---

# 7. Struktur File yang Akan Dibuat

Agent harus membuat atau mengubah file berikut:

```text
app/
├── Http/
│   ├── Controllers/
│   │   └── Notifications/
│   │       ├── NotificationCenterController.php
│   │       └── AnnouncementController.php
│   └── Requests/
│       └── Notifications/
│           └── StoreAnnouncementRequest.php
├── Notifications/
│   ├── AnnouncementNotification.php
│   ├── HafalanRecordCreatedNotification.php
│   └── TahfizhDebtBehindNotification.php
├── Services/
│   └── Notifications/
│       ├── NotificationRecipientResolver.php
│       └── NotificationDispatchService.php

database/
└── migrations/
    └── xxxx_xx_xx_xxxxxx_create_notifications_table.php

resources/
└── views/
    └── notifications/
        ├── index.blade.php
        ├── show.blade.php
        └── announcements/
            └── create.blade.php

routes/
└── web.php

docs/
└── phase-8-notification-center.md
```

---

# 8. Buat Migration Notifications

Jalankan:

```powershell
php artisan make:notifications-table
```

Lalu jalankan:

```powershell
php artisan migrate
```

Jika file migration `create_notifications_table` sudah ada, jangan buat ulang. Jalankan saja:

```powershell
php artisan migrate
```

Target tabel:

```text
notifications
```

Kolom penting:

```text
id
type
notifiable_type
notifiable_id
data
read_at
created_at
updated_at
```

---

# 9. Pastikan Model `User` Memakai `Notifiable`

Buka:

```text
app/Models/User.php
```

Pastikan ada import:

```php
use Illuminate\Notifications\Notifiable;
```

Pastikan class memakai trait:

```php
use HasFactory, Notifiable;
```

Jika sudah ada, jangan duplikasi.

---

# 10. Buat Controller, Request, Notification, dan Service

Jalankan:

```powershell
php artisan make:controller Notifications/NotificationCenterController
php artisan make:controller Notifications/AnnouncementController

php artisan make:request Notifications/StoreAnnouncementRequest

php artisan make:notification AnnouncementNotification
php artisan make:notification HafalanRecordCreatedNotification
php artisan make:notification TahfizhDebtBehindNotification
```

Buat folder service:

```powershell
mkdir app\Services\Notifications
```

Buat file service:

```powershell
New-Item app\Services\Notifications\NotificationRecipientResolver.php
New-Item app\Services\Notifications\NotificationDispatchService.php
```

Buat folder view:

```powershell
mkdir resources\views\notifications
mkdir resources\views\notifications\announcements
```

---

# 11. Request `StoreAnnouncementRequest`

Buka:

```text
app/Http/Requests/Notifications/StoreAnnouncementRequest.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Requests\Notifications;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAnnouncementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole(['super_admin', 'admin']) ?? false;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:150'],
            'body' => ['required', 'string', 'max:2000'],
            'type' => [
                'required',
                'string',
                Rule::in([
                    'info',
                    'success',
                    'warning',
                    'danger',
                ]),
            ],
            'recipient_type' => [
                'required',
                'string',
                Rule::in([
                    'all',
                    'role',
                    'class_room_parents',
                    'student_parents',
                    'specific_users',
                ]),
            ],
            'roles' => ['nullable', 'array'],
            'roles.*' => [
                'string',
                Rule::in([
                    'super_admin',
                    'admin',
                    'principal',
                    'teacher',
                    'parent',
                    'student',
                ]),
            ],
            'class_room_id' => ['nullable', 'exists:class_rooms,id'],
            'student_id' => ['nullable', 'exists:students,id'],
            'user_ids' => ['nullable', 'array'],
            'user_ids.*' => ['integer', 'exists:users,id'],
            'action_url' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'judul',
            'body' => 'isi pengumuman',
            'type' => 'jenis notifikasi',
            'recipient_type' => 'jenis penerima',
            'roles' => 'role penerima',
            'class_room_id' => 'kelas',
            'student_id' => 'santri',
            'user_ids' => 'user penerima',
            'action_url' => 'tautan aksi',
        ];
    }
}
```

---

# 12. Notification `AnnouncementNotification`

Buka:

```text
app/Notifications/AnnouncementNotification.php
```

Isi lengkap:

```php
<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AnnouncementNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly string $title,
        private readonly string $body,
        private readonly string $type = 'info',
        private readonly ?string $actionUrl = null,
        private readonly ?string $senderName = null,
    ) {
        //
    }

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'category' => 'announcement',
            'title' => $this->title,
            'body' => $this->body,
            'type' => $this->type,
            'action_url' => $this->actionUrl,
            'sender_name' => $this->senderName,
            'sent_at' => now()->toDateTimeString(),
        ];
    }
}
```

---

# 13. Notification `HafalanRecordCreatedNotification`

Buka:

```text
app/Notifications/HafalanRecordCreatedNotification.php
```

Isi lengkap:

```php
<?php

namespace App\Notifications;

use App\Models\HafalanRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class HafalanRecordCreatedNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly HafalanRecord $record
    ) {
        //
    }

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $this->record->loadMissing([
            'student',
            'teacher',
            'startSurah',
            'endSurah',
        ]);

        return [
            'category' => 'hafalan_record_created',
            'title' => 'Setoran tahfizh baru',
            'body' => $this->record->student?->full_name . ' telah menambahkan setoran tahfizh.',
            'type' => 'success',
            'student_id' => $this->record->student_id,
            'student_name' => $this->record->student?->full_name,
            'teacher_name' => $this->record->teacher?->name,
            'record_id' => $this->record->id,
            'record_date' => $this->record->record_date?->format('Y-m-d'),
            'range' => "Hlm {$this->record->start_page}:{$this->record->start_line} - Hlm {$this->record->end_page}:{$this->record->end_line}",
            'total_lines' => $this->record->total_lines,
            'status' => $this->record->status,
            'action_url' => null,
            'sent_at' => now()->toDateTimeString(),
        ];
    }
}
```

---

# 14. Notification `TahfizhDebtBehindNotification`

Buka:

```text
app/Notifications/TahfizhDebtBehindNotification.php
```

Isi lengkap:

```php
<?php

namespace App\Notifications;

use App\Models\TahfizhDebt;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TahfizhDebtBehindNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly TahfizhDebt $debt
    ) {
        //
    }

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $this->debt->loadMissing([
            'student',
            'classRoom',
        ]);

        return [
            'category' => 'tahfizh_debt_behind',
            'title' => 'Santri belum mencapai target',
            'body' => $this->debt->student?->full_name . ' masih memiliki hutang hafalan.',
            'type' => 'warning',
            'student_id' => $this->debt->student_id,
            'student_name' => $this->debt->student?->full_name,
            'class_room_name' => $this->debt->classRoom?->name,
            'debt_id' => $this->debt->id,
            'period_type' => $this->debt->period_type,
            'period_start' => $this->debt->period_start?->format('Y-m-d'),
            'period_end' => $this->debt->period_end?->format('Y-m-d'),
            'target_lines' => $this->debt->target_lines,
            'actual_lines' => $this->debt->actual_lines,
            'debt_lines' => $this->debt->debt_lines,
            'cumulative_debt_lines' => $this->debt->cumulative_debt_lines,
            'action_url' => null,
            'sent_at' => now()->toDateTimeString(),
        ];
    }
}
```

---

# 15. Service `NotificationRecipientResolver`

Buka:

```text
app/Services/Notifications/NotificationRecipientResolver.php
```

Isi lengkap:

```php
<?php

namespace App\Services\Notifications;

use App\Models\ClassRoom;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Collection;

class NotificationRecipientResolver
{
    public function allActiveUsers(): Collection
    {
        return User::query()
            ->where('is_active', true)
            ->get();
    }

    public function usersByRoles(array $roles): Collection
    {
        return User::query()
            ->where('is_active', true)
            ->whereHas('role', function ($query) use ($roles): void {
                $query->whereIn('name', $roles);
            })
            ->get();
    }

    public function specificUsers(array $userIds): Collection
    {
        return User::query()
            ->where('is_active', true)
            ->whereIn('id', $userIds)
            ->get();
    }

    public function parentsOfStudent(Student $student): Collection
    {
        return $student->parents()
            ->with('user')
            ->get()
            ->pluck('user')
            ->filter()
            ->unique('id')
            ->values();
    }

    public function parentsOfClassRoom(ClassRoom $classRoom): Collection
    {
        return $classRoom->students()
            ->with('parents.user')
            ->where('is_active', true)
            ->get()
            ->flatMap(function (Student $student) {
                return $student->parents
                    ->pluck('user')
                    ->filter();
            })
            ->unique('id')
            ->values();
    }

    public function resolveFromAnnouncementPayload(array $payload): Collection
    {
        $recipientType = $payload['recipient_type'] ?? null;

        return match ($recipientType) {
            'all' => $this->allActiveUsers(),

            'role' => $this->usersByRoles($payload['roles'] ?? []),

            'specific_users' => $this->specificUsers($payload['user_ids'] ?? []),

            'student_parents' => $this->parentsOfStudent(
                Student::query()->findOrFail($payload['student_id'])
            ),

            'class_room_parents' => $this->parentsOfClassRoom(
                ClassRoom::query()->findOrFail($payload['class_room_id'])
            ),

            default => collect(),
        };
    }
}
```

---

# 16. Service `NotificationDispatchService`

Buka:

```text
app/Services/Notifications/NotificationDispatchService.php
```

Isi lengkap:

```php
<?php

namespace App\Services\Notifications;

use App\Models\HafalanRecord;
use App\Models\TahfizhDebt;
use App\Models\User;
use App\Notifications\AnnouncementNotification;
use App\Notifications\HafalanRecordCreatedNotification;
use App\Notifications\TahfizhDebtBehindNotification;
use Illuminate\Notifications\Notification as BaseNotification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;

class NotificationDispatchService
{
    public function __construct(
        private readonly NotificationRecipientResolver $recipientResolver,
    ) {
        //
    }

    public function sendToUsers(iterable $users, BaseNotification $notification): void
    {
        $collection = collect($users)
            ->filter()
            ->unique('id')
            ->values();

        if ($collection->isEmpty()) {
            return;
        }

        Notification::send($collection, $notification);
    }

    public function sendAnnouncement(array $payload, User $sender): int
    {
        $recipients = $this->recipientResolver->resolveFromAnnouncementPayload($payload);

        $this->sendToUsers(
            users: $recipients,
            notification: new AnnouncementNotification(
                title: $payload['title'],
                body: $payload['body'],
                type: $payload['type'] ?? 'info',
                actionUrl: $payload['action_url'] ?? null,
                senderName: $sender->name,
            )
        );

        return $recipients->count();
    }

    public function notifyHafalanRecordCreated(HafalanRecord $record): int
    {
        $record->loadMissing(['student.parents.user', 'student.user']);

        $recipients = collect();

        if ($record->student?->user) {
            $recipients->push($record->student->user);
        }

        if ($record->student) {
            $recipients = $recipients->merge(
                $this->recipientResolver->parentsOfStudent($record->student)
            );
        }

        $recipients = $recipients
            ->filter()
            ->unique('id')
            ->values();

        $this->sendToUsers(
            users: $recipients,
            notification: new HafalanRecordCreatedNotification($record)
        );

        return $recipients->count();
    }

    public function notifyTahfizhDebtBehind(TahfizhDebt $debt): int
    {
        $debt->loadMissing(['student.parents.user', 'student.user']);

        $recipients = collect();

        if ($debt->student?->user) {
            $recipients->push($debt->student->user);
        }

        if ($debt->student) {
            $recipients = $recipients->merge(
                $this->recipientResolver->parentsOfStudent($debt->student)
            );
        }

        $recipients = $recipients
            ->filter()
            ->unique('id')
            ->values();

        $this->sendToUsers(
            users: $recipients,
            notification: new TahfizhDebtBehindNotification($debt)
        );

        return $recipients->count();
    }
}
```

---

# 17. Controller `NotificationCenterController`

Buka:

```text
app/Http/Controllers/Notifications/NotificationCenterController.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Controllers\Notifications;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\View\View;

class NotificationCenterController extends Controller
{
    public function index(Request $request): View
    {
        $notifications = $request->user()
            ->notifications()
            ->latest()
            ->paginate(15);

        return view('notifications.index', [
            'notifications' => $notifications,
            'unreadCount' => $request->user()->unreadNotifications()->count(),
        ]);
    }

    public function show(Request $request, string $notification): View
    {
        $notification = $this->findUserNotification($request, $notification);

        if (! $notification->read_at) {
            $notification->markAsRead();
        }

        return view('notifications.show', [
            'notification' => $notification,
            'data' => $notification->data,
        ]);
    }

    public function markAsRead(Request $request, string $notification): RedirectResponse
    {
        $notification = $this->findUserNotification($request, $notification);

        $notification->markAsRead();

        return back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }

    public function markAllAsRead(Request $request): RedirectResponse
    {
        $request->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }

    public function destroy(Request $request, string $notification): RedirectResponse
    {
        $notification = $this->findUserNotification($request, $notification);

        $notification->delete();

        return redirect()
            ->route('notifications.index')
            ->with('success', 'Notifikasi berhasil dihapus.');
    }

    private function findUserNotification(Request $request, string $notificationId): DatabaseNotification
    {
        return $request->user()
            ->notifications()
            ->where('id', $notificationId)
            ->firstOrFail();
    }
}
```

---

# 18. Controller `AnnouncementController`

Buka:

```text
app/Http/Controllers/Notifications/AnnouncementController.php
```

Isi lengkap:

```php
<?php

namespace App\Http\Controllers\Notifications;

use App\Http\Controllers\Controller;
use App\Http\Requests\Notifications\StoreAnnouncementRequest;
use App\Models\ClassRoom;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use App\Services\Notifications\NotificationDispatchService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function create(): View
    {
        return view('notifications.announcements.create', [
            'roles' => Role::query()
                ->where('is_active', true)
                ->orderBy('label')
                ->get(),
            'classRooms' => ClassRoom::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->get(),
            'students' => Student::query()
                ->where('is_active', true)
                ->orderBy('full_name')
                ->get(),
            'users' => User::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(
        StoreAnnouncementRequest $request,
        NotificationDispatchService $dispatchService
    ): RedirectResponse {
        $sentCount = $dispatchService->sendAnnouncement(
            payload: $request->validated(),
            sender: $request->user(),
        );

        return redirect()
            ->route('notifications.index')
            ->with('success', "Pengumuman berhasil dikirim ke {$sentCount} user.");
    }
}
```

---

# 19. Opsional Aman — Trigger Notifikasi Saat Setoran Baru

Buka:

```text
app/Http/Controllers/Tahfizh/HafalanRecordController.php
```

Tambahkan import:

```php
use App\Services\Notifications\NotificationDispatchService;
```

Ubah method `store()` agar setelah `HafalanRecord::query()->create(...)`, record disimpan ke variabel lalu dikirim notifikasi.

Cari bagian:

```php
DB::transaction(function () use ($request, $user, $totalLines, $sequence): void {
```

Ubah menjadi:

```php
$record = DB::transaction(function () use ($request, $user, $totalLines, $sequence): HafalanRecord {
```

Lalu di dalam transaction, ubah:

```php
HafalanRecord::query()->create([
```

menjadi:

```php
return HafalanRecord::query()->create([
```

Setelah transaction selesai, tambahkan:

```php
app(NotificationDispatchService::class)->notifyHafalanRecordCreated($record);
```

Contoh struktur akhir:

```php
$record = DB::transaction(function () use ($request, $user, $totalLines, $sequence): HafalanRecord {
    $teacherId = $user->hasRole('teacher')
        ? $user->id
        : $request->input('teacher_id');

    return HafalanRecord::query()->create([
        'school_id' => $request->integer('school_id'),
        'student_id' => $request->integer('student_id'),
        'teacher_id' => $teacherId,
        'tahfizh_target_id' => $request->input('tahfizh_target_id'),
        'record_date' => $request->input('record_date'),

        'start_surah_id' => $request->input('start_surah_id'),
        'start_ayah' => $request->input('start_ayah'),
        'end_surah_id' => $request->input('end_surah_id'),
        'end_ayah' => $request->input('end_ayah'),

        'start_page' => $request->integer('start_page'),
        'start_line' => $request->integer('start_line'),
        'end_page' => $request->integer('end_page'),
        'end_line' => $request->integer('end_line'),

        'total_lines' => $totalLines,
        'status' => $request->input('status'),
        'quality_score' => $request->input('quality_score'),
        'notes' => $request->input('notes'),

        'is_sequence_valid' => true,
        'sequence_note' => $sequence['note'],

        'created_by' => $user->id,
        'updated_by' => null,
    ]);
});

app(NotificationDispatchService::class)->notifyHafalanRecordCreated($record);
```

Catatan:

1. Trigger otomatis ini hanya untuk setoran baru.
2. Jika parent/santri belum punya akun, service tidak error.
3. Jika parent belum terhubung ke santri, tidak ada parent yang menerima.
4. Jangan membuat observer dulu jika controller sudah cukup.
5. Observer bisa dibuat nanti saat sistem stabil.

---

# 20. Update Routes

Buka:

```text
routes/web.php
```

Tambahkan import:

```php
use App\Http\Controllers\Notifications\AnnouncementController;
use App\Http\Controllers\Notifications\NotificationCenterController;
```

Di dalam group `Route::middleware('auth')->group(...)`, tambahkan:

```php
Route::prefix('notifications')
    ->name('notifications.')
    ->group(function (): void {
        Route::get('/', [NotificationCenterController::class, 'index'])
            ->name('index');

        Route::post('mark-all-as-read', [NotificationCenterController::class, 'markAllAsRead'])
            ->name('mark-all-as-read');

        Route::get('{notification}', [NotificationCenterController::class, 'show'])
            ->name('show');

        Route::patch('{notification}/mark-as-read', [NotificationCenterController::class, 'markAsRead'])
            ->name('mark-as-read');

        Route::delete('{notification}', [NotificationCenterController::class, 'destroy'])
            ->name('destroy');
    });

Route::middleware('role:super_admin,admin')
    ->prefix('notifications/announcements')
    ->name('notifications.announcements.')
    ->group(function (): void {
        Route::get('create', [AnnouncementController::class, 'create'])
            ->name('create');

        Route::post('/', [AnnouncementController::class, 'store'])
            ->name('store');
    });
```

---

# 21. Update Navigasi Layout

Buka:

```text
resources/views/layouts/app.blade.php
```

Tambahkan di area navigasi `@auth`:

```blade
@php
    $unreadNotificationCount = auth()->user()->unreadNotifications()->count();
@endphp

<a href="{{ route('notifications.index') }}" class="relative font-semibold text-slate-700 hover:text-slate-950">
    Notifikasi

    @if ($unreadNotificationCount > 0)
        <span class="absolute -right-4 -top-2 rounded-full bg-red-600 px-2 py-0.5 text-xs font-bold text-white">
            {{ $unreadNotificationCount > 99 ? '99+' : $unreadNotificationCount }}
        </span>
    @endif
</a>

@if (auth()->user()->hasRole(['super_admin', 'admin']))
    <a href="{{ route('notifications.announcements.create') }}" class="font-semibold text-slate-700 hover:text-slate-950">
        Kirim Pengumuman
    </a>
@endif
```

Jika layout sudah punya struktur menu khusus, sesuaikan posisinya tanpa menghapus menu lama.

---

# 22. View `notifications/index.blade.php`

Buat file:

```text
resources/views/notifications/index.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold">Notification Center</h2>
            <p class="text-sm text-slate-500">
                {{ $unreadCount }} notifikasi belum dibaca.
            </p>
        </div>

        <div class="flex flex-wrap gap-2">
            @if (auth()->user()->hasRole(['super_admin', 'admin']))
                <a href="{{ route('notifications.announcements.create') }}"
                   class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                    Kirim Pengumuman
                </a>
            @endif

            <form method="POST" action="{{ route('notifications.mark-all-as-read') }}">
                @csrf
                <button type="submit"
                        class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                    Tandai Semua Dibaca
                </button>
            </form>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm font-semibold text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm">
        <table class="w-full border-collapse text-left text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Judul</th>
                    <th class="px-4 py-3">Kategori</th>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($notifications as $notification)
                    @php
                        $data = $notification->data;
                    @endphp

                    <tr class="border-t {{ $notification->read_at ? 'bg-white' : 'bg-blue-50' }}">
                        <td class="px-4 py-3">
                            @if ($notification->read_at)
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                    Dibaca
                                </span>
                            @else
                                <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                                    Baru
                                </span>
                            @endif
                        </td>

                        <td class="px-4 py-3">
                            <div class="font-semibold">
                                {{ $data['title'] ?? 'Notifikasi' }}
                            </div>
                            <div class="mt-1 line-clamp-1 text-xs text-slate-500">
                                {{ $data['body'] ?? '-' }}
                            </div>
                        </td>

                        <td class="px-4 py-3">
                            {{ str_replace('_', ' ', $data['category'] ?? '-') }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $notification->created_at?->format('d/m/Y H:i') }}
                        </td>

                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('notifications.show', $notification->id) }}"
                                   class="rounded-lg bg-slate-900 px-3 py-2 text-xs font-semibold text-white hover:bg-slate-700">
                                    Detail
                                </a>

                                @if (! $notification->read_at)
                                    <form method="POST" action="{{ route('notifications.mark-as-read', $notification->id) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="rounded-lg border border-slate-300 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                                            Dibaca
                                        </button>
                                    </form>
                                @endif

                                <form method="POST"
                                      action="{{ route('notifications.destroy', $notification->id) }}"
                                      onsubmit="return confirm('Hapus notifikasi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="rounded-lg border border-red-300 px-3 py-2 text-xs font-semibold text-red-700 hover:bg-red-50">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-slate-500">
                            Belum ada notifikasi.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $notifications->links() }}
    </div>
@endsection
```

---

# 23. View `notifications/show.blade.php`

Buat file:

```text
resources/views/notifications/show.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold">{{ $data['title'] ?? 'Detail Notifikasi' }}</h2>
            <p class="text-sm text-slate-500">
                {{ $notification->created_at?->format('d/m/Y H:i') }}
            </p>
        </div>

        <a href="{{ route('notifications.index') }}"
           class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
            Kembali
        </a>
    </div>

    <div class="rounded-2xl bg-white p-6 shadow-sm">
        <div class="mb-4 flex flex-wrap gap-2">
            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                {{ str_replace('_', ' ', $data['category'] ?? '-') }}
            </span>

            <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                {{ strtoupper($data['type'] ?? 'info') }}
            </span>
        </div>

        <div class="prose max-w-none">
            <p class="whitespace-pre-line text-slate-700">
                {{ $data['body'] ?? '-' }}
            </p>
        </div>

        @if (! empty($data['action_url']))
            <div class="mt-6">
                <a href="{{ $data['action_url'] }}"
                   class="inline-block rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                    Buka Tautan
                </a>
            </div>
        @endif

        <div class="mt-8 rounded-xl bg-slate-50 p-4">
            <h3 class="mb-3 text-sm font-bold">Data Notifikasi</h3>

            <dl class="grid gap-3 text-sm md:grid-cols-2">
                @foreach ($data as $key => $value)
                    <div>
                        <dt class="font-semibold text-slate-600">{{ str_replace('_', ' ', $key) }}</dt>
                        <dd class="text-slate-800">
                            @if (is_array($value))
                                {{ json_encode($value) }}
                            @else
                                {{ $value ?? '-' }}
                            @endif
                        </dd>
                    </div>
                @endforeach
            </dl>
        </div>
    </div>
@endsection
```

---

# 24. View `notifications/announcements/create.blade.php`

Buat file:

```text
resources/views/notifications/announcements/create.blade.php
```

Isi lengkap:

```blade
@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold">Kirim Pengumuman</h2>
            <p class="text-sm text-slate-500">
                Pengumuman akan masuk ke Notification Center penerima.
            </p>
        </div>

        <a href="{{ route('notifications.index') }}"
           class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
            Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-700">
            <div class="font-bold">Validasi gagal:</div>
            <ul class="mt-2 list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('notifications.announcements.store') }}"
          class="rounded-2xl bg-white p-6 shadow-sm">
        @csrf

        <div class="grid gap-5">
            <div>
                <label class="mb-1 block text-sm font-semibold">Judul</label>
                <input type="text" name="title" value="{{ old('title') }}"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2"
                       required>
            </div>

            <div>
                <label class="mb-1 block text-sm font-semibold">Isi Pengumuman</label>
                <textarea name="body" rows="6"
                          class="w-full rounded-lg border border-slate-300 px-3 py-2"
                          required>{{ old('body') }}</textarea>
            </div>

            <div>
                <label class="mb-1 block text-sm font-semibold">Jenis</label>
                <select name="type" class="w-full rounded-lg border border-slate-300 px-3 py-2" required>
                    <option value="info" @selected(old('type') === 'info')>Info</option>
                    <option value="success" @selected(old('type') === 'success')>Success</option>
                    <option value="warning" @selected(old('type') === 'warning')>Warning</option>
                    <option value="danger" @selected(old('type') === 'danger')>Danger</option>
                </select>
            </div>

            <div>
                <label class="mb-1 block text-sm font-semibold">Jenis Penerima</label>
                <select name="recipient_type" class="w-full rounded-lg border border-slate-300 px-3 py-2" required>
                    <option value="all" @selected(old('recipient_type') === 'all')>Semua User Aktif</option>
                    <option value="role" @selected(old('recipient_type') === 'role')>Berdasarkan Role</option>
                    <option value="class_room_parents" @selected(old('recipient_type') === 'class_room_parents')>Orang Tua per Kelas</option>
                    <option value="student_parents" @selected(old('recipient_type') === 'student_parents')>Orang Tua Santri Tertentu</option>
                    <option value="specific_users" @selected(old('recipient_type') === 'specific_users')>User Tertentu</option>
                </select>

                <p class="mt-1 text-xs text-slate-500">
                    Isi field terkait sesuai jenis penerima yang dipilih.
                </p>
            </div>

            <div>
                <label class="mb-1 block text-sm font-semibold">Role Penerima</label>
                <select name="roles[]" multiple class="w-full rounded-lg border border-slate-300 px-3 py-2">
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}">
                            {{ $role->label }} / {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1 block text-sm font-semibold">Kelas untuk Orang Tua</label>
                <select name="class_room_id" class="w-full rounded-lg border border-slate-300 px-3 py-2">
                    <option value="">- Tidak dipilih -</option>
                    @foreach ($classRooms as $classRoom)
                        <option value="{{ $classRoom->id }}" @selected(old('class_room_id') == $classRoom->id)>
                            {{ $classRoom->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1 block text-sm font-semibold">Santri untuk Orang Tua</label>
                <select name="student_id" class="w-full rounded-lg border border-slate-300 px-3 py-2">
                    <option value="">- Tidak dipilih -</option>
                    @foreach ($students as $student)
                        <option value="{{ $student->id }}" @selected(old('student_id') == $student->id)>
                            {{ $student->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1 block text-sm font-semibold">User Tertentu</label>
                <select name="user_ids[]" multiple class="w-full rounded-lg border border-slate-300 px-3 py-2">
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">
                            {{ $user->name }} — {{ $user->email }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1 block text-sm font-semibold">Action URL</label>
                <input type="text" name="action_url" value="{{ old('action_url') }}"
                       placeholder="/portal/parent/dashboard"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2">
            </div>

            <div class="flex justify-end">
                <button type="submit"
                        class="rounded-lg bg-slate-900 px-5 py-2 text-sm font-semibold text-white hover:bg-slate-700">
                    Kirim Pengumuman
                </button>
            </div>
        </div>
    </form>
@endsection
```

---

# 25. Update Dashboard Role

Tambahkan link Notification Center pada dashboard role jika belum ada.

## 25.1 Dashboard Parent

Buka:

```text
resources/views/dashboards/parent.blade.php
```

Tambahkan:

```blade
<a href="{{ route('notifications.index') }}"
   class="mt-4 inline-block rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
    Lihat Notifikasi
</a>
```

## 25.2 Dashboard Student

Buka:

```text
resources/views/dashboards/student.blade.php
```

Tambahkan:

```blade
<a href="{{ route('notifications.index') }}"
   class="mt-4 inline-block rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
    Lihat Notifikasi
</a>
```

## 25.3 Dashboard Teacher

Buka:

```text
resources/views/dashboards/teacher.blade.php
```

Tambahkan:

```blade
<a href="{{ route('notifications.index') }}"
   class="mt-4 inline-block rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
    Lihat Notifikasi
</a>
```

---

# 26. Validasi Route

Jalankan:

```powershell
php artisan route:list
```

Pastikan route berikut ada:

```text
notifications.index
notifications.show
notifications.mark-as-read
notifications.mark-all-as-read
notifications.destroy
notifications.announcements.create
notifications.announcements.store
```

---

# 27. Test Manual Notification Center

## 27.1 Login Admin

Login sebagai admin:

```text
admin@hafizplus.test
password
```

Buka:

```text
/notifications/announcements/create
```

Kirim pengumuman:

```text
Judul: Uji Notification Center
Isi: Ini pengumuman uji coba Phase 8.
Jenis: Info
Jenis Penerima: Berdasarkan Role
Role: parent
Action URL: /portal/parent/dashboard
```

Target:

1. Pengumuman berhasil dikirim.
2. Redirect ke notification center.
3. Ada pesan sukses jumlah penerima.

---

## 27.2 Login Parent

Login sebagai parent:

```text
ortu@hafizplus.test
password
```

Buka:

```text
/notifications
```

Target:

1. Notifikasi tampil.
2. Status notifikasi baru.
3. Badge unread muncul di navbar.
4. Klik detail membuat notifikasi menjadi dibaca.

---

## 27.3 Mark All As Read

Di halaman:

```text
/notifications
```

Klik:

```text
Tandai Semua Dibaca
```

Target:

1. Semua notifikasi menjadi dibaca.
2. Badge unread hilang.

---

## 27.4 Delete Notification

Klik hapus pada satu notifikasi.

Target:

1. Notifikasi terhapus hanya untuk user tersebut.
2. Tidak menghapus notifikasi user lain.

---

## 27.5 Test Akses Ilegal Pengumuman

Login sebagai parent atau student.

Buka:

```text
/notifications/announcements/create
```

Target:

```text
403 Forbidden
```

---

## 27.6 Test Ownership Notification

Login user A.

Coba buka URL detail notification milik user B:

```text
/notifications/{id-notifikasi-user-lain}
```

Target:

```text
404 Not Found
```

Alasan:

Controller mencari notifikasi lewat relasi:

```text
$request->user()->notifications()
```

Jadi user tidak dapat mengambil notifikasi milik user lain.

---

# 28. Test Manual Trigger Setoran Baru

Login sebagai guru.

Buat setoran baru di:

```text
/tahfizh/hafalan-records/create
```

Target:

1. Setoran berhasil dibuat.
2. Parent yang terhubung ke santri menerima notifikasi.
3. User santri yang terhubung ke data santri menerima notifikasi.
4. Jika tidak ada parent atau user santri, sistem tetap tidak error.

---

# 29. Troubleshooting

## 29.1 Error `notifications table does not exist`

Jalankan:

```powershell
php artisan make:notifications-table
php artisan migrate
```

Jika migration sudah ada tapi belum jalan:

```powershell
php artisan migrate
```

---

## 29.2 Error `Call to undefined method unreadNotifications`

Cek `app/Models/User.php`.

Pastikan ada:

```php
use Illuminate\Notifications\Notifiable;
```

Dan trait:

```php
use HasFactory, Notifiable;
```

---

## 29.3 Error Role Tidak Bisa Membuka Pengumuman

Cek route:

```php
Route::middleware('role:super_admin,admin')
```

Cek nama role di database:

```powershell
php artisan tinker
```

Lalu:

```php
App\Models\Role::pluck('name')->toArray();
```

Target role:

```text
super_admin
admin
principal
teacher
parent
student
```

---

## 29.4 Notifikasi Tidak Terkirim ke Parent

Cek relasi parent dan santri:

```powershell
php artisan tinker
```

Lalu:

```php
$student = App\Models\Student::with('parents.user')->first();
$student->parents->pluck('user.email')->toArray();
```

Jika kosong, berarti parent belum terhubung ke santri lewat tabel:

```text
parent_student
```

---

# 30. Dokumentasi Phase 8

Buat file:

```text
docs/phase-8-notification-center.md
```

Isi lengkap:

````md
# Phase 8 — Notification Center

## Status

Phase 8 membangun Notification Center berbasis database.

Kanal awal:

```text
database
````

Belum memakai:

```text
email
whatsapp
push notification
websocket
sms
```

## Output

1. Tabel `notifications`.
2. Halaman daftar notifikasi.
3. Halaman detail notifikasi.
4. Mark as read.
5. Mark all as read.
6. Delete personal notification.
7. Pengumuman manual dari admin.
8. Notifikasi setoran baru.
9. Notifikasi hutang hafalan.
10. Badge unread notification di navbar.

## Controller Baru

```text
App\Http\Controllers\Notifications\NotificationCenterController
App\Http\Controllers\Notifications\AnnouncementController
```

## Request Baru

```text
App\Http\Requests\Notifications\StoreAnnouncementRequest
```

## Notification Class Baru

```text
App\Notifications\AnnouncementNotification
App\Notifications\HafalanRecordCreatedNotification
App\Notifications\TahfizhDebtBehindNotification
```

## Service Baru

```text
App\Services\Notifications\NotificationRecipientResolver
App\Services\Notifications\NotificationDispatchService
```

## Route Baru

```text
notifications.index
notifications.show
notifications.mark-as-read
notifications.mark-all-as-read
notifications.destroy
notifications.announcements.create
notifications.announcements.store
```

## Role Access

| Role           | Lihat Notifikasi Sendiri | Kirim Pengumuman |
| -------------- | -----------------------: | ---------------: |
| Super Admin    |                       Ya |               Ya |
| Admin Sekolah  |                       Ya |               Ya |
| Kepala Sekolah |                       Ya |            Tidak |
| Guru Tahfidz   |                       Ya |            Tidak |
| Orang Tua      |                       Ya |            Tidak |
| Santri         |                       Ya |            Tidak |

## Jenis Notifikasi

| Jenis          | Kategori                 |
| -------------- | ------------------------ |
| Pengumuman     | `announcement`           |
| Setoran Baru   | `hafalan_record_created` |
| Hutang Hafalan | `tahfizh_debt_behind`    |

## Aturan Keamanan

1. User hanya boleh melihat notifikasi miliknya sendiri.
2. User tidak boleh membaca notifikasi user lain.
3. User tidak boleh menghapus notifikasi user lain.
4. Parent dan student tidak boleh membuat pengumuman.
5. Guru tidak boleh membuat pengumuman umum.
6. Notifikasi tidak boleh membuka data anak lain.
7. Pengumuman dikirim hanya oleh Super Admin dan Admin Sekolah.

## Belum Dibuat

Phase 8 belum membuat:

1. WhatsApp gateway.
2. Push notification.
3. Firebase.
4. Websocket.
5. Email otomatis.
6. SMS.
7. Chat parent-guru.
8. Scheduled reminder otomatis.
9. Notification preference per user.
10. Notification template manager.

## Definition of Done

Phase 8 selesai jika:

1. Tabel `notifications` berhasil dibuat.
2. Semua user bisa membuka Notification Center masing-masing.
3. Admin bisa mengirim pengumuman.
4. Parent menerima pengumuman sesuai target penerima.
5. Badge unread tampil.
6. Detail notifikasi bisa dibuka.
7. Notifikasi berubah menjadi dibaca setelah dibuka.
8. Tombol mark all as read berjalan.
9. Tombol delete notification berjalan.
10. Parent/student tidak bisa membuat pengumuman.
11. User tidak bisa membuka notifikasi milik user lain.
12. Notifikasi setoran baru terkirim ke parent/santri yang terhubung.
13. Dokumentasi Phase 8 dibuat.

````

---

# 31. Update Project Progress

Buka:

```text
docs/project-progress.md
````

Update menjadi:

```md
# Project Progress — HafizPlus School Platform

| Phase | Nama | Status |
|---:|---|---|
| 0 | Product Foundation | Done |
| 1 | Auth, Role, and Initial Database Foundation | Done |
| 2 | Master Data Foundation | Done |
| 3 | Tahfizh Core Database Foundation | Done |
| 4 | Tahfizh Input Foundation | Done |
| 5 | Target and Debt Calculation | Done |
| 6 | Dashboard and Reports | Done |
| 7 | Parent and Student Progress Portal | Done |
| 8 | Notification Center | Done |
| 9 | Export PDF/Excel | Pending |
| 10 | Production Hardening | Pending |
```

---

# 32. Build Frontend

Jalankan:

```powershell
npm run build
```

---

# 33. Validasi Akhir

Jalankan:

```powershell
php artisan route:list
php artisan migrate:status
npm run build
```

Jalankan server:

```powershell
php artisan serve
```

Buka:

```text
http://127.0.0.1:8000/notifications
http://127.0.0.1:8000/notifications/announcements/create
```

---

# 34. Commit Phase 8

Jalankan:

```powershell
git status
git add .
git commit -m "feat: add database notification center"
```

Jika remote sudah tersedia:

```powershell
git push origin phase-8-notification-center
```

---

# 35. Output Akhir yang Harus Dilaporkan Agent

Setelah selesai, agent harus melaporkan:

```text
Phase 8 selesai.

Project:
- HafizPlus School Platform
- Laravel 12
- MySQL

Fitur dibuat:
- Notification Center
- Database notifications table
- Unread badge
- Notification list
- Notification detail
- Mark as read
- Mark all as read
- Delete personal notification
- Admin announcement sender
- Hafalan record created notification
- Tahfizh debt behind notification
- Notification recipient resolver
- Notification dispatch service

Route dibuat:
- notifications.index
- notifications.show
- notifications.mark-as-read
- notifications.mark-all-as-read
- notifications.destroy
- notifications.announcements.create
- notifications.announcements.store

Role access:
- Semua role bisa melihat notifikasi sendiri
- Super Admin dan Admin bisa kirim pengumuman
- Parent, Student, Teacher, Principal tidak bisa kirim pengumuman
- User tidak bisa melihat notifikasi milik user lain

Belum dibuat:
- WhatsApp gateway
- Push notification
- Email otomatis
- Websocket
- SMS
- Chat parent-guru
- Scheduled reminder otomatis

Status:
- Siap lanjut Phase 9 setelah validasi manual.
```

---

# 36. Larangan Setelah Phase 8

Agent harus berhenti setelah Phase 8 selesai.

Jangan lanjut membuat:

1. Export PDF.
2. Export Excel.
3. WhatsApp gateway.
4. Push notification.
5. Websocket.
6. API mobile.
7. Native mobile.
8. Attendance.
9. Mutabaah.
10. Tahsin.
11. Finance.
12. Cashless.

Semua itu masuk fase berikutnya.
