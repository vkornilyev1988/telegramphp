<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Electronic telegraph</title>
        <link rel="stylesheet" href="/public/styles/bootstrap.min.css">
        <link rel="stylesheet" href="/public/styles/all.css">
        <link rel="stylesheet" type="text/css" href="/public/styles/style.css">
        <!--<script src="/public/scripts/debug.js"></script>-->
        <link rel="stylesheet" type="text/css" href="/public/styles/select2.min.css">
        <link rel="stylesheet" type="text/css" href="/public/styles/select2-bootstrap4.min.css">
        <link rel="stylesheet" type="text/css" href="/public/styles/vanilla-dataTables.min.css">

        <script src="/public/scripts/jquery.min.js"></script>
        <script src="/public/scripts/bootstrap.min.js"></script>
        <script src="/public/scripts/sweetalert.min.js"></script>
        <script src="/public/scripts/select2.min.js"></script>
        <script src="/public/scripts/vanilla-dataTables.min.js"></script>
        <style>
            .dropdown-menu .dropdown-menu {

            }
        </style>
    </head>
    <body>
        <div class="alert-wrapper">
            <?php foreach ($alerts as $alert): ?>
                <div class="alert alert-<?=$alert['type'] ?> alert-dismissible fade show" role="alert">
                    <?=$alert['message'] ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endforeach; ?>
        </div>
        <header class="mb-4">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <a class="navbar-brand" href="/">Electronic telegraph</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto">
                        <?php if($role == 1): ?>
                            <!--<li class="nav-item">
                                <a class="nav-link" href="/departments">{# Groups #}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/users">{# Users #}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/telegrams">{# Telegrams #}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/profile">{# Profile #}</a>
                            </li>-->
                            <li class="nav-item">
                                <a class="nav-link text-success" href="/balance/add">
                                    <span class="balance"><?=$balance ?> ₸</span>
                                </a>
                            </li>
                        <?php elseif($role == 2): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/telegrams">{# Telegrams #}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/destinations">{# Receivers #}</a>
                            </li>
                        <?php elseif($role == 3): ?>
                            <?php if (isset($rights['company.get'])): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/companies">{# Companies #}</a>
                            </li>
                            <?php endif; ?>
                            <?php if (isset($rights['destinations.get'])): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/destinations">{# Receivers #}</a>
                            </li>
                            <?php endif; ?>
                            <?php if (isset($rights['user.get'])): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/users">{# Users #}</a>
                            </li>
                            <?php endif; ?>
                            <?php if (isset($rights['telegram.cost.get'])): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/telegrams/cost">{# Telegram costs #}</a>
                            </li>
                            <?php endif; ?>
                            <?php if (isset($rights['user.register'])): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/requests">{# Requests #}</a>
                            </li>
                            <?php endif; ?>
                            <?php if (isset($rights['database.get'])): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/database">{# Backup & Restore #}</a>
                            </li>
                            <?php endif; ?>
                            <?php if (isset($rights['certificate.ca.set'])): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/certificates">{# Certificates #}</a>
                            </li>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if ($role): ?>
                            <li class="nav-item">
                                <div class="dropdown">
                                    <a href="#" class="nav-link" data-toggle="dropdown" title="<?=$login ?>">
                                        <i class="fa fa-user"></i>
                                        <span class="user"><?=$login ?></span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right">
										<?php if ($role == 1): ?>
                                            <a class="dropdown-item" href="/profile">{# Profile #}</a>
                                            <a class="dropdown-item" href="/">{# Telegrams #}</a>
                                        <?php endif; ?>
                                        <a class="dropdown-item" href="/logout">{# Log out #}</a>
                                    </div>
                                </div>
                            </li>
						<?php endif; ?>
                    </ul>
                </div>
            </nav>
        </header>
        <div class="container mb-2 main">
            <?=$content ?>
        </div>
        <script>
            $('.select2').select2({theme:'bootstrap4',width:'100%',disabled: <?=isset($fromContent['disableSelect']) ? 'true' : 'false' ?>});
            $('.main').on('click','.delete', function(e) {
                let that = this;
                let title = this.dataset.text;
                swal({
                    icon: 'warning',
                    title: title,
                    text: '{# Are you sure? #}',
                    buttons: {
                        cancel: {
                            text: "{# No #}",
                            className: 'btn btn-secondary',
                            visible: true
                        },
                        confirm: {
                            text: "{# Yes #}",
                            className: 'btn btn-primary'
                        }
                    }
                }).then(res => {
                    if (res) {

                        /*let ix = $(that).next('form');
                        ix.append('<button class="t-trig"></button>');
                        ix.find('.t-trig').trigger('click');*/
                        $(that).next('form')[0].submit();
                    }
                });
            });
            $('.dropdown-menu input, .dropdown-menu select').click((ev) => ev.stopPropagation());
            $('.dropdown-menu [data-toggle="dropdown"]').click((ev) => {
                ev.stopPropagation();
                ev.preventDefault();
                let that = ev.target.closest('[data-toggle="dropdown"]');
                let target;
                if (that.dataset.target)
                    target = document.querySelector('#'+ that.dataset.target);
                else
                    target = that.nextElementSibling;
                let x = target.parentElement.closest('.dropdown-menu')
                    .querySelectorAll('.dropdown-menu.show');
                if (target.classList.contains('show'))
                    x.forEach((el) => el.classList.remove('show'));
                else {
                    x.forEach((el) => el.classList.remove('show'));
                    target.classList.add('show');
                }

            });

            $('.dropdown').on("hidden.bs.dropdown", function() {
                // hide any open menus when parent closes
                $('.dropdown-menu.show').removeClass('show');
            });

            let table = document.querySelector('.table');
        </script>
    </body>
</html>
