<!DOCTYPE html>
<html>
<?php $validation = \Config\Services::validation(); ?>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta name="description" content="Responsive Admin Template" />
    <meta name="author" content="SmartUniversity" />
    <title><?= $page->title ?></title>
    <!-- icons -->
    <link href="<?= base_url() ?>assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/iconic/css/material-design-iconic-font.min.css">
    <!-- bootstrap -->
    <link href="<?= base_url() ?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- style -->
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/pages/extra_pages.css">
    <!-- favicon -->
    <link rel="shortcut icon" href="<?= base_url() ?>/assets/images/wma1.png" />
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <style>
        .swal-button {
            padding: 7px 19px;
            border-radius: 2px;
            background-color: #B64B11;
            font-size: 12px;
            border: 1px solid #e1e1e1;
            text-shadow: 0px -1px 0px rgba(0, 0, 0, 0.3);
        }

        .swal-button-container .swal-button--danger {

            background: #B64B11 !important;

        }


        .swal-button--cancel {

            background-color: #333 !important;
            color: #fff !important;

        }

        .swal-title {
            font-weight: 300;
        }


        .swal-button--confirm:hover,
        .swal-button-container .swal-button--danger:hover {
            background-color: #e56824 !important;
            border-radius: 2px;
        }

        .swal-button--cancel:hover {
            background-color: #555 !important;
            border-radius: 2px;
        }
    </style>
</head>

<body>
    <div class="limiter">
        <div class="container-login100 " style="background-image:url('<?= base_url() ?>/assets/images/slide4.JPG') ;">
            <div class="wrap-login100">
                <form action="<?= base_url('service-request/login') ?>" method="post" class="login100-form1 validate-form4" id="signupForm">
                    <span class="login100-form-logo">
                        <img src="<?= base_url() ?>/assets/images/wma1.png" alt="">
                    </span>
                    <span class="login100-form-title text-sm p-b-10 p-t-17">
                        Weights And Measures Agency
                    </span>
                    <?php if (session()->getFlashdata('error')) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>


                    <div class="text-center text-white mb-2" id="loader" style="display:none;">
                        <div class="spinner-border" role="status" style="width: 3rem; height: 3rem;">
                        </div>
                    </div>
                    <div class="row">


                        <div class="col-lg-12 p-t-20">
                            <div class="wrap-input100 validate-input">
                                <input class="input100" type="email" name="email" placeholder="Email">
                                <span class="text-light"><?= displayError($validation, 'email') ?></span>
                            </div>
                        </div>

                        <div class="col-lg-12 p-t-10">
                            <div class="wrap-input100 validate-input">
                                <input class="input100" type="password" name="password" placeholder="Password">
                                <span class="text-light"><?= displayError($validation, 'password') ?></span>
                            </div>
                        </div>

                    </div>

                    <div class="container-login100-form-btn">
                        <button type="submit" class="login100-form-btn">
                            Sign In
                        </button>
                    </div>
                    <div class="text-center p-t-10">
                        <a href="<?= base_url('service-request/forgot-password') ?>" class="text-center text-white" style="text-decoration: underline;">Forgot Password?</a><br>
                        You Don't Have An Account?
                        <a class="txt1" style="text-decoration: underline;" href="<?= base_url('service-request/create-account') ?>">
                            SignUp Now
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- <script>
        const loader = document.querySelector('#loader')

        function requestOnProgress() {
            loader.style.display = 'block'
        }

        function requestDone() {
            loader.style.display = 'none'
        }

        function verifyNida(nationalId) {

            //19940408231130000125
            if (nationalId.value.length == 20) {

                const data = {
                    nationalId: nationalId.value
                };
                requestOnProgress()
                fetch('<?= base_url() ?>/license-application/verifyNida', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(data),
                    })
                    .then((response) => response.json())
                    .then((data) => {
                        const firstName = document.querySelector('#firstName')
                        const lastName = document.querySelector('#lastName')
                        const userData = data.user
                        if (data.status == 1) {
                            nationalId.setAttribute('disabled', 'disabled')
                            requestDone()
                            firstName.value = userData.FirstName
                            lastName.value = userData.LastName
                        } else {
                            nationalId.removeAttribute('disabled', 'disabled')
                            requestDone()
                            swal({
                                title: data.msg,
                                icon: "warning",
                                timer: 4500
                            });
                        }
                        console.log(data);
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                    });


            } else {
                console.log('nida incomplete')
            }


        }
    </script> -->
    <!-- start js include path -->
    <script src="<?= base_url() ?>assets/plugins/jquery/jquery.min.js"></script>
    <!-- bootstrap -->
    <script src="<?= base_url() ?>assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?= base_url() ?>assets/js/pages/extra_pages/login.js"></script>
    <!-- end js include path -->
</body>


</html>