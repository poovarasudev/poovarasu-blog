@component('mail::message')
# Hi, abc

Daily post update mail,<br>
@if($no_of_post != 0)
Number of new posts added is {{ $no_of_post }}.
@else
Sorry no new posts added.
@endif

@component('mail::button', ['url' => 'http://blog.test/post'])
    {{ config('app.name') }}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
