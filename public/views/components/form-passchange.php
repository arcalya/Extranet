<p class="alert alert-info">
    <button type="button" class="close" data-dismiss="alert">×</button>
    Vous pouvez définir un mot de passe d'au moins 6 caractères.
</p>
<p class="alert alert-danger alert-display-ajax passerror"></p>

<div class="form-group">
    <label class="col-sm-3 control-label"><?php echo $datas; ?></label>
    <div class="col-sm-9">
        <input type="password" class="form-control" name="password1" placeholder="Mot de passe" required="required">
    </div>
</div>

<div class="form-group">
    <label class="col-sm-3 control-label"><?php echo $datas; ?></label>
    <div class="col-sm-9">
        <input type="password" class="form-control" name="password2" placeholder="Confirmer le mot de passe" required="required">
    </div>
</div>