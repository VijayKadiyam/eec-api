{!! $subProduct->email_html !!}

<br>
<br>
Regards,
<br>
{{ $user->name }}
<br>
{{ $user->email }}
<br>
Phone: {{ $user->phone }}
<br>
Address: {{ $user->address }}

<style type="text/css">
  table, tr, td {
    border-top: 1px solid black;
    border-bottom : 1px solid black;
    border-right: 1px solid black;
    border-left: 1px solid black;
    border-collapse: collapse;
  }
</style>