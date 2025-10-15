@if ($block)
  @php
    $layout = isset($block->json_params->layout) && $block->json_params->layout != '' ? $block->json_params->layout : 'default';
  @endphp

  @if (\View::exists('frontend.blocks.' . $block->block_code . '.styles.' . $layout))
    @include('frontend.blocks.' . $block->block_code . '.styles.' . $layout)
  @else
    {{ 'Style: frontend.blocks.' . $block->block_code . '.styles.' . $layout . ' do not exists!' }}
  @endif

@endif
