@props(['icon', 'iconBg', 'label', 'value', 'sublabel' => null])

<div style="background:#fff; border-radius:10px; padding:18px; box-shadow:0 1px 3px rgba(0,0,0,0.06); display:flex; gap:14px; align-items:flex-start;">
    <div style="width:46px; height:46px; border-radius:10px; background:{{ $iconBg }}; display:flex; align-items:center; justify-content:center; font-size:20px; flex-shrink:0;">
        {{ $icon }}
    </div>
    <div>
        <p style="font-size:13px; color:#6b7280; margin:0 0 4px;">{{ $label }}</p>
        <p style="font-size:24px; font-weight:700; color:#111827; margin:0;">{{ $value }}</p>
        @if($sublabel)
            <p style="font-size:12px; color:#9ca3af; margin:2px 0 0;">{{ $sublabel }}</p>
        @endif
    </div>
</div>
