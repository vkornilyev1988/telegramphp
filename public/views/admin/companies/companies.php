<div class="card" id="companies-card">
    <div class="card-header">
        <h1>{# Companies #}</h1>
        <a href="/company/add">{# Add #}</a>
    </div>
    <div class="card-body">
        <div class="table-responsives">
            <table class="table">
                <thead>
                <tr>
                    <th>{# Company #}</th>
                    <?php if (isset($rights['company.balance.set']) && isset($rights['company.balance.bill'])): ?>
                        <th>{# Balance #}</th>

                    <?php endif; ?>
					<?php if (isset($rights['company.balance.bill'])): ?>
                        <th>{# Must pay #}</th>
                    <?php endif; ?>
                    <th>{# Status #}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($companies as $company): ?>
                <tr>
                    <td><?=$company['name'] ?></td>
                    <?php if (isset($rights['company.balance.set']) && isset($rights['company.balance.bill'])): ?>
                        <td><?=$company['balance'] ?> ₸</td>
                    <?php endif; ?>
					<?php if (isset($rights['company.balance.bill'])): ?>
                        <td><?=$company['cost'] ?: 0 ?> ₸</td>
					<?php endif; ?>
                    <td><?=$company['active'] ? '{# Active #}' : '{# Blocked #}' ?></td>
                    <td class="text-right">
                        <div class="dropdown">
                          <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {# Actions #}
                          </button>
                          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" data-reference="#companies-card">

                        <?php if (isset($rights['company.block']) || isset($rights['company.block.notpaid'])): ?>
                            <?php if ($company['active']): ?>
                                <span class="dropdown">
                                    <a class="dropdown-item" href="/company/<?=$company['id'] ?>/block" data-toggle="dropdown"
                                        data-target="block-form">
                                        {# Block #}
                                    </a>
                                    <form action="/company/<?=$company['id'] ?>/block" method="post" class="dropdown-menu"
                                        id="block-form">
                                        <label for="block-reason">{# Block reason #}</label>
                                        <select id="block-reason" name="reason" class="form-control">
                                            <?php if(isset($rights['company.block.notpaid'])): ?>
                                                <option value="1">{# Not paid #}</option>
                                            <?php endif; ?>
                                            <?php if(isset($rights['company.block'])): ?>
                                                <option value="">{# Other #}</option>
                                            <?php endif; ?>
                                        </select>
                                        <button class="btn btn-primary" type="submit">{# Block #}</button>
                                    </form>
                                </span>
                            <?php else: ?>
                                <a class="dropdown-item" href="/company/<?=$company['id'] ?>/unblock">
                                    {# Unblock #}
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if (isset($rights['company.set'])): ?>
                            <a class="delete dropdown-item" href="#">
                                {# Delete #}
                            </a>
                            <form class="d-none" method="post">
                                    <input type="hidden" name="id" value="<?=$company['id'] ?>">
                                    <input type="hidden" name="_method" value="delete">
                                </form>

                            <a class="dropdown-item" href="/company/<?=$company['id'] ?>/edit">
                                {# Edit #}
                            </a>

                        <?php endif; ?>
                        <?php if (isset($rights['company.balance.set'])): ?>
                            <span class="dropdown">
                                <a class="dropdown-item" href="#" data-toggle="dropdown" data-target="add-sum-form">
                                    {# Add balance #}
                                </a>
                                <form action="/company/<?=$company['id'] ?>/balance/add" method="post" class="dropdown-menu"
                                    id="add-sum-form">
                                    <label for="add-sum">{# Sum #}</label>
                                    <input id="add-sum" type="number" name="sum" value="1000" class="form-control">

                                    <button class="btn btn-primary" type="submit">
                                        {# Add #}
                                    </button>
                                </form>
                            </span>

                            <span class="dropdown">
                                <a class="dropdown-item" href="#" data-toggle="dropdown">
                                    {# Remove balance #}
                                </a>
                                <form action="/company/<?=$company['id'] ?>/balance/remove" method="post" class="dropdown-menu">
                                    <label for="add-sum">{# Sum #}</label>
                                    <input id="add-sum" type="number" name="sum" value="1000" class="form-control">

                                    <button class="btn btn-primary" type="submit">
                                        {# Remove #}
                                    </button>
                                </form>
                            </span>

                        <?php endif; ?>
                        <?php if (isset($rights['company.balance.bill'])): ?>
                            <span class="dropdown">
                                <a class="dropdown-item" href="#" data-toggle="dropdown">
                                    {# Bill #}
                                </a>
                                <form action="/company/<?=$company['id'] ?>/bill" method="post" class="dropdown-menu" enctype="multipart/form-data">
                                    <!--<div class="custom-file">
                                        <input type="file" class="custom-file-input" id="customFile" name="bill" required>
                                        <label class="custom-file-label" for="customFile">{# Choose file #}</label>
                                    </div>-->
                                    <input type="file" id="customFile" name="bill" required>
                                    <button class="btn btn-primary" type="submit">
                                        {# Bill #}
                                    </button>
                                </form>
                            </span>
                            
                        <?php endif; ?>
                    </div>
                  </div>
                    </td>
                </tr>
                <?php endforeach; ?>

                </tbody>
            </table>
        </div>
    </div>
</div>
