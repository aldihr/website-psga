
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Berita - PSGA</title>

    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css"
          rel="stylesheet">

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f7f5fa;
            margin: 0;
            padding: 30px 16px;
            color: #333;
        }

        .form-container {
            max-width: 900px;
            margin: auto;
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px #00000012;
        }

        h1 { color: #A10F79; }

        .field { margin-bottom: 20px; }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input, select, textarea {
            width: 100%;
            padding: 11px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
            font: inherit;
        }

        #editor-ringkasan {
        height: 150px;
        background: white;
        margin-bottom: 20px;
        }

        #editor-ringkasan .ql-editor {
        min-height: 110px;
        }

        #editor {
            height: 280px;
            background: white;
        }

        .btn {
            display: inline-block;
            border: 0;
            border-radius: 6px;
            padding: 12px 18px;
            background: #A10F79;
            color: white;
            text-decoration: none;
            cursor: pointer;
            font-size: 15px;
        }

        .btn-secondary {
            background: #6c757d;
        }

        .error {
            color: #b42318;
            font-size: 14px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
<div class="form-container">
    <h1>Tambah Berita PSGA</h1>
    <p>Buat berita baru untuk website PSGA UIN Jakarta.</p>

    @if ($errors->any())
        <div class="error">
            <strong>Periksa kembali formulir:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('berita.store') }}"
          method="POST"
          enctype="multipart/form-data"
          id="formBerita">
        @csrf

        <div class="field">
            <label for="judul">Judul Berita *</label>
            <input type="text" id="judul" name="judul"
                   value="{{ old('judul') }}" required>
        </div>

        <div class="field">
            <label for="kategori">Kategori</label>
            <input type="text" id="kategori" name="kategori"
                   value="{{ old('kategori') }}"
                   placeholder="Contoh: Kegiatan, Pengumuman, Artikel">
        </div>

        <div class="field">
            <label for="editor-ringkasan">Ringkasan Berita</label>
            <input type="hidden" name="ringkasan" id="ringkasan">
            <div id="editor-ringkasan" style="height: 150px; background: white;"></div>
        </div>

        <div class="field">
            <label>Isi Berita *</label>
            <div id="editor"></div>
            <input type="hidden" name="isi" id="isi">
        </div>

        <div class="field">
            <label for="gambar">Gambar Berita</label>
            <input type="file" id="gambar" name="gambar"
                   accept=".jpg,.jpeg,.png,.webp,image/*">
            <small>Format JPG, PNG, atau WebP. Maksimal 2 MB.</small>
        </div>

        <div class="field">
            <label for="status">Status Publikasi *</label>
            <select name="status" id="status" required>
                <option value="draft"
                    {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>
                    Draft — belum ditampilkan
                </option>
                <option value="terbit"
                    {{ old('status') === 'terbit' ? 'selected' : '' }}>
                    Terbit — tampilkan di website
                </option>
            </select>
        </div>

        <button type="submit" class="btn">Simpan Berita</button>
        <a href="{{ route('berita.index') }}"
           class="btn btn-secondary">Kembali</a>
    </form>
</div>


<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>

<script>
    const toolbarOptions = [
        [{ header: [1, 2, 3, false] }],
        ['bold', 'italic', 'underline'],
        [{ list: 'ordered' }, { list: 'bullet' }],
        [{ align: [] }],
        ['link', 'clean']
    ];

    // Editor Ringkasan Berita
    const ringkasanEditor = new Quill('#editor-ringkasan', {
        theme: 'snow',
        placeholder: 'Tulis ringkasan berita di sini...',
        modules: {
            toolbar: toolbarOptions
        }
    });

    // Editor Isi Berita
    const isiEditor = new Quill('#editor', {
        theme: 'snow',
        placeholder: 'Tulis isi berita di sini...',
        modules: {
            toolbar: toolbarOptions
        }
    });

    
    // Muat kembali data jika validasi form gagal
    ringkasanEditor.root.innerHTML = @json(old('ringkasan', ''));
    isiEditor.root.innerHTML = @json(old('isi', ''));

    // Simpan kedua editor sebelum form dikirim
    document.getElementById('formBerita')
        .addEventListener('submit', function (event) {
            const ringkasan = ringkasanEditor.root.innerHTML;
            const isi = isiEditor.root.innerHTML;

            document.getElementById('ringkasan').value =
                ringkasanEditor.getText().trim() ? ringkasan : '';

            if (!isiEditor.getText().trim()) {
                event.preventDefault();
                alert('Isi berita wajib diisi.');
                return;
            }

            document.getElementById('isi').value = isi;
        });

</script>

</body>
</html>
