<div class="container">
    <h3 class="mt-3">List Of Peoples</h3>

    <div class="row">
        <div class="col-md-5">
            <!-- ke controler pople -->
            <form action="<?= base_url('peoples'); ?>" method="POST">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Cari.." name="keyword" autocomplete="off" autofocus>
                    <div class="input-group-append">
                        <input class="btn btn-primary" type="submit" name="submit">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md">
            <h5>Result : <?= $total_rows ?></h5>
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- jika tdk ada dt / kosong -->
                    <?php if (empty($peoples)) : ?>
                        <tr>
                            <td colspan="4">
                                <div class="alert alert-danger shadow my-3" role="alert" style="border-radius: 3px">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="True" style="color:#721C24">&times;</span>
                                    </button>
                                    <div class="text-center">
                                        <svg width="3em" height="3em" viewBox="0 0 16 16" class="m-1 bi bi-exclamation-circle-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
                                        </svg>
                                    </div>
                                    <p style="font-size:18px" class="mb-0 font-weight-light"><b class="mr-1">
                                            <center>Danger!</center>
                                        </b>
                                        <center>Data Tidak Ditemukan</center>
                                    <p>
                                </div>

                            </td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($peoples as $p) : ?>
                        <tr>
                            <th><?= ++$start ?></th>
                            <td><?= $p['name'] ?></td>
                            <td><?= $p['email'] ?></td>
                            <td>
                                <a href="#" class="badge badge-warning">Detail</a>
                                <a href="#" class="badge badge-success">Edit</a>
                                <a href="#" class="badge badge-danger">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- nampilkan -->
            <?= $this->pagination->create_links(); ?>

        </div>
    </div>
</div>