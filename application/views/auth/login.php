<div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

        <div class="col-lg-5">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">

                        <div class="col-lg">
                            <div class="p-5">
                                <div class="text-center">
                                    <img src="assets/img/AIN.png" width="170px">
                                    <p>

                                </div>
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4"><strong>Silahkan Login</strong></h1>
                                </div>

                                <?= $this->session->flashdata('message'); ?>
                                <form class="user" method="POST" action="<?= base_url('auth'); ?>">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-user" id="email" name="email" placeholder="Masukkan Email" value="<?= set_value('email'); ?>"> <?= form_error('email', '<small class="text-danger pl-3">', '</small>') ?>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Masukkan Password"> <?= form_error('password', '<small class="text-danger pl-3">', '</small>') ?>
                                        <!-- <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                        <script>
                                            $(".toggle-password").click(function() {

                                                $(this).toggleClass("fa-eye fa-eye-slash");
                                                var input = $($(this).attr("toggle"));
                                                if (input.attr("type") == "password") {
                                                    input.attr("type", "text");
                                                } else {
                                                    input.attr("type", "password");
                                                }
                                            });
                                        </script> -->
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        Login
                                    </button>


                                </form>
                                <hr>
                                <div class="text-center">
                                    <a class="small" href="<?= base_url('auth/forgotpassword'); ?>">Lupa Password?</a>
                                </div>
                                <div class="text-center">
                                    <a class="small" href="<?= base_url('auth/registration'); ?>">Buat Akun!</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>