<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu">
      @foreach ($accessMenus as $item)
        @if ($item->parent_id == 0 || $item->parent_id == null)
          @php
            $check = 0;
            if (Request::segment(2) == $item->url_link) {
                $check++;
            }
            foreach ($accessMenus as $sub) {
                if ($sub->parent_id == $item->id && Request::segment(2) == $sub->url_link) {
                    $check++;
                }
            }
          @endphp
          <li class="header">
            <i class="{{ $item->icon != '' ? $item->icon : 'fa fa-angle-right' }}"></i>
            {{ $item->name }}
          </li>
          @if ($item->submenu > 0)
            {{-- <ul class="treeview-menu"> --}}
            @foreach ($accessMenus as $sub)
              @if ($sub->parent_id == $item->id)
                <li class="{{ Request::segment(2) == $sub->url_link ? 'active' : '' }}">
                  <a href="/admin/{{ $sub->url_link }}">
                    <i class="{{ $sub->icon != '' ? $sub->icon : 'fa fa-angle-right' }}"></i>
                    <span>{{ $sub->name }}</span>
                  </a>
                  @if ($sub->submenu > 0)
                    <ul class="treeview-menu">
                      @foreach ($accessMenus as $sub_child)
                        @if ($sub_child->parent_id == $sub->id)
                          <li class="{{ Request::segment(2) == $sub_child->url_link ? 'active' : '' }}">
                            <a href="/admin/{{ $sub_child->url_link }}">
                              <i class="{{ $sub_child->icon != '' ? $sub_child->icon : 'fa fa-angle-right' }}"></i>
                              <span>{{ $sub_child->name }}</span>
                            </a>
                          </li>
                        @endif
                      @endforeach
                    </ul>
                  @endif
                </li>
              @endif
            @endforeach
            {{-- </ul> --}}
          @endif
        @endif
      @endforeach
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>
