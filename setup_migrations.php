<?php

$migrationsPath = __DIR__ . '/database/migrations/';
$files = glob($migrationsPath . '*.php');
foreach ($files as $file) {
    unlink($file);
}

$migrations = [
    '0001_01_01_000000_create_roles_table.php' => <<<EOT
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('roles', function (Blueprint \$table) {
            \$table->id();
            \$table->string('name'); // Admin, Teacher, Student
            \$table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('roles'); }
};
EOT,

    '0001_01_01_000001_create_users_table.php' => <<<EOT
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('users', function (Blueprint \$table) {
            \$table->id();
            \$table->string('name');
            \$table->string('email')->unique();
            \$table->timestamp('email_verified_at')->nullable();
            \$table->string('password');
            \$table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
            \$table->rememberToken();
            \$table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint \$table) {
            \$table->string('email')->primary();
            \$table->string('token');
            \$table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint \$table) {
            \$table->string('id')->primary();
            \$table->foreignId('user_id')->nullable()->index();
            \$table->string('ip_address', 45)->nullable();
            \$table->text('user_agent')->nullable();
            \$table->longText('payload');
            \$table->integer('last_activity')->index();
        });
    }
    public function down(): void {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
EOT,

    '0001_01_01_000002_create_stages_table.php' => <<<EOT
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('stages', function (Blueprint \$table) {
            \$table->id();
            \$table->string('name'); // e.g. Primary, Middle, High
            \$table->text('notes')->nullable();
            \$table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('stages'); }
};
EOT,

    '0001_01_01_000003_create_school_classes_table.php' => <<<EOT
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('school_classes', function (Blueprint \$table) {
            \$table->id();
            \$table->string('name'); // e.g. First Grade
            \$table->foreignId('stage_id')->constrained('stages')->cascadeOnDelete();
            \$table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('school_classes'); }
};
EOT,

    '0001_01_01_000004_create_teachers_table.php' => <<<EOT
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('teachers', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            \$table->string('phone_number')->nullable();
            \$table->text('address')->nullable();
            \$table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('teachers'); }
};
EOT,

    '0001_01_01_000005_create_subjects_table.php' => <<<EOT
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('subjects', function (Blueprint \$table) {
            \$table->id();
            \$table->string('name');
            \$table->foreignId('school_class_id')->constrained('school_classes')->cascadeOnDelete();
            \$table->foreignId('teacher_id')->nullable()->constrained('teachers')->nullOnDelete();
            \$table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('subjects'); }
};
EOT,

    '0001_01_01_000006_create_students_table.php' => <<<EOT
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('students', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            \$table->string('student_id')->unique();
            \$table->foreignId('stage_id')->constrained('stages')->cascadeOnDelete();
            \$table->foreignId('school_class_id')->constrained('school_classes')->cascadeOnDelete();
            \$table->date('date_of_birth');
            \$table->enum('gender', ['Male', 'Female']);
            \$table->string('parent_phone')->nullable();
            \$table->text('address')->nullable();
            \$table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('students'); }
};
EOT,

    '0001_01_01_000007_create_attendances_table.php' => <<<EOT
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('attendances', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            \$table->date('date');
            \$table->enum('status', ['Present', 'Absent', 'Late']);
            \$table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('attendances'); }
};
EOT,
];

foreach ($migrations as $name => $content) {
    file_put_contents($migrationsPath . $name, $content);
}
echo "Migrations generated successfully.\n";

