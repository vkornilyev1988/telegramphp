<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h1>{# View invoice #}</h1>
            </div>
            <form action="https://wl.walletone.com/checkout/checkout/Index" method="POST">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td>{# Invoce id #}</td>
                                    <td><?=$invoice['id']?></td>
                                </tr>
                                <tr>
                                    <td>{# Amount #}</td>
                                    <td><?=$invoice['sum']?> ₸</td>
                                </tr>
                                <tr>
                                    <td>{# Status #}</td>
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
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <?php foreach($params as $key => $value): ?>
                    <input type="hidden" name="<?=$key?>" value="<?=$value?>">
                <?php endforeach; ?>
                <div class="card-footer text-right">
                    <?php if($invoice['status']==0):?>
                    <button class="btn btn-primary">{# Pay #}</button>
                    <a class="btn btn-secondary" href="/telegrams">{# Cancel #}</a>
                    <?php endif;?>
                </div>
            </form>
        </div>
    </div>
</div>
