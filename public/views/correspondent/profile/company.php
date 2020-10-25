<div id="main" class="row">
    <div class="col-md-12">
        <!--h1 id="title">Ваш личный кабинет</h1>
        <p class="lead padding-bottom-10">Здесь размещена информация о купленных вами услугах, счетах на оплату, доступном балансе и обо всем, что может пригодиться для работы с нами.</p><p><span-->
        <div class="row">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <span class="text-h4"><?=$company['name']?> (<?=$company['dir_surname']?> <?=$company['dir_name']?> <?=$company['dir_patronym']?>)</span><br>
                        <?=$company['accountant_email']?><br>
                        <?=$company['address']?>
                    </div>
                    <div class="list-group">
                        <a href="/user/change-password" class="list-group-item">{# Change password #}</a>
                        <!--a href="/user/info" class="list-group-item">Изменить контактные данные</a-->
                    </div>
                </div>
            </div>
            <!--END-->
            <?php if($user['is_head'] or $user['rights']['telegram.sign']==1): ?>
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-6">
                        <div style="min-height: 126px" class="card text-center p15">
                            <span class="h4">{# Your balance #}: <?=$balance?>{# tg #}</span>
                            <br/>
                            <a href="/balance/add">{# Add #}</a>
                        </div>
                    </div>
                    <!--div class="col-sm-6">
                        <div style="min-height: 126px" class="card text-center p15">
                            <span class="h4 text-danger">Всего к оплате: 3388 тг</span>
                            <br>
                            <a href="/invoices">Подробнее</a>
                        </div>
                    </div-->
                </div>
                <div class="row"></div>
                <div class="card text-center padding-15">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{# Group #}</th>
                                <th>{# Sum #}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($departments as $department): ?>
                            <tr>
                                <td><?=$department['name']?></td>
                                <td><?=$department['balance']?>тг</td>
                                <td><a href="/profile/balance/<?=$department['id']?>">{# Edit #}</a></td>
                            </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif;?>
        </div>
        <?php if($user['is_head'] or $user['rights']['telegram.sign']==1): ?>
        <div class="spacer-25"></div>
        <h3>Ваши неоплаченные счета</h3>
        <div class="table-responsive">
            <table class="table table-bordered text-center" id="invoices-table">
                <thead>
                    <tr>
                        <th class="text-center">{# invoice # #}</th>
                        <th class="text-center">{# Date #}</th>
                        <!--th class="text-center">Срок оплаты</th-->
                        <th class="text-center">{# Amount #}</th>
                        <th class="text-center">{# Status #}</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($transactions as $invoice):?>
                    <tr>
                        <td><a href="/profile/invoice/<?=$invoice['id']?>" class="text-bold"><?=$invoice['id']?></a></td>
                        <td><?=date("d.m.Y",$invoice['create_date'])?></td>
                        <!--td>2019-05-13</td-->
                        <td class="text-bold"><?=$invoice['sum']?> {# tg #}</td>
                        <td>
                            <?php if($invoice['status']==0):?>
                            <span class="badge badge-warning">{# Not paid #}</span>
                            <?php endif;?>
                            <?php if($invoice['status']==1):?>
                            <span class="badge badge-success">{# Paid #}</span>
                            <?php endif;?>
                            <?php if($invoice['status']==2):?>
                            <span class="badge badge-danger">{# Rejected #}</span>
                            <?php endif;?>
                            <br>
                            <a href="/profile/invoice/<?=$invoice['id']?>">{# View invoice #}</a>
                        </td>
                    </tr>
                    <?php endforeach;?>
                </tbody>
                <tfoot>
                    <td colspan="5"><a href="/profile/invoices">{# Go to invoices page #}</a></td>
                </tfoot>
            </table>
        </div>
        <div class="spacer-25"></div>
        <?php endif;?>
    </div>
</div>
