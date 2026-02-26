<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->rebuildUserForeignKey('students', 'cascade');
        $this->rebuildUserForeignKey('instructors', 'cascade');
    }

    public function down(): void
    {
        $this->rebuildUserForeignKey('students', 'null');
        $this->rebuildUserForeignKey('instructors', 'null');
    }

    private function rebuildUserForeignKey(string $tableName, string $onDelete): void
    {
        foreach ($this->findUserForeignKeyNames($tableName) as $foreignKeyName) {
            DB::statement(sprintf(
                'ALTER TABLE `%s` DROP FOREIGN KEY `%s`',
                $tableName,
                $foreignKeyName
            ));
        }

        Schema::table($tableName, function (Blueprint $table) use ($onDelete): void {
            $foreign = $table->foreign('user_id')->references('id')->on('users');

            if ($onDelete === 'cascade') {
                $foreign->cascadeOnDelete();
            } else {
                $foreign->nullOnDelete();
            }
        });
    }

    /**
     * @return array<int, string>
     */
    private function findUserForeignKeyNames(string $tableName): array
    {
        $databaseName = DB::getDatabaseName();

        return DB::table('information_schema.KEY_COLUMN_USAGE')
            ->where('TABLE_SCHEMA', $databaseName)
            ->where('TABLE_NAME', $tableName)
            ->where('COLUMN_NAME', 'user_id')
            ->where('REFERENCED_TABLE_NAME', 'users')
            ->whereNotNull('CONSTRAINT_NAME')
            ->pluck('CONSTRAINT_NAME')
            ->filter(fn (mixed $name): bool => $name !== 'PRIMARY')
            ->unique()
            ->values()
            ->all();
    }
};
