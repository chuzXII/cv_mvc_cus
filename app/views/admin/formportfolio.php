<?php \Core\View::extends('layout.admin.layout'); ?>

<?php \Core\View::startSection('content'); ?>
<div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
    <div class="card-body px-4 py-3">
        <div class="row align-items-center">
            <div class="col-9">
                <h4 class="fw-semibold mb-8"><?php echo isset($projectData['id_project']) ? 'Edit Project' : 'Add New Project'; ?></h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a class="text-muted text-decoration-none" href="../dark/index.html">Home</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page"><?php echo isset($projectData) ? 'Edit Project' : 'Add New Project'; ?></li>
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
        <h4 class="card-title mb-0"><?php echo isset($projectData['id_project']) ?  'Edit Project' : 'Add New Project'; ?></h4>
    </div>
    <div class="card-body p-4">
        <form action="<?php echo BASE_URL; ?>/exe/saveproject" method="POST" enctype="multipart/form-data">
        <?php echo \Core\View::csrfField(); ?>

            <?php if (isset($projectData['id_project'])) : ?>
                <input type="hidden" name="id_project" value='<?php echo $projectData['id_project']; ?>'>
            <?php endif; ?>
            <div class="mb-4">
                <label for="project_name" class="form-label text-light">Nama Project</label>
                <div class="input-group">
                    <span class="input-group-text px-6">
                        <i class="ti ti-prompt fs-6"></i>
                    </span>
                    <input type="text" class="form-control ps-2 text-light" placeholder="Masukkan Nama Project" id="project_name" name="project_name" value='<?php echo isset($projectData['nama_project']) ? $projectData['nama_project'] : ''; ?>' required>
                </div>
                <?php if (!empty($_SESSION['errors']['project_name'])) : ?>
                    <div class="alert alert-danger mt-2" role="alert">
                        <?= htmlspecialchars($_SESSION['errors']['project_name']) ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="mb-4">
                <label for="description" class="form-label text-light">Deskripsi Project</label>
                <div class="input-group">
                    <span class="input-group-text px-6">
                        <i class="ti ti-file-description fs-6"></i>
                    </span>
                    <input type="text" class="form-control ps-2 text-light" placeholder="Masukkan Description" id="description" name="description" value='<?php echo isset($projectData['deksripsi_project']) ? $projectData['deksripsi_project'] : ''; ?>' required>
                </div>
                <?php if (!empty($_SESSION['errors']['description'])) : ?>
                    <div class="alert alert-danger mt-2" role="alert">
                        <?= htmlspecialchars($_SESSION['errors']['description']) ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="mb-4">
                <label for="category" class="form-label text-light">Kategori Project</label>
                <div class="input-group">
                    <span class="input-group-text px-6">
                        <i class="ti ti-category fs-6"></i>
                    </span>
                    <select class="form-select" id="category" name="category">
                        <option value="web-app" <?php echo isset($projectData['kategori_project']) && $projectData['kategori_project'] == 'web-app' ? 'selected' : ''; ?>>Web App</option>
                        <option value="mobile-app" <?php echo isset($projectData['kategori_project']) && $projectData['kategori_project'] == 'mobile-app' ? 'selected' : ''; ?>>Mobile App</option>
                        <option value="desktop-app" <?php echo isset($projectData['kategori_project']) && $projectData['kategori_project'] == 'desktop-app' ? 'selected' : ''; ?>>Desktop App</option>
                        <option value="iot" <?php echo isset($projectData['kategori_project']) && $projectData['kategori_project'] == 'iot' ? 'selected' : ''; ?>>IOT</option>
                        <option value="other" <?php echo isset($projectData['kategori_project']) && $projectData['kategori_project'] == 'other' ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
                <?php if (!empty($_SESSION['errors']['category'])) : ?>
                    <div class="alert alert-danger mt-2" role="alert">
                        <?= htmlspecialchars($_SESSION['errors']['category']) ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="mb-4">
                <label for="project_link" class="form-label text-light">Link Project</label>
                <div class="input-group">
                    <span class="input-group-text px-6">
                        <i class="ti ti-link fs-6"></i>
                    </span>
                    <input type="text" class="form-control ps-2 text-light" placeholder="Masukkan Link Project" id="project_link" name="project_link" value='<?php echo isset($projectData['link_project']) ? $projectData['link_project'] : ''; ?>' required>
                </div>
                <?php if (!empty($_SESSION['errors']['project_link'])) : ?>
                    <div class="alert alert-danger mt-2" role="alert">
                        <?= htmlspecialchars($_SESSION['errors']['project_link']) ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="mb-4">
                <label for="screenshot" class="form-label text-light">Screenshoot Project</label>
                <div class="input-group">
                    <span class="input-group-text px-6">
                        <i class="ti ti-photo fs-6"></i>
                    </span>
                    <input class="form-control" type="file" accept="image/*" id="screenshot" name="screenshot">

                </div>
                <img id="imagePreview" class="preview" src='<?php echo !empty($projectData['nama_file']) ?  '../image.php?filename=img/project/' . urlencode($projectData['nama_file']) : ''; ?>'>
                <?php if (!empty($_SESSION['errors']['screenshot'])) : ?>
                    <div class="alert alert-danger mt-2" role="alert">
                        <?= htmlspecialchars($_SESSION['errors']['screenshot']) ?>
                    </div>
                <?php endif; ?>
            </div>

            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <button type="submit" class="btn btn-primary"><?php echo isset($projectData['id_project']) ? 'Update' : 'Add'; ?></button>
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