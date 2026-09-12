<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportSetting extends Model
{
    protected $fillable = [
        'school_name',
        'school_address',
        'school_phone',
        'school_email',
        'school_website',
        'report_title',
        'department_name',
        'head_of_school_name',
        'head_of_school_nip',
        'head_of_school_position',
        'head_of_school_signature',
        'head_of_department_name',
        'head_of_department_nip',
        'head_of_department_position',
        'head_of_department_signature',
        'staff_name',
        'staff_nip',
        'staff_position',
        'staff_signature',
        'logo_path',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the active report settings
     */
    public static function getActive()
    {
        return static::where('is_active', true)->first() ?? static::getDefault();
    }

    /**
     * Get default report settings
     */
    public static function getDefault()
    {
        return new static([
            'school_name' => 'PEMERINTAH PROVINSI JAWA TIMUR',
            'school_address' => 'DINAS PENDIDIKAN',
            'school_phone' => 'SMK NEGERI 4 MALANG',
            'school_email' => null,
            'school_website' => 'Website: www.smkn4malang.sch.id | Email: info@smkn4malang.sch.id',
            'report_title' => 'LAPORAN RINGKASAN AKTIVITAS INVENTARIS',
            'department_name' => 'Laboratorium Network Operation Center (NOC)',
            'head_of_school_name' => '__________________________',
            'head_of_school_nip' => 'NIP. .......................',
            'head_of_school_position' => 'Kepala Sekolah',
            'head_of_department_name' => '__________________________',
            'head_of_department_nip' => 'NIP. .......................',
            'head_of_department_position' => 'Kepala Lab NOC',
            'staff_name' => '__________________________',
            'staff_nip' => 'NIP. .......................',
            'staff_position' => 'Petugas Inventaris',
            'is_active' => true,
        ]);
    }
}