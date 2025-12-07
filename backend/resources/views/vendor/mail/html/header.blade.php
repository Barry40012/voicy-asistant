@props(['url'])
<tr>
<td class="header">
<a href="{{ $url ?? config('app.url') }}" style="display: inline-block; text-decoration: none;">
<span class="logo-text">{{ config('app.name', 'Voicy Assistant') }}</span>
</a>
</td>
</tr>
