<?
$m = $_GET['m'];
if (!$m) {
    $m = date('m');
}
?>
<nav id="sidebar">
    <div class="sidebar-header d-flex align-items-center">
        <div class="title w-100">
            <h1 class="h5 text-center"><?=name($_SESSION['id']);?></h1>
            <hr>
        </div>
    </div>
    <ul class="list-unstyled">
        <? if($_SESSION['is_admin']==1){ ?>
            <span class="heading">Admin menu</span>
            <li><a href="<?= $url; ?>last_action"> <i class="fad fa-undo-alt fa-fw"></i> Posledné udalosti</a></li>
            <li><a href="<?= $url; ?>zamestnanci"> <i class="fad fa-users fa-fw"></i> Zamestnanci</a></li>
            <li><a href="<?= $url; ?>requests/0/1"> <i class="fad fa-clipboard-check fa-fw"></i> Schvaľovanie</a></li>
        <? }?>
        <? if($_SESSION['id']==150){ ?>
        <li><a href="<?= $url; ?>import"> <i class="fad fa-upload fa-fw"></i> Import</a></li>
        <? }?>
        <span class="heading">Menu</span>
        <li><a href="<?=$url?>home"> <i class="fad fa-home-lg-alt fa-fw"></i> Domov</a></li>
        <li><a href="<?= $url . 'hodiny/' . date('m') . '/' . date('Y');?>"> <i class="fad fa-stopwatch fa-fw"></i> Dochádzka</a></li>
        <li><a href="<?= $url; ?>request"> <i class="fad fa-calendar-edit fa-fw"></i> Požiadavka</a></li>
        <li><a href="<?=$url?>logout"> <i class="fad fa-sign-out fa-fw"></i> Odhlásenie</a></li>

    </ul>
</nav>