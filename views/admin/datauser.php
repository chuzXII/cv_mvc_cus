<div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
    <div class="card-body px-4 py-3">
        <div class="row align-items-center">
            <div class="col-9">
                <h4 class="fw-semibold mb-8">Data User</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a class="text-muted text-decoration-none" href="/dashboard">Home</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Data User</li>
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
    <div class="card-body">
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <a href="adduser" class="btn btn-primary btn-sm">Tambah Data</a>


        </div>

        <div class="table-responsive border rounded-4 p-2 mt-2">
            <table id="example" class="table table-dark text-nowrap table-bordered table-sm" style="width:100%">
                <thead class="fs-4">
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>password</th>
                        <th>action</th>



                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $index => $user) : ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= $user['username'] ?></td>
                            <td><?= $user['email'] ?></td>
                            <td><?= $user['password'] ?></td>
                            <td> <a href='/edituser/<?= $user['id_user'] ?>' class="btn btn-warning btn-sm"><i class="ti ti-edit nav-small-cap-icon fs-4"></i></a> 
                            | <a onclick="return confirmDelete(<?= $user['id_user'] ?>)" class="btn btn-danger btn-sm"><i class="ti ti-trash nav-small-cap-icon fs-4"></i></a> </td>
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
    function confirmDelete() {
        return Swal.fire({
            title: 'Anda Yakin Ingin Menghapus User ini?',
            text: "Tindakan Ini Tidak Dapat Menghapus Data!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                return true; // Lanjutkan menghapus
            } else {
                return false; // Batalkan penghapusan
            }
        });
    }
</script>