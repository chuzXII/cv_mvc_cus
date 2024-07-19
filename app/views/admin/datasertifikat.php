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
                    <img src="assets/images/breadcrumb/ChatBc.png" alt="modernize-img" class="img-fluid mb-n4">
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <a href="addsertifikat" class="btn btn-primary btn-sm">Tambah Data</a>
        </div>
        <div class="table-responsive border rounded-4 p-2 mt-2">
            <table id="example" class="table table-dark text-nowrap table-bordered table-sm" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Sertifikat</th>
                        <th>Img</th>
                        <th>Action</th>

                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dsertifikat as $index => $d) : ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= $d['nama_sertifikat'] ?></td>
                            <td><?= $d['nama_file_sertifikat'] ?></td>
                            <td><a href='detailsertifikat/<?= $d['id_sertifikat'] ?>' class="btn btn-info btn-sm"><i class="ti ti-eye nav-small-cap-icon fs-4"></i></a>
                                | <a href='editsertifikat/<?= $d['id_sertifikat'] ?>' class="btn btn-warning btn-sm"><i class="ti ti-edit nav-small-cap-icon fs-4"></i></a>
                                | <a class="btn btn-danger btn-sm" onclick="return confirmDelete(<?= $user['id_sertifikat'] ?>)"><i class="ti ti-trash nav-small-cap-icon fs-4"></i></a></td>


                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
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
<script>
    function confirmDelete(projectId) {
        return Swal.fire({
            title: 'Anda yakin ingin menghapus proyek ini?',
            text: "Tindakan ini tidak dapat dibatalkan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '/exe/deleteproject/' + projectId;
            }
        });
    }
</script>
<?php \Core\View::stopSection(); ?>