@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src={{ asset('img/smallLogo.ico') }} class="logo" alt="BA Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
