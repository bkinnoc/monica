@extends('layouts.skeleton')

@section('content')
    <div id="mailbox-container" class="dashboard">
        <iframe src="{{ $url }}" id="mailbox"
            style="opacity: 0; width: 100%; height: calc(100vh - 134px); border: none" border="0"
            onload="completeLoad()"></iframe>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript">
        function completeLoad() {
            var iframe = document.querySelector('#mailbox');
            var container = document.querySelector('#mailbox-container');
            iframe.style.opacity = 1;
        }
        // var xhr = new XMLHttpRequest();

        // xhr.open('GET', '{{ $url }}');
        // xhr.onreadystatechange = handler;
        // xhr.responseType = 'blob';
        // xhr.setRequestHeader('Authorization', 'Basic ' + '{{ $userName }}:{{ $password }}');
        // // xhr.setRequestHeader('X-Auth', 'Basic ' + '{{ $authString }}');
        // // xhr.setRequestHeader('X-Auth-Type', 'Basic');
        // // xhr.setRequestHeader('X-User', '{{ $userName }}');
        // xhr.send();

        // function handler() {
        //     if (this.readyState === this.DONE) {
        //         if (this.status === 200) {
        //             // this.response is a Blob, because we set responseType above
        //             var data_url = URL.createObjectURL(this.response);
        //             document.querySelector('#mailbox').src = data_url;
        //         } else {
        //             console.error('Unable to fetch mailbox');
        //         }
        //     }
        // }
    </script>
@endpush
