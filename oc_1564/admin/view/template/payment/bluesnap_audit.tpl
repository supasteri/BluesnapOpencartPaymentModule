<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
    <div class="warning" style="display:none;"></div>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/payment.png" alt="" /><?php echo $audit_title; ?></h1>
    </div>
    <div class="content">
	   <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
	   <div id="tab-traces">
                <table class="list">
		<?php foreach($bluesnap_audit_record as $key => $val ){ ?>
                          <tr><td><?php echo $key; ?></td><td><?php echo $val;?></td></tr>
		<?php } ?>
                </table>
            </div>
      </form>
    </div>
  </div>
</div>
<script>
<?php echo $footer; ?>
