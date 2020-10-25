<div class="row">
    <div class="col">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" href="/requests/register">{# New correspondents #}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/requests/sign">{# Users signature #}</a>
            </li>
        </ul>
        <div class="card">
            <div class="card-header">
                <h1>{# New correspondents #}</h1>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php foreach ($companies as $company): ?>
                        <div class="col-12 col-lg-6 mb-2">
                            <div class="card">
                                <div class="card-header">
                                    <span class="company-name"><?=$company['name'] ?></span>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-3 company-key">{# BIN #}</div>
                                        <div class="col company-value"><?=$company['bin'] ?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3 company-key">{# IBAN #}</div>
                                        <div class="col company-value"><?=$company['iban'] ?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3 company-key">{# Address #}</div>
                                        <div class="col company-value"><?=$company['address'] ?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3 company-key">{# Website #}</div>
                                        <div class="col company-value"><?=$company['site'] ?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3 company-key">{# Director #}</div>
                                        <div class="col company-value">
                                            <?=$company['dir_surname'] ?>
                                            <?=$company['dir_name'] ?>
                                            <?=$company['dir_patronym'] ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col company-link">
                                            <a href="<?=$company['decision_doc'] ?>">{# Decision #}</a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col company-link">
                                            <a href="<?=$company['const_doc'] ?>">{# Constituent documents #}</a>
                                        </div>
                                    </div>
                                    <div class="row company-actions">
                                        <div class="col">
                                            <form class="" action="/request/<?=$company['id'] ?>/register/accept" method="post">
                                                <button class="btn btn-primary">{# Accept #}</button>
                                            </form>
                                        </div>
                                        <div class="col">
                                            <form class="" action="/request/<?=$company['id'] ?>/register/reject" method="post">
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
