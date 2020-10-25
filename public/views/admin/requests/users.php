<div class="row">
    <div class="col">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link" href="/requests/register">{# New correspondents #}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="/requests/sign">{# Users signature #}</a>
            </li>
        </ul>
        <div class="card">
            <div class="card-header">
                <h1>{# Users signature #}</h1>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php foreach ($users as $user): ?>
                        <div class="col-12 col-lg-6 mb-2">
                            <div class="card">
                                <div class="card-header">
                                    <span class="company-name"><?=$user['surname'] ?> <?=$user['name'] ?></span>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-3 company-key">{# Login #}</div>
                                        <div class="col company-value"><?=$user['login'] ?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3 company-key">{# IIN #}</div>
                                        <div class="col company-value"><?=$user['iin'] ?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3 company-key">{# Company #}</div>
                                        <div class="col company-value"><?=$user['company'] ?></div>
                                    </div>
                                    <div class="row company-actions">
                                        <div class="col">
                                            <form class="" action="/request/<?=$user['id'] ?>/sign/accept" method="post">
                                                <button class="btn btn-primary">{# Accept #}</button>
                                            </form>
                                        </div>
                                        <div class="col">
                                            <form class="" action="/request/<?=$user['id'] ?>/sign/reject" method="post">
                                                <button class="btn btn-danger">{# Reject #}</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
