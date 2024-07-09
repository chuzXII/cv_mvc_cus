<div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
    <div class="card-body px-4 py-3">
        <div class="row align-items-center">
            <div class="col-9">
                <h4 class="fw-semibold mb-8"><?php echo isset($userData['id_user']) ?  'Edit User':'Add New User' ; ?></h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a class="text-muted text-decoration-none" href="../dark/index.html">Home</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page"><?php echo isset($userData['id_user']) ?'Edit User':'Add New User'; ?></li>
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
        <h4 class="card-title mb-0"><?php echo isset($userData['id_user']) ? 'Edit User':'Add New User'; ?></h4>
    </div>
    <div class="card-body p-4">
        <form action="/exe/saveuser" method="POST">
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $_SESSION['error']; ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            <!-- Jika dalam mode edit, tambahkan input tersembunyi untuk ID -->
            <?php if (isset($userData['id_user'])): ?>
                <input type="hidden" name="user_id" value='<?php echo $userData['id_user']; ?>'>
            <?php endif; ?>
            <div class="mb-4">
                <label for="username" class="form-label text-light">Username</label>
                <div class="input-group">
                    <span class="input-group-text px-6">
                        <i class="ti ti-user fs-6"></i>
                    </span>
                    <input type="text" class="form-control ps-2 text-light" placeholder="Masukkan Username" id="username" name="username" value='<?php echo isset($userData['username']) ? $userData['username'] : ''; ?>' required>
                </div>
            </div>
            <div class="mb-4">
                <label for="email" class="form-label text-light">Email</label>
                <div class="input-group">
                    <span class="input-group-text px-6">
                        <i class="ti ti-mail fs-6"></i>
                    </span>
                    <input type="email" class="form-control ps-2 text-light" placeholder="Masukkan Email" id="email" name="email" value='<?php echo isset($userData['email']) ? $userData['email'] : ''; ?>' required>
                </div>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label text-light">Password</label>
                <div class="input-group">
                    <span class="input-group-text px-6">
                        <i class="ti ti-password fs-6"></i>
                    </span>
                    <input type="password" class="form-control ps-2 text-light" placeholder="Masukkan Password" id="password" name="password"value='<?php echo isset($userData['password']) ? $userData['password'] : ''; ?>' required>
                </div>
            </div>
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <button type="submit" class="btn btn-primary"><?php echo isset($userData['id_user']) ? 'Update' : 'Add'; ?></button>
        </form>
    </div>
</div>
