<style>
  /**/#accordion .panel-default .panel-heading{background-color: #00a157;border-color: #00a157;}
  #accordion .panel-default .panel-heading .panel-title a{color: #fff;}
  #accordion .panel-default .panel-heading .panel-title.collapsed a{color: #fff}
  #accordion .panel-collapse.collapse.in .panel-body label, #accordion .panel-collapse.collapse .panel-body label { padding: 5px; border-bottom: 1px dotted #00a157;}
  #accordion .panel-collapse.collapse.in .panel-body label:hover a, #accordion .panel-collapse.collapse .panel-body label:hover a{font-weight: bold;color: #f39c12;}
  #accordion .panel-collapse.collapse .panel-body label a{color: #00a157;font-weight: 500;}
  #accordion .panel-collapse.collapse .panel-body label a.active{font-weight: bold;color: #f39c12;}

</style>
<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
     
    <div class="panel-group" id="accordion">
    
	<?php 
	$p_id = $p_id2 = 0; $_id = NULL;
	foreach ($accessMenus as $item){
		$url_re_2 = "";
		
        if(Request::segment(3) !="" and !is_numeric(Request::segment(3))){
			$url_re_2 = Request::segment(2).'/'.Request::segment(3);
		}
		
		$url_re = Request::segment(2);
		
		if ($url_re == $item->url_link) {
          $p_id = $item->parent_id;
        }
		if ($url_re_2 == $item->url_link) {
          $p_id2 = $item->parent_id;
        }
		
        if ($item->parent_id == 0 || $item->parent_id == null){
          
			foreach ($accessMenus as $sub) {
			  
				if($p_id2 > 0){
					$url_re = $url_re_2;
				}
			  
				if ($url_re == $sub->url_link) {
					$p_id = $sub->parent_id;
					$_id = $sub->id;
				}
			}
	?>
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4 class="panel-title <?php if($p_id != $item->id) echo 'collapsed'; ?>" style="cursor: pointer;" data-toggle="collapse" data-parent="#accordion" href="#collapse{{ $item->id }}">
            <a  class="">
              <i class="{{ $item->icon != '' ? $item->icon : 'fa fa-angle-right' }}"></i>
              {{ $item->name }}
            </a>
          </h4>
        </div>
        <?php if ($item->submenu > 0){
            ?>
          <div id="collapse{{ $item->id }}" class="panel-collapse collapse <?php if($p_id == $item->id) echo 'in'; ?>">
            <div class="panel-body">
              <?php foreach ($accessMenus as $sub){
              if ($sub->parent_id == $item->id){  ?>
                <label style="width: 100%;">
                  <a href="/admin/{{ $sub->url_link }}" class="<?php if($_id == $sub->id) echo 'active'; ?>">
                      <i class="{{ $sub->icon != '' ? $sub->icon : 'fa fa-angle-right' }}"></i>
                      <span>{{ $sub->name }}</span>
                    </a>
                </label>
               <?php }} ?>
            </div>
          </div>
        <?php } ?>
      </div>
    <?php }} ?>
    </div>

  </section>
  <!-- /.sidebar -->
</aside>
