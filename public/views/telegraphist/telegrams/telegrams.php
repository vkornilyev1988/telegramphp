<div class="row">
    <div class="col">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link <?=$status == 7 ? 'active' : '' ?>" href="/telegrams/status/7">{# On check #}</a>
            </li>
            <?php if(isset($user['rights']['telegram.send'])):?>
            <li class="nav-item">
                <a class="nav-link <?=$status == 8 ? 'active' : '' ?>" href="/telegrams/status/8">{# On send #}</a>
            </li>
            <?php endif;?>
            <li class="nav-item">
                <a class="nav-link <?=$status == 9 ? 'active' : '' ?>" href="/telegrams/all">{# Archive #}</a>
            </li>
            <!--<li class="nav-item">
                <a class="nav-link <?=$status == 1 ? 'active' : '' ?>" href="/telegrams/status/1">{# Sent's #}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?=$status == 6 ? 'active' : '' ?>" href="/telegrams/status/6">{# Rejected #}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link " href="/telegrams/reports">{# Reports #}</a>
            </li>-->
        </ul>
        <?php if (in_array($status,[1,6])): ?>
            <button class="btn btn-primary">{# Export #}</button>
        <?php endif; ?>
        <table class="table table-bordered w-100">
            <thead>
            <tr>
                <th>#</th>
                <?php if ($status != 9): ?>
                    <th>{# Duration #}</th>
                <?php endif; ?>
                <th>{# Sign date #}</th>
                <?php if (in_array($status,[8])): ?>
                    <th>{# Number #}</th>
                    <th>{# Register date #}</th>
                    <th>{# Accept date #}</th>
                <?php endif; ?>
                <th>{# Where #}</th>
                <th>{# Addresses #}</th>
                <th>{# Words count #}</th>
                <th>{# Total #}</th>
                <th>{# Company #}</th>
                <th>{# Author #}</th>
                <?php if ($status == 9): ?>
                    <th>{# Status #}</th>
                    <th>{# Telegraphist #}</th>
                    <th>{# Confirmer #}</th>
                <?php endif; ?>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($telegrams as $key => $telegram): ?>
                <tr class="<?=$telegram['is_urgent'] ? 'urgent' : '' ?> telegram-row link-row" data-href="/telegram/<?=$telegram['id'] ?>">
                    <td><?=$telegram['id'] ?></td>
					<?php if ($status != 9): ?>
                        <td><?=(new DateTime())->format('U') - (new DateTime($telegram['send_date']))->format('U') + 21600?></td>
                    <?php endif; ?>
                    <td><?=explode('.',$telegram['sign_date'])[0] ?></td>
                    <?php if (in_array($status,[8])): ?>
                        <td><?=$telegram['id'] ?></td>
                        <td><?=explode('.',$telegram['register_date'])[0] ?></td>
                        <td></td>
                    <?php endif; ?>
                    <td><?=$telegram['points'] ?></td>

                    <td><?=$telegram['destinations'] ?></td>
                    <td><?=$telegram['wordcount'] ?></td>
                    <td><?=$telegram['cost'] ?> ₸</td>
                    <td><?=$telegram['company'] ?></td>
                    <td>
                        <?=$telegram['uSurname'] ?>
                        <?=$telegram['uName'] ?>
                    </td>
					<?php if ($status == 9): ?>
                        <td>{# <?=$statuses[$telegram['status']] ?> #}</td>
                        <td>
							<?=$telegram['vSurname'] ?>
							<?=$telegram['vName'] ?>
                        </td>
                        <td>
							<?=$telegram['wSurname'] ?>
							<?=$telegram['wName'] ?>
                        </td>
					<?php endif; ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script src="/public/scripts/telegrams.js"></script>