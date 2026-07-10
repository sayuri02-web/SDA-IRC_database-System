@props(['member' => null, 'size' => 36])

<div class="member-avatar-wrap" style="width:{{ $size }}px; height:{{ $size }}px; border-radius:50%; background:#e9fff3; border:2px solid #28a745; display:flex; align-items:center; justify-content:center; overflow:hidden; flex-shrink:0;">
    @if($member?->photo && file_exists(public_path('uploads/' . $member->photo)))
        <img src="{{ asset('uploads/' . $member->photo) }}" alt="{{ $member->full_name ?? 'Avatar' }}" style="width:100%; height:100%; object-fit:cover;">
    @else
        <i class="mdi mdi-account" style="font-size:{{ round($size * 0.5) }}px; color:#28a745;"></i>
    @endif
</div>
