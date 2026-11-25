@props(['rating' => 0])
@php $full = (int) round($rating); @endphp
<span class="stars" aria-label="{{ number_format($rating, 1) }} out of 5">{{ str_repeat('★', $full) }}{{ str_repeat('☆', 5 - $full) }}</span>
