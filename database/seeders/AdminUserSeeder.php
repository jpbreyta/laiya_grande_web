<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $adminPassword = $this->password(
            'SEED_ADMIN_PASSWORD',
            'Admin123!'
        );

        $superAdminPassword = $this->password(
            'SEED_SUPER_ADMIN_PASSWORD',
            'SuperAdmin123!'
        );

        DB::transaction(function () use ($adminPassword, $superAdminPassword): void {
            $adminId = $this->upsertUser(
                env('SEED_ADMIN_NAME', 'Admin User'),
                env('SEED_ADMIN_EMAIL', 'admin@laiyagrande.com'),
                $adminPassword
            );

            $superAdminId = $this->upsertUser(
                env('SEED_SUPER_ADMIN_NAME', 'Super Admin'),
                env('SEED_SUPER_ADMIN_EMAIL', 'super_admin@laiyagrande.com'),
                $superAdminPassword
            );

            $this->assignRoles($adminId, ['admin']);
            $this->assignRoles($superAdminId, ['admin', 'manager']);
        });
    }

    private function password(string $environmentKey, string $localDefault): string
    {
        $configuredPassword = env($environmentKey);

        if (is_string($configuredPassword) && $configuredPassword !== '') {
            return $configuredPassword;
        }

        if (app()->environment('production')) {
            throw new RuntimeException(
                "{$environmentKey} must be configured before running production seeders."
            );
        }

        return $localDefault;
    }

    private function upsertUser(string $name, string $email, string $password): int
    {
        $existingId = DB::table('users')
            ->where('email', $email)
            ->value('id');

        if ($existingId !== null) {
            DB::table('users')
                ->where('id', $existingId)
                ->update([
                    'name' => $name,
                    'is_active' => true,
                    'deleted_at' => null,
                    'updated_at' => now(),
                ]);

            return (int) $existingId;
        }

        return (int) DB::table('users')->insertGetId([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function assignRoles(int $userId, array $roleNames): void
    {
        $roles = DB::table('roles')
            ->whereIn('name', $roleNames)
            ->pluck('id', 'name');

        foreach ($roleNames as $roleName) {
            $roleId = $roles->get($roleName);

            if ($roleId === null) {
                throw new RuntimeException(
                    "Role '{$roleName}' is missing. Run DefaultRolesSeeder first."
                );
            }

            DB::table('role_user')->updateOrInsert(
                [
                    'user_id' => $userId,
                    'role_id' => $roleId,
                ],
                [
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}
