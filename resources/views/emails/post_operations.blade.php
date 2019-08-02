@component('mail::message')
# Hello, {{ $auth }}

The "{{ $post->title }}" post was {{ $operation }} successfully.

@component('mail::button', ['url' => $btn_link])
{{ $button }}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
