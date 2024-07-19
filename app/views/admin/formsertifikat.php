<?php \Core\View::extends('layout.admin.layout'); ?>

<?php \Core\View::startSection('content'); ?>
<div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
    <div class="card-body px-4 py-3">
        <div class="row align-items-center">
            <div class="col-9">
                <h4 class="fw-semibold mb-8"><?php echo isset($sertifikatData['id_sertifikat']) ? 'Edit Project' : 'Add New Project'; ?></h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a class="text-muted text-decoration-none" href="../dark/index.html">Home</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page"><?php echo isset($sertifikatData['id_sertifikat']) ? 'Edit Project' : 'Add New Project'; ?></li>
                    </ol>
                </nav>
            </div>
            <div class="col-3">
                <div class="text-center mb-n5">
                    <img src="../assets/images/breadcrumb/ChatBc.png" alt="modernize-img" class="img-fluid mb-n4">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="px-4 py-3 border-bottom">
        <h4 class="card-title mb-0"><?php echo isset($sertifikatData['id_sertifikat']) ?  'Edit Project' : 'Add New Project'; ?></h4>
    </div>
    <div class="card-body p-4">
        <form action="<?php echo BASE_URL; ?>/exe/savesertifikat" method="POST" enctype="multipart/form-data">
            <?php echo \Core\View::csrfField();
            $errors = $_SESSION['errors'] ?? [];
            unset($_SESSION['errors']); ?>

            <?php if (isset($sertifikatData['id_sertifikat'])) : ?>
                <input type="hidden" name="id_sertifikat" value='<?php echo $sertifikatData['id_sertifikat']; ?>'>
            <?php endif; ?>
            <div class="mb-4">
                <label for="sertifikat_name" class="form-label text-light">Nama Sertifikat</label>
                <div class="input-group">
                    <span class="input-group-text px-6">
                        <i class="ti ti-prompt fs-6"></i>
                    </span>
                    <input type="text" class="form-control ps-2 text-light" placeholder="Masukkan Nama Sertifikat" id="sertifikat_name" name="sertifikat_name" value='<?php echo isset($sertifikatData['nama_sertifikat']) ? $sertifikatData['nama_sertifikat'] : ''; ?>' required>
                </div>
                <?php if (!empty($errors['sertifikat_name'])) : ?>
                    <?php foreach ($errors['sertifikat_name'] as $error) : ?>
                        <div class="alert alert-danger mt-2" role="alert"><?php echo $error; ?></div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="mb-4">
                <label for="screenshot" class="form-label text-light">Screenshoot Sertifikat</label>
                <div class="input-group">
                    <span class="input-group-text px-6">
                        <i class="ti ti-photo fs-6"></i>
                    </span>
                    <input class="form-control" type="file" accept="image/*" id="screenshot" name="screenshot" value="">
                </div>
                <?php
                // Assuming $sertifikatData['nama_file_sertifikat'] contains the filename
                $imageUrl = isset($sertifikatData['nama_file_sertifikat']) ? '../image.php?filename=img/sertifikat/' . urlencode($sertifikatData['nama_file_sertifikat']) : '';
                ?>
                <img id="imagePreview" class="preview" src='<?php echo !empty($sertifikatData['nama_file_sertifikat']) ? '../image.php?filename=img/sertifikat/' . urlencode($sertifikatData['nama_file_sertifikat']) : ''; ?>'>
                <?php if (!empty($errors['screenshot'])) : ?>
                    <?php foreach ($errors['screenshot'] as $error) : ?>
                        <div class="alert alert-danger mt-2" role="alert"><?php echo $error; ?></div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <button type="submit" class="btn btn-primary"><?php echo isset($sertifikatData['id_project']) ? 'Update' : 'Add'; ?></button>
        </form>
        <?php unset($_SESSION['errors']); ?>
    </div>
</div>

<script>
    document.getElementById('screenshot').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const imagePreview = document.getElementById('imagePreview');
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block'; // Tampilkan gambar
            }
            reader.readAsDataURL(file);
        } else {
            imagePreview.style.display = 'none'; // Sembunyikan gambar jika tidak ada file
            imagePreview.src = ''; // Kosongkan src
        }
    });

    // Tampilkan gambar jika sudah ada
    document.addEventListener('DOMContentLoaded', function() {
        const imagePreview = document.getElementById('imagePreview');
        if (imagePreview.src) {
            imagePreview.style.display = 'block';
        } else {
            imagePreview.style.display = 'none'; // Sembunyikan gambar jika tidak ada file

        }
    });
</script>
<?php if (isset($_SESSION['sweet'])) : ?>
    <script>
        Swal.fire({
            title: "<?php echo $_SESSION['sweet']['title']; ?>",
            text: "<?php echo $_SESSION['sweet']['text']; ?>",
            icon: "<?php echo $_SESSION['sweet']['icon']; ?>"
        });
    </script>
    <?php unset($_SESSION['sweet']); ?>
<?php endif; ?>
<?php \Core\View::stopSection(); ?>