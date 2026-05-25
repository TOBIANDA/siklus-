@extends('layouts.app')

@section('content')
<style>
/* ===== UPLOAD BOOK PAGE ===== */
.upload-page {
    padding: 28px 32px;
    max-width: 1100px;
    margin: 0 auto;
}

/* Back / Header */
.upload-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 28px;
    font-size: 17px;
    font-weight: 700;
    color: var(--dark, #111);
}
.upload-back-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    background: var(--blue, #2563EB);
    color: #fff;
    border-radius: 50%;
    font-size: 16px;
    text-decoration: none;
    flex-shrink: 0;
    transition: background .15s;
}
.upload-back-icon:hover { background: #1d4ed8; }

/* Card wrapper */
.upload-card {
    background: var(--white, #fff);
    border: 1px solid var(--gray-border, #E5E7EB);
    border-radius: 16px;
    padding: 28px;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
}

/* 3-column grid */
.upload-grid {
    display: grid;
    grid-template-columns: 200px 1fr 1fr;
    gap: 20px;
    align-items: start;
}

/* ---- LEFT: Cover uploader ---- */
.upload-cover-box {
    border: 2px dashed #D1D5DB;
    border-radius: 14px;
    background: var(--gray-light, #F9FAFB);
    min-height: 220px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 10px;
    cursor: pointer;
    transition: border-color .2s, background .2s;
    position: relative;
    overflow: hidden;
}
.upload-cover-box:hover {
    border-color: var(--blue, #2563EB);
    background: #EFF6FF;
}
.upload-cover-box input[type="file"] {
    position: absolute;
    inset: 0;
    opacity: 0;
    cursor: pointer;
    width: 100%;
    height: 100%;
}
.upload-cover-icon {
    width: 52px;
    height: 60px;
    border: 2px solid #9CA3AF;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    color: #6B7280;
    position: relative;
    background: #fff;
}
.upload-cover-icon::before {
    content: '';
    position: absolute;
    top: -1px;
    right: -1px;
    width: 14px;
    height: 14px;
    background: #fff;
    border-left: 2px solid #9CA3AF;
    border-bottom: 2px solid #9CA3AF;
    clip-path: polygon(0 0, 0 100%, 100% 100%);
}
.upload-cover-label {
    font-size: 13px;
    font-weight: 600;
    color: #6B7280;
}
.upload-cover-preview {
    width: 100%;
    height: 100%;
    object-fit: cover;
    position: absolute;
    inset: 0;
    border-radius: 12px;
    display: none;
}

/* ---- MIDDLE column ---- */
.upload-col-mid {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

/* ---- RIGHT column ---- */
.upload-col-right {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

/* Input fields */
.upload-input {
    width: 100%;
    padding: 12px 14px;
    border: 1.5px solid var(--gray-border, #E5E7EB);
    border-radius: 10px;
    background: var(--gray-light, #F3F4F6);
    font-family: 'DM Sans', sans-serif;
    font-size: 14px;
    color: var(--dark, #111);
    outline: none;
    box-sizing: border-box;
    transition: border-color .15s, background .15s, box-shadow .15s;
}
.upload-input:focus {
    border-color: var(--blue, #2563EB);
    background: #fff;
    box-shadow: 0 0 0 3px rgba(37,99,235,.1);
}
.upload-input::placeholder { color: #9CA3AF; }

.upload-textarea {
    resize: none;
    flex: 1;
    min-height: 140px;
    font-family: 'DM Sans', sans-serif;
    font-size: 14px;
    line-height: 1.6;
}

/* Category select */
.upload-select {
    appearance: none;
    -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236B7280' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
    padding-right: 36px;
}

/* Buttons row */
.upload-btn-row {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 24px;
}
.upload-btn-cancel {
    padding: 11px 28px;
    background: #9CA3AF;
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    font-family: 'DM Sans', sans-serif;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    transition: background .15s;
}
.upload-btn-cancel:hover { background: #6B7280; }
.upload-btn-save {
    padding: 11px 28px;
    background: var(--blue, #2563EB);
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    font-family: 'DM Sans', sans-serif;
    transition: background .15s, transform .1s;
}
.upload-btn-save:hover { background: #1d4ed8; transform: translateY(-1px); }
.upload-btn-save:active { transform: translateY(0); }
.upload-btn-save:disabled { opacity: .6; cursor: not-allowed; }

/* Alert */
.upload-alert-err {
    background: #FEE2E2; color: #991B1B; border-radius: 10px;
    padding: 12px 18px; font-size: 13px; font-weight: 600;
    margin-bottom: 16px;
}
.upload-alert-success {
    background: #D1FAE5; color: #065F46; border-radius: 10px;
    padding: 12px 18px; font-size: 13px; font-weight: 600;
    margin-bottom: 16px;
}

/* Dark mode */
[data-theme="dark"] .upload-card { background: #1F2937; border-color: #374151; }
[data-theme="dark"] .upload-cover-box { background: #374151; border-color: #4B5563; }
[data-theme="dark"] .upload-cover-box:hover { background: #1e3a5f; border-color: var(--blue,#2563EB); }
[data-theme="dark"] .upload-input { background: #374151; border-color: #4B5563; color: #F9FAFB; }
[data-theme="dark"] .upload-input:focus { background: #1F2937; }
[data-theme="dark"] .upload-header { color: #F9FAFB; }

/* Responsive */
@media (max-width: 768px) {
    .upload-grid { grid-template-columns: 1fr; }
    .upload-cover-box { min-height: 160px; }
}
</style>

<div class="upload-page">

    {{-- Header --}}
    <div class="upload-header">
        <a href="{{ route('lent') }}" class="upload-back-icon" title="Kembali">&#8592;</a>
        Upload Book
    </div>

    {{-- Flash messages --}}
    @if($errors->any())
    <div class="upload-alert-err">
        ❌ {{ $errors->first() }}
    </div>
    @endif

    <div class="upload-card">
        <form
            id="form-upload-book"
            action="{{ route('lent.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf

            {{-- 3-column grid --}}
            <div class="upload-grid">

                {{-- ===== LEFT: Cover Uploader ===== --}}
                <div
                    class="upload-cover-box"
                    id="coverBox"
                    title="Klik untuk memilih gambar cover"
                >
                    <input
                        type="file"
                        name="cover"
                        id="coverInput"
                        accept="image/*"
                    >
                    <img id="coverPreview" class="upload-cover-preview" alt="Preview cover">
                    <div class="upload-cover-icon" id="coverPlaceholder">&#8679;</div>
                    <span class="upload-cover-label" id="coverText">Click to upload</span>
                </div>

                {{-- ===== MIDDLE: Title + Description ===== --}}
                <div class="upload-col-mid">
                    <input
                        type="text"
                        name="title"
                        class="upload-input"
                        placeholder="Book Title"
                        value="{{ old('title') }}"
                        required
                    >
                    <textarea
                        name="description"
                        class="upload-input upload-textarea"
                        placeholder="Book Short Description"
                        maxlength="500"
                    >{{ old('description') }}</textarea>
                </div>

                {{-- ===== RIGHT: Author + Category + Location ===== --}}
                <div class="upload-col-right">
                    <input
                        type="text"
                        name="author"
                        class="upload-input"
                        placeholder="Author's Name"
                        value="{{ old('author') }}"
                        required
                    >
                    <select name="category" class="upload-input upload-select" required>
                        <option value="" disabled {{ old('category') ? '' : 'selected' }}>Genre</option>
                        @foreach(['Fiksi','Non-Fiksi','Akademik','Komik','Biografi','Pengembangan Diri','Umum'] as $cat)
                        <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                    <input
                        type="text"
                        name="location"
                        class="upload-input"
                        placeholder="Date of Release (Lokasi)"
                        value="{{ old('location') }}"
                    >
                    <input
                        type="number"
                        name="pages"
                        class="upload-input"
                        placeholder="Number of Pages"
                        value="{{ old('pages') }}"
                        min="1"
                    >
                </div>

            </div>{{-- end grid --}}

            {{-- Action buttons --}}
            <div class="upload-btn-row">
                <a href="{{ route('lent') }}" class="upload-btn-cancel" id="cancelBtn">Cancel</a>
                <button type="submit" class="upload-btn-save" id="saveBtn">Save Changes</button>
            </div>

        </form>
    </div>
</div>

<script>
(function () {
    'use strict';

    // ---- Cover preview ----
    const coverInput   = document.getElementById('coverInput');
    const coverPreview = document.getElementById('coverPreview');
    const coverHolder  = document.getElementById('coverPlaceholder');
    const coverText    = document.getElementById('coverText');

    coverInput.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function (e) {
            coverPreview.src = e.target.result;
            coverPreview.style.display = 'block';
            coverHolder.style.display  = 'none';
            coverText.style.display    = 'none';
        };
        reader.readAsDataURL(file);
    });

    // ---- AJAX submit ----
    const form    = document.getElementById('form-upload-book');
    const saveBtn = document.getElementById('saveBtn');
    const csrf    = document.querySelector('meta[name="csrf-token"]').content;

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        saveBtn.disabled    = true;
        saveBtn.textContent = '⏳ Menyimpan...';

        const formData = new FormData(form);

        try {
            const res  = await fetch(form.action, {
                method : 'POST',
                headers: {
                    'Accept'          : 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN'    : csrf,
                },
                body: formData,
            });

            const data = await res.json();

            if (res.ok && data.success) {
                if (window.showToast) showToast(data.message || 'Buku berhasil ditambahkan!');
                // Redirect to lent page after success
                setTimeout(() => { window.location.href = '{{ route("lent") }}'; }, 900);
            } else {
                const errors = data.errors
                    ? Object.values(data.errors).flat().join('\n')
                    : (data.message || 'Terjadi kesalahan.');
                if (window.showToast) showToast(errors, true);
                saveBtn.disabled    = false;
                saveBtn.textContent = 'Save Changes';
            }
        } catch (err) {
            console.error(err);
            if (window.showToast) showToast('Gagal terhubung ke server.', true);
            saveBtn.disabled    = false;
            saveBtn.textContent = 'Save Changes';
        }
    });
})();
</script>

@endsection
