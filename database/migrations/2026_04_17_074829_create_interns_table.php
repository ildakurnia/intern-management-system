<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interns', function (Blueprint $table) {
            $table->id();

            // Link to login account
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            // Division assignment
            $table->foreignId('division_id')->nullable()->constrained('divisions')->nullOnDelete();

            // --- Basic Info ---
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('photo')->nullable();

            // --- Intern Type ---
            $table->enum('type', ['siswa', 'mahasiswa']);
            $table->string('institution');  // nama sekolah / kampus
            $table->string('major');        // jurusan
            $table->string('faculty')->nullable(); // fakultas (khusus mahasiswa)

            // --- Siswa specific (nullable for mahasiswa) ---
            $table->string('nis')->nullable();          // Nomor Induk Siswa
            $table->string('school_grade')->nullable(); // kelas: XI, XII

            // --- Mahasiswa specific (nullable for siswa) ---
            $table->string('nim')->nullable();          // Nomor Induk Mahasiswa
            $table->string('semester')->nullable();     // semester berapa
            $table->decimal('gpa', 3, 2)->nullable();  // IPK

            // --- Internship Period ---
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['active', 'completed', 'terminated'])->default('active');

            // --- Documents ---
            $table->string('ktp_path')->nullable();
            $table->string('student_card_path')->nullable(); // kartu pelajar/mahasiswa
            $table->string('bpjs_path')->nullable();
            $table->string('recommendation_letter_path')->nullable(); // surat pengantar

            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interns');
    }
};
