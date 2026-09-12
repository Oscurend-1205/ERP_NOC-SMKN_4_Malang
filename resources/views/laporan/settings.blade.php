@extends('layouts.app')

@section('title', 'Pengaturan Laporan')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">

    {{-- Page header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs font-medium text-blue-600 mb-2">
                <span class="material-symbols-outlined text-[18px]">settings</span>
                <span>Konfigurasi Sistem</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight text-slate-900">Pengaturan Laporan</h1>
            <p class="mt-1.5 max-w-2xl text-sm leading-6 text-slate-500">
                Atur informasi, tampilan, penandatangan, dan aset yang digunakan pada dokumen laporan.
            </p>
        </div>

        <form action="{{ route('laporan.settings.reset') }}" method="POST"
              onsubmit="return confirm('Apakah Anda yakin ingin mereset pengaturan ke default? Semua customisasi akan hilang.')"
              class="no-print">
            @csrf
            @method('POST')
            <button type="submit"
                    class="inline-flex w-full sm:w-auto items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-600 shadow-sm transition hover:border-slate-300 hover:bg-slate-50 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-200">
                <span class="material-symbols-outlined text-[18px]">refresh</span>
                Reset ke Default
            </button>
        </form>
    </div>

    {{-- Preview --}}
    <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-5 py-4 sm:px-6">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                </div>
                <div>
                    <h2 class="text-base font-semibold text-slate-900">Preview Dokumen</h2>
                    <p class="mt-0.5 text-xs sm:text-sm text-slate-500">Buka contoh laporan untuk melihat hasil pengaturan saat ini.</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-3 p-4 sm:grid-cols-2 lg:grid-cols-3 sm:p-5">
            <a href="{{ route('export.ringkasan.print') }}" target="_blank"
               class="group flex items-center gap-3 rounded-lg border border-slate-200 p-3.5 transition hover:border-blue-300 hover:bg-blue-50/40">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-slate-50 text-slate-600 transition group-hover:bg-blue-100 group-hover:text-blue-700">
                    <span class="material-symbols-outlined text-[20px]">bar_chart</span>
                </div>
                <div class="min-w-0">
                    <div class="text-sm font-semibold text-slate-900">Ringkasan Aktivitas</div>
                    <div class="mt-0.5 text-xs text-slate-500">Laporan statistik & kondisi</div>
                </div>
                <span class="material-symbols-outlined ml-auto h-4 w-4 text-slate-300 transition group-hover:text-blue-600">open_in_new</span>
            </a>

            <a href="{{ route('export.inventaris.print') }}" target="_blank"
               class="group flex items-center gap-3 rounded-lg border border-slate-200 p-3.5 transition hover:border-emerald-300 hover:bg-emerald-50/40">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-slate-50 text-slate-600 transition group-hover:bg-emerald-100 group-hover:text-emerald-700">
                    <span class="material-symbols-outlined text-[20px]">inventory</span>
                </div>
                <div class="min-w-0">
                    <div class="text-sm font-semibold text-slate-900">Daftar Inventaris</div>
                    <div class="mt-0.5 text-xs text-slate-500">Laporan semua barang</div>
                </div>
                <span class="material-symbols-outlined ml-auto h-4 w-4 text-slate-300 transition group-hover:text-emerald-600">open_in_new</span>
            </a>

            <a href="{{ route('export.barang-masuk.print') }}" target="_blank"
               class="group flex items-center gap-3 rounded-lg border border-slate-200 p-3.5 transition hover:border-sky-300 hover:bg-sky-50/40">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-slate-50 text-slate-600 transition group-hover:bg-sky-100 group-hover:text-sky-700">
                    <span class="material-symbols-outlined text-[20px]">download</span>
                </div>
                <div class="min-w-0">
                    <div class="text-sm font-semibold text-slate-900">Barang Masuk</div>
                    <div class="mt-0.5 text-xs text-slate-500">Laporan barang masuk</div>
                </div>
                <span class="material-symbols-outlined ml-auto h-4 w-4 text-slate-300 transition group-hover:text-sky-600">open_in_new</span>
            </a>
        </div>
    </section>

    {{-- Main settings form --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <form action="{{ route('laporan.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- School information --}}
            <section class="p-5 sm:p-6">
                <div class="mb-6 flex items-start gap-3">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                        <span class="material-symbols-outlined text-[20px]">school</span>
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-slate-900">Informasi Sekolah</h2>
                        <p class="mt-0.5 text-xs sm:text-sm text-slate-500">Data sekolah yang akan muncul di kop surat laporan.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div class="space-y-1.5">
                        <label class="block text-sm font-medium text-slate-700">Nama Sekolah (Baris 1)</label>
                        <input type="text" name="school_name" value="{{ $settings->school_name }}" required
                               class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 hover:border-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                               placeholder="Contoh: PEMERINTAH PROVINSI JAWA TIMUR">
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-sm font-medium text-slate-700">Dinas/Bidang (Baris 2)</label>
                        <input type="text" name="school_address" value="{{ $settings->school_address }}" required
                               class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 hover:border-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                               placeholder="Contoh: DINAS PENDIDIKAN">
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-sm font-medium text-slate-700">Nama Sekolah (Baris 3)</label>
                        <input type="text" name="school_phone" value="{{ $settings->school_phone }}" required
                               class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 hover:border-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                               placeholder="Contoh: SMK NEGERI 4 MALANG">
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-sm font-medium text-slate-700">Alamat & Kontak (Baris 4)</label>
                        <textarea name="school_email" rows="2"
                                  class="w-full resize-none rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 hover:border-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                  placeholder="Contoh: Jl. Ki Ageng Gribig No. 28, Kedungkandang, Malang 65139 | Telp: (0341) 712345">{{ $settings->school_email }}</textarea>
                    </div>

                    <div class="space-y-1.5 md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700">Website & Email (Baris 5)</label>
                        <input type="text" name="school_website" value="{{ $settings->school_website }}" required
                               class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 hover:border-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                               placeholder="Contoh: Website: www.smkn4malang.sch.id | Email: info@smkn4malang.sch.id">
                    </div>
                </div>
            </section>

            <div class="border-t border-slate-100"></div>

            {{-- Report configuration --}}
            <section class="bg-slate-50/70 p-5 sm:p-6">
                <div class="mb-6 flex items-start gap-3">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                        <span class="material-symbols-outlined text-[20px]">description</span>
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-slate-900">Konfigurasi Laporan</h2>
                        <p class="mt-0.5 text-xs sm:text-sm text-slate-500">Judul dan nama unit yang akan ditampilkan pada laporan.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div class="space-y-1.5">
                        <label class="block text-sm font-medium text-slate-700">Judul Laporan</label>
                        <input type="text" name="report_title" value="{{ $settings->report_title }}" required
                               class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 hover:border-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                               placeholder="Contoh: LAPORAN RINGKASAN">
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-sm font-medium text-slate-700">Nama Departemen/Unit</label>
                        <input type="text" name="department_name" value="{{ $settings->department_name }}" required
                               class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 hover:border-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                               placeholder="Contoh: Laboratorium Network">
                    </div>
                </div>
            </section>

            <div class="border-t border-slate-100"></div>

            {{-- Signatures --}}
            <section class="p-5 sm:p-6">
                <div class="mb-6 flex items-start gap-3">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-violet-50 text-violet-600">
                        <span class="material-symbols-outlined text-[20px]">draw</span>
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-slate-900">Tanda Tangan & Penandatangan</h2>
                        <p class="mt-0.5 text-xs sm:text-sm text-slate-500">Informasi penandatangan dan tanda tangan digital.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">

                    {{-- Head of School --}}
                    <div class="rounded-xl border border-slate-200 bg-white p-4">
                        <div class="mb-5 flex items-center gap-2.5">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                                <span class="material-symbols-outlined text-[18px]">workspace_premium</span>
                            </div>
                            <h3 class="text-sm font-semibold text-slate-900">Kepala Sekolah</h3>
                        </div>

                        <div class="space-y-4">
                            <div class="space-y-1.5">
                                <label class="block text-xs font-medium text-slate-600">Nama</label>
                                <input type="text" name="head_of_school_name" value="{{ $settings->head_of_school_name }}" required
                                       class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none transition placeholder:text-slate-400 hover:border-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                       placeholder="Nama lengkap">
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-xs font-medium text-slate-600">NIP</label>
                                <input type="text" name="head_of_school_nip" value="{{ $settings->head_of_school_nip }}"
                                       class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none transition placeholder:text-slate-400 hover:border-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                       placeholder="Nomor Induk Pegawai">
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-xs font-medium text-slate-600">Jabatan</label>
                                <input type="text" name="head_of_school_position" value="{{ $settings->head_of_school_position }}" required
                                       class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none transition placeholder:text-slate-400 hover:border-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                       placeholder="Contoh: Kepala Sekolah">
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-xs font-medium text-slate-600">Tanda Tangan</label>
                                @if($settings->head_of_school_signature)
                                    <div class="group relative flex min-h-20 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 p-3">
                                        <img src="{{ asset('storage/' . $settings->head_of_school_signature) }}" alt="Tanda Tangan" class="max-h-16 max-w-full object-contain">
                                        <button type="button" onclick="deleteSignature('head_of_school')"
                                                class="absolute right-2 top-2 rounded-md bg-white p-1.5 text-red-500 opacity-0 shadow-sm ring-1 ring-slate-200 transition group-hover:opacity-100 hover:bg-red-50 hover:text-red-600"
                                                title="Hapus tanda tangan">
                                            <span class="material-symbols-outlined text-[16px]">delete</span>
                                        </button>
                                    </div>
                                @else
                                    <input type="file" name="head_of_school_signature" accept="image/*"
                                           class="block w-full cursor-pointer rounded-lg border border-dashed border-slate-300 bg-slate-50 px-3 py-2 text-xs text-slate-600 transition hover:border-blue-300 hover:bg-blue-50/30 file:mr-3 file:rounded-md file:border-0 file:bg-white file:px-2.5 file:py-1.5 file:text-xs file:font-medium file:text-slate-700">
                                    <p class="text-[11px] text-slate-400">JPEG, PNG, JPG, GIF · Maks. 2MB</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Head of Department --}}
                    <div class="rounded-xl border border-slate-200 bg-white p-4">
                        <div class="mb-5 flex items-center gap-2.5">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                                <span class="material-symbols-outlined text-[18px]">groups</span>
                            </div>
                            <h3 class="text-sm font-semibold text-slate-900">Kepala Departemen</h3>
                        </div>

                        <div class="space-y-4">
                            <div class="space-y-1.5">
                                <label class="block text-xs font-medium text-slate-600">Nama</label>
                                <input type="text" name="head_of_department_name" value="{{ $settings->head_of_department_name }}" required
                                       class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none transition placeholder:text-slate-400 hover:border-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                       placeholder="Nama lengkap">
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-xs font-medium text-slate-600">NIP</label>
                                <input type="text" name="head_of_department_nip" value="{{ $settings->head_of_department_nip }}"
                                       class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none transition placeholder:text-slate-400 hover:border-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                       placeholder="Nomor Induk Pegawai">
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-xs font-medium text-slate-600">Jabatan</label>
                                <input type="text" name="head_of_department_position" value="{{ $settings->head_of_department_position }}" required
                                       class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none transition placeholder:text-slate-400 hover:border-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                       placeholder="Contoh: Kepala Program Keahlian">
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-xs font-medium text-slate-600">Tanda Tangan</label>
                                @if($settings->head_of_department_signature)
                                    <div class="group relative flex min-h-20 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 p-3">
                                        <img src="{{ asset('storage/' . $settings->head_of_department_signature) }}" alt="Tanda Tangan" class="max-h-16 max-w-full object-contain">
                                        <button type="button" onclick="deleteSignature('head_of_department')"
                                                class="absolute right-2 top-2 rounded-md bg-white p-1.5 text-red-500 opacity-0 shadow-sm ring-1 ring-slate-200 transition group-hover:opacity-100 hover:bg-red-50 hover:text-red-600"
                                                title="Hapus tanda tangan">
                                            <span class="material-symbols-outlined text-[16px]">delete</span>
                                        </button>
                                    </div>
                                @else
                                    <input type="file" name="head_of_department_signature" accept="image/*"
                                           class="block w-full cursor-pointer rounded-lg border border-dashed border-slate-300 bg-slate-50 px-3 py-2 text-xs text-slate-600 transition hover:border-emerald-300 hover:bg-emerald-50/30 file:mr-3 file:rounded-md file:border-0 file:bg-white file:px-2.5 file:py-1.5 file:text-xs file:font-medium file:text-slate-700">
                                    <p class="text-[11px] text-slate-400">JPEG, PNG, JPG, GIF · Maks. 2MB</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Staff --}}
                    <div class="rounded-xl border border-slate-200 bg-white p-4">
                        <div class="mb-5 flex items-center gap-2.5">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-orange-50 text-orange-600">
                                <span class="material-symbols-outlined text-[18px]">person</span>
                            </div>
                            <h3 class="text-sm font-semibold text-slate-900">Petugas Inventaris</h3>
                        </div>

                        <div class="space-y-4">
                            <div class="space-y-1.5">
                                <label class="block text-xs font-medium text-slate-600">Nama</label>
                                <input type="text" name="staff_name" value="{{ $settings->staff_name }}" required
                                       class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none transition placeholder:text-slate-400 hover:border-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                       placeholder="Nama lengkap">
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-xs font-medium text-slate-600">NIP</label>
                                <input type="text" name="staff_nip" value="{{ $settings->staff_nip }}"
                                       class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none transition placeholder:text-slate-400 hover:border-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                       placeholder="Nomor Induk Pegawai">
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-xs font-medium text-slate-600">Jabatan</label>
                                <input type="text" name="staff_position" value="{{ $settings->staff_position }}" required
                                       class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none transition placeholder:text-slate-400 hover:border-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                       placeholder="Contoh: Petugas Inventaris">
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-xs font-medium text-slate-600">Tanda Tangan</label>
                                @if($settings->staff_signature)
                                    <div class="group relative flex min-h-20 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 p-3">
                                        <img src="{{ asset('storage/' . $settings->staff_signature) }}" alt="Tanda Tangan" class="max-h-16 max-w-full object-contain">
                                        <button type="button" onclick="deleteSignature('staff')"
                                                class="absolute right-2 top-2 rounded-md bg-white p-1.5 text-red-500 opacity-0 shadow-sm ring-1 ring-slate-200 transition group-hover:opacity-100 hover:bg-red-50 hover:text-red-600"
                                                title="Hapus tanda tangan">
                                            <span class="material-symbols-outlined text-[16px]">delete</span>
                                        </button>
                                    </div>
                                @else
                                    <input type="file" name="staff_signature" accept="image/*"
                                           class="block w-full cursor-pointer rounded-lg border border-dashed border-slate-300 bg-slate-50 px-3 py-2 text-xs text-slate-600 transition hover:border-orange-300 hover:bg-orange-50/30 file:mr-3 file:rounded-md file:border-0 file:bg-white file:px-2.5 file:py-1.5 file:text-xs file:font-medium file:text-slate-700">
                                    <p class="text-[11px] text-slate-400">JPEG, PNG, JPG, GIF · Maks. 2MB</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <div class="border-t border-slate-100"></div>

            {{-- Logo --}}
            <section class="bg-slate-50/70 p-5 sm:p-6">
                <div class="mb-6 flex items-start gap-3">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-amber-50 text-amber-600">
                        <span class="material-symbols-outlined text-[20px]">image</span>
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-slate-900">Logo Sekolah</h2>
                        <p class="mt-0.5 text-xs sm:text-sm text-slate-500">Upload logo sekolah untuk kop surat laporan.</p>
                    </div>
                </div>

                <div class="flex flex-col gap-5 sm:flex-row sm:items-start">
                    <div class="min-w-0 flex-1 space-y-1.5">
                        <label class="block text-sm font-medium text-slate-700">Upload Logo</label>
                        <input type="file" name="logo" accept="image/*"
                               class="block w-full cursor-pointer rounded-lg border border-dashed border-slate-300 bg-white px-3 py-2.5 text-xs text-slate-600 transition hover:border-blue-300 hover:bg-blue-50/20 file:mr-3 file:rounded-md file:border-0 file:bg-slate-100 file:px-3 file:py-1.5 file:text-xs file:font-medium file:text-slate-700">
                        <p class="text-xs text-slate-400">JPEG, PNG, JPG, GIF · Maks. 2MB · Rekomendasi rasio 1:1</p>
                    </div>

                    @if($settings->logo_path)
                        <div class="group relative flex h-24 w-24 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white p-3">
                            <img src="{{ asset('storage/' . $settings->logo_path) }}" alt="Logo Sekolah" class="max-h-full max-w-full object-contain">
                            <button type="button" onclick="deleteLogo()"
                                    class="absolute -right-2 -top-2 rounded-md bg-white p-1.5 text-red-500 opacity-0 shadow-sm ring-1 ring-slate-200 transition group-hover:opacity-100 hover:bg-red-50 hover:text-red-600"
                                    title="Hapus logo">
                                <span class="material-symbols-outlined text-[16px]">delete</span>
                            </button>
                        </div>
                    @endif
                </div>
            </section>

            {{-- Actions --}}
            <div class="sticky bottom-0 z-10 border-t border-slate-200 bg-white/95 px-5 py-4 sm:px-6">
                <div class="flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                    <a href="{{ route('laporan.index') }}"
                       class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-50 hover:text-slate-900">
                        Batal
                    </a>
                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-200">
                        <span class="material-symbols-outlined text-[18px]">save</span>
                        Simpan Pengaturan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function deleteSignature(type) {
        if (confirm('Apakah Anda yakin ingin menghapus tanda tangan ini?')) {
            fetch(`{{ route('laporan.settings.delete-signature', 'type') }}`.replace('type', type), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Gagal menghapus tanda tangan');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus tanda tangan');
            });
        }
    }

    function deleteLogo() {
        if (confirm('Apakah Anda yakin ingin menghapus logo ini?')) {
            fetch('{{ route('laporan.settings.delete-logo') }}', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Gagal menghapus logo');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus logo');
            });
        }
    }
</script>
@endpush