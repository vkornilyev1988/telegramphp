<div class="card">
    <div class="card-header">
        <h1>{# Telegram #}</h1>
    </div>
    <div class="card-body">
        <div class="row text-right">
            <span class="telegram-no col-12">№ <?=$telegram['id']?></span>
            <span class="telegram-status col-12">{# <?=$statuses[$telegram['status']] ?> #}</span>
        </div>
        <div class="row custom-control custom-switch">
            <input class="custom-control-input" id="is-urgent" type="checkbox" name="is-urgent" value="1" <?=$telegram['is_urgent'] ? 'checked' : '' ?> disabled>
            <label class="custom-control-label" for="is-urgent">{# Urgent #}</label>
        </div>
        <div class="row form-group">
            <label for="points">{# Where, whom #}</label>
            <select id="points" class="select2 form-control select2-container--disabled" name="points[]" multiple="multiple" readonly>
                <?php foreach($points as $point):?>
                    <option value="<?=$point['id']?>" <?=$point['selected'] ? 'selected' : '' ?>><?=$point['name']?></option>
                <?php endforeach;?>
            </select>
        </div>
        <!--<div class="row form-group">
        <!--<div class="row form-group">
            <label for="destination">{# Where, whom #}</label>
            <textarea id="destination" class="form-control" readonly><?=$telegram['destination'] ?></textarea>
        </div>-->
        <div class="row form-group">
            <label for="message">{# Message #}</label>
            <textarea id="message"  class="form-control" readonly><?=$telegram['message'] ?></textarea>
        </div>
        <?php if ($telegram['status'] == 9): ?>
            <div class="row form-group">
                <label for="message">{# Reject reason #}</label>
                <textarea id="message"  class="form-control" readonly><?=$telegram['reason'] ?></textarea>
            </div>
        <?php endif; ?>
        <form class="row form-group" method="post" enctype="multipart/form-data" action="/telegram/<?=$telegram['id'] ?>/sign">
            <label class="col-2">{# EDS #}:</label>
            <span class="eds-info col"><?=$telegram['sign'] ?></span>
            <span class="eds-status ml-1 <?=$telegram['sign'] ? '' :'d-none' ?>">{# Signed #}</span>
        </form>
        <div class="row form-group">
            <label class="col-2">{# Actor #}:</label>
            <div class="col-4">
                <span class="actor-name">
                    <?=$telegram['surname'] ?>
                    <?=$telegram['name'] ?>
                    <?=$telegram['patronym'] ?>
                </span>
            </div>
            <div class="col-6">
                <label>{# Phone #}:</label>
                <span class="actor-phone"><?=$telegram['mobile'] ?>, <?=$telegram['work_phone'] ?></span>
            </div>
        </div>
        <div class="row form-group">
            <div class="col-6">
                <label>{# Words count #}:</label>
                <span class="word-count"><?=$telegram['wordcount'] ?></span>
            </div>
            <div class="col-6">
                <label>{# Total cost #}:</label>
                <span class="cost-value"><?=$telegram['cost'] ?></span>
                <span class="cost-sign">₸</span>
            </div>
        </div>
    </div>
    <div class="card-footer text-right">
        <?php if ($telegram['status'] == 7): ?>
            <a class="btn btn-primary" href="/telegram/<?=$telegram['id'] ?>/accept">{# Accept #}</a>
            <a class="btn btn-danger" href="#" data-toggle="dropdown">{# Reject #}</a>
            <form class="dropdown-menu" method="post" action="/telegram/<?=$telegram['id'] ?>/reject">
                <label for="reject-reason">{# Reason #}</label>
                <textarea type="text" name="reason" id="reject-reason" class="form-control"></textarea>
                <button class="btn btn-primary">{# Reject #}</button>
            </form>
        <?php endif; ?>
        <?php if ($canSign && $telegram['status'] == 8 ): ?>
            <a class="btn primary" href="/telegram/<?=$telegram['id'] ?>/edit">{# Send #}</a>
        <?php endif; ?>
        <?php if ($canConfirm && $telegram['status'] == 11): ?>
            <a class="btn primary" href="/telegram/<?=$telegram['id'] ?>/confirm">{# Confirm #}</a>
        <?php endif; ?>
        <a class="btn btn-secondary" href="/telegrams">{# Back #}</a>
    </div>
</div>
