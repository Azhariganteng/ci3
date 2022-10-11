<div class="container">

    <div class="card o-hidden border-0 shadow-lg my-5 col-lg-5 mx-auto">
        <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
                <link rel="icon" type="image/png" size="16x16" href=".../assets/img/yai.png">



                <style>
                    body {


                        background-image: url("../assets/img/intisabdulu.jpg");
                        background-size: cover;
                        background-attachment: initial;
                        background-repeat: no-repeat;

                    }
                </style>

                <div class="col-lg">
                    <div class="p-5">
                        <div style="display: flex; height: 150px; justify-content:center; margin-bottom: 1rem;">
                            <img src="<?= base_url('assets/img/AIN.png'); ?>" width="170px">
                        </div>

                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4"><strong>Daftar Akun!</strong></h1>
                        </div>
                        <form class="user" method="POST" action="<?= base_url('auth/registration'); ?>">
                            <!-- set_value mempopulasi ulang value spy tdk ngisi lg -->
                            <div class="form-group">
                                <input type="text" class="form-control form-control-user" id="name" name="name" placeholder="Nama Lengkap" value="<?= set_value('name') ?>"> <?= form_error('name', '<small class="text-danger pl-3">', '</small>') ?>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control form-control-user" id="email" name="email" placeholder="Email" value="<?= set_value('email') ?>"> <?= form_error('email', '<small class="text-danger pl-3">', '</small>') ?>
                            </div>
                            <div class=" form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="password" class="form-control form-control-user" id="password1" name="password1" placeholder="Password"> <?= form_error('password1', '<small class="text-danger pl-3">', '</small>') ?>
                                </div>
                                <div class="col-sm-6">
                                    <input type="password" class="form-control form-control-user" id="password2" name="password2" placeholder="Konfirmasi Password">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-user btn-block">
                                Daftar Akun
                            </button>

                        </form>
                        <hr>
                        <!-- <div class="text-center">
                            <a class="small" href="forgot-password.html">Lupa Password?</a>
                        </div> -->
                        <div class="text-center">
                            <a class="small" href="<?= base_url('auth'); ?>">Sudah Punya Akun? Login!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>