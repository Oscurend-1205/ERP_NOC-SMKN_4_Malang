<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('report_settings', function (Blueprint $table) {
            $table->id();
            
            // School Information
            $table->string('school_name')->default('PEMERINTAH PROVINSI JAWA TIMUR');
            $table->string('school_address')->default('DINAS PENDIDIKAN');
            $table->string('school_phone')->default('SMK NEGERI 4 MALANG');
            $table->text('school_email')->nullable();
            $table->string('school_website')->default('Website: www.smkn4malang.sch.id | Email: info@smkn4malang.sch.id');
            
            // Report Configuration
            $table->string('report_title')->default('LAPORAN RINGKASAN AKTIVITAS INVENTARIS');
            $table->string('department_name')->default('Laboratorium Network Operation Center (NOC)');
            
            // Head of School
            $table->string('head_of_school_name')->default('__________________________');
            $table->string('head_of_school_nip')->default('NIP. .......................');
            $table->string('head_of_school_position')->default('Kepala Sekolah');
            $table->string('head_of_school_signature')->nullable();
            
            // Head of Department
            $table->string('head_of_department_name')->default('__________________________');
            $table->string('head_of_department_nip')->default('NIP. .......................');
            $table->string('head_of_department_position')->default('Kepala Lab NOC');
            $table->string('head_of_department_signature')->nullable();
            
            // Staff
            $table->string('staff_name')->default('__________________________');
            $table->string('staff_nip')->default('NIP. .......................');
            $table->string('staff_position')->default('Petugas Inventaris');
            $table->string('staff_signature')->nullable();
            
            // Logo
            $table->string('logo_path')->nullable();
            
            // Status
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_settings');
    }
};
