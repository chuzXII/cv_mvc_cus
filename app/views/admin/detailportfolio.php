<?php \Core\View::extends('layout.admin.layout'); ?>

<?php \Core\View::startSection('content'); ?>
<div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
    <div class="card-body px-4 py-3">
        <div class="row align-items-center">
            <div class="col-9">
                <h4 class="fw-semibold mb-8">Datatable Basic</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a class="text-muted text-decoration-none" href="../dark/index.html">Home</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Datatable Basic</li>
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
        <h4 class="card-title mb-0">Basic Layout</h4>
    </div>
    <div class="card-body p-4">
        <div class="row">
            <div class="col-sm-4">
                <img src='../uploads/<?= $project['nama_file']?>' class="img-thumbnail" alt="...">
            </div>
            <div class="col-sm">
                <div>
                    <label class="form-label text-light">Nama Project</label>
                    <p><?php echo htmlspecialchars($project['nama_project']); ?></p>
                </div>
                <div>
                    <label class="form-label text-light">Kategori Project</label>
                    <p><?php echo htmlspecialchars($project['kategori_project']); ?></p>
                </div>
                <div>
                    <label class="form-label text-light">Link Project</label>
                    <p><?php echo htmlspecialchars($project['link_project']); ?></p>
                </div>


            </div>

        </div>
        <div>
            <label class="form-label text-light">Deskripsi Project</label>
            <p><?php echo htmlspecialchars($project['deksripsi_project']); ?></p>
        </div>

    </div>
</div>
<?php \Core\View::stopSection(); ?>